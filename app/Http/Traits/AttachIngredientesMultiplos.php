<?php


namespace App\Http\Traits;


use App\Models\Ingrediente_multiplo;
use Illuminate\Database\Eloquent\Model;

trait AttachIngredientesMultiplos
{
    protected function attachMultiplos(Model $entity, array $multiplos): bool
    {
        $shouldAttachIds = [];
        $multObj = new Ingrediente_multiplo();
        foreach ($multiplos as $multiploLoop){
            $multData = [
                'nome'              => $multiploLoop['nome'],
                'obrigatorio'       => $multiploLoop['obrigatorio'],
                'visibilidade'      => 'Ingrediente',
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
            $this->attachSubIngredientes($multiplo, $multiploLoop['ingredientes']);
            array_push($shouldAttachIds, $multiplo->id);
        }
        $entity->multiplos()->sync($shouldAttachIds);
        return true;
    }

    protected function attachSubIngredientes(Ingrediente_multiplo $multiplo, array $ingredientes): bool
    {
        $shouldAttach = [];
        foreach ($ingredientes as $ingrediente) {
            $shouldAttach[$ingrediente['id']] = [ 'valor' => $ingrediente['nesseMultiplo'] ];
        }
        $multiplo->ingredientes()->sync($shouldAttach);
        return true;
    }
}
