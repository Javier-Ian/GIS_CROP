<!DOCTYPE html>
<html>
<head>
    <title>Edit Map - {{ $mapImage->title ?? 'Unknown' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #4299e1;
            --secondary-color: #3182ce;
            --accent-color: #ed8936;
            --text-color: #2d3748;
            --light-gray: #e2e8f0;
            --bg-color: #f7fafc;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
            --input-bg: #f9fafb;
            --input-border: #dfe3e8;
            --section-bg: #f1f5f9;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            width: 96%;
            margin: 20px auto;
            background: var(--white);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            display: flex;
            align-items: center;
            position: relative;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .page-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        
        .page-header h1 i {
            margin-right: 12px;
            background: var(--primary-color);
            color: white;
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            background-color: var(--input-bg);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            outline: none;
            background-color: var(--white);
        }
        
        textarea.form-control {
            min-height: 140px;
            resize: vertical;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            font-size: 15px;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            min-width: 140px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(49, 130, 206, 0.2);
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
        }
        
        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .alert i {
            font-size: 18px;
            margin-right: 12px;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border-left: 4px solid #48bb78;
        }
        
        .alert-danger {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid #f56565;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            cursor: pointer;
        }
        
        .file-input-wrapper input[type="file"] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-input-button {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            background: var(--primary-color);
            border-radius: 8px;
            font-size: 14px;
            color: white;
            transition: var(--transition);
            box-shadow: 0 2px 4px rgba(66, 153, 225, 0.2);
            width: 220px;
            justify-content: center;
        }
        
        .file-input-button i {
            margin-right: 8px;
        }
        
        .file-input-wrapper:hover .file-input-button {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }
        
        .current-files {
            margin: 20px 0 10px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px dashed #cbd5e0;
        }
        
        .current-files h4 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #4a5568;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .current-files h4 i {
            margin-right: 8px;
            color: var(--secondary-color);
        }
        
        .file-item {
            padding: 10px;
            margin: 8px 0;
            background: var(--white);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .file-item:last-child {
            margin-bottom: 0;
        }
        
        .file-name {
            display: flex;
            align-items: center;
        }
        
        .file-name i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        .delete-file {
            display: flex;
            align-items: center;
        }
        
        .checkbox-custom {
            position: relative;
            display: inline-block;
        }
        
        .checkbox-custom input {
            opacity: 0;
            position: absolute;
        }
        
        .checkbox-custom-label {
            position: relative;
            cursor: pointer;
            padding-left: 25px;
            font-size: 14px;
        }
        
        .checkbox-custom-label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 16px;
            height: 16px;
            border: 2px solid #cbd5e0;
            background: var(--white);
            border-radius: 3px;
            transition: var(--transition);
        }
        
        .checkbox-custom input:checked + .checkbox-custom-label:before {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .checkbox-custom-label:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 5px;
            width: 6px;
            height: 10px;
            border: solid var(--white);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
            transition: var(--transition);
        }
        
        .checkbox-custom input:checked + .checkbox-custom-label:after {
            opacity: 1;
        }
        
        .form-footer {
            margin-top: 30px;
            padding: 20px 0;
            border-top: 1px solid var(--light-gray);
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .form-section {
            margin-bottom: 35px;
            background: var(--section-bg);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(203, 213, 224, 0.4);
        }
        
        .form-section-title i {
            margin-right: 10px;
            background-color: var(--secondary-color);
            color: white;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .image-preview {
            max-width: 300px;
            max-height: 220px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid var(--light-gray);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin: 5px 0;
        }
        
        .image-preview:hover {
            transform: scale(1.02);
        }
        
        .image-preview img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .help-text {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-map-marked-alt"></i> Edit Map: {{ $mapImage->title ?? 'Unknown Map' }}</h1>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('maps.update', $mapImage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-info-circle"></i> Basic Information
                </div>
                
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $mapImage->title ?? '') }}" required placeholder="Enter map title">
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter a detailed description of this map">{{ old('description', $mapImage->description ?? '') }}</textarea>
                    <p class="help-text">Provide details about the map's contents and purpose</p>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-image"></i> Map Image
                </div>
                
                <div class="form-group">
                    <label class="form-label">Upload New Map Image (Optional)</label>
                    <div class="file-input-wrapper">
                        <div class="file-input-button">
                            <i class="fas fa-upload"></i> Choose Image File
                        </div>
                        <input type="file" id="map_image" name="map_image" accept="image/*">
                    </div>
                    <p class="help-text">Supported formats: JPEG, PNG, JPG, GIF, SVG (Max: 10MB)</p>
                    
                    @if($mapImage->map_image_path)
                        <div class="current-files">
                            <h4><i class="fas fa-image"></i> Current Map Image</h4>
                            <div class="file-item">
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" alt="Map Image">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="current-files" style="text-align: center; padding: 20px;">
                            <i class="fas fa-image" style="color: #a0aec0; font-size: 36px; margin-bottom: 10px;"></i>
                            <p>No map image currently uploaded</p>
                            <p style="font-size: 13px; color: #718096; margin-top: 5px;">Upload a map image to visualize your geographic data</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-file-alt"></i> GIS Files
                </div>
                
                <div class="form-group">
                    <label class="form-label">Upload New GIS Files (Optional)</label>
                    <div class="file-input-wrapper">
                        <div class="file-input-button">
                            <i class="fas fa-upload"></i> Choose GIS Files
                        </div>
                        <input type="file" id="gis_files" name="gis_files[]" multiple>
                    </div>
                    <p class="help-text">Allowed file types: shp, dbf, shx, prj, qgz, qlr, qmd, geojson, kml, kmz, gml, gpx (Max: 50MB each)</p>
                    
                    @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                        <div class="current-files">
                            <h4><i class="fas fa-layer-group"></i> Current GIS Files</h4>
                            @foreach($mapImage->gis_files as $index => $file)
                                <div class="file-item">
                                    <div class="file-name">
                                        <i class="fas fa-file-code"></i> 
                                        {{ $file['original_name'] ?? 'Unknown file' }}
                                    </div>
                                    <div class="delete-file">
                                        <div class="checkbox-custom">
                                            <input type="checkbox" id="delete_gis_files_{{ $index }}" name="delete_gis_files[]" value="{{ $index }}">
                                            <label class="checkbox-custom-label" for="delete_gis_files_{{ $index }}">Delete this file</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="current-files" style="text-align: center; padding: 20px;">
                            <i class="fas fa-info-circle" style="color: #4a5568; font-size: 24px; margin-bottom: 10px;"></i>
                            <p>No GIS files currently associated with this map.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Map
                </button>
                <a href="{{ route('maps.show', $mapImage) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
    
    <script>
        // Show file name when selected
        document.getElementById('map_image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const parent = this.closest('.file-input-wrapper');
                const button = parent.querySelector('.file-input-button');
                button.innerHTML = '<i class="fas fa-check"></i> ' + (fileName.length > 25 ? fileName.substring(0, 22) + '...' : fileName);
                button.style.background = '#48bb78';
                
                // Preview image
                if (e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Create preview element if it doesn't exist
                        let previewContainer = parent.parentNode.querySelector('.preview-container');
                        if (!previewContainer) {
                            previewContainer = document.createElement('div');
                            previewContainer.className = 'preview-container';
                            previewContainer.style.marginTop = '15px';
                            parent.parentNode.appendChild(previewContainer);
                        }
                        
                        previewContainer.innerHTML = `
                            <p style="margin-bottom: 8px; font-size: 14px; color: #4a5568;"><i class="fas fa-eye"></i> New image preview:</p>
                            <div class="image-preview" style="max-width: 250px;">
                                <img src="${e.target.result}" alt="Preview">
                            </div>
                        `;
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            }
        });
        
        document.getElementById('gis_files').addEventListener('change', function(e) {
            const fileCount = e.target.files.length;
            if (fileCount) {
                const parent = this.closest('.file-input-wrapper');
                const button = parent.querySelector('.file-input-button');
                button.innerHTML = '<i class="fas fa-check"></i> ' + fileCount + ' file(s) selected';
                button.style.background = '#48bb78';
                
                // Show file list
                if (fileCount > 0) {
                    let fileListContainer = parent.parentNode.querySelector('.file-list-container');
                    if (!fileListContainer) {
                        fileListContainer = document.createElement('div');
                        fileListContainer.className = 'file-list-container';
                        fileListContainer.style.marginTop = '15px';
                        parent.parentNode.appendChild(fileListContainer);
                    }
                    
                    let fileListHTML = `
                        <p style="margin-bottom: 8px; font-size: 14px; color: #4a5568;"><i class="fas fa-list"></i> Selected files:</p>
                        <div style="max-height: 150px; overflow-y: auto; padding: 10px; background: #f8fafc; border-radius: 8px;">
                    `;
                    
                    for (let i = 0; i < e.target.files.length; i++) {
                        const file = e.target.files[i];
                        const extension = file.name.split('.').pop().toLowerCase();
                        let iconClass = 'fas fa-file';
                        
                        // Assign icon based on file type
                        if (['shp', 'dbf', 'shx', 'prj'].includes(extension)) {
                            iconClass = 'fas fa-globe';
                        } else if (['geojson', 'kml', 'kmz'].includes(extension)) {
                            iconClass = 'fas fa-map';
                        } else if (['qgz', 'qlr'].includes(extension)) {
                            iconClass = 'fas fa-layer-group';
                        }
                        
                        fileListHTML += `
                            <div style="display: flex; align-items: center; margin-bottom: 8px; padding: 5px;">
                                <i class="${iconClass}" style="margin-right: 8px; color: var(--primary-color);"></i>
                                <span style="font-size: 13px;">${file.name}</span>
                                <span style="font-size: 12px; color: #718096; margin-left: auto;">${(file.size / 1024).toFixed(1)} KB</span>
                            </div>
                        `;
                    }
                    
                    fileListHTML += `</div>`;
                    fileListContainer.innerHTML = fileListHTML;
                }
            }
        });
        
        // Confirm before submitting if files are marked for deletion
        document.querySelector('form').addEventListener('submit', function(e) {
            const deleteBoxes = document.querySelectorAll('input[name="delete_gis_files[]"]:checked');
            if (deleteBoxes.length > 0) {
                const confirmed = confirm(`You've marked ${deleteBoxes.length} file(s) for deletion. This cannot be undone. Proceed?`);
                if (!confirmed) {
                    e.preventDefault();
                }
            }
        });
        
        // Highlight checkboxes on change
        document.querySelectorAll('.checkbox-custom input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fileItem = this.closest('.file-item');
                if (this.checked) {
                    fileItem.style.backgroundColor = '#FEEBC8';
                    fileItem.style.transition = 'background-color 0.3s';
                } else {
                    fileItem.style.backgroundColor = '';
                }
            });
        });
    </script>
</body>
</html>
