<?php

namespace App\Observers;

use App\Entities\ChildAccount;
use App\Entities\Money;

class ChildAccountObserver
{
    /**
     * Handle the child account "created" event.
     *
     * @param  \App\Entities\ChildAccount  $childAccount
     * @return void
     */
    public function created(ChildAccount $childAccount)
    {
//        Money::create([
//            "child_account_id" => $childAccount->id,
//            "amount" => "50.00",
//            "type" => Money::TYPE_RECHARGE,
//            "comment" => "新账号赠送",
//        ]);
    }

    /**
     * Handle the child account "updated" event.
     *
     * @param  \App\Entities\ChildAccount  $childAccount
     * @return void
     */
    public function updated(ChildAccount $childAccount)
    {
        //
    }

    /**
     * Handle the child account "deleted" event.
     *
     * @param  \App\Entities\ChildAccount  $childAccount
     * @return void
     */
    public function deleted(ChildAccount $childAccount)
    {
        //
    }

    /**
     * Handle the child account "restored" event.
     *
     * @param  \App\Entities\ChildAccount  $childAccount
     * @return void
     */
    public function restored(ChildAccount $childAccount)
    {
        //
    }

    /**
     * Handle the child account "force deleted" event.
     *
     * @param  \App\Entities\ChildAccount  $childAccount
     * @return void
     */
    public function forceDeleted(ChildAccount $childAccount)
    {
        //
    }
}
