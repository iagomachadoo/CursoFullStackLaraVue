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
        //Passando variáveis pra view - array associativo
        // return view('user.index', [
        //     'user' => 'Jhon Snow',
        // ]);

        //Passando variáveis pra view - método with
        // return view('user.index')->with([
        //     'user' => 'Jhon Snow',
        // ]);

        //Ao user o with() podemos aplicar lógica juntamente a renderização de views
        // $view = view('user.index');

        //Lógica 1 - if e else
        // $view->with(['user' => 'Jhon Snow']);

        //Lógica 2 - if e else
        // $view->with(['user2' => 'Arya Stark']);

        //Final da lógica
        // return $view;

        //Passando dados para todas a views de forma global
        //Para isso, devemos usar o Facades/Views como o método share() passando um array associativo com a chave e o valor da variável. Isso deve ser feito dentro do arquivo app/providers/AppServiceProvider.php. Mas isso deve ser feito com cuidado

        //Passando dados do bd para view
        // $users = User::all();
        // $quant = count($users);
        // return view('user.index', [
        //     'users' => $users,
        // ]);

        //Passando variáveis com compact
        // return view('user.index', compact('users'));

        // return view('user.index', compact('users', 'quant'));

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
