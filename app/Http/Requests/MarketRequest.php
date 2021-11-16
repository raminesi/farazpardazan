<?php

namespace App\Http\Requests;

use App\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MarketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $routeName = $this->route()->getName();
        switch ($routeName) {
            case 'order':
                $warehouse = Warehouse::first();
                $user      = Auth::user();
                if(request('transaction') == 'buy'){
                    $maxQuantity = floor($user->wallet->credit / request('price'));
                    $maxQuantity = ($warehouse->quantity <= $maxQuantity ? $warehouse->quantity : $maxQuantity);
                }elseif(request('transaction') == 'sell'){
                    $maxQuantity = floor($warehouse->credit / request('price'));
                    $maxQuantity = ($user->wallet->quantity <= $maxQuantity ? $user->wallet->quantity : $maxQuantity);
                }
                $min_price = (($warehouse->price - 5) < 0 ? 0.5 : ($warehouse->price - 5));
                $max_price = $warehouse->price + 5;
                return [
                    'price' => 'required|numeric|min:'.$min_price.'|max:'.$max_price,
                    'quantity' => 'required|numeric|min:0.5|max:'.$maxQuantity,
                    'transaction' => 'in:buy,sell'
                ];
            break;
        }
    }
}
