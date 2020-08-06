<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SiteEnderecoFormRequest extends FormRequest
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
            'CEP'                       => 'required',
            'Logradouro'                => 'required',
            'Bairro'                    => 'nullable|min:3',
            'Cidade'                    => 'required',
            'Referencia'                => 'nullable|min:5',
        ];
    }
}
