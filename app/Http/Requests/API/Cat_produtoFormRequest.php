<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class Cat_produtoFormRequest extends FormRequest
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
            'nome'										=> 'required|min:3|max:70',
            'grupo'										=> 'nullable|min:3|max:70',
            'layout'									=> 'required',
            'permiteCombinacao'							=> 'nullable',
            'quantidadeCombinacoes'						=> [
                'nullable',
                'required_if:layout,"Pizza"',
                'required_if:permiteCombinacao,"true"',
                'numeric',
                'min:2',
                'max:4'
            ],
//            'Lojas_idLojas'                             => 'required|exists:Lojas,id'
        ];
    }
}
