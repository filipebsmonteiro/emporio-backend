<?php

namespace App\Http\Controllers\Painel\Loja;

use App\Events\chatEvent;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MensagemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Chat $chatObj)
    {
//        if (Auth::user()->hasAnyPerfils('Atendente') ){
            $chats      = $this->carregaChats();
//        }

        $chatsNull      = $chatObj->whereNull('Users_idUsers')->get();
        return view('Painel.Loja.home', compact('chatsNull', 'chats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Chat $chatObj)
    {
        // Aceita o chat
//        $this->authorize('chats');

        $chat           = $chatObj->find($id);
        if ( $chat ) {

            if( $chat->Users_idUsers ) {
                flash('Outro atendente já abriu este Chat!');
            }else{
                $chat->update(['Users_idUsers' => Auth::id()]);
                flash('Chat com: ' . $chat->cliente->nome . ' aceito com Sucesso !!!', 'success');
            }

        }else{
            flash('Cliente: ' . $chat->cliente->nome . ' já finalizou o Chat');
        }
        return redirect()->route('mensagem.index');
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
//        $this->authorize('chats');

        $msg        = $request->except('_token');
        $chat       = $c->where('channel', $msg['channel'])->first();

        //Monta a Mensagem com todos os dados
        $seconds                    = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());
        $mensagem   = [
            'id'                    => $seconds,
            'texto'                 => $msg['texto'],
            'channel'               => $chat->channel,
        ];

        $channel    = $msg['channel'];

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
//        $this->authorize('chats');

        $chatObj        = new Chat();
        $chat           = $chatObj->where('channel', $id)->first();
        if ($chat) {
            $chat->delete();
        }
        flash('Chat finalizado com sucesso!', 'success');
        return redirect(route('home'));
    }

    /**
     * @param array $msgData
     *
     * Armazena mensagem no arquivo
     */
    public function storeMessageChat(array $msgData)
    {
        $fileName           = public_path('chat/'.$msgData['channel'].'.json');

//        {
//            author: 'them',
//            type: 'text',
//            data: {
//                text: 'some text'
//            }
//        }

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

    public function carregaChats()
    {
        $chatObj    = new Chat();
        $chats      = $chatObj
                    ->where('Users_idUsers', Auth::user()->id)
                    ->where('created_at', '>', Carbon::today()->toDateString())
                    ->get();

        $chatsArray       = [];
        if ($chats){
            foreach($chats as $chat){

                $chatsArray[$chat->channel] = [
                    'Users_idUsers'         => $chat->Users_idUsers,
                    'cliente'   => [
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

        if ( empty($chatsArray) ){
            $chatsArray['Users_idUsers']    = Auth::user()->id;
        }

        $chats  = json_encode($chatsArray);

        return $chats;
    }
}
