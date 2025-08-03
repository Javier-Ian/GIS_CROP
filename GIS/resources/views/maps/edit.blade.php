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
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

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

                <!-- File Upload -->
                <div class="mt-6">
                    <label for="map_image" class="block text-sm font-medium text-gray-700 mb-2">Upload New Image (Optional)</label>
                    <input type="file" id="map_image" name="map_image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <!-- Current Image -->
                @if($mapImage->map_image_path && file_exists(public_path('storage/' . $mapImage->map_image_path)))
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" 
                             alt="{{ $mapImage->title }}" 
                             class="w-full max-w-md h-auto rounded-lg border shadow-sm">
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t">
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
