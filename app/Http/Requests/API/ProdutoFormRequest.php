<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoFormRequest extends FormRequest
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
            'nome'								=> 'required|min:3|max:70',
            'preco'								=> 'required|numeric',//array('required', 'regex:/^\d*(\,\d{2})?$/'),
            'status'						    => 'required|string',
            'unidade_medida'                    => 'nullable|string',
            'intervalo'                         => 'nullable|numeric',
            'minimo_unidade'                    => 'nullable|numeric',
//            'imagem'							=> 'nullable|image|max:150',
            'Cat_produtos_idCat_produtos'       => 'required|exists:categoria_produtos,id',

            'promocionar'				        => 'nullable|boolean',
            'valorPromocao'						=> 'required_if:promocionar,true',

            'domingo'							=> 'nullable|boolean',
            'segunda'							=> 'nullable|boolean',
            'terca'								=> 'nullable|boolean',
            'quarta'							=> 'nullable|boolean',
            'quinta'							=> 'nullable|boolean',
            'sexta'								=> 'nullable|boolean',
            'sabado'							=> 'nullable|boolean',

            'disponibilidade'					=> 'required|string',
            'inicio_periodo1'					=> 'required_if:disponibilidade,1 Turno|required_if:disponibilidade,2 Turnos',
            'termino_periodo1'					=> 'required_if:disponibilidade,1 Turno|required_if:disponibilidade,2 Turnos',
            'inicio_periodo2'					=> 'required_if:disponibilidade,2 Turnos',
            'termino_periodo2'					=> 'required_if:disponibilidade,2 Turnos',

            'multiplos'							=> 'nullable|array',
        ];
    }
}
