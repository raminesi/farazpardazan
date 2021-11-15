<?php

namespace App\Http\Requests;

use App\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;

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
                $rule = [
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                    'transaction' => 'in:buy,sell'
                ];
                if(request('transaction') == 'buy'){
                    $warehouse = Warehouse::first();
                    $rule['quantity'] = 'required|numeric|max:'.$warehouse->quantity;
                }
                return $rule;
            break;
        }
    }
}
