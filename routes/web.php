<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Handlers\OpenTabHandler;

Route::get('/', function () {
    return view('tab', [
        'tabs' => (new \Nokios\Cafe\Tab\TabRepository)->getOpenTabs()
    ]);
});

Route::post('/open-tab', function (Request $request) {
    $waiter = $request->input('waiter');
    $tableNumber = $request->input('tableNumber');

    \Illuminate\Support\Facades\Log::info("Opening tab for $waiter and $tableNumber");

    $tabId = \Ramsey\Uuid\Uuid::uuid4();
    $command = new OpenTab($tabId, $tableNumber, $waiter);
    $commandHandler = new OpenTabHandler($command);
    $commandHandler->handle();

    return redirect('/');
});