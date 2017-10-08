<?php

namespace Nokios\Cafe\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Nokios\Cafe\Http\Requests\AddMenuItem;
use Nokios\Cafe\Http\Resources\MenuItemCollection;
use Nokios\Cafe\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $query = MenuItem::query();

        if ($request->has('is_drink')) {
            $isDrink = $request->get('is_drink');
            if ($isDrink) {
                $query->isDrink();
            } else {
                $query->isNotDrink();
            }
        }

        return JsonResponse::create(
            \Nokios\Cafe\Http\Resources\MenuItem::collection(
                $query->get()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Nokios\Cafe\Http\Requests\AddMenuItem $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(AddMenuItem $request)
    {
        $menuItem = MenuItem::create($request->input());

        return new JsonResponse(new \Nokios\Cafe\Http\Resources\MenuItem($menuItem), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Nokios\Cafe\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Nokios\Cafe\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem)
    {
        //
    }
}
