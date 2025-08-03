<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $mapImage->title ?? 'Map' }} - GIS Crop Mapping</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Map</h1>
                    <p class="text-gray-600">{{ $mapImage->title ?? 'Unknown Map' }}</p>
                </div>
                <a href="{{ route('maps.show', $mapImage) }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    ← Back to Details
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Map Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" 
                               value="{{ old('title', $mapImage->title ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <!-- Crop Type -->
                    <div>
                        <label for="crop_type" class="block text-sm font-medium text-gray-700 mb-2">Crop Type *</label>
                        <input type="text" id="crop_type" name="crop_type" 
                               value="{{ old('crop_type', $mapImage->crop_type ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <!-- Hectares -->
                    <div>
                        <label for="hectares" class="block text-sm font-medium text-gray-700 mb-2">Area (Hectares) *</label>
                        <input type="number" id="hectares" name="hectares" 
                               value="{{ old('hectares', $mapImage->hectares ?? '') }}" 
                               step="0.01" min="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" id="location" name="location" 
                               value="{{ old('location', $mapImage->location ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Planting Date -->
                    <div>
                        <label for="planting_date" class="block text-sm font-medium text-gray-700 mb-2">Planting Date</label>
                        <input type="date" id="planting_date" name="planting_date" 
                               value="{{ old('planting_date', $mapImage->planting_date ? $mapImage->planting_date->format('Y-m-d') : '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Land Status -->
                    <div>
                        <label for="land_status" class="block text-sm font-medium text-gray-700 mb-2">Land Status *</label>
                        <select id="land_status" name="land_status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                required>
                            <option value="planted" {{ old('land_status', $mapImage->land_status ?? '') == 'planted' ? 'selected' : '' }}>Planted</option>
                            <option value="harvested" {{ old('land_status', $mapImage->land_status ?? '') == 'harvested' ? 'selected' : '' }}>Harvested</option>
                            <option value="fallow" {{ old('land_status', $mapImage->land_status ?? '') == 'fallow' ? 'selected' : '' }}>Fallow</option>
                            <option value="prepared" {{ old('land_status', $mapImage->land_status ?? '') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $mapImage->description ?? '') }}</textarea>
                </div>
            </div>

            <!-- Map Image Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Map Image</h2>
                
                <!-- Current Image -->
                @if($mapImage->map_image_path && file_exists(public_path('storage/' . $mapImage->map_image_path)))
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" 
                             alt="{{ $mapImage->title }}" 
                             class="w-full max-w-md h-auto rounded-lg border shadow-sm">
                    </div>
                @elseif($mapImage->file_path && file_exists(public_path('storage/' . $mapImage->file_path)))
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ asset('storage/' . $mapImage->file_path) }}" 
                             alt="{{ $mapImage->title }}" 
                             class="w-full max-w-md h-auto rounded-lg border shadow-sm">
                    </div>
                @endif

                <!-- Upload New Image -->
                <div>
                    <label for="map_image" class="block text-sm font-medium text-gray-700 mb-2">Upload New Image (Optional)</label>
                    <input type="file" id="map_image" name="map_image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <!-- GIS Files Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">GIS Files Management</h2>
                
                <!-- Current GIS Files -->
                @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">Current GIS Files ({{ count($mapImage->gis_files) }})</h3>
                        <div class="space-y-3">
                            @foreach($mapImage->gis_files as $index => $file)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                                    <div class="flex items-center min-w-0 flex-1">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="text-xs font-bold text-blue-800">
                                                    {{ strtoupper(substr($file['extension'] ?? 'FILE', 0, 3)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $file['original_name'] ?? 'Unknown file' }}
                                            </p>
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span>{{ number_format(($file['file_size'] ?? 0) / 1024 / 1024, 2) }} MB</span>
                                                <span>{{ $file['extension'] ?? 'unknown' }}</span>
                                                @if(isset($file['upload_date']))
                                                    <span>{{ $file['upload_date'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if(isset($file['file_path']) && file_exists(public_path('storage/' . $file['file_path'])))
                                            <a href="{{ asset('storage/' . $file['file_path']) }}" 
                                               download="{{ $file['original_name'] ?? 'download' }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Download
                                            </a>
                                        @endif
                                        <div class="flex items-center">
                                            <input type="checkbox" name="delete_gis_files[]" value="{{ $index }}" 
                                                   id="delete_{{ $index }}"
                                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label for="delete_{{ $index }}" class="ml-2 text-sm text-red-600">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-sm text-gray-600">
                            <strong>Note:</strong> Check the "Delete" checkbox next to files you want to remove.
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500 text-center">No GIS files currently uploaded.</p>
                    </div>
                @endif

                <!-- Upload New GIS Files -->
                <div>
                    <label for="gis_files" class="block text-sm font-medium text-gray-700 mb-2">
                        Add New GIS Files (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gis_files" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload GIS files</span>
                                    <input id="gis_files" name="gis_files[]" type="file" class="sr-only" multiple 
                                           accept=".shp,.dbf,.shx,.prj,.qgz,.qlr,.qmd,.geojson,.kml,.kmz,.gml,.gpx,.json">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                Supports: .shp, .dbf, .shx, .prj, .qgz, .qlr, .qmd, .geojson, .kml, .kmz, .gml, .gpx
                            </p>
                        </div>
                    </div>
                    <div id="selected-files" class="mt-3 text-sm text-gray-600"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <a href="{{ route('maps.show', $mapImage) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-3 px-6 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded">
                    Update Map
                </button>
            </div>
        </form>
    </div>

    <script>
        // File upload preview functionality
        document.getElementById('gis_files').addEventListener('change', function(e) {
            const files = e.target.files;
            const selectedFilesDiv = document.getElementById('selected-files');
            
            if (files.length > 0) {
                let fileList = '<strong>Selected files:</strong><br>';
                for (let i = 0; i < files.length; i++) {
                    fileList += `• ${files[i].name} (${(files[i].size / 1024 / 1024).toFixed(2)} MB)<br>`;
                }
                selectedFilesDiv.innerHTML = fileList;
            } else {
                selectedFilesDiv.innerHTML = '';
            }
        });

        // Form submission handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                // Disable submit button to prevent double submission
                submitBtn.disabled = true;
                submitBtn.textContent = 'Updating...';
                
                // Show confirmation for file deletions
                const deleteCheckboxes = document.querySelectorAll('input[name="delete_gis_files[]"]:checked');
                if (deleteCheckboxes.length > 0) {
                    const confirmed = confirm(`Are you sure you want to delete ${deleteCheckboxes.length} GIS file(s)? This action cannot be undone.`);
                    if (!confirmed) {
                        e.preventDefault();
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Update Map';
                        return;
                    }
                }
                
                // Re-enable after 10 seconds in case of error
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Update Map';
                }, 10000);
            });
        });
    </script>
</body>
</html>
