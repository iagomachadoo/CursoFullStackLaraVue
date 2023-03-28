<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Middleware no controller
    //Para isso, devemos instanciar o método __construct() do php, que vai rodar sempre que essa classe for instanciada ($this)
    //Agora, todos os métodos dentro desse controller terão middleware aplicado
    public function __construct()
    {
        //$this->middleware('checkToken'); //Aplica o middleware a todos os métodos

        //$this->middleware('checkToken')->only('show2'); //O método only() serve para aplicar o middleware apneas ao métodos passados como parâmetro
        //$this->middleware('checkToken')->only(['index', 'show']);

        //$this->middleware('checkToken')->except('show2'); //O método except() serve para aplicar o middleware a todos os métodos, menos ao que foi passado como parâmetro

        //Passando um closure - é o mesmo closure que tem dentro do arquivo de middleware
        //Dessa forma, estamos criando o middleware apenas para esse controller, sem a necessidade de fazer o registro dentro do kernel.php
        //Esse caso é mais usado quando precisamos de um middleware  muito específico, que só será usado em um controller e em nenhum outro lugar  
        //Aceita os métodos only() e except()
        /* $this->middleware(function(){
            dd("Middleware User");
        }); */
    }

    //Por convenção, a função que lista todos os dados (função inicial) recebe o nome de index
    public function index()
    {
        dd('x');
    }

    //Passando parâmetro pro controller e injetando dependência
    //Quando injetamos um model, na rota devemos passar o parâmetro com o mesmo nome do parâmetro no método. Nesse exemplo, o parâmetro da rota está como id, e o do model binding está como $user, então, teremo o retorno da classe model User sem a consulta no banco de dados, o que não é o resultado esperado 
    public function show(Request $request, User $user, $id)
    {
        dd('show', $request, $user, $id);
    }

    //Model binding - injetando um model dentro do controller
    //Já nesse exemplo, o parâmetro na rota está com o mesmo nome do parâmetro do método (user), assim, teremos o retorno esperado, que é o model User com as informações referente ao valor do parâmetro passado
    public function show2(User $user)
    {
        dd($user);
    }
}
