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
    -   ```
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
- A classe do Laravel **Illuminate\Http\Request** fornece uma maneira orientada a objetos para interagir com a **solicitação HTTP atual** que está sendo tratada pelo seu aplicativo, bem como recuperar a entrada, os cookies e os arquivos que foram enviados com a solicitação.

- Para obtermos uma instância da solicitação Http, devemos injetar a classe Request como parâmetro do callback da rota ou na ação de um controller [doc](https://laravel.com/docs/10.x/requests#accessing-the-request)
    - ```
            use Illuminate\Http\Request;

            Route::get(uri, callback(Request $request)){} - aplicado na rota

            public function store(Request $request){} - aplicado na ação de um controller
      ```

    - Quando uma rota estiver esperando um parâmetro, devemos listar os parâmetros após a injeção de dependência
        - ```
                use Illuminate\Http\Request;

                public function store(Request $request, $id){} - aplicado na ação de um controller
          ```

- Através da classe Request, podemos recuperar os valores de entrada de uma requisição com uma série de métodos [doc](https://laravel.com/docs/10.x/requests#retrieving-input)



## VIEWS
- Os arquivos de view ficam armazenados dentro do diretório **resource/views** e levam a extensão **.blade.php** para poderem usar a engenharia de template blade

- Para acessarmos arquivos de view dentro de subdiretórios, devemos utilizar a notação de ponto **view('users.profile')** - a view **profile** está dentro de **views/user**

- Para exibir uma view, devemos usar o helper global **view()**. 
    - ```
            Route::get('/', function () {
                return view('greeting', ['name' => 'James']);
            });
      ```

- Para exibir uma view podemos também utilizar a **classe View** que é importada do Facades 
    - Utilizando a classe **View** temos acesso a métodos que o helper **view()** não disponibiliza.
    - A classe View nós dá por exemplo, os métodos **View::first()** (Renderiza a primeira view existente em um array de views) e **View::exists()** (Verifica se uma view existe) e muitos mais

    - ```
            use Illuminate\Support\Facades\View;
 
            return View::make('greeting', ['name' => 'James']);
      ```

- A sintaxe padrão do helper **view()** é o primeiro parâmetro sendo o nome do arquivo de exibição e o segundo, um array de variáveis que serão usadas na view
    - ```
            use Illuminate\Support\Facades\View;
 
            return View::make('greeting', ['name' => 'James']);
      ```

- Passando dados para a view
    - Podemos passar dados pra uma view, passando como segundo parâmetro do helper view() um array associativo, sendo a chave o nome da variável e o valor, o valor da variável **return view('greetings', ['name' => 'Victoria']);**

    - Podemos também encadear o método **with()** que nos possibilita encadear novos métodos antes de retornar a view **return view('greeting')->with(['name', 'Victoria']);** 



## Blade Template
- Blade é o mecanismo de modelagem de views incluído no laravel.

- Os arquivos blade usam a extensão **.blade.php** e ficam armazenados dentro da pasta **resources/views**

- A exibição de um modelo blade pode ser retorno de rotas e controladores através do helper global **view()**

- Para exibirmos dados dentro de um modelo blade, devemos usar a sintaxe **{{$nomeVar}}** (essa sintaxe blade usa internamente a função **htmlspecialchars** do php para evitar ataques XSS). E não estamos limitados a exibição de variáveis que foram passadas para a exibição, podemos ecoar qualquer função/código php dentro dessa sintaxe

- Temos a opção de renderizar o conteúdo sem escape html (não passa pela função htmlspecialchars) utilizando a sintaxe **{!! !!}**, mas devemos ter cuidado com essa opção, pois com ela estamos sujeitos a ataques XSS

- O blade template nos disponibiliza atalhos para as estruturas de controle PHP comuns, como instruções condicionais e de loop.
    - Sintaxe comum de uma diretiva - **@NomeDiretiva @endNomeDiretiva**

    - Alguns exemplos de diretivas
        - @if e @elseif
        - @unless
        - @isset
        - @empty
        - @auth
        - @switch
        - @foreach

- Podemos incluir uma visualização blade dentro de outra através da diretiva **@include(nomeView)**
    - A view que for incluída através do @include herdará todas as variáveis existente na view pai

    - Podemos passar variáveis para a view incluída, passando um segundo parâmetro para a diretiva @include, esse segundo parâmetro é um array associativo contendo o nome da variável e seu valor
        - `@include('view.name', ['status' => 'complete'])`

    - Temos algumas variações da diretiva @include
        - **@includeIf()** - Verifica se a view passada existe, caso ela exista, a renderização é feita, mas caso não exista, nada acontece e não temos um erro como retorno

        - **@includeWhen()** - Como primeiro parâmetro passamos uma expressão booleana. Dessa forma, a view só será renderizada caso essa expressão seja true

        - **@includeUnless()** - Como primeiro parâmetro passamos uma expressão booleana. Dessa forma, a view só será renderizada caso essa expressão seja false

        - **@includeFirst** - Recebe como parâmetro um array de views e fará a renderização da primeira view que existir

    - Podemos utilizar o @include juntamente com loops e o laravel nos disponibiliza a diretiva **@each()** onde criamos um loop apenas com uma linha de código
        - Visualizações renderizadas via @each não herdam as variáveis ​​da visualização pai

        - 1º parâmetro - a view que será renderizada

        - 2º parâmetro - é a coleção sobre a qual teremos a iteração

        - 3º parâmetro - é a variável que receberá o resultado de cada iteração

        - `@each('view.name', $jobs, 'job')`  

        - Podemos passar um quarto parâmetro pra diretiva @each, que será a view a ser renderizada caso a coleção estiver vazia
            - `@each('view.name', $jobs, 'job', 'view.empty')` 

- Layouts usando herança de modelo
    - Várias páginas dentro de um app usam a mesma estrutura, como o header e o footer, além da estrutura padrão html. Para evitar a repetição dessas estruturas em todas a views, podemos usar a herança de modelo

    - Criamos um arquivo que terá a estrutura padrão do layout e nos demais arquivos que usarão essa estrutura, devemos declarar a diretiva **@extends()** passando o caminho do arquivo com a estrutura padrão. Essa diretiva estende o layout padrão para as demais views

    - Podemos injetar conteúdo dentro do layout padrão de algumas formas
        - Com o **@section**
            - A diretiva **@section** dentro do layout padrão, serve para criarmos uma sessão dentro do layout que receberá o conteúdo das páginas que estão usando esse template. Para que possamos injetar conteúdo nessa section, ela deve terminar com **@show** ou invés de **@endsection**

            - Quando utilizamos **@section @show** podemos criar blocos html dentro dessa sessão com uma estrutura padrão, e nas views que usarão esse template, podemo herdar essa estrutura padrão com a diretiva **@parent**

            - E além de herdar a estrutura padrão da section, podemos adicionar mais conteúdo 

            - ```
                - Template
                @section('sidbar')
                    <ul>
                        <li>Menu principal 1</li>
                        <li>Menu principal 2</li>
                        <li>Menu principal 3</li>
                    </ul>
                @show

                - Views
                @section('sidbar')
                    @parent
                    <ul>
                        <li>Menu secundáio 1</li>
                        <li>Menu secundáio 2</li>
                        <li>Menu secundáio 3</li>
                    </ul>
                @endsection
              ```

        - Com o **@yeld**  
            - A diretiva **@yeld** renderiza conteúdo da mesma forma que a **@section**, mas existem algumas diferenças entre as duas

            - O **@yeld** não aceita que criemos um bloco html dentro dele que servirá como conteúdo padrão, mas podemos passar como segundo parâmetro uma string que servirá como valor padrão

            - Não conseguimos usar o valor padrão do **@yeld** e acrescentar mais, se injetarmos conteúdo no **@yeld** será o conteúdo a ser renderizado

            - O **@yeld** é utilizado para injetarmos conteúdo que mudará em toda view, ou seja, o conteúdo principal, já o **@section** serve para injetarmos conteúdos que se repetem entre as views, como o header, footer, sidbar e outros

            - ```
                - Template 
                    @yield('content', 'Default content')

                - View
                    @section('content')
                        @each('user.user', $users, 'user')
                    @endsection
              ``` 
- Empilhando conteúdo em determinadas views [doc](https://laravel.com/docs/10.x/blade#stacks)
    - A diretiva **@stack** possibilita que adicionemos arquivos e códigos css e js em views específicas

    - Assim, podemos incluir código apenas onde ele será usado, fazendo com que a página só carregue o que ela realmente irá usar

    - ```
        - Template
            //@stack cria o espaço pro conteúdo
            @stack('scripts')

        - View
            //@push empurra de fato o conteúdo
            @push('scripts')
                <script src="/example.js"></script>
            @endpush
      ```

    - O conteúdo é inserido de forma sequencial, ou seja, do primeiro para o último, então o último conteúdo a ser inserido na stack estará na última posição da pilha. Contudo existem meios de inserir um conteúdo no começo da pilha

    - Podemos empilhar um arquivo no começo da pilha utilizando a diretiva **@prepend**
        `@prepend('scripts') <script src="/example.js"></script> @endprepend`

## Componentes [doc](https://laravel.com/docs/10.x/blade#components)

- Em um contexto padrão de uso do blade template, enviamos dados para as views através dos métodos do controller, dessa forma temos ums relação muito forte entre view/controller

- No contexto do uso de componentes, essa relação se torna fraca e flexível porque um componente vive em um encapsulamento isolado podendo ser aplicado em qualquer parte do projeto

- Ou seja, no Laravel, um componente é uma unidade de funcionalidade encapsulada que pode ser reutilizada em diferentes partes de um aplicativo. É construído como uma classe e segue o padrão de projeto "Componente". Os componentes ajudam a modularizar e organizar o código, tornando-o mais legível e fácil de manter. Eles lidam com tarefas específicas, como notificações, autenticação, upload de arquivos, etc. Podem ser distribuídos como pacotes Composer para serem compartilhados entre projetos Laravel.

- Comando para criar um componente
    - `php artisan make:component NomeComponente`

    - Criando componentes dentro de subdiretórios
        - `php artisan make:component Forms/Input` 

    -  Esse comando cria um componente baseado em classes

    - Será criado um diretório **app/View/Components** com a classe referente ao componente

    - Também será criado um modelo de exibição para o componente dentro do diretório **resources/views/components**

- Renderizando um componente
    - Para renderizarmos um componente, podemos usar a tag de componente balde que começam com a string **x-** seguido pelo nome da classe do componente

    -   ```
        <x-alert/>
 
        <x-user-profile/>

        // Para classes aninhadas dentro de app/Views/Components
        // Exemplo de classe aninhada - app/View/Components/Inputs/Button.php
        <x-inputs.button/>

        ```

- Como um componente tem um ciclo de vida por sí só e conseguimos passar dados para ele através do método **__construct**, não precisamos de um controller e podemos utilizar o router **view**
    - `Route::view(uri, view);`

- Passando dados para componentes
    - Podemos tornar os componentes ainda mais flexíveis quando passamos dados utilizando atributos html
        -   ```
                // Valores primitivos embutidos em código podem ser passados ​​para o componente usando strings de atributo HTML simples (type="error")

                // As expressões e variáveis ​​PHP devem ser passadas para o componente por meio de atributos que usam o :caractere como prefixo (:message="$message")

                <x-alert type="error" :message="$message"/>
            ```

        - Dessa forma conseguimos atribuir comportamentos diferentes para o mesmo componente de acordo com o valor do atributo

        - É necessário definir todos os atributos de dados do componente em seu construtor de classe. Todas as propriedades públicas em um componente serão automaticamente disponibilizadas para a exibição do componente
            -   ```
                    // Classe do componente

                    // O valor passado no atributo do componente será atribuído ao valor do parâmetro da função construtora

                    // Contudo, se caso não passemos nenhum valor como atributo para o componente, teremos um erro e a aplicação será paralisada, assim devemos passar um valor default para o parâmetro da função

                    class UserList extends Component
                    {
                        public $users;
                        public $type;
                        
                        public function __construct($type = 'lista')
                        {
                            $this->users = User::all();
                            $this->type = $type;
                        }

                    }

                    // Renderizando o componente
                    <x-user.user-list/> (Sem o atributo type declarado, ele terá por padrão o valor lista passado como default na função construtora)

                    <x-user.user-list type="card"/> (Com o atributo type definido, essa valor será o atribuído ao parâmetro da função construtora)
                ``` 

    - Contudo, um componente quando recebe os dados diretamente do banco de dados, passa a ter uma relacionamento com ele muito forte, tornando esse componente menos flexível. 
    
    - Portanto, uma forma de deixar o componente mais flexível é dar um passo atrás, deixando toda a regra de negócio pro controller e passando os dados pro componente, assim, esse componente se torna altamente flexível

    - Para isso, o roteamento e o controller passam a ter o funcionamento padrão, mas o componente recebe os dados do controller através de um atributo. O atributo que vai receber os dados deve ter o prefixo **:**
        -   ```
                // :message="$message" - recebendo os dados do controller
                <x-alert type="error" :message="$message"/>
            ```
- Quando estivermos usando componentes do blade e javascript (vue, alphine ...) juntamente, o laravel pode se confundir com a sintaxe usada pelos frameworks js, no que diz respeito ao atributos dos componentes, tanto os componentes laravel como os javascript, utilizam a **sintaxe :**, logo, para evitar erros, no componente laravel, basta usarmos a **sintaxe ::**

- Em relação a escrita das variáveis, o indicado é usar o **CamelCase** nas variáveis no **controller e na viwe** e no componente, o formato **kebab-case**

- Podemos criar métodos públicos dentro da classe do componentes, alterando suas características da forma que quisermos
    - Por exemplo, o trecho de código a seguir mudar o background de um elemento que satisfazer a condição imposta
        -   ```
                // Classe co componente
                public function isSelected($userId){
                    return $userId === 3;
                }

                // View do componente
                bg-{{ $isSelected($user->id) ? 'info' : $cardClass }}
            ```

- Podemos criar atributos para serem injetados na raiz do nosso componente, e que não serão declarados na sua classe, ou seja, atributos padrão do html como, uma classe e um id são acessados através da variável **$attributes**. Portanto, todo atributo que for passado para o componente e que não esteja listado dentro da sua classe, estarão acessíveis nessa variável **$attributes**
    -   ```
        // Os atributos class e id serão inseridos na raiz do componente através da variável $attributes
        
        // View do componente
        <div {{ $attributes }}> ... </div>

        // Renderização do componente
        <x-user.user-list type="card" :users="$users"  card-class="danger" class="container py-5" id="atributo-raiz"/>
        ```

- Mesclando atributos [doc](https://laravel.com/docs/10.x/blade#default-merged-attributes)
    - Podemos mesclar atributos do componente com atributos padrão do html
    - Como no exemplo abaixo, estamos mesclando a classe com atributos dinâmicos do componente com atributos padrão do html. Essa é uma forma de deixar o componente mais flexível, podendo aceitar atributos adicionais 
        -   ```
                // View do Componente
                <div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
                    {{ $message }}
                </div>

                // Componente
                <x-alert type="error" :message="$message" class="mb-4"/>

                // Componente renderizado
                <div class="alert alert-error mb-4">
                    <!-- Contents of the $message variable -->
                </div>
            ```

- Mesclando classes condicionalmente
    - Podemos mesclar atributos baseados em uma condição. Para fazermos isso, devemos usar o método **class()**
    - Como no exemplo abaixo. A classe 'bg-red' só será usada caso a condição $hasError seja verdadeira. Podemos também encadear o método merge para mesclar classes
        -   ```
                <button {{ $attributes->class(['p-4', 'bg-red' => $hasError])->merge(['type' => 'button']) }}>
                    {{ $slot }}
                </button>

                <button {{ $attributes->class(['btn', 'btn-danger' => $isRed])->merge(['id' => 'btn-'.$variant, 'type' => $type]) }}>
                    {{$name}}
                </button>
            ```

- Podemos mesclar atributos não pertencentes a classe, ou seja, não são atributos injetados. Esse caso é particularmente útil para criarmos atributos com valores padrão para um elemento, e caso precise, podemos sobrescrever esse valor
    - Como no exemplo abaixo, declaramos um valor padrão pro atributo type de botão, mas quando invocamos o componente, passamos outro valor, sobrescrevendo o valor padrão
        -   ```
                // View do componente
                <button {{ $attributes->merge(['type' => 'button']) }}>
                    {{ $slot }}
                </button>

                // Invocando o componente
                <x-button type="submit">
                    Submit
                </x-button>

                // Componente renderizado
                <button type="submit">
                    Submit
                </button>
            ```

- Mas se quisermos que um atributo tenha valores padrão e injetados, podemos usar o método **prepend()** para concatenar esses valores
    - Como no exemplo abaixo,ambos os valores pro atributo data-controller foram concatenados
        -   ```
                // View do componente
                <div {{ $attributes->merge(['data-controller' => $attributes->prepends('profile-controller')]) }}>
                    {{ $slot }}
                </div>

                // Invocando componente
                <x-component data-controller="user-controller">
                    Olá, mundo
                </x-component>

                // Componente renderizado
                <x-component data-controller="profile-controller user-controller">
                    Olá, mundo
                </x-component>
            ```

- Se quisermos adicionar atributos fora da raiz do elemento, ou seja, em elementos filhos, podemos usar o filtro, que nos possibilita isolar um atributo em específico. Contudo, esse filtro só funciona nos atributos que foram declarados fora da classe do elemento, ou seja, que são acessíveis na variável **$attributes**
    - [Documentação](https://laravel.com/docs/10.x/blade#filtering-attributes)

    - Exemplos
        -   ```
                // Filtrando com closure
                // Esse filtro retornara o atributo passado como parâmetro e seu valor
                {{ $attributes->filter(fn (string $value, string $key) => $key == 'data-url') }}

                // Recupera todos os atributos cuja chave comece com uma determinada string
                {{ $attributes->whereStartsWith('user') }}

                // Recupera todos os atributos cuja chave não comece com uma determinada string
                {{ $attributes->whereDoesntStartWith('user') }}

                // Adicionando o método first() podemos renderizar o primeiro valor do atributo em um determinado pacote de atributos
                {{ $attributes->whereStartsWith('data-url')->first() }}
            ```