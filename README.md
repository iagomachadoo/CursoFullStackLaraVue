# Curso Full Stack LaraVue

## COMANDOS
- Verificar versão do laravel -> `php artisan --version`

- Iniciar um servidor local -> `php artisan serve`

- Instalar composer globalmente -> `composer global require laravel/installer`

- Criar projeto com composer global -> `laravel new nomeProjeto`

- Mais detalhes de um comando artisan ->` php artisan help nomeComando` (Ex: nomeComando - make:controller)

- Subir as tabelas pro banco de dados -> `php artisan migrate` (caso o banco de dados não esteja criado, esse comando cria e sobre as tabelas, o nome do banco de dados será o que está nas configurações de ambiente - .env - DB_DATABASE='')

- Criar um middleware -> `php artisan make:middleware NomeMiddleware`

- Criar um controller -> `php artisan make:controller NomeController`
    - `php artisan make:controller NomeController --resource` (cria um controller resource)
    - `php artisan make:controller NomeController --model=NomeModel` (faz a injeção do model nas ações do controller)
    - `php artisan make:controller NomeController --requests` (cria classes de validação request e já faz a injeção dessas classes nas ações do controlador)
    - `php artisan make:controller NomeController --api` (cria um controller resource para api)

- Criar controller de ação única -> `php artisan make:controller NomeController --invokable`

- Listar rotas existentes -> `php artisan route:list` 
    - `php artisan route:list -v` (mostra os middleware aplicado a cada rota)
    - `php artisan route:list --path=users` (mostra apenas as rotas que começam com o path)
    - `php artisan route:list --only-vendor` (mostra apenas as rotas definidas por pacotes de terceiros)

## ESTRUTURA DE ARQUIVOS
- composer.json -> lista as dependências instaladas no projeto

- package.json -> lista de dependências para o front-end

- /config -> armazena os arquivos de config do projeto. Esses arquivos puxam informações do .env

- /public -> é a pasta pública do laravel a qual um servidor deve apontar

- /public/index.php -> arquivo de entrada do laravel

- /storage -> armazena dados do sistema, como arquivos de upload



## ROTAS
- O carregamentos das rotas (web/api) são feitos dentro de **app/providers/RouteServiceProvider.php** no método **boot()**

- Estrutura de uma rota 
    - `Route::get(uri, callback)` 
        - Route é uma classe
        - get() é um verbo http
        - uri é a url da rota
        - callback é o retorno da rota que aceita uma função, array e string

