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
// Route::get('/users', function(){
//     return "Hello world";
// });

//O método match() aceita um array com métodos que serão usados na rota - Seu uso não é comum, o normal é ter uma rota get e outra post
//O método name() atribui um nome para a rota. Na montagem de links na view, podemos ao invés de usar a url, apenas passar o nome da rota, deixando que o laravel vá atrás da rota com esse nome. Essa funcionalidade faz com que, caso mudemos a url da rota, nada se altera, porque estamos referenciando a rota pelo seu nome 
// Route::match(['get', 'post'], '/users', function(){
//     return "Hello world!";
// })->name('users');

//REDIRECIONAMENTO DE ROTAS
//Caso 1 - Sem o uso de lógica. Quando o usuário acessar uma rota ele será redirecionado para outra. Essa situação é interessante quando há a troca do endereço da rota. Nesse caso, devemos passar o parâmetro com o status do redirecionamento, esses status são os códigos 3xx
//Route::redirect por padrão retorna o código 302
//Route::redirect('/rota-origem', '/rota-destino', 301);

//O método permanentRedirect() já passa implicitamente o status 301 do redirecionamento
//Route::permanentRedirect('/rota-origem', '/rota-destino');

// Route::get('/rota-destino', function(){
//     return "Rota destino ";
// });

//Caso 2 - Quando é necessário aplicar alguma lógica
//Dentro da rota origem, criamos a rota e a lógica, no final da rota damos um retorno com o helper global redirect(), passando para ele a rota destino
//Mas a forma mais aconselhável de criar redirecionamentos é utilizando o nome da rota ao invés da url, para isso, deixamos de passar a url para o método redirect e concatenamos a ele outro helper global, o route() e então, dentro do route() passamos o nome da rota destino
// Route::get('/rota-origem', function(){
//     //lógica
//     return redirect()->route('rota-destino');
// });

// Route::get('/rota-destino', function(){
//     return "Rota destino!";
// })->name('rota-destino');

//ROTAS DE VISUALIZAÇÃO
//Esse caso é valido quando não precisamos passar por algum controller com as regras de negócio para no final mostra uma view
//Podemos passar variáveis para a view nesse caso também, para isso, basta passar como terceiro parâmetro um array com os valores
//Route::view(uri, nomeView, [variáveis])
// Route::view('/welcome', 'welcome', [
//     'title' => 'Hello World!'
// ]);

//ROTAS COM PARÂMETRO
//Caso 1 - Parâmetro obrigatório - caso o parâmetro não seja passado, teremos um 404
// Route::get('/user/{id}', function (string $id) {
//     return 'User '.$id;
// });

//Caso 2 - Parâmetro opcional - com o parâmetro opcional, caso não passemos um parâmetro, não teremos um 404 como retorno, pois indicamos ao laravel através da sintaxe {par?} que podemos ou não passar ele. Mas o callback ainda fica esperando um valor para o parâmetro, então devemos inicializar ele com um valor padrão - $par = null (esse valor padrão pode ser qualquer valor)
// Route::get('user/{id?}',  function(string $id = null){
//     return "User " . $id;
// });

//Podemos passar mais de um parâmetro, tanto obrigatório como opcional (a ordem desses parâmetros importa)
// Route::get('user/{id?}/{nome?}',  function(string $id = null, string $nome = null){
//     return "User " . $id . " - " . $nome;
// });

//VALIDANDO PARÂMETROS DE UMA ROTA
//Para validarmos o parâmetro da rota, devemos utilizar o método where() passando para ele o parâmetro e a expressão regular que limitará o formato do parâmetro (caso a rota tenha mais de um parâmetro, o método where aceita um array) - Route::get(rota/{par})->where(['par', 'regex'])
// Route::get('user/{id?}/{nome?}', function($id = null, $nome = null){
//     return "User " . $id . " - " . $nome;
// })->where(['id' => '[0-9]+', 'nome' => '[a-zA-Z]+'])->name('user');

//O laravel disponibiliza métodos que implicitamente aplicam uma limitação no tipo de parâmetro - whereNumber('parâmetro') limita o tipo de parâmetro a apenas números
// Route::get('token/{token}', function($token){
//     return $token;
// })->whereNumber('token');

//Apenas parâmetro que sejam letras
// Route::get('token/{token}', function($token){
//     return $token;
// })->whereAlpha('token');

