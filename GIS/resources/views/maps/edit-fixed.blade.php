<!DOCTYPE html>
<html>
<head>
    <title>Edit Map - {{ $mapImage->title ?? 'Unknown' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .current-files { margin: 15px 0; padding: 10px; background: #f8f9fa; border-radius: 4px; }
        .file-item { margin: 5px 0; padding: 5px; border-bottom: 1px solid #eee; }
        .file-item:last-child { border-bottom: none; }
        .checkbox-label { display: inline; font-weight: normal; margin-left: 5px; }
        .error { color: red; margin-top: 5px; }
        .success { color: green; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Map: {{ $mapImage->title ?? 'Unknown Map' }}</h1>
        
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ old('title', $mapImage->title ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $mapImage->description ?? '') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="map_image">Upload New Map Image (Optional):</label>
                <input type="file" id="map_image" name="map_image" accept="image/*">
                @if($mapImage->map_image_path)
                    <div class="current-files">
                        <p><strong>Current Map Image:</strong></p>
                        <div class="file-item">
                            <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" alt="Map Image" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="form-group">
                <label for="gis_files">Upload New GIS Files (Optional):</label>
                <input type="file" id="gis_files" name="gis_files[]" multiple>
                <small>Allowed file types: shp, dbf, shx, prj, qgz, qlr, qmd, geojson, kml, kmz, gml, gpx</small>
                
                @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                    <div class="current-files">
                        <p><strong>Current GIS Files:</strong></p>
                        @foreach($mapImage->gis_files as $index => $file)
                            <div class="file-item">
                                {{ $file['original_name'] ?? 'Unknown file' }}
                                <input type="checkbox" id="delete_gis_files_{{ $index }}" name="delete_gis_files[]" value="{{ $index }}">
                                <label class="checkbox-label" for="delete_gis_files_{{ $index }}">Delete this file</label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit">Update Map</button>
                <a href="{{ route('maps.show', $mapImage) }}" style="text-decoration: none; margin-left: 10px;">
                    <button type="button" class="btn-secondary">Cancel</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
