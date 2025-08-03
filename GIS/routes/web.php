<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MapImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return '<h1>Laravel is working!</h1><p>Database connection: ' . (DB::connection()->getPdo() ? 'OK' : 'Failed') . '</p><p>Maps count: ' . App\Models\MapImage::count() . '</p>';
});

Route::get('/debug-edit/{id}', function ($id) {
    try {
        $mapImage = \App\Models\MapImage::find($id);
        
        if (!$mapImage) {
            return '<h1>Map ID ' . $id . ' not found!</h1><p><a href="/test">Back to test</a></p>';
        }
        
        return '<h1>Debug Edit - Map Found!</h1>
                <p><strong>ID:</strong> ' . $mapImage->id . '</p>
                <p><strong>Title:</strong> ' . $mapImage->title . '</p>
                <p><strong>Crop Type:</strong> ' . $mapImage->crop_type . '</p>
                <p><strong>Hectares:</strong> ' . $mapImage->hectares . '</p>
                <p><strong>Status:</strong> ' . $mapImage->land_status . '</p>
                <p><a href="/maps/' . $mapImage->id . '/edit">Try Real Edit</a></p>
                <p><a href="/maps/' . $mapImage->id . '">View Details</a></p>';
    } catch (Exception $e) {
        return '<h1>Error!</h1><p>' . $e->getMessage() . '</p>';
    }
});

Route::get('/test-map/{id}', function ($id) {
    $mapImage = \App\Models\MapImage::with('user')->find($id);
    
    if (!$mapImage) {
        return '<h1>Map not found!</h1>';
    }
    
    return '<h1>Map Found!</h1>
            <p><strong>Title:</strong> ' . $mapImage->title . '</p>
            <p><strong>Crop Type:</strong> ' . $mapImage->crop_type . '</p>
            <p><strong>Hectares:</strong> ' . $mapImage->hectares . '</p>
            <p><strong>Owner:</strong> ' . $mapImage->user->name . '</p>
            <p><a href="/maps/' . $mapImage->id . '">View Full Details</a></p>';
});

Route::get('/test-dashboard', function () {
    $mapImages = \App\Models\MapImage::with('user')->latest()->get();
    $totalMaps = $mapImages->count();
    $totalHectares = $mapImages->sum('hectares');
    $recentMaps = $mapImages->take(5);
    
    return view('dashboard', compact('mapImages', 'totalMaps', 'totalHectares', 'recentMaps'));
});

Route::get('/dashboard', function () {
    $mapImages = \App\Models\MapImage::with('user')->latest()->get();
    $totalMaps = $mapImages->count();
    $totalHectares = $mapImages->sum('hectares');
    $recentMaps = $mapImages->take(5);
    
    return view('dashboard', compact('mapImages', 'totalMaps', 'totalHectares', 'recentMaps'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Map Image Routes (authentication required)
    Route::get('/maps/create', [MapImageController::class, 'create'])->name('maps.create');
    Route::post('/maps', [MapImageController::class, 'store'])->name('maps.store');
    Route::delete('/maps/{mapImage}', [MapImageController::class, 'destroy'])->name('maps.destroy');
});

// Public map routes (no authentication required)
Route::get('/maps', [MapImageController::class, 'index'])->name('maps.index');
Route::get('/maps/{mapImage}', [MapImageController::class, 'show'])->name('maps.show');
Route::get('/maps/{mapImage}/edit', [MapImageController::class, 'edit'])->name('maps.edit');
Route::patch('/maps/{mapImage}', [MapImageController::class, 'update'])->name('maps.update');

require __DIR__.'/auth.php';
