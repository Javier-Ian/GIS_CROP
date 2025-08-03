<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\MapFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maps = Map::where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('maps.index', compact('maps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('maps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'crop_type' => 'nullable|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'planting_date' => 'nullable|date',
            'harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'notes' => 'nullable|string',
            'map_files.*' => 'nullable|file|mimes:qgz,qlr,qmd,shp,kml,gpx,geojson|max:50240',
        ]);

        // Store the map image
        $imagePath = $request->file('image')->store('maps/images', 'public');

        // Create the map record
        $map = Map::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'crop_type' => $request->crop_type,
            'area_size' => $request->area_size,
            'planting_date' => $request->planting_date,
            'harvest_date' => $request->harvest_date,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Store additional map files if uploaded
        if ($request->hasFile('map_files')) {
            foreach ($request->file('map_files') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('maps/files', $filename, 'public');

                MapFile::create([
                    'map_id' => $map->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('maps.show', $map)
            ->with('success', 'Map uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Map $map)
    {
        // Check if user can view this map
        if ($map->user_id !== Auth::id()) {
            abort(403);
        }

        return view('maps.show', compact('map'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Map $map)
    {
        // Check if user can edit this map
        if ($map->user_id !== Auth::id()) {
            abort(403);
        }

        return view('maps.edit', compact('map'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Map $map)
    {
        // Check if user can update this map
        if ($map->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'crop_type' => 'nullable|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'planting_date' => 'nullable|date',
            'harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'notes' => 'nullable|string',
            'map_files.*' => 'nullable|file|mimes:qgz,qlr,qmd,shp,kml,gpx,geojson|max:50240',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'crop_type' => $request->crop_type,
            'area_size' => $request->area_size,
            'planting_date' => $request->planting_date,
            'harvest_date' => $request->harvest_date,
            'notes' => $request->notes,
        ];

        // Update image if new one is uploaded
        if ($request->hasFile('image')) {
            // Delete old image
            if ($map->image_path) {
                Storage::disk('public')->delete($map->image_path);
            }
            
            $updateData['image_path'] = $request->file('image')->store('maps/images', 'public');
        }

        $map->update($updateData);

        // Add new map files if uploaded
        if ($request->hasFile('map_files')) {
            foreach ($request->file('map_files') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('maps/files', $filename, 'public');

                MapFile::create([
                    'map_id' => $map->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('maps.show', $map)
            ->with('success', 'Map updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Map $map)
    {
        // Check if user can delete this map
        if ($map->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated files
        if ($map->image_path) {
            Storage::disk('public')->delete($map->image_path);
        }

        // Delete map files
        foreach ($map->mapFiles as $mapFile) {
            Storage::disk('public')->delete($mapFile->file_path);
            $mapFile->delete();
        }

        $map->delete();

        return redirect()->route('maps.index')
            ->with('success', 'Map deleted successfully!');
    }

    /**
     * Delete a specific map file
     */
    public function deleteFile(MapFile $mapFile)
    {
        // Check if user can delete this file
        if ($mapFile->map->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($mapFile->file_path);
        $mapFile->delete();

        return back()->with('success', 'File deleted successfully!');
    }
}
