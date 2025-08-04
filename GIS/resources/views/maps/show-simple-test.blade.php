<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mapImage->title }} - GIS Crop Mapping</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                        ← Back to Dashboard
                    </a>
                    <div class="text-gray-300">|</div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $mapImage->title }}</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Map Information</h3>
                        
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-6">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Title</label>
                                <p class="text-sm font-medium text-gray-900">{{ $mapImage->title }}</p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Upload Date</label>
                                <p class="text-sm font-medium text-gray-900">{{ $mapImage->created_at->format('M d, Y - H:i') }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Uploaded By</label>
                                <p class="text-sm font-medium text-gray-900">{{ $mapImage->user->name }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">File Size</label>
                                <p class="text-sm font-medium text-gray-900">{{ number_format($mapImage->file_size / 1024, 2) }} KB</p>
                            </div>
                        </div>

                        @if($mapImage->description)
                        <div class="mb-6">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</label>
                            <p class="text-sm text-gray-700 mt-1">{{ $mapImage->description }}</p>
                        </div>
                        @endif

                        <!-- Image Section -->
                        @if($mapImage->map_image_path && file_exists(public_path('storage/' . $mapImage->map_image_path)))
                        <div class="mb-6">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">Map Image</label>
                            <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" alt="{{ $mapImage->title }}" class="max-w-full h-auto rounded-lg shadow-md">
                        </div>
                        @elseif($mapImage->file_path && file_exists(public_path('storage/' . $mapImage->file_path)))
                        <div class="mb-6">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">Map File</label>
                            <img src="{{ asset('storage/' . $mapImage->file_path) }}" alt="{{ $mapImage->title }}" class="max-w-full h-auto rounded-lg shadow-md">
                        </div>
                        @endif

                        <!-- GIS Files -->
                        @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                        <div class="mb-6">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">GIS Files ({{ count($mapImage->gis_files) }})</label>
                            <div class="space-y-2">
                                @foreach($mapImage->gis_files as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ strtoupper($file['extension'] ?? 'FILE') }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $file['original_name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format(($file['file_size'] ?? 0) / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    @if(isset($file['file_path']) && file_exists(public_path('storage/' . $file['file_path'])))
                                    <a href="{{ asset('storage/' . $file['file_path']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium" download>
                                        Download
                                    </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        @if($mapImage->user_id === auth()->id())
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <a href="{{ route('maps.edit', $mapImage) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                                    Edit Map
                                </a>
                                
                                <form action="{{ route('maps.destroy', $mapImage) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this map?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-sm">
                                        Delete Map
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
