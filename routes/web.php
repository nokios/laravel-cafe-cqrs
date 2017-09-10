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
    return view('open-tabs', [
        'tabs' => (new \Nokios\Cafe\Tab\ReadModels\EventSourcedOpenTabQueries())->getOpenTabs()
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

Route::get('/tab/{id}', function ($id) {
    return view('tab', [
        'tab' => (new \Nokios\Cafe\Tab\TabRepository)->load(\Ramsey\Uuid\Uuid::fromString($id)),
        'foodItems' => [[
            'number' => 100,
            'description' => 'Breakfast Special',
            'price' => 4.50
        ]],
        'drinkItems' => [[
            'number' => 200,
            'description' => 'Orange Juice',
            'price' => 2.50
        ]],
    ]);
});