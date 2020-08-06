<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ClienteFormRequest extends FormRequest
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
        if ($this->route()->getName() === 'cliente.update'){
            return $this->updateRules();
        }
        return $this->createRules();
    }

    public function createRules()
    {
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 12;
            return (new \DateTime())->diff(new \DateTime($value))->y >= $minAge;
        });

        return [
            'nome'              => 'required|min:3|max:70',
            'CPF'               => 'required|regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/',//|unique:clientes
            'phone'             => 'required',
            'nascimento'        => 'required|date|before_or_equal:12 years ago',
            'email'             => 'required|email|max:70|unique:clientes',
            'sexo'              => 'required',
            'password'          => 'required|min:6'
        ];
    }

    public function updateRules()
    {
        return [
            'nome'              => 'required|min:3|max:70',
            'CPF'               => 'required|unique:clientes|regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/',
            'phone'             => 'required',
            'nascimento'        => 'required|date',
            'sexo'              => 'required',
            'password'          => 'nullable|min:6'
        ];
    }
}
