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
        <!-- Compact Header with Back Button -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                    <div class="text-gray-300">|</div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $mapImage->title }}</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Content -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 text-gray-900">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Image Section -->
                            <div class="lg:col-span-1">
                                @if($mapImage->map_image_path && file_exists(public_path('storage/' . $mapImage->map_image_path)))
                                    <img src="{{ asset('storage/' . $mapImage->map_image_path) }}" alt="{{ $mapImage->title }}" class="w-full h-auto rounded-lg shadow-md">
                                @elseif($mapImage->file_path && file_exists(public_path('storage/' . $mapImage->file_path)))
                                    <img src="{{ asset('storage/' . $mapImage->file_path) }}" alt="{{ $mapImage->title }}" class="w-full h-auto rounded-lg shadow-md">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">Map Preview</p>
                                            <p class="text-xs text-gray-400">{{ $mapImage->original_name ?? 'No image' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Details Section - Compact Grid -->
                            <div class="lg:col-span-2 space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Map Information</h3>
                                
                                <!-- Key Details in 2-column grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Title</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->title }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Crop Type</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->crop_type ?: 'Not specified' }}</p>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Area</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->hectares ? number_format($mapImage->hectares, 2) . ' ha' : 'Not specified' }}</p>
                                    </div>

                                    @if($mapImage->location)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->location }}</p>
                                    </div>
                                    @endif

                                    @if($mapImage->planting_date)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Planting Date</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->planting_date->format('M d, Y') }}</p>
                                    </div>
                                    @endif

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                        <p class="text-sm font-medium">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $mapImage->land_status === 'planted' ? 'bg-green-100 text-green-800' : 
                                                   ($mapImage->land_status === 'harvested' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($mapImage->land_status === 'fallow' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ ucfirst($mapImage->land_status ?: 'planted') }}
                                            </span>
                                        </p>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Uploaded by</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->user->name }}</p>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Upload Date</label>
                                        <p class="text-sm font-medium text-gray-900">{{ $mapImage->created_at->format('M d, Y - H:i') }}</p>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">File Size</label>
                                        <p class="text-sm font-medium text-gray-900">{{ number_format($mapImage->file_size / 1024, 2) }} KB</p>
                                    </div>
                                </div>

                                @if($mapImage->description)
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $mapImage->description }}</p>
                                </div>
                                @endif

                                @if($mapImage->gis_files && count($mapImage->gis_files) > 0)
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">GIS Files ({{ count($mapImage->gis_files) }})</label>
                                    <div class="space-y-2">
                                        @foreach($mapImage->gis_files as $file)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded border">
                                            <div class="flex items-center min-w-0 flex-1">
                                                <div class="flex-shrink-0 mr-2">
                                                    @switch(strtoupper($file['extension']))
                                                        @case('QGZ')
                                                        @case('QLR')
                                                        @case('QMD')
                                                            <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                                                                <span class="text-xs font-bold text-green-800">{{ strtoupper($file['extension']) }}</span>
                                                            </div>
                                                            @break
                                                        @case('SHP')
                                                        @case('DBF')
                                                        @case('SHX')
                                                        @case('PRJ')
                                                            <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                                                <span class="text-xs font-bold text-blue-800">{{ strtoupper($file['extension']) }}</span>
                                                            </div>
                                                            @break
                                                        @case('GEOJSON')
                                                        @case('KML')
                                                        @case('KMZ')
                                                        @case('GML')
                                                        @case('GPX')
                                                            <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center">
                                                                <span class="text-xs font-bold text-purple-800">{{ strtoupper($file['extension']) }}</span>
                                                            </div>
                                                            @break
                                                        @default
                                                            <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center">
                                                                <span class="text-xs font-bold text-gray-800">{{ strtoupper($file['extension']) }}</span>
                                                            </div>
                                                    @endswitch
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $file['original_name'] }}</p>
                                                    <p class="text-xs text-gray-500">{{ number_format($file['file_size'] / 1024 / 1024, 2) }} MB</p>
                                                </div>
                                            </div>
                                            @if(file_exists(public_path('storage/' . $file['file_path'])))
                                                <a href="{{ asset('storage/' . $file['file_path']) }}" 
                                                   download="{{ $file['original_name'] }}"
                                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium whitespace-nowrap ml-2">
                                                    Download
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs whitespace-nowrap ml-2">Unavailable</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Action Buttons -->
                                @if($mapImage->user_id === auth()->id())
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('maps.edit', $mapImage) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Map
                                        </a>
                                        <form action="{{ route('maps.destroy', $mapImage) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this map? This action cannot be undone.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-sm inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete Map
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @else
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500">You can only edit maps that you have uploaded.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
