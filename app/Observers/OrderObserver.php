<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\UserWallet;
use App\Models\Warehouse;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $wallet = UserWallet::where('user_id' , $order->user_id)->first();
        switch ($order->transaction) {
            case 'buy':
                $wallet->update([
                    'credit' => $wallet->credit - $order->total_price
                ]);
            break;
            case 'sell':
                $wallet->update([
                    'quantity' => $wallet->quantity - $order->quantity
                ]);
            break;
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $wallet    = UserWallet::where('user_id' , $order->user_id)->first();
        $warehouse = Warehouse::first();
        switch ($order->transaction) {
            case 'buy':
                if ($order->status_id == 2){
                    $wallet->update([
                        'credit' => $wallet->credit + $order->total_price
                    ]);
                }elseif ($order->status_id == 3){
                    $warehouse->update([
                        'quantity' => $warehouse->quantity - $order->quantity,
                        'credit' => $warehouse->credit + $order->total_price,
                        'price' => $order->price
                    ]);
                    $wallet->update([
                        'quantity' => $wallet->quantity + $order->quantity
                    ]);
                }
            break;
            case 'sell':
                if ($order->status_id == 2){
                    $wallet->update([
                        'quantity' => $wallet->quantity + $order->quantity
                    ]);
                }elseif ($order->status_id == 3){
                    $warehouse->update([
                        'quantity' => $warehouse->quantity + $order->quantity,
                        'credit' => $warehouse->credit - $order->total_price,
                        'price' => $order->price
                    ]);
                    $wallet->update([
                        'credit' => $wallet->credit + $order->total_price
                    ]);
                }
            break;
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
