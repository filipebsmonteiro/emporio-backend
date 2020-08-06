<?php

namespace App\Http\Controllers\Site;

use App\Events\chatEvent;
use App\Models\Chat;
use App\Models\Cliente;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MensagemController extends Controller
{
    public function __construct()
    {
        $this->middleware('siteAuth')->only(['index', 'create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Chat $chatObj)
    {
        $cookieUser     = $request->cookie('user');
        $chat           = $chatObj
                        ->where('Clientes_idClientes', $cookieUser->id)
                        ->where('channel', $cookieUser->id.'-'.Carbon::now()->dayOfYear.Carbon::now()->year)
                        ->first();
        if ($chat){
            $chatArray = [
                'Users_idUsers'         => $chat->Users_idUsers,
                'channel'               => $chat->channel,
                'historico'             => []
            ];
            $fileName   = public_path('/chat/'.$chat->channel.'.json');
            if( file_exists($fileName) ){
                $chatArray['historico'] = file_get_contents($fileName);
            }

            $chat = json_encode($chatArray);
            return view('Site.Chat.index', compact('chat'));
        }

        return view('Site.Chat.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Chat $chatObj)
    {
        $cliente        = json_decode( $request->cookies->get('user') );
        $hashChannel    = $cliente->id.'-'.Carbon::now()->dayOfYear.Carbon::now()->year;

        $chat           = $chatObj->updateOrCreate(
            [
                'channel'               => $hashChannel,
                'Clientes_idClientes'   => $cliente->id
            ],
            [
            'channel'               => $hashChannel,
            'Clientes_idClientes'   => $cliente->id,
            'mensagens'             => ''
        ]);
//        flash("Chat Iniciado com Sucesso !!!")->important();
//        flash("Aguarde o contato de nossos atendentes!!!")->important();

        $chatArray      = $chat->toArray();

        $chatArray['cliente']['id']     = $chat->Clientes_idClientes;
        $chatArray['cliente']['nome']   = $chat->cliente->nome;

        //Mostra Para os Atendentes que existem chats sem Atendimento
        $e              = new chatEvent(json_encode($chatArray), 'novo-chat');
        event($e);

        return $this->loadClienteChat($chat->Clientes_idClientes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chatObj    = new Chat();
        $chat       = $chatObj
                    ->where('channel', $id)
                    ->where('created_at', '>', Carbon::now()->toDateString())
                    ->first();
        return $chat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Chat $c)
    {
        $msg        = $request->except('_token');
        $chat       = $c->where('channel', $msg['channel'])->first();

        //Monta a Mensagem com todos os dados
        $seconds                    = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());
        $mensagem   = [
            'id'                    => $seconds,
            'texto'                 => $msg['texto'],
            'channel'               => $chat->channel,
        ];

        //Verifica se é cliente mandando para atendente
        $mensagem['Users_idUsers']  = $chat->Users_idUsers;
        $channel    = 'Atendente-'.$chat->Users_idUsers;

        $this->storeMessageChat($mensagem);

        $e          = new chatEvent($mensagem, $channel);
        event($e);
        return $mensagem;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chatObj        = new Chat();
        if ( $chatObj->where('channel', $id)->first() ){
            $chatObj->where('channel', $id)->first()->delete();
        }
        flash('Chat finalizado com sucesso!', 'success');
        return redirect( route('menu') );
    }

    /**
     * @param array $msgData
     *
     * Armazena mensagem no arquivo
     */
    public function storeMessageChat(array $msgData)
    {
        $fileName           = public_path('chat/'.$msgData['channel'].'.json');

        if ( file_exists($fileName) ){
            $oldFileInfo    = file_get_contents($fileName);
            $fileArray      = json_decode($oldFileInfo, true);

            //Calcula qual segundo do dia está
            $seconds        = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());

            $fileArray[$seconds] = $msgData;

            $newFileInfo    = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);
        }else{
            fopen($fileName, 'x+');

            //Calcula qual segundo do dia está
            $seconds        = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());

            $fileArray      = [
                $seconds => $msgData
            ];

            $newFileInfo    = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);

            $e      = new chatEvent('reload', $msgData['channel']);
            event($e);

        }

        return;
    }

    public function loadClienteChat($idCliente)
    {
        $chatObj        = new Chat();
        $chat           = $chatObj
                        ->where('Clientes_idClientes', $idCliente)
                        ->where('channel', $idCliente.'-'.Carbon::now()->dayOfYear.Carbon::now()->year)
                        ->first();
        if ($chat){
            $chatArray = [
                'Users_idUsers'         => $chat->Users_idUsers,
                'channel'               => $chat->channel,
                'historico'             => []
            ];
            $fileName   = public_path('/chat/'.$chat->channel.'.json');
            if( file_exists($fileName) ){
                $chatArray['historico'] = file_get_contents($fileName);
            }

            $chat = json_encode($chatArray);
            return $chat;
        }

        return false;
    }

    public function loadUserChat($idUser)
    {
//        if (Auth::user()->hasAnyPerfils('Atendente') ){
            $chatObj    = new Chat();
            $chats      = $chatObj
                        ->where('Users_idUsers', $idUser)
                        ->where('created_at', '>', Carbon::now()->toDateString())
                        ->get();

            $chatsArray       = [];
            if ($chats){
                foreach($chats as $chat){

                    $chatsArray[$chat->channel] = [
                        'Users_idUsers'         => $chat->Users_idUsers,
                        'Cliente'   => [
                            'id'    => $chat->cliente->id,
                            'nome'  => $chat->cliente->nome
                        ],
                        'historico'             => []
                    ];

                    $fileName = public_path('/chat/'.$chat->channel.'.json');
                    if (file_exists($fileName)){
                        $fileInfo   = file_get_contents($fileName);
                        $chatsArray[$chat->channel]['historico'] = json_decode($fileInfo, true);
                    }
                }
            }

            $chats  = json_encode($chatsArray);
            return $chats;
//        }
//
//        return false;
    }
}