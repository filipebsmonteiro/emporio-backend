<?php


namespace App\Http\Traits;


use App\Models\Ingrediente_multiplo;
use App\Models\Produto_multiplo;
use Illuminate\Database\Eloquent\Model;

trait AttachProdutosMultiplos
{
    protected function attachProdutosMultiplos(Model $entity, array $multiplos): bool
    {
        $shouldAttachIds = [];
        $multObj = new Produto_multiplo();
        foreach ($multiplos as $multiploLoop){
            $multData = [
                'nome'              => $multiploLoop['nome'],
                'obrigatorio'       => $multiploLoop['obrigatorio'],
                'quantidade_min'    => $multiploLoop['quantidade_min'],
                'quantidade_max'    => $multiploLoop['quantidade_max'],
                'Lojas_idLojas'     => $entity->Lojas_idLojas
            ];
            if (isset($multiploLoop['id'])){
                $multiplo = $multObj->find($multiploLoop['id']);
                $multiplo->update($multData);
            }else {
                $multiplo = $multObj->create($multData);
            }
            $this->attachSubProdutos($multiplo, $multiploLoop['produtos']);
            array_push($shouldAttachIds, $multiplo->id);
        }
        $entity->produtosMultiplos()->sync($shouldAttachIds);
        return true;
    }

    protected function attachSubProdutos(Produto_multiplo $multiplo, array $produtos): bool
    {
        $shouldAttach = [];
        foreach ($produtos as $produto) {
            $shouldAttach[$produto['id']] = [ 'valor' => 0 ];
        }
        $multiplo->produtos()->sync($shouldAttach);
        return true;
    }
}
