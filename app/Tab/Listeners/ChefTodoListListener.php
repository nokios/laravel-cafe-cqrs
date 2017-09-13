<?php

namespace Nokios\Cafe\Tab\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Nokios\Cafe\Tab\Events\FoodOrdered;

class ChefTodoListListener
{
    /**
     * The events handled by the listener.
     *
     * @var array
     */
    public static $listensFor = [
        FoodOrdered::class,
        FoodPrepared::class
    ];

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
