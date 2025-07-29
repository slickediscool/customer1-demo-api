<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'OK']);
});

Route::post('/messages', function (Request $request) {
    return response()->json(['message' => 'Message received', 'data' => $request->all()]);
});

Route::get('/messages', function () {
    return response()->json(['messages' => ['Sample message 1', 'Sample message 2']]);
});

Route::post('/chat', function (Request $request) {
    return response()->json(['response' => 'This is a simple chat response']);
});