//Podemos fazer essa validação de forma global, através do arquivo app/providers/RouteServiceProvider.php usando o método pattern 
// Route::get('user/{age}', function($age){
//     return $age;
// });

// Route::get('user/{name}', function($name){
//     return $name;
// });

//GRUPO DE ROTAS
//PREFIXO - dá o prefixo na url
//NAME - dá o prefixo no nome da rota
//MIDDLEWARE - cria uma barreira para a requisição - ex: login de usuário | app/http/kernel.php controla as camadas web/api. Dentro dele já existem middleware prefixados para essas camadas, ou seja, qualquer requisição para essas camadas já passam por esses middleware por default. Nesse arquivo também contém os middleware default das rotas ($middlewareAliases[])
// Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
//     //rota admin/
//     Route::get('', function(){
//         return "Admin";
//     })->name('');

//     //rota admin/user
//     Route::get('users', function(){
//         return "Usuários";
//     })->name('users');
    
//     //rota admin/user/1
//     Route::get('user/{id}', function($id){
//         return 'Usuário' . $id;
//     })->name('user');
// });

// Route::get('test', function(){
//     return 'test';
// })->middleware('auth');

//CRIANDO SUBDOMÍNIOS NA ROTA
// Route::domain('{user}.cursolaravelpro.test')->group(function(){
//     //user.subdominio/id
//     Route::get('{id}', function($user, $id){
//         return $user . " - " . $id;
//     });
// });

//FALLBACK DE ROTAS
//Fallback - você pode definir uma rota que será executada quando nenhuma outra rota corresponder à solicitação recebida, então, ao invés de termos um erro 404, teremos a rota que foi passado no fallback
//A rota de fallback deve ser sempre a última rota registrada pelo seu aplicativo.
// Route::fallback(function(){
//     //return "Hello World";
//     return view('welcome', [
//         'title' => 'Olá mundo!'
//     ]);
// });

//INJEÇÃO DE DEPENDÊNCIA
//Quando damos um return no laravel, ele tenta transformar esse return num objeto json, mas as vezes, por falta de compatibilidade com o tipo de objeto que está vindo, o retorno será vazio
// Route::get('painel', function(Request $request){
//     dd($request);
//     return $request;
// });

//INJEÇÃO DE MODEL
//A injeção de model funciona da mesma forma que a de dependência, contudo, na de model estamos injetando um model
//O parâmetro da rota deve ter o mesmo nome do usado no callback/controller
// Route::prefix('painel')->name('painel.')->group(function(){
//     Route::get('', function(){
//         return "Painel";
//     })->name('');

//     Route::get('{user:email}', function(User $user){
//         return "Nome: " . $user->name . "<br> Email: " . $user->email;
//     })->name('user');
// });

//Por default, o model binding faz a pesquisa pelo id, mas podemos personalizar em qual coluna a pesquisa será feita, passando o nome da coluna para o parâmetro da rota
//Agora na url, ao invés de ser passado o id, o email será passado para fazer a pesquisa
// Route::get('/posts/{user:email}', function (User $user) {
//     return $user;
// });

//FALSIFICAÇÃO DE MÉTODO PARA FORMULÁRIO
//Por default, um formulário HTML não aceita os verbos put, patch e delete. Então, para usarmos as rotas com esses verbos em formulários, precisamos passar no formulário um campo oculto com o name="_method" e value="verboHttp"
//Mas o laravel já nos disponibiliza uma diretiva blade que cria esse campo, para isso, basta usarmos a diretiva @method('verboHttp')

//MIDDLEWARE
//Usando middleware diretamente na rota
//Podemos passar um array ao invés de uma string, nos casos onde queremos aplicar mais de um middleware
// Route::get('user', function(){
//     return "Olá!";
// })->middleware('userAgent');

//Usando um middleware global - o middleware agora está sendo aplicado em todas as requisições de forma global, para isso, o middleware foi registrado no atributo $middleware do arquivo app/http/kernel.php
// Route::get('user', function(){
//     return "Olá!";
// });

//Aplicando middleware no grupo de rotas
//Podemos aplicar mais de um middleware nas rotas, passando ao invés de uma string, um array com todos os middleware que queremos aplicar. A ordem em que esses middleware serão aplicados depende de sua posição dentro do array. Nesse caso abaixo, o primeiro a ser aplicado será o checkToken
// Route::middleware(['checkToken', 'userAgent'])->group(function(){
//     Route::get('user', function(){
//         return "User";
//     });
    
