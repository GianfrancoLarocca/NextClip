<?php

use App\Http\Controllers\Api\ChannelController;
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

// 🔐 Rotte protette da token
Route::middleware('auth:sanctum')->group(function () {
    // Info utente
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD canali
    Route::apiResource('channels', ChannelController::class);
});