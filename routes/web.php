<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User2Controller;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

//Verbos http
/* 
Route::get();
Route::post();
Route::put();
Route::delete(); 
Route::patch(); 
Route::options(); 
*/

//Route::verboHttp(uri, callback)
Route::get('users', function(){
    return "Hello world";
});

//O método match() aceita um array com métodos que serão usados na rota - Seu uso não é comum, o normal é ter uma rota get e outra post
//O método name() atribui um nome para a rota. Na montagem de links na view, podemos ao invés de usar a url, apenas passar o nome da rota, deixando que o laravel vá atrás da rota com esse nome. Essa funcionalidade faz com que, caso mudemos a url da rota, na se altera, porque estamos referenciando a rota pelo seu nome 
Route::match(['get', 'post'], 'users2', function(){
    return "Hello world!";
})->name('users2');

//REDIRECIONAMENTO DE ROTAS
//Caso 1 - Sem o uso de lógica. Quando o usuário acessar uma rota ele será redirecionado para outra. Essa situação é interessante quando há a troca do endereço da rota. Nesse caso, devemos passar o parâmetro com o status do redirecionamento, esses status são os códigos 3xx
Route::redirect('rota-origem', 'rota-destino', 301);

//O método permanentRedirect() já passa implicitamente o status do redirecionamento
Route::permanentRedirect('rota-origem2', 'rota-destino2');

Route::get('rota-destino2', function(){
    return "Rota destino 2";
});

//Caso 2 - Quando é necessário aplicar alguma lógica
//Dentro da rota origem, criamos a rota e a lógica, no final da rota damos um retorno com o helper global redirect(), passando para ele a rota destino
//Mas a forma mais aconselhável de criar redirecionamentos é utilizando o nome da rota ao invés da url, para isso, deixamos de passar a url para o método redirect e concatenamos a ele outro helper global, o route() e então, dentro do route() passamos o nome da rota destino
Route::get('rota-origem3', function(){
    //lógica
    return redirect()->route('rota-destino3');
});

Route::get('rota-destino3', function(){
    return "Rota destino 3!";
})->name('rota-destino3');

//ROTAS DE VISUALIZAÇÃO
//Esse caso é valido quando não precisamos passar por algum controller com as regras de negócio para no final mostra uma view
//Podemos passar variáveis para a view nesse caso também, para isso, basta passar como terceiro parâmetro um array com os valores
Route::view('welcome', 'welcome', [
    'title' => 'Hello World!'
]);

//ROTAS COM PARÂMETRO
//Caso 1 - Parâmetro obrigatório - caso o parâmetro não seja passado, teremos um 404
Route::get('user/{id}',  function($id){
    return "User " . $id;
});

//Caso 2 - Parâmetro opcional - com o parâmetro opcional, caso não passemos um parâmetro, não teremos um 404 como retorno, pois indicamos ao laravel através da sintaxe {par?} que podemos ou não passar ele. Mas o callback ainda fica esperando um valor para o parâmetro, então devemos inicializar ele com um valor padrão - $par = null (esse valor padrão pode ser qualquer valor)
Route::get('user2/{id?}',  function($id = null){
    return "User " . $id;
});

//Podemos passar mais de um parâmetro, tanto obrigatório como opcional (a ordem desses parâmetros importa)
Route::get('user3/{id?}/{nome?}',  function($id = null, $nome = null){
    return "User " . $id . " - " . $nome;
});

//VALIDANDO PARÂMETROS DE UMA ROTA
//Para validarmos o parâmetro da rota, devemos utilizar o método where() passando para ele o parâmetro e a expressão regular que limitará o formato do parâmetro (caso a rota tenha mais de um parâmetro, o método where aceita um array) - Route::get(rota/{par})->where(['par', 'regex'])
Route::get('user4/{id?}/{nome?}', function($id = null, $nome = null){
    return "User" . $id . " - " . $nome;
})->where(['id' => '[0-9]+', 'nome' => '[a-zA-Z]'])->name('user4');

//O laravel disponibiliza métodos que implicitamente aplicam uma limitação no tipo de parâmetro - whereNumber('par') limita o o tipo de parâmetro a apenas números
Route::get('token/{token}', function($token){
    return $token;
})->whereNumber('token');

Route::get('token2/{token}', function($token){
    return $token;
})->whereAlpha('token');

//Podemos fazer essa validação de forma global, através do arquivo app/providers/RouteServiceProvider.php
Route::get('user5/{age}', function($age){
    return $age;
});

Route::get('user6/{name}', function($name){
    return $name;
});

//GRUPO DE ROTAS
//PREFIXO - dá o prefixo na url
//NAME - dá o prefixo no nome da rota
//MIDDLEWARE - cria uma barreira para a requisição - ex: login de usuário | app/http/kernel.php controla as camadas web/api. Dentro dele já existem middleware prefixados para essas camadas, ou seja, qualquer requisição para essas camadas já passam por esses middleware por default. Nesse arquivo também contém os middleware default das rotas ($middlewareAliases[])
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
    //rota admin/
    Route::get('', function(){
        return "Admin";
    })->name('');

    //rota admin/user7
    Route::get('user7', function(){
        return "Usuários";
    })->name('users');
    
    //rota admin/user7/1
    Route::get('user7/{id}', function($id){
        return 'Usuário' . $id;
    })->name('user');
});

