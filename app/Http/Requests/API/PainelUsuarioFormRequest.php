<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class PainelUsuarioFormRequest extends FormRequest
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
            'name'                      => 'required|min:3',
            'email'                     => 'required|min:3',
            'password'                  => 'nullable|min:3',
            'perfil'                    => 'required'
        ];
    }
}
