<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Models\Ingrediente;

class IngredienteController extends ApiController
{
    public function __construct(Ingrediente $ingrediente)
    {
        parent::__construct($ingrediente);
    }
}
