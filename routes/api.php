<?php

use App\Models\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-signal-result/{id}', function ($id) {
    $signal = Signal::findOrFail($id);
    $res = [
        'win'       => $signal->win,
        'pips'      => $signal->pips,
        'status_id' => $signal->status_id,
    ];
    return response()->json($res, 200);
});

Route::get('get-signal-home/{id}', function ($id) {
    $signal = Signal::findOrFail($id);
    $res = [
        'home'      => $signal->home,
        'home_lock' => $signal->home_lock,
    ];
    return response()->json($res, 200);
});
