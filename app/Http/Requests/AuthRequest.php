<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            case 'register':
                return [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|string|min:6|required_with:confirm_password|same:confirm_password',
                    'confirm_password' => 'required|string|min:6'
                ];
            break;
            case 'login':
                return [
                    'email' => 'required|email',
                    'password' => 'required|string|min:6'
                ];
            break;
        }
    }
}
