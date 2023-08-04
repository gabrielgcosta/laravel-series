<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serie;

class SeasonsController extends Controller
{
    public function index(Serie $series){
        //Essa forma de acessar as seasons da série é através de uma colection do eloquent
        $seasons = $series->seasons;
        return $series;
    }
}
