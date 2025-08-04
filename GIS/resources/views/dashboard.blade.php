<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button id="overview-tab" class="tab-button w-1/3 py-2 px-1 text-center border-b-2 border-blue-500 font-medium text-sm text-blue-600 whitespace-nowrap focus:outline-none" onclick="showTab('overview')">
                            Overview
                        </button>
                        <button id="upload-tab" class="tab-button w-1/3 py-2 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap focus:outline-none" onclick="showTab('upload')">
                            Upload Map
                        </button>
                        <button id="gallery-tab" class="tab-button w-1/3 py-2 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap focus:outline-none" onclick="showTab('gallery')">
                            Map Gallery
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div id="overview-content" class="tab-content">
                        <div class="text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Welcome to GIS Crop Land Use Mapping</h3>
                            <p class="mb-4">{{ __("You're logged in!") }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 mb-8">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-blue-800">Total Maps</h4>
                                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\MapImage::count() }}</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-800">Your Maps</h4>
                                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\MapImage::where('user_id', auth()->id())->count() }}</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-purple-800">Recent Uploads</h4>
                                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\MapImage::whereDate('created_at', today())->count() }}</p>
                                </div>
                            </div>

                            <!-- Recent Map Images -->
                            @php
                                $recentMaps = \App\Models\MapImage::with('user')->latest()->take(6)->get();
                            @endphp

                            @if($recentMaps->count() > 0)
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-semibold text-gray-900">Recent Map Uploads</h4>
                                        <button onclick="showTab('gallery')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View All Maps →
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($recentMaps as $map)
                                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                                <div class="aspect-w-16 aspect-h-9">
                                                    <img src="{{ asset('storage/' . $map->file_path) }}" 
                                                         alt="{{ $map->title }}" 
                                                         class="w-full h-32 object-cover">
                                                </div>
                                                <div class="p-3">
                                                    <h5 class="font-medium text-gray-900 text-sm mb-1 truncate">{{ $map->title }}</h5>
                                                    @if($map->description)
                                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ Str::limit($map->description, 60) }}</p>
                                                    @endif
                                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                                        <span>{{ $map->user->name }}</span>
                                                        <span>{{ $map->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{ route('maps.show', $map) }}" 
                                                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                            View Details →
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No map images yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by uploading your first map image.</p>
                                    <div class="mt-4">
                                        <button onclick="showTab('upload')" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Upload Your First Map
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div id="upload-content" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Upload New GIS Map</h3>
                        
                        <form action="{{ route('maps.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- General file upload error -->
                            @error('files')
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                                    <span class="block sm:inline">{{ $message }}</span>
                                </div>
                            @enderror
                            
                            <!-- Basic Information -->
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Basic Information</h4>
                                
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Optional description of the map...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Uploads -->
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">File Uploads</h4>
                                
                                <!-- GIS Files Upload -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">GIS Data Files</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <label for="gis_files" class="cursor-pointer">
                                                    <span class="mt-2 block text-sm font-medium text-gray-900">Upload GIS Files</span>
                                                    <input type="file" id="gis_files" name="gis_files[]" multiple 
                                                           accept=".shp,.dbf,.shx,.prj,.qgz,.qlr,.qmd,.geojson,.kml,.kmz,.gml,.gpx" 
                                                           class="sr-only">
                                                </label>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Supported: SHP, DBF, SHX, PRJ, QGZ, QLR, QMD, GeoJSON, KML, KMZ, GML, GPX
                                                </p>
                                                <p class="text-xs text-gray-400">Max 50MB per file</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="gis-files-list" class="mt-4 space-y-2"></div>
                                </div>

                                <!-- Map Image Upload (Optional) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Map Image (Optional)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <label for="map_image" class="cursor-pointer">
                                                    <span class="mt-2 block text-sm font-medium text-gray-900">Upload Map Image</span>
                                                    <input type="file" id="map_image" name="map_image" 
                                                           accept="image/*" class="sr-only">
                                                </label>
                                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, SVG up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="map-image-preview" class="mt-4 hidden">
                                        <img id="preview-image" src="" alt="Preview" class="max-w-full h-48 object-contain rounded-lg border border-gray-300">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-center">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 text-white font-semibold rounded-lg text-base px-6 py-3.5 inline-flex items-center transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Map
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Gallery Tab -->
                    <div id="gallery-content" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Map Gallery</h3>
                        
                        @php
                            $mapImages = \App\Models\MapImage::with('user')->latest()->get();
                        @endphp

                        @if($mapImages->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($mapImages as $mapImage)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ asset('storage/' . $mapImage->file_path) }}" 
                                                 alt="{{ $mapImage->title }}" 
                                                 class="w-full h-48 object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $mapImage->title }}</h4>
                                            @if($mapImage->description)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($mapImage->description, 100) }}</p>
                                            @endif
                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                                <span>By {{ $mapImage->user->name }}</span>
                                                <span>{{ $mapImage->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-3 flex items-center space-x-2">
                                                <a href="{{ route('maps.show', $mapImage) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View Details
                                                </a>
                                                @if($mapImage->user_id === auth()->id())
                                                    <form action="{{ route('maps.destroy', $mapImage) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this map image?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No map images</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by uploading your first map image.</p>
                                <div class="mt-6">
                                    <button onclick="showTab('upload')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Upload Map
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Remove active styles from all tabs
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active styles to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        // File handling
        document.addEventListener('DOMContentLoaded', function() {
            // GIS files handling
            const gisFilesInput = document.getElementById('gis_files');
            const gisFilesList = document.getElementById('gis-files-list');
            
            if (gisFilesInput) {
                gisFilesInput.addEventListener('change', function() {
                    gisFilesList.innerHTML = '';
                    
                    if (this.files.length > 0) {
                        for (let i = 0; i < this.files.length; i++) {
                            const file = this.files[i];
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                            
                            fileItem.innerHTML = `
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                                        <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 bg-blue-100 px-2 py-1 rounded">
                                    ${file.name.split('.').pop().toUpperCase()}
                                </div>
                            `;
                            
                            gisFilesList.appendChild(fileItem);
                        }
                    }
                });
            }
            
            // Map image preview
            const mapImageInput = document.getElementById('map_image');
            const previewContainer = document.getElementById('map-image-preview');
            const previewImage = document.getElementById('preview-image');
            
            if (mapImageInput) {
                mapImageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                        };
                        
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        previewContainer.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>
