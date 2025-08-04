<?php

namespace App\Http\Controllers;

use App\Models\MapImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapImageController extends Controller
{
    public function index()
    {
        $mapImages = MapImage::with('user')->latest()->get();
        return view('maps.index', compact('mapImages'));
    }

    public function create()
    {
        return view('maps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'map_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'gis_files.*' => 'nullable|file|max:50240', // 50MB max for GIS files
        ]);

        // Ensure at least one file is being uploaded
        if (!$request->hasFile('map_image') && !$request->hasFile('gis_files')) {
            return back()->withErrors(['files' => 'Please upload at least one file (map image or GIS files).'])->withInput();
        }

        // Additional validation for GIS file extensions
        if ($request->hasFile('gis_files')) {
            $allowedExtensions = ['shp', 'dbf', 'shx', 'prj', 'qgz', 'qlr', 'qmd', 'geojson', 'kml', 'kmz', 'gml', 'gpx'];
            foreach ($request->file('gis_files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedExtensions)) {
                    return back()->withErrors(['gis_files' => "The file '{$file->getClientOriginalName()}' must be a file of type: " . implode(', ', $allowedExtensions) . "."])->withInput();
                }
            }
        }

        $gisFiles = [];
        $mapImagePath = null;
        $mainFilePath = null;
        $mainFileName = null;
        $mainOriginalName = null;
        $mainFileSize = 0;
        $mainMimeType = null;

        // Handle map image upload
        if ($request->hasFile('map_image')) {
            $mapImageFile = $request->file('map_image');
            $mapImageFilename = Str::random(40) . '.' . $mapImageFile->getClientOriginalExtension();
            $mapImagePath = $mapImageFile->storeAs('map-images', $mapImageFilename, 'public');
            
            $mainFilePath = $mapImagePath;
            $mainFileName = $mapImageFilename;
            $mainOriginalName = $mapImageFile->getClientOriginalName();
            $mainFileSize = $mapImageFile->getSize();
            $mainMimeType = $mapImageFile->getMimeType();
        }

        // Handle GIS files upload
        if ($request->hasFile('gis_files')) {
            foreach ($request->file('gis_files') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('gis-files', $filename, 'public');
                
                $gisFiles[] = [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ];

                // If no map image was uploaded, use the first GIS file as main file
                if (!$mapImagePath && empty($mainFilePath)) {
                    $mainFilePath = $filePath;
                    $mainFileName = $filename;
                    $mainOriginalName = $file->getClientOriginalName();
                    $mainFileSize = $file->getSize();
                    $mainMimeType = $file->getMimeType();
                }
            }
        }

        // Prepare data for creation
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'filename' => $mainFileName ?: 'gis_files',
            'original_name' => $mainOriginalName ?: 'GIS Files',
            'file_path' => $mainFilePath,
            'gis_files' => $gisFiles,
            'map_image_path' => $mapImagePath,
            'file_size' => $mainFileSize,
            'mime_type' => $mainMimeType,
            'user_id' => auth()->id(),
        ];

        MapImage::create($data);

        return redirect()->route('dashboard')->with('success', 'Map and GIS files uploaded successfully!');
    }

    public function show(MapImage $mapImage)
    {
        return view('maps.show-simple', compact('mapImage'));
    }

    public function edit($id)
    {
        $mapImage = MapImage::findOrFail($id);
        return view('maps.edit-fixed', compact('mapImage'));
    }

    public function update(Request $request, MapImage $mapImage)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'map_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'gis_files.*' => 'nullable|file|max:51200', // 50MB max for GIS files
                'delete_gis_files' => 'nullable|array',
                'delete_gis_files.*' => 'integer',
            ]);

            // Additional validation for GIS file extensions
            if ($request->hasFile('gis_files')) {
                $allowedExtensions = ['shp', 'dbf', 'shx', 'prj', 'qgz', 'qlr', 'qmd', 'geojson', 'kml', 'kmz', 'gml', 'gpx'];
                foreach ($request->file('gis_files') as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Illuminate\Validation\ValidationException(
                            validator([], []),
                            ['gis_files' => ["The file '{$file->getClientOriginalName()}' must be a file of type: " . implode(', ', $allowedExtensions) . "."]]
                        );
                    }
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $gisFiles = $mapImage->gis_files ?? [];
        $mapImagePath = $mapImage->map_image_path;
        $mainFilePath = $mapImage->file_path;
        $mainFileName = $mapImage->filename;
        $mainOriginalName = $mapImage->original_name;
        $mainFileSize = $mapImage->file_size;
        $mainMimeType = $mapImage->mime_type;

        // Handle deletion of selected GIS files
        if ($request->has('delete_gis_files') && is_array($request->delete_gis_files)) {
            $filesToDelete = array_map('intval', $request->delete_gis_files);
            \Log::info('Deleting GIS files', ['indices' => $filesToDelete]);
            foreach ($filesToDelete as $index) {
                if (isset($gisFiles[$index])) {
                    // Delete file from storage
                    if (isset($gisFiles[$index]['file_path'])) {
                        Storage::disk('public')->delete($gisFiles[$index]['file_path']);
                        \Log::info('Deleted file from storage', ['file_path' => $gisFiles[$index]['file_path']]);
                    }
                    // Remove from array
                    unset($gisFiles[$index]);
                }
            }
            // Re-index array to avoid gaps
            $gisFiles = array_values($gisFiles);
        }

        // Handle new map image upload
        if ($request->hasFile('map_image')) {
            \Log::info('New map image uploaded');
            // Delete old map image if exists
            if ($mapImagePath) {
                Storage::disk('public')->delete($mapImagePath);
                \Log::info('Deleted old map image', ['path' => $mapImagePath]);
            }

            $mapImageFile = $request->file('map_image');
            $mapImageFilename = Str::random(40) . '.' . $mapImageFile->getClientOriginalExtension();
            $mapImagePath = $mapImageFile->storeAs('maps', $mapImageFilename, 'public');
            
            // Update main file info if this is the primary file
            if ($mapImage->file_path === $mapImage->map_image_path) {
                $mainFilePath = $mapImagePath;
                $mainFileName = $mapImageFilename;
                $mainOriginalName = $mapImageFile->getClientOriginalName();
                $mainFileSize = $mapImageFile->getSize();
                $mainMimeType = $mapImageFile->getMimeType();
            }
            
            \Log::info('Map image uploaded successfully', ['path' => $mapImagePath]);
        }

        // Handle new GIS files upload
        if ($request->hasFile('gis_files')) {
            \Log::info('New GIS files uploaded', ['count' => count($request->file('gis_files'))]);
            foreach ($request->file('gis_files') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('gis-files', $filename, 'public');
                
                $gisFiles[] = [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                ];
                
                \Log::info('GIS file uploaded', ['original_name' => $file->getClientOriginalName(), 'path' => $filePath]);
            }
        }

        // Update the database record
        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'gis_files' => $gisFiles,
            'map_image_path' => $mapImagePath,
        ];

        // Only update file-related fields if they changed
        if ($mainFilePath !== $mapImage->file_path) {
            $updateData['filename'] = $mainFileName;
            $updateData['original_name'] = $mainOriginalName;
            $updateData['file_path'] = $mainFilePath;
            $updateData['file_size'] = $mainFileSize;
            $updateData['mime_type'] = $mainMimeType;
        }

        \Log::info('Updating map in database', ['update_data' => $updateData]);
        $mapImage->update($updateData);
        \Log::info('Map updated successfully', ['map_id' => $mapImage->id]);

        return redirect()->route('maps.show', $mapImage)->with('success', 'Map updated successfully!');
    }

    public function destroy(MapImage $mapImage)
    {
        // Check if user owns the image or is admin
        if ($mapImage->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete main file from storage if exists
        if ($mapImage->file_path) {
            Storage::disk('public')->delete($mapImage->file_path);
        }

        // Delete map image file if exists
        if ($mapImage->map_image_path) {
            Storage::disk('public')->delete($mapImage->map_image_path);
        }

        // Delete GIS files if they exist
        if ($mapImage->gis_files && is_array($mapImage->gis_files)) {
            foreach ($mapImage->gis_files as $file) {
                if (isset($file['file_path'])) {
                    Storage::disk('public')->delete($file['file_path']);
                }
            }
        }
        
        // Delete record from database
        $mapImage->delete();

        return redirect()->route('dashboard')->with('success', 'Map and all associated files deleted successfully!');
    }
}
