<?php

namespace App\Observers;

use App\Entities\Client;
use App\Entities\Money;

class ClientObserver
{
    /**
     * Handle the child account "created" event.
     *
     * @param  \App\Entities\Client  $client
     * @return void
     */
    public function created(Client $client)
    {

    }

    /**
     * Handle the child account "updated" event.
     *
     * @param  \App\Entities\Client  $client
     * @return void
     */
    public function updated(Client $client)
    {
        //
    }

    /**
     * Handle the child account "deleted" event.
     *
     * @param  \App\Entities\Client  $client
     * @return void
     */
    public function deleted(Client $client)
    {
        //
    }

    /**
     * Handle the child account "restored" event.
     *
     * @param  \App\Entities\Client  $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the child account "force deleted" event.
     *
     * @param  \App\Entities\Client  $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
