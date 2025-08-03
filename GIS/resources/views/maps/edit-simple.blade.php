<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $mapImage->title }} - GIS Crop Mapping</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-6">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b mb-6">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('maps.show', $mapImage) }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                        ← Back to Map Details
                    </a>
                    <div class="text-gray-300">|</div>
                    <h1 class="text-xl font-semibold text-gray-900">Edit: {{ $mapImage->title }}</h1>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Main Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Map Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $mapImage->title) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Crop Type -->
                        <div>
                            <label for="crop_type" class="block text-sm font-medium text-gray-700 mb-1">Crop Type *</label>
                            <input type="text" id="crop_type" name="crop_type" value="{{ old('crop_type', $mapImage->crop_type) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('crop_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Area (Hectares) -->
                        <div>
                            <label for="hectares" class="block text-sm font-medium text-gray-700 mb-1">Area (Hectares) *</label>
                            <input type="number" id="hectares" name="hectares" step="0.01" min="0.01" 
                                   value="{{ old('hectares', $mapImage->hectares) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('hectares')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $mapImage->location) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Planting Date -->
                        <div>
                            <label for="planting_date" class="block text-sm font-medium text-gray-700 mb-1">Planting Date</label>
                            <input type="date" id="planting_date" name="planting_date" 
                                   value="{{ old('planting_date', $mapImage->planting_date ? $mapImage->planting_date->format('Y-m-d') : '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('planting_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Land Status -->
                        <div>
                            <label for="land_status" class="block text-sm font-medium text-gray-700 mb-1">Land Status *</label>
                            <select id="land_status" name="land_status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="planted" {{ old('land_status', $mapImage->land_status) == 'planted' ? 'selected' : '' }}>Planted</option>
                                <option value="harvested" {{ old('land_status', $mapImage->land_status) == 'harvested' ? 'selected' : '' }}>Harvested</option>
                                <option value="fallow" {{ old('land_status', $mapImage->land_status) == 'fallow' ? 'selected' : '' }}>Fallow</option>
                                <option value="prepared" {{ old('land_status', $mapImage->land_status) == 'prepared' ? 'selected' : '' }}>Prepared</option>
                            </select>
                            @error('land_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $mapImage->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Map Image Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Map Image</h2>
                    
                    <!-- Current Image Display -->
                    @if($mapImage->map_image_path && file_exists(public_path('storage/' . $mapImage->map_image_path)))
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Current Image</h3>
                            <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" alt="{{ $mapImage->title }}" 
                                 class="w-full max-w-md h-auto rounded-lg border shadow-sm">
                        </div>
                    @elseif($mapImage->file_path && file_exists(public_path('storage/' . $mapImage->file_path)))
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Current Image</h3>
                            <img src="{{ asset('storage/' . $mapImage->file_path) }}" alt="{{ $mapImage->title }}" 
                                 class="w-full max-w-md h-auto rounded-lg border shadow-sm">
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="w-full max-w-md h-48 bg-gray-200 rounded-lg border flex items-center justify-center">
                                <div class="text-center">
                                    <p class="mt-2 text-sm text-gray-500">No current image</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Image -->
                    <div>
                        <label for="map_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Image (Optional)
                        </label>
                        <input type="file" id="map_image" name="map_image" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('map_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current GIS Files Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">GIS Files</h2>
                    
                    <!-- Current Files -->
                    @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Current Files ({{ count($mapImage->gis_files) }})</h3>
                            <div class="space-y-3">
                                @foreach($mapImage->gis_files as $index => $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                        <div class="flex items-center min-w-0 flex-1">
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-xs font-bold text-blue-800">{{ strtoupper($file['extension'] ?? 'FILE') }}</span>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format(($file['file_size'] ?? 0) / 1024 / 1024, 2) }} MB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @if(isset($file['file_path']) && file_exists(public_path('storage/' . $file['file_path'])))
                                                <a href="{{ asset('storage/' . $file['file_path']) }}" download="{{ $file['original_name'] ?? 'download' }}"
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    Download
                                                </a>
                                            @endif
                                            <input type="checkbox" name="delete_gis_files[]" value="{{ $index }}" 
                                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label class="text-xs text-red-600">Delete</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mb-6">No GIS files currently uploaded.</p>
                    @endif

                    <!-- Upload New GIS Files -->
                    <div>
                        <label for="gis_files" class="block text-sm font-medium text-gray-700 mb-2">
                            Add New GIS Files (Optional)
                        </label>
                        <input type="file" id="gis_files" name="gis_files[]" multiple
                               accept=".shp,.dbf,.shx,.prj,.qgz,.qlr,.qmd,.geojson,.kml,.kmz,.gml,.gpx"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        @error('gis_files.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('maps.show', $mapImage) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded">
                        Update Map
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
