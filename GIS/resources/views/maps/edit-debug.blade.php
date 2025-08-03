<!DOCTYPE html>
<html>
<head>
    <title>Edit Map - {{ $mapImage->title ?? 'Unknown' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Map: {{ $mapImage->title ?? 'Unknown Map' }}</h1>
        
        <p><strong>Debug Info:</strong></p>
        <ul>
            <li>Map ID: {{ $mapImage->id ?? 'N/A' }}</li>
            <li>Current Title: {{ $mapImage->title ?? 'N/A' }}</li>
            <li>Current Crop: {{ $mapImage->crop_type ?? 'N/A' }}</li>
            <li>Current Hectares: {{ $mapImage->hectares ?? 'N/A' }}</li>
        </ul>
        
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ old('title', $mapImage->title ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label for="crop_type">Crop Type:</label>
                <input type="text" id="crop_type" name="crop_type" value="{{ old('crop_type', $mapImage->crop_type ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label for="hectares">Hectares:</label>
                <input type="number" id="hectares" name="hectares" value="{{ old('hectares', $mapImage->hectares ?? '') }}" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="{{ old('location', $mapImage->location ?? '') }}">
            </div>
            
            <div class="form-group">
                <label for="land_status">Land Status:</label>
                <select id="land_status" name="land_status" required>
                    <option value="planted" {{ ($mapImage->land_status ?? '') == 'planted' ? 'selected' : '' }}>Planted</option>
                    <option value="harvested" {{ ($mapImage->land_status ?? '') == 'harvested' ? 'selected' : '' }}>Harvested</option>
                    <option value="fallow" {{ ($mapImage->land_status ?? '') == 'fallow' ? 'selected' : '' }}>Fallow</option>
                    <option value="prepared" {{ ($mapImage->land_status ?? '') == 'prepared' ? 'selected' : '' }}>Prepared</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $mapImage->description ?? '') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="map_image">Upload New Image (Optional):</label>
                <input type="file" id="map_image" name="map_image" accept="image/*">
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit">Update Map</button>
                <a href="{{ route('maps.show', $mapImage) }}" style="text-decoration: none;">
                    <button type="button" class="btn-secondary">Cancel</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
