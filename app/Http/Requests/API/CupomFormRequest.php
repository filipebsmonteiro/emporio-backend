<?php

namespace App\Http\Requests\API;

use App\Models\Cupom;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CupomFormRequest extends FormRequest
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
            'codigo'                                    => 'required|max:1',
            'hash'                                      => 'nullable|required_if:codigo,A,B,C,D,E|min:3|max:15',
            'quantidade'                                => 'nullable|required_if:codigo,N,O,P,Q,R|min:1|max:5',
            'porcentagem'                               => 'nullable|required_if:codigo,B,O|min:1|max:3',
            'valor'                                     => 'nullable|required_if:codigo,C,P|min:1|max:4',
            'Cat_produtos_idCat_produtos'               => 'nullable|required_if:codigo,D,Q|exists:categoria_produtos,id',
            'Produtos_idProdutos'                       => 'nullable|required_if:codigo,E,R|exists:produtos,id',
            'validade'                                  => 'required|date_format:Y-m-d|after:'.Carbon::now()->toDateString(),

        ];//return[];
    }
}
