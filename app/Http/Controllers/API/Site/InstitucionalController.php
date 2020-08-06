<?php

namespace App\Http\Controllers\API\Site;

use App\Http\Requests\SACMailRequest;
use App\Mail\SACMail;
use App\Models\FormaPagamento;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class InstitucionalController extends Controller
{

    public function sacEnvia(SACMailRequest $request)
    {
        Mail::to(env('MAIL_LOJA'))->send( new SACMail($request));

        if ($request->ajax() ){//|| $request->wantsJson()) {
            $json = [
                'message' => 'Email enviado com sucesso!',
            ];

            return response()->json($json, 200);
        }else{
            flash('Email enviado com sucesso!', 'success');
            return redirect()->route('sac');
        }
    }

    public function lojas()
    {
        $title		= 'Lojas';
        $lojaObj    = new Loja();
        $lojas      = Loja::all();
        $formasPgto = FormaPagamento::all();
        return view('Site.Insitucional.lojas', compact('title', 'lojas', 'formasPgto'));
    }

}
