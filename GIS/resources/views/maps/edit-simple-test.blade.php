<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Map - Simple Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Map: {{ $mapImage->title ?? 'Unknown' }}</h1>
        
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" value="{{ $mapImage->title ?? '' }}" 
                       class="w-full px-3 py-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Crop Type</label>
                <input type="text" name="crop_type" value="{{ $mapImage->crop_type ?? '' }}" 
                       class="w-full px-3 py-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Hectares</label>
                <input type="number" name="hectares" value="{{ $mapImage->hectares ?? '' }}" 
                       step="0.01" class="w-full px-3 py-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Location</label>
                <input type="text" name="location" value="{{ $mapImage->location ?? '' }}" 
                       class="w-full px-3 py-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Land Status</label>
                <select name="land_status" class="w-full px-3 py-2 border rounded" required>
                    <option value="planted" {{ ($mapImage->land_status ?? '') == 'planted' ? 'selected' : '' }}>Planted</option>
                    <option value="harvested" {{ ($mapImage->land_status ?? '') == 'harvested' ? 'selected' : '' }}>Harvested</option>
                    <option value="fallow" {{ ($mapImage->land_status ?? '') == 'fallow' ? 'selected' : '' }}>Fallow</option>
                    <option value="prepared" {{ ($mapImage->land_status ?? '') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Upload New Image</label>
                <input type="file" name="map_image" accept="image/*" class="w-full px-3 py-2 border rounded">
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ route('maps.show', $mapImage) }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Update Map
                </button>
            </div>
        </form>
    </div>
</body>
</html>
