<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class IngredienteFormRequest extends FormRequest
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
            'nome'              => 'required|min:3|max:70',
            'status'            => 'required|boolean',
            'preco'             => 'nullable',
            'codigo_PDV'        => 'nullable',
            'Categoria_ingredientes_idCategoria_ingredientes' => 'nullable|exists:categoria_ingredientes,id'
        ];
    }
}
