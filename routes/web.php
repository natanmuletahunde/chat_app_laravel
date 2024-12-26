<?php

use Illuminate\Support\Facades\Route;
use App\Models\Message;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('chat');
});

Route::get('/messages', function () {
    return Message::all();
});

Route::post('/messages', function (Request $request) {
    $message = Message::create([
        'username' => $request->username,
        'message' => $request->message,
    ]);

    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return $message;
});

