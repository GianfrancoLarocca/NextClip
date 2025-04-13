<?php

use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\ChannelVideoController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\PlaylistVideoController;
use App\Http\Controllers\Api\PublicPlaylistController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\VideoHistoryController;
use App\Http\Controllers\Api\SavedVideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/auth.php';

Route::post('/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // $token = $user->createToken($request->device_name)->plainTextToken;
    $token = $user->createToken($request->device_name, ['*'], now()->addDays(7))->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

// Rotte protette da token
Route::middleware('auth:sanctum')->group(function () {
    // Info utente
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD canali
    Route::apiResource('channels', ChannelController::class)->scoped([
        'channel' => 'slug',
    ]);

    // Video annidati dentro canali
    Route::apiResource('channels.videos', ChannelVideoController::class)->scoped([
        'channel' => 'slug',
        'video' => 'slug',
    ]);

    Route::post('/videos/upload', [VideoUploadController::class, 'store']);

    Route::get('/videos/{video:slug}/comments', [CommentController::class, 'index']);
    Route::post('/videos/{video:slug}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    Route::post('/videos/{video:slug}/like', [LikeController::class, 'toggle']);
    
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{video:slug}', [VideoController::class, 'show']);

    Route::get('/notifications', function () {
        return request()->user()->unreadNotifications()->paginate(20);
    });

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = request()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
    
        return response()->json(['message' => 'Notifica segnata come letta.']);
    });    

    // Iscrizione/disiscrizione ai canali
    Route::post('/channels/{channel:slug}/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/channels/{channel:slug}/unsubscribe', [SubscriptionController::class, 'unsubscribe']);

    Route::apiResource('playlists', PlaylistController::class)->scoped([
        'playlist' => 'slug',
    ]);    

    Route::get('/playlists/{playlist}/videos', [PlaylistVideoController::class, 'index']);
    Route::post('/playlists/{playlist}/videos/{video}', [PlaylistVideoController::class, 'store']);
    Route::delete('/playlists/{playlist}/videos/{video}', [PlaylistVideoController::class, 'destroy']);

    Route::get('/history', [VideoHistoryController::class, 'index']);
    Route::post('/history/{video:slug}', [VideoHistoryController::class, 'store']);
    Route::delete('/history/{video:slug}', [VideoHistoryController::class, 'destroy']);

    Route::get('/search', [SearchController::class, 'index']);

    Route::apiResource('tags', TagController::class);

    Route::get('/videso/{video:slug}/related', [VideoController::class, 'related']);

    Route::post('/videos/{video:slug}/report', [ReportController::class, 'store']);

    Route::get('/saved-videos', [SavedVideoController::class, 'index']);
    Route::post('/saved-videos/{video:slug}', [SavedVideoController::class, 'store']);
    Route::delete('/saved-videos/{video:slug}', [SavedVideoController::class, 'destroy']);
    
});

Route::get('/playlists', [PublicPlaylistController::class, 'index']);
Route::get('/playlists/{playlist}', [PublicPlaylistController::class, 'show']);