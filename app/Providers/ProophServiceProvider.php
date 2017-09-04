<?php

namespace Nokios\Cafe\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\PostgresSingleStreamStrategy;
use Prooph\EventStore\Pdo\PostgresEventStore;

class ProophServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EventStore::class, function ($app) {
            return new PostgresEventStore(
                new FQCNMessageFactory(),
                DB::connection('pgsql'),
                new PostgresSingleStreamStrategy()
            );
        });
    }
}
