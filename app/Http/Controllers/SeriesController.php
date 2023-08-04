<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Models\Season;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::all();

        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        $request->session()->forget('mensagem.sucesso');


        return view('series.index')->with('series', $series)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {
        //permite que sejam criadas regras de validação para a request
        //caso as regras não sejam cumpridas, o usuário é redirecionado para a url anterior
        /*$request->validate([
            'nome' => ['required','min:3']
        ]);*/

        //permite a criação de linhas nas tabelas do banco, nesse caso, na tabela serie
        //pegando tudo que veio na request
        $series = Serie::create($request->all());

        //rodando um for para criar as temporadas das series
        for($i=0; $i<=$request->seasonsQty; $i++){
            //é possível chamar o metodo seasons da model que faz a relação entre as tabelas
            //e utilizar o create para que seja criada uma season na serie
            $season = $series->seasons()->create([
                'number' => $i
            ]);
            //Da mesma forma é necessário rodar um for para criar os episódios em cada temporada
            for($j=0; $j<=$request->episodesPerSeason; $j++){
                //Essa forma de gerar a criação do episódio, onde é chamado o método create, e então
                //informado quais os campos a serem preenchidos, é chamado de mass assignment, e por isso
                //é necessário que, na model, esse campo seja definido como fillable
                $season->episodes()->create([
                    'number' => $j
                ]);
            }

            /*
            A  forma realizada anteriormente não é a mais otimizada para realizara  inserção no banco,
            pois gera muitas queries para que todas as informações sejam inseridas no banco.
            Uma forma melhor seria gerar um array com todas as informações, e então usar a função
            Episode::insert(*array com os elementos*), pois dessa forma iria diminuir a quantidade de queries
            Foi deixado dessa forma pouco ótimizada apenas por questões de estudo, para que eu possa saber que 
            existe essa forma
            */

        }

        //coloca uma informação na sessão, no caso uma mensagem de sucesso
        $request->session()->put('mensagem.sucesso', "Série '{$series->nome}' adicionada com sucesso!");


        //to_route permite redicionar diretamente para outra rota
        return to_route('series.index');
    }

    //O fato do parâmetro ser do tipo model Serie, faz com que o laravel pegue o id que foi enviado
    //e faça um select no banco para localizar essa série
    public function destroy(Serie $series, Request $request){
        //O formato comentado a baixo seria recebendo o id da série através da request
        //Serie::destroy($request->series);

        //Uma vez que a serie já foi localizada, e está armazenada na variável $series
        //é possível deletar dessa forma
        $series->delete();

        $request->session()->put('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso!");

        return to_route('series.index');
    }

    public function edit(Serie $series){
        return view('series.edit')->with('serie', $series);

    }

    public function update(Serie $series, SeriesFormRequest $request){


        $series->nome = $request->nome;
        $series->save();

        

        return to_route('series.index')->with('mensagem.sucesso', "Série '{$series->nome}' alterada com sucesso!");
    }
}