//     Route::get('posts', function(){
//         return "Posts";
//     });
    
//     //Removendo uma rota da aplicação da regra do middleware dentro de um grupo de rotas
//     Route::get('services', function(){
//         return "Services";
//     })->withoutMiddleware('userAgent');
// });

//Podemos ao invés de passar todos os middlewares num array, criar um grupo de middlewares e passá-lo para a rota. 
//Esse grupo deve ser criado dentro do atributo $middlewareGroups do arquivo app/http/kernel.php
// Route::middleware('myApp')->group(function(){
//     Route::get('user', function(){
//         return "User";
//     });
    
//     Route::get('posts', function(){
//         return "Posts";
//     });
    
//     Route::get('services', function(){
//         return "Services";
//     });
// });

//Passando parâmetro para a middleware
//Podemos passar parâmetros pra dentro de um middleware aplicado a uma rota, mas para que isso seja possível, o registro do middleware que vai receber o parâmetro, deve ser feito no atributo $middlewareAliases. A sintaxe para passar um parâmetro para o middleware é middleware('nomeMiddleware:parâmetro')
//Dentro do middleware, no método handle, passamos como 3º parâmetro uma variável que vai receber o valor passado para o middleware
// Route::get('admin', function(){
//     return "Admin";
// })->middleware('checkToken:editor');

//CONTROLLER
//Ligando um controller a uma rota
//Nova estrutura da rota - (uri, [controller, métodoDoController])
//Route::get('users', [UserController::class, 'index'])->name('users.index');

//Passando parâmetro pro controller
//Route::get('users/{id}', [UserController::class, 'show'])->name('user.show');

//Model bilnding
//Route::get('users/{user}', [UserController::class, 'show2'])->name('user.show2');

//Controller de ação única
//Essa rota invoca automaticamente o controller através da sua classe, porque o controller tem o método __invoke
//Route::get('checkout/{token}', CheckoutController::class);

//Controller resource
//O método resource é utilizado para chamar um controller de CRUD
//Para criar esse controller, devemos usar a option --resource no comando make
//Quando criamos um controller dessa maneira, ele já vem por default com todos os métodos do CRUD criados
//Podemos adicionar outra option a esse comando, --model=NomeModel para o controller já vir com o model binding feito
// Route::resource('users', User2Controller::class);

//Personalizando o resource
//Método only() - cria apenas as rotas para os métodos passados como parâmetro
// Route::resource('users', User2Controller::class)->only(['index', 'edit']);

//Método except() - cria todas as rotas, menos as para os métodos passados como parâmetro
// Route::resource('users', User2Controller::class)->except(['index', 'edit']);

//Mais de um resource
//Utilizamos o método resources ao invés de resource
//Passamos as uri e classes dentro de um array associativo
// Route::resources([
//     'users' => User2Controller::class,
//     'posts' => User2Controller::class
// ]);

//Resource para api 
//Quando usamos o apiResource, as rotas create e edit não são criadas
// Route::apiResource('users', UserController::class);
// Route::apiResources([
//     'users' => UserController::class,
//     'posts' => UserController::class,
// ]);

//Aninhamento de resource (nested resource)
//Usuário e comentário
//users/{user}/comment - recupera todos os comentários de um usuário
//users/{user}/comment/{comment} - recupera um comentário específico
// Route::resource('users/{user}/comments', UserController::class);

//Ao invés de criar esse padrão de rota, podemos passar users.comments que teremos o mesmo resultado de users/{user}/comments, mas agora com um código mais limpo 
// Route::resource('users.comments', UserController::class);

//Existem casos onde não precisamos do id do usuário para ter acesso a um comentário, apenas do id do comentário, caso ele seja único. Para essa situação, temos o método shallow() que separa as rotas em duas entidades: a entidade users/{user}/comments e comments/{comment}
// Route::resource('users.comments', UserController::class)->shallow();

//Renomeando rotas resource
//Podemos renomear as rotas padrão criadas pelo resource com o método name()
// Route::resource('fotos', UserController::class)->names([
//     'create' => 'fotos.criar'
// ]);

//Renomeando parâmetros na rota resource
//Para renomear um parâmetro da rota resource, podemos usar o método parameters()
// Route::resource('users', UserController::class)->parameters([
//     'users' => 'admin_user',
//     //'users' => 'admin_user?',
// ]);