Route::get('test', function(){
    return 'test';
})->middleware('auth');

//CRIANDO SUBDOMÍNIOS NA ROTA
Route::domain('{user}.cursolaravelpro.test')->group(function(){
    //user.subdominio/id
    Route::get('{id}', function($user, $id){
        return $user . " - " . $id;
    });
});

//FALLBACK DE ROTAS
//Fallback - funciona como um backup, caso ocorra algum erro no sistema. Ou seja, se não conseguirmos acessar nenhuma rota listada no sistema o fallback entra em ação, então, ao invés de termos um erro 404, teremos o que foi passado no fallback
Route::fallback(function(){
    //return "Hello World";
    return view('welcome2');
});

//INJEÇÃO DE DEPENDÊNCIA
//Quando damos um return no laravel, ele tenta transformar esse return num objeto json, mas as vezes, por falta de compatibilidade com o tipo de objeto que está vindo, o retorno será vazio
Route::get('painel', function(Request $request){
    dd($request);
    return $request;
});

//INJEÇÃO DE MODEL
//A injeção de model funciona da mesma forma que a de dependência, contudo, na de model estamos injetando um model
Route::prefix('painel2')->name('painel2.')->group(function(){
    Route::get('', function(){
        return "Painel";
    })->name('');

    Route::get('{user}', function(User $user){
        return 'Usuário - ' . $user;
    })->name('user');
});

//MIDDLEWARE
//Usando middleware diretamente na rota
//Podemos passar um array ao invés de uma string, nos casos onde queremos aplicar mais de um middleware
Route::get('user8', function(){
    return "Olá!";
})->middleware('userAgent');

//Usando um middleware global - o middleware agr está sendo aplicado em todas as requisições de forma global, para isso, o middleware foi registrado no atributo $middleware do arquivo app/http/kernel.php
Route::get('user9', function(){
    return "Olá!";
});

//Aplicando middleware no grupo de rotas
//Podemos aplicar mais de um middleware nas rotas, passando ao invés de uma string, um array com todos os middleware que queremos aplicar. A ordem em que esses middleware serão aplicados depende de sua posição dentro do array. Nesse caso abaixo, o primeiro a ser aplicado será o checkToken
Route::middleware(['checkToken', 'userAgent'])->group(function(){
    Route::get('user10', function(){
        return "User";
    });
    
    Route::get('posts', function(){
        return "Posts";
    });
    
    //Removendo uma rota da aplicação da regra do middleware dentro de um grupo de rotas
    Route::get('services', function(){
        return "Services";
    })->withoutMiddleware('userAgent');
});

//Podemos ao invés de passar todos os middleware num array, podendo deixar muito verboso, criar um grupo de middleware e passá-lo para a rota. Esse grupo deve ser criado dentro do atributo $middlewareGroups do arquivo app/http/kernel.php
Route::middleware('myApp')->group(function(){
    Route::get('user11', function(){
        return "User";
    });
    
    Route::get('posts1', function(){
        return "Posts";
    });
    
    Route::get('services2', function(){
        return "Services";
    });
});

//Passando parâmetro para a middleware
//Podemos passar parâmetros pra dentro de uma middleware aplicado a uma rota, mas para que isso seja possível, o registro do middleware que vai receber o parâmetro, deve ser feito no atributo $middlewareAliases. A sintaxe para passar um parâmetro para o middleware é middleware('nomeMiddleware:parâmetro')
//Dentro do middleware, no método handle, passamos como 3º parâmetro uma variável que vai receber o valor passado para o middleware
Route::get('admin', function(){
    return "Admin";
})->middleware('checkToken:editor');

//CONTROLLER
//Ligando um controller a uma rota
//Nova estrutura da rota - (uri, [controller, métodoDoController])
Route::get('users3', [UserController::class, 'index'])->name('users.index');

//Passando parâmetro pro controller
Route::get('users3/{id}', [UserController::class, 'show'])->name('user.show');

//Model bilnding
Route::get('users4/{user}', [UserController::class, 'show2'])->name('user.show2');

//Controller de ação única
//Essa rota invoca automaticamente o controller através da sua classe, porque o controller tem o método __invoke
Route::get('checkout/{token}', CheckoutController::class);

//Controller resource
//O método resource é utilizado para chamar um controller de CRUD
//Para criar esse controller, devemos usar a option --resource no comando make
//Quando criamos um controller dessa maneira, ele já vem por default com todos os métodos do CRUD criados
//Podemos adicionar outra option a esse comando, --model=NomeModel para o controller já vir com o model binding feito
Route::resource('users5', User2Controller::class);

//Personalizando o resource
//Método only() - cria apenas as rotas para os métodos passados como parâmetro
Route::resource('users6', User2Controller::class)->only(['index', 'edit']);

//Método except() - cria todas as rotas, menos as para os métodos passados como parâmetro
Route::resource('users7', User2Controller::class)->except(['index', 'edit']);

//Mais de um resource
//Utilizamos o método resources ao invés de resource
//Passamos as uri e classes dentro de um array associativo
Route::resources([
    'users8' => User2Controller::class,
    'posts2' => User2Controller::class
]);