- Métodos de roteamento disponíveis [doc](https://laravel.com/docs/10.x/routing#available-router-methods) 
    - `Route::get($uri, $callback);`
    - `Route::post($uri, $callback);`
    - `Route::put($uri, $callback);`
    - `Route::patch($uri, $callback);`
    - `Route::delete($uri, $callback);`
    - `Route::options($uri, $callback);`
    - `Route::match([get, post, ...], $uri, $callback);` - o método match() faz com que a rota aceite os verbos que foram passados no array
    - `Route::any($uri, $callback);` - o método any() faz com que a rota responda a todos os verbos existentes

- Rotas nomeadas [doc](https://laravel.com/docs/10.x/routing#named-routes)
    - `Route::get(uri, callback)->name('nomeRota')`
    - O método **name()** atribui um nome para a rota. 
    - Na montagem de links na view, podemos ao invés de usar a url, apenas passar o nome da rota, deixando que o laravel vá atrás da rota com esse nome. 
    - Essa funcionalidade faz com que, caso mudemos a url da rota, nada se altera, porque estamos referenciando a rota pelo seu nome 

- Redirecionamento de rotas [doc](https://laravel.com/docs/10.x/routing#redirect-routes)
    - Caso 1 - Sem o uso de lógica. 
        - Quando o usuário acessar uma rota ele será redirecionado para outra. Essa situação é interessante quando há a troca do endereço da rota. Nesse caso, devemos passar o parâmetro com o status do redirecionamento, esses status são os códigos **3xx**. O laravel disponibiliza o método **permanentRedirect()** que já trás o status internamente
    - Caso 2 - Quando é necessário aplicar alguma lógica. 
        - Dentro da rota origem, criamos a rota, a lógica e no final da rota damos um retorno com o helper global **redirect()**, passando para ele a rota destino. Mas a forma mais aconselhável de criar redirecionamentos é utilizando o nome da rota ao invés da url, para isso, deixamos de passar a url para o método redirect e concatenamos a ele outro helper global, o **route()** e então, dentro do **route()** passamos o nome da rota

- Rotas de visualização [doc](https://laravel.com/docs/10.x/routing#view-routes)
    - Esse caso é valido quando não precisamos passar por algum controller com as regras de negócio para no final mostrar uma view. O retorno da view é feito diretamente pela rota através do método **view()**. 
    - Também podemos passar variáveis para a view nesse caso, basta passar como terceiro parâmetro um array com os valores - `Route::view(url, view, ['chave' => 'valor'])`

- Rotas com parâmetro [doc](https://laravel.com/docs/10.x/routing#route-parameters)
    - Caso 1 - Parâmetro obrigatório. 
        - Se o parâmetro não for passado, teremos um 404 - `Route::get('user/{id}', function($id){})`
    - Caso 2 - Parâmetro opcional. 
        - Com o parâmetro opcional, se o parâmetro não for passado, não teremos um 404 como retorno, pois indicamos ao laravel através da sintaxe **{par?}** que podemos ou não passá-lo. Mas o callback ainda fica esperando um valor para o parâmetro, então devemos inicializar ele com um valor padrão - **($par = null)** (esse valor padrão pode ser qualquer valor) - `Route::get('user/{id?}', function($id = valorDefault){})`. 
        - Podemos também passar mais de um parâmetro, tanto obrigatório como opcional (a ordem desses parâmetros importa) - `Route::get('user/{id?}/{nome?}', function($id = valorDefault, $nome = valorDefault){})`

- Validando o tipo do parâmetro [doc](https://laravel.com/docs/10.x/routing#parameters-regular-expression-constraints)
    - Para validarmos o parâmetro da rota, devemos utilizar o método **where()** passando para ele o parâmetro a ser validado e a expressão regular que limitará o formato do parâmetro (caso a rota tenha mais de um parâmetro, o método where aceita um array) - `Route::get(rota/{par})->where(['par', 'regex'])`. 
    - Mas o laravel já disponibiliza helpers que fazem essa validação implicitamente, como o **whereNumber()**, **whereAlpha()** e outros mais
    - Quando a validação de parâmetros se tornar repetitiva, o ideal é torná-la global, criando essas verificações dentro do arquivo **app/providers/RouteServiceProvider.php**, para isso, dentro do método **boot()** desse arquivo, devemos usar a classe `Route::pattern('par', 'regex')`, assim, todas as rotas que tiverem esse parâmetro, terão a validação

- Grupo de rotas [doc](https://laravel.com/docs/10.x/routing#route-groups)
    - MIDDLEWARE - atribui middleware a todas as rotas do grupo - `Route::middleware()->group()`
    - CONTROLLER - atribui um controller a todas as rotas do grupo - `Route::middleware()->group()`
    - PREFIXO - atribui um prefixo de uri a todas as rotas do grupo - `Route::prefix()->group()`
    - NAME - atribui um prefixo de nome a todas as rotas do grupo - `Route::name()->group()`
    - O arquivo **app/http/kernel.php** controla as camadas **web/api**. Dentro dele já existem middleware prefixados para essas camadas, ou seja, qualquer requisição para essas camadas já passam por esses middleware por default. Nesse arquivo também contém os middleware default das rotas **($middlewareAliases[])**

- Fallback [doc](https://laravel.com/docs/10.x/routing#fallback-routes)
    - Podemos definir uma rota que será executada quando nenhuma outra rota corresponder à solicitação recebida, então, ao invés de termos um erro 404, teremos a rota que foi passado no fallback - `Route::fallback(function(){})`
    - A rota de fallback deve ser sempre a última rota registrada pelo seu aplicativo.

- Injeção de dependência [doc](https://laravel.com/docs/10.x/routing#dependency-injection)
    - Serve para injetarmos uma classe - `Route::get(uri, function(Classe $classe){})` 

- Injeção de model - model binding [doc](https://laravel.com/docs/10.x/routing#route-model-binding)
    - A injeção de model funciona da mesma forma que a de dependência, contudo, na de model estamos injetando um model - `Route::get(uri, function(Model $model){})` 
    - Contudo, o nome do parâmetro da rota e o do callback/controller devem ser o mesmo - `Route::get('/users/{user}', function (User $user) { return $user->email;});`
    - Por default, o model binding faz a pesquisa pelo id, mas podemos personalizar em qual coluna a pesquisa será feita, passando o nome da coluna para o parâmetro da rota - `Route::get('/posts/{user:email}', function (User $user) {return $user;});` - agora na url, ao invés de ser passado o id, o email será passado para fazer a pesquisa

- Falsificação de métodos para formulário [doc](https://laravel.com/docs/10.x/routing#form-method-spoofing)
    - Por default, um formulário HTML não aceita os verbos **put**, **patch** e **delete**
    - Então, para usarmos as rotas com esses verbos em formulários, precisamos passar no formulário um campo oculto com o **name="_method"** e **value="verboHttp"**
    - Mas o laravel já nos disponibiliza uma diretiva blade que cria esse campo - diretiva **@method('verboHttp')**
    - Nos formulários que apontam para os verbos **post**, **put**, **patch** e **delete** devem ter a proteção **CSRF**, caso contrário, o requisição será rejeitada - diretiva **@csrf** - [doc](https://laravel.com/docs/10.x/routing#csrf-protection)



## MIDDLEWARE
- Middleware [doc](https://laravel.com/docs/10.x/middleware)
    - Os middleware são um mecanismo para inspecionar e filtrar as solicitações Http. Eles agem como camadas que a solicitação Http deve passar antes de chegar na aplicação. Cada camada pode examinar a solicitação e até rejeitá-la totalmente. 

- Criando e aplicando middleware [doc](https://laravel.com/docs/10.x/middleware#registering-middleware)
    - Para criar um middleware usamos o comando `php artisan make:middleware`
    - Depois do arquivo criado, precisamos registrar esse novo middleware para que o laravel saiba de sua existência, o registro de middleware para rotas (esse caso é para quando iremos usar um middleware diretamente em uma rota com o método middleware()) fica dentro de **app/http/kernel.php** no atributo **$middlewareAliases** (esse tipo de registro serve para atribuirmos um alias ao middleware, ou seja, um nome, para que possamos referenciá-lo no método middleware() pelo seu nome e não pela sua classe), já na rota, temos que concatenar o método `middleware('NomeMiddleware')` para que ele seja aplicado, mas esse formato demanda que passemos esse método middleware('NomeMiddleware') em todas as rotas ou grupo de rotas que queremos que ele seja aplicado. 
    - Podemos excluir a aplicação de um middleware para uma rota específica quando aplicado a um grupo de rotas, para isso, devemos usar o método **withoutMiddleware()** passando como parâmetro, os middleware que queremos que sejam excluídos. Podemos passar esse método diretamente ao grupo de rotas, se quisermos que todas as rotas do grupo não passem por determinados middelwares

- Middleware global [doc](https://laravel.com/docs/10.x/middleware#global-middleware)
    - Podemos declarar um middleware globalmente passando a classe do middleware para o atributo global **$middleware** dentro de **app/http/kernel.php** e a partir disso, todas as requisições passarão por esse middleware sem a necessidade de usarmos o método **middleware()**

- Grupo de middleware [doc](https://laravel.com/docs/10.x/middleware#middleware-groups)
    - Quando estamos utilizando grupos de rotas ou rotas individuais passando mais de um middleware para as rotas, dependendo da quantidade de middleware, podemos deixar nossas rotas muito verbosas, para minimizar isso, temos a possibilidade de criar um grupo de middleware.
    - Para criar esse grupo, devemos adicionar um novo grupo de middleware dentro do atributo **middlewareGroups[]** do arquivo **app/http/kernel.php**, aqui, a ordem do middleware dentro do grupo importa

- Definindo prioridade de middleware [doc](https://laravel.com/docs/10.x/middleware#sorting-middleware)
    - Podemos definir a ordem de aplicação dos middleware de forma global, ou seja, toda aplicação de middleware seguirá essa ordem. Para fazer isso, temos que criar o atributo público **$middlewarePriority** dentro do arquivo **app/http/kernel.php** e passar os middleware na ordem desejada

- Passando parâmetro para dentro do middleware [doc](https://laravel.com/docs/10.x/middleware#middleware-parameters)
    - Podemos passar parâmetros pra dentro de um middleware aplicado a uma rota, mas para que isso seja possível, o registro do middleware que vai receber o parâmetro, deve ser feito no atributo **$middlewareAliases**. 
    - A sintaxe para passar um parâmetro para o middleware é **middleware('nomeMiddleware:parâmetro')**. 
    - Dentro do middleware, no método **handle()**, passamos como 3º parâmetro uma variável que vai receber o valor passado para o middleware



## CONTROLLER
- Um controller serve para controlar a requisição do usuário, fazer o processamento necessário e ao final, devolver esses dados para o usuário

- A convenção de nomenclatura para controllers é o uso do pascal case com a primeira palavra no singular seguido por controller (Ex: UserController)

- Para ligar uma rota a um controller, devemos mudar um pouco a estrutura padrão da rota onde passamos uma função como segundo parâmetro (`Route::get(uri, function)`). No lugar da função, devemos passar um array com a primeira posição sendo ocupada pela classe do controller e a segunda pelo método que iremos usar desse controller (`Route::get(uri, [NomeController::class, 'nomeMétodo'])`)

- Passando parâmetros pro controller
    - Para passarmos um parâmetro vindo da rota pro controller, primeiro, nesse rota, devemos declarar o parâmetro (`rota/{parâmetro}`) para depois, no método do controller, declararmos esse parâmetro (`public function método($parâmetro)`). 
    - O ideal é que ambos tenham o mesmo nome

- Injeção de dependência no controller
    - Injetando uma classe dentro de um método do controller. 
    - Para fazer isso, dentro do método, basta passar o nome da classe e pendurar seu valor numa variável que será usada dentro do controller (`show(Request $request)`). 
    - Também devemos importar o caminho dessa classe pra dentro do controller (`use Illuminate\Http\Request;`). 
    - Nesse caso, a classe Request nos dará acesso a todas as informações passadas na request.
    - Quando injetarmos um model, teremos acesso ao dados que viram do banco de dados

- Aplicando middleware no controller [doc](https://laravel.com/docs/10.x/controllers#controller-middleware)
    - Para isso, devemos instanciar o método **__construct()** do php e dentro dele chamar o middleware (`__construct($this->middleware())`)
    - A partir disso, todos os métodos/ações dentro desse controller passarão pelo middleware instanciado. 
    - Podemos escolher quais métodos usarão ou não o middleware, isso é possível com o método **only()** - only() aplicará o middleware apenas aos métodos que foram passados como parâmetro - e **except()** - except() aplicará o middleware a todos os métodos , exceto aos que foram passados como parâmetro.
    - Também podemos passar um middleware através de um **closure** (é o mesmo closure que tem dentro do arquivo de middleware). Dessa forma, estamos criando o middleware apenas para esse controller, sem a necessidade de fazer o registro dentro do **kernel.php**. Esse caso é mais usado quando precisamos de um middleware muito específico, que só será usado em um controller e em nenhum outro lugar. Aceita os métodos only() e except() - `$this->middleware(function(){})`

- Controller de ação única (Single Action Controller) [doc](https://laravel.com/docs/10.x/controllers#single-action-controllers)
    - Esse tipo de controller é utilizado para quando precisamos executar apenas uma ação ou quando essa ação é muito complexa. 
    - Ele é invocado automaticamente sem precisarmos passar o seu método para a rota (`Route::get('checkout', CheckoutController::class);`). 
    - Para criar esse tipo de controller usamos o comando `php artisan make:controller NomeController --invokable`. 
    - Dentro do controller de ação única, existe um método default que é o **__invoke**. 
    - Esse método é chamado automaticamente pela rota para invocar o controller 

- Controller resource [doc](https://laravel.com/docs/10.x/controllers#resource-controllers)
    - Para não precisarmos declarar as rotas de um CRUD manualmente, o laravel disponibiliza o controller resource, que por default já é criado com todas a ações padrões de um CRUD. 
    - Para criar esse tipo de controller, temos que usar o comando `php artisan make:controller NomeController --resource`
    - Para chamá-lo na rota, usamos o método resource e a classe do controller, sem a necessidade de especificar o método (`Route::resource(uri, NomeController::class)`). 
    - Utilizando o método resource na rota, o laravel já cria as rotas padrões de um CRUD em apenas uma linha de código
    - E o laravel vai além, se adicionarmos o option **--model=NomeModel** ao comando make:controller, o laravel já faz a injeção do model nos métodos do controller - `php artisan make:controller NomeController --resource --model=NomeModel` 
    - Podemos gerar classes de solicitação de formulário (Classes que farão a validação dos dados enviados em um formulário, esses arquivos serão criados dentro de **app/Http/Requests**) para os métodos de armazenamento e atualização do controlador, dessa forma, as ações de **store** e **update** já terão essas classes injetadas por default. Para isso, usamos o comando `php artisan make:controller NomeController --requests`

- Personalizando os métodos do controller resource [doc](https://laravel.com/docs/10.x/controllers#restful-partial-resource-routes)
    - Na rota, ao chamarmos o controller resource, o laravel já cria todas as rotas automaticamente, mas podemos indicar para ele, as rotas que queremos que sejam criadas, para isso, temos dois métodos - **only()** e **except()** - o método only() diz quais rotas queremos que sejam criadas, já o except, cria todas as rotas, menos as passadas como parâmetro
    - `Route::resource('users', UserController::class)->only(['index', 'store']);` apenas as rotas index e store serão criadas
    - `Route::resource('users', UserController::class)->except(['index', 'store']);` todas as rotas serão criadas, menos index e store

- Utilizando mais de um resource
    - Quando temos mais de uma rota resource, para não precisarmos declarar cada uma, tornando o arquivo de rotas verboso, o laravel nos disponibiliza uma maneira de passar vários resources em apenas um método, ou invés de usarmos o **::resource**, utilizaremos o **::resources**
    - `Route::resources(['users' => UserController::class, 'posts' => PostsController::class])` - a desvantagem do método **resources**, é que ele não aceita os métodos only() e except() 

- Utilizando resource para api [doc](https://laravel.com/docs/10.x/controllers#api-resource-routes)
    - `Route::apiResource('uri', Controller)` - único resource
    - `Route::apiResources(['uri' => Controller])` - múltiplos resources
    - Quando estamos trabalhando com api, podemos criar o método apiResource() que no geral, faz a mesma coisa que o resource comum, mas agora, as rotas que renderizam views (create e edit) não são criadas, apenas as de consumo e modificação de dados (index, store, show, update e delete)
    - O apiResource seria a mesma coisa que `Route::resource()->except('create', 'edit')`

- Aninhamento de resource (nested resource) [doc](https://laravel.com/docs/10.x/controllers#restful-nested-resources)
    - Imagine um sistema onde temos usuários e seus comentários, a rota `/users/{users}/comments` irá retornar todos os comentários de um usuário específico 
    - Imagine também que queremos retornar um comentário específico, a rota seria `/users/{users}/comments/{comment}`. O resource desse caso seria `Route::resource('/users/{users}/comments', Controller)` e assim todas as rotas de listagem até a de excluir seriam criadas
    - Mas temos uma alternativa mais limpa para a criação desse padrão de rotas, que seria o uso da notação de ponto (.) `Route::resource('users.comments', Controller)` que nos daria o mesmo resultado do exemplo acima, mas agora com um código mais limpo
    - Aninhamento superficial [doc](https://laravel.com/docs/10.x/controllers#shallow-nesting)
        - Contudo, existem cenários onde queremos retornar um comentário específico, mas não precisamos do id do usuário, porque o próprio comentário tem seu id único (a rota seria algo como `comments/{comment}`) e para criar esse padrão dentro do resource, temos o método **shallow()** (`Route::resource('users.comments', Controller)->shallow()`) que resultará em duas entidades, termos as rotas **index**, **store** e **create** com o padrão `users/{user}/comments` e as rotas **show**, **update**, **destroy** e **edit** com o padrão `comments/{comment}`

- Renomeando rotas resource [doc](https://laravel.com/docs/10.x/controllers#restful-naming-resource-routes)
    - Quando usamos `Route::resource()` as rotas que são criadas já tem tem seus nomes definidos, mas se caso precisarmos modificar esses nomes, podemos usar o método **names()** 
    - `Route::resource()->names(['create' => 'criar', 'update' => 'atualizar'])`

- Renomeando parâmetros de rota resource [doc](https://laravel.com/docs/10.x/controllers#restful-naming-resource-route-parameters)
    - Por padrão, a rota resource criará os parâmetros para as rotas **show**, **update**, **destroy** e **edit**, mas se caso quisermos mudar o nome desses parâmetros, podemos usar o método **parameters** que recebe um array associativo tento como chave o nome do parâmetro e o valor o novo nome do parâmetro
    - ```
        Route::resource('users', AdminUserController::class)->parameters([
            'users' => 'admin_user'
        ]);

        /users/{admin_user}
    ```

- Traduzindo (localizando) rotas [doc](https://laravel.com/docs/10.x/controllers#restful-localizing-resource-uris) 
    - Podemos traduzir os métodos das rotas do resource, que por default vem em inglês 
    - Para isso, devemos criar uma configuração global no arquivo **app/providers/RouteServiceProvider.php** dentro do método **boot()**
    - No início do método **boot()** devemos declarar `Route::resourceVerbs(['create' => 'criar', 'update' => 'atualizar']);`

- Adicionando rotas extras em uma rota resource [doc](https://laravel.com/docs/10.x/controllers#restful-supplementing-resource-controllers)
    - Podemos incluir mais rotas dentro da rota resource, basta apenas declararmos normalmente a rota que queremos, mas usando a mesma url da rota resource
    - Devemos declarar as rotas complementares antes da resource, para não haver sobrescrita
    - A rota foto/posts será adicionada as rotas do resource fotos
    - ```
        Route::resource('fotos', UserController::class); 
        Route::get('fotos/posts', [UserController::class, 'posts'])->name(fotos.posts);

      ```


## REQUEST
*última aula assistida - 41. 415 CONTROLLERS Resource controllers - adicionando rotas extras