//Adicionando rotas extras em uma rota resource
// Route::resource('fotos', UserController::class);

//Essa nova rota será adicionada as rotas do resource, para isso, devemos passar a mesma url
// Route::get('fotos/posts', [UserController::class, 'posts'])->name('fotos.posts');

//Trabalhando com Request
// Route::get('user', function(Request $request){
    // dd($request);
    // dd($request->path()); // Retorna o caminho da url sem o hostname
    // dd($request->url()); // Retorna toda a url, mas sem as query string, caso existam
    // dd($request->fullUrl()); // Retorna toda a url e as query strings
    // dd($request->fullUrlWithQuery(['curso' => 'Laravel'])); // Adiciona os parâmetros passados a url e retorna a nova url com os novos parâmetros
    // dd($request->fullUrlIs("http://127.0.0.1:8000/user" )); // Compara a string passada como parâmetro com a full url retornando true ou false
    // dd($request->is("user")); // Compara a string passada como parâmetro com a request retornando true ou false. Caso a request tenha parâmetros, basta usarmos /* para indicar que é uma variável - $request->is("user/*")
    // dd($request->routeIs("user")); // Compara a string passada como parâmetro com o nome da rota, retornando true ou false
    // dd($request->method()); // Retorna o tipo de método da requisição
    // dd($request->isMethod('get')); // Compara o parâmetro passado com o método da requisição e retorna true ou false

// })->name('user');

//Capturando dados de input do usuário
// Route::get('user', function(Request $request){
    // dd($request);
    // dd($request->input()); //O método input sem parâmetro captura todos os campos da requisição (query string e post)
    // dd($request->input('token')); // Retorna o valor da query string (mesma aplicação pro método POST) passada como parâmetro
    // dd($request->input('curso', 'Laravel')); // Podemos passar um valor padrão como segundo parâmetro, para evitar erros se caso um campo vier vazio - null -
    // dd($request->input('products.curso')); // Retorna o valor de um campo array buscando pela chave (Ex: products[curso])
    // dd($request->input('products.*')); // Retorna todos valores de um campo array com um index numérico (Ex: products[])
    
    // dd($request->query()); //Retorna apenas as query string (não pegar o post)
    // dd($request->query('products')); //Retorna a query string passada como parâmetro
    // dd($request->query('city', 'São Paulo')); //Podemos passar um valor padrão como segundo parâmetro, para evitar erros se caso um campo vier vazio - null - 

    // dd($request->token); //Podemos passar diretamente a chave do campo que o laravel retorna seu valor
    // dd($request->only('token')); //Retorna o valor apenas do campo passado como parâmetro
    // dd($request->except('token')); //Retorna todos os valor menos o do campo passado como parâmetro

// })->name('user');

//Fazendo checagem nos inputs
Route::get('user', function(Request $request){
    // if($request->has('token')){
    //     dd('Token existe');  
    // } //O método has() verifica se o campo passado como parâmetro existe, retornando true ou false
    
    // if($request->has(['token', 'curso'])){
    //     dd('Token e curso existem');  
    // } //O método has() verifica se o campo passado como parâmetro existe, retornando true ou false. Podemos passar um array e verificar todos os campos passados nesse array, contudo, só retornará true caso todos os campos existam (funciona igual ao operador lógico &&)

    // $request->whenHas('token', function($input){
    //     dd('O valor do token é ' . $input);  
    // }); //O método whenHas() verifica se o campo existe e se caso exista, executa um closure (função)

    // if($request->hasAny(['token', 'curso'])){
    //     dd('Token ou curso existe');  
    // } //O método hasAny() verifica se o campo passado como parâmetro existe, retornando true ou false. Podemos passar um array e verificar todos os campos passados nesse array e retornará true caso pelo menos um desses campos exista (funciona igual ao operador lógico ||)

    // if($request->filled(['curso'])){
    //     dd('Curso não é vazio');  
    // } //O método filled() verificar se um campo não é vazio (funciona igual ao empty do php)

    // $request->whenFilled('curso', function($input){
    //     dd('O nome do curso é ' . $input);  
    // }); //O método whenFilled() verifica se o campo não é vazio e executa um closure (função)

    // if($request->missing('token')){
    //     dd('O campo token está faltando');
    // } // O método missing() verifica se o campo passado como parâmetro não está na request,

})->name('user');