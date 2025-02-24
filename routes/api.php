<?php

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\TestingController;

Route::get('/user', function (Request $request) {
    return [
        'message' => 'User info',
        'status' => 'success',
        'user' => array_merge_recursive($request->user()->toArray(), 
                    ['abbilities' => [
                        "read" => $request->user()->tokenCan("read"),
                        "create" => $request->user()->tokenCan("create"),
                        "update" => $request->user()->tokenCan("update"),
                        "delete" => $request->user()->tokenCan("delete")
                    ]]),
    ];
})->middleware('auth:sanctum');

// Create Token
Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    // ['read', 'create','update','delete'] || ['*']
    return $user->createToken($request->device_name, ['read'], now()->addHour(2))->plainTextToken;
});


// Group
Route::middleware('auth:sanctum')->group(function () {

    // Single action controller
    Route::get('/testing/test', [TestingController::class, 'test'])->name('testing.test');
    Route::post('/testing/search', [TestingController::class, 'search'])->name('testing.search');

    // Resource controller
    Route::apiResources([
        'testing' => TestingController::class,
    ]);
    
});