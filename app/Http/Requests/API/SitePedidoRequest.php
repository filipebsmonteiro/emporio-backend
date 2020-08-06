<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SitePedidoRequest extends FormRequest
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
        return [
            'agendamento'       => 'nullable|datetime',
            'cupom_field'       => 'nullable|string',
            'endereco_id'       => 'required',
            'fidelidade_field'  => 'nullable',
            'forma_pagamento'   => 'required',
            'loja_id'           => 'required',
            'observacoes'       => 'nullable|string',
            'produtos'          => 'required|array',
            'troco'             => 'required_if:forma_pagamento,1',
        ];
    }
}
