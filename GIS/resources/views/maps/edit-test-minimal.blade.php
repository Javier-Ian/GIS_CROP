<!DOCTYPE html>
<html>
<head>
    <title>Edit Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Map: {{ $mapImage->title }}</h1>
        
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ $mapImage->title }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4">{{ $mapImage->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="map_image">New Map Image (optional):</label>
                <input type="file" id="map_image" name="map_image" accept="image/*">
            </div>
            
            <div class="form-group">
                <button type="submit">Save Changes</button>
                <a href="{{ route('maps.show', $mapImage) }}" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
        
        <hr style="margin: 30px 0;">
        
        <p><strong>Debug Info:</strong></p>
        <p>Map ID: {{ $mapImage->id }}</p>
        <p>Title: {{ $mapImage->title }}</p>
        <p>File Path: {{ $mapImage->file_path }}</p>
        <p>Original Name: {{ $mapImage->original_name }}</p>
    </div>
</body>
</html>
