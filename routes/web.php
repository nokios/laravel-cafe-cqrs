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
use Illuminate\Validation\Validator;
use Nokios\Cafe\Tab\Commands\OpenTab;
use Nokios\Cafe\Tab\Commands\PlaceOrder;
use Nokios\Cafe\Tab\Handlers\OpenTabHandler;
use Nokios\Cafe\Tab\Handlers\PlaceOrderHandler;

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
        'foodItems' => \Nokios\Cafe\MenuItem::isNotDrink()->get()->toArray(),
        'drinkItems' => \Nokios\Cafe\MenuItem::isDrink()->get()->toArray(),
    ]);
});

Route::post('/tab/{id}/add-items', function ($id) {
//    $tabId = \Ramsey\Uuid\Uuid::fromString($id);
//    $command = new PlaceOrder($tabId, []);
//    $commandHandler = new PlaceOrderHandler($command);
//    $commandHandler->handle();
});

Route::resource('menu', 'MenuController');

Route::post('/menu/add-item', function (\Nokios\Cafe\Http\Requests\AddMenuItem $request) {

    $tabId = $request->get('tabId');
    \Log::critical("Thingie thing thing");
    $menuItem = \Nokios\Cafe\MenuItem::create($request->only('is_drink', 'description', 'price'));
    \Log::critical(json_encode($menuItem->getAttributes()));
    return redirect('/tab/' . $tabId);
});