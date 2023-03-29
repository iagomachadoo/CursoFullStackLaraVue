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

- Criar controller de ação única -> `php artisan make:controller NomeController --invokable`

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

- Rotas nomeadas
    - `Route::get(uri, callback)->name('nomeRota')`
    - O método **name()** atribui um nome para a rota. 
    - Na montagem de links na view, podemos ao invés de usar a url, apenas passar o nome da rota, deixando que o laravel vá atrás da rota com esse nome. 
    - Essa funcionalidade faz com que, caso mudemos a url da rota, nada se altera, porque estamos referenciando a rota pelo seu nome 

-Redirecionamento de rotas
    - Caso 1 - Sem o uso de lógica. 
        - Quando o usuário acessar uma rota ele será redirecionado para outra. Essa situação é interessante quando há a troca do endereço da rota. Nesse caso, devemos passar o parâmetro com o status do redirecionamento, esses status são os códigos **3xx**. O laravel disponibiliza o método **permanentRedirect()** que já trás o status internamente
    - Caso 2 - Quando é necessário aplicar alguma lógica. 
        - Dentro da rota origem, criamos a rota, a lógica e no final da rota damos um retorno com o helper global **redirect()**, passando para ele a rota destino. Mas a forma mais aconselhável de criar redirecionamentos é utilizando o nome da rota ao invés da url, para isso, deixamos de passar a url para o método redirect e concatenamos a ele outro helper global, o **route()** e então, dentro do **route()** passamos o nome da rota

-Rotas de visualização
    - Esse caso é valido quando não precisamos passar por algum controller com as regras de negócio para no final mostrar uma view. O retorno da view é feito diretamente pela rota através do método **view()**. 
    - Também podemos passar variáveis para a view nesse caso, basta passar como terceiro parâmetro um array com os valores - `Route::view(url, view, ['chave' => 'valor'])`

-Rotas com parâmetro
    - Caso 1 - Parâmetro obrigatório. 
        - Se o parâmetro não for passado, teremos um 404 - `Route::get('user/{id}', function($id){})`
    - Caso 2 - Parâmetro opcional. 
        - Com o parâmetro opcional, se o parâmetro não for passado, não teremos um 404 como retorno, pois indicamos ao laravel através da sintaxe **{par?}** que podemos ou não passá-lo. Mas o callback ainda fica esperando um valor para o parâmetro, então devemos inicializar ele com um valor padrão - **($par = null)** (esse valor padrão pode ser qualquer valor) - `Route::get('user/{id?}', function($id = valorDefault){})`. 
        - Podemos também passar mais de um parâmetro, tanto obrigatório como opcional (a ordem desses parâmetros importa) - `Route::get('user/{id?}/{nome?}', function($id = valorDefault, $nome = valorDefault){})`

-Validando o tipo do parâmetro
    - Para validarmos o parâmetro da rota, devemos utilizar o método **where()** passando para ele o parâmetro a ser validado e a expressão regular que limitará o formato do parâmetro (caso a rota tenha mais de um parâmetro, o método where aceita um array) - `Route::get(rota/{par})->where(['par', 'regex'])`. 
    - Mas o laravel já disponibiliza helpers que fazem essa validação implicitamente, como o **whereNumber()**, **whereAlpha()** e outros mais
    - Quando a validação de parâmetros se tornar repetitiva, o ideal é torná-la global, criando essas verificações dentro do arquivo **app/providers/RouteServiceProvider.php**, para isso, dentro do método **boot()** desse arquivo, devemos usar a classe `Route::pattern('par', 'regex')`, assim, todas as rotas que tiverem esse parâmetro, terão a validação

-Grupo de rotas
    - PREFIXO - dá o prefixo na url - `Route::prefix()->group()`
    - NAME - dá o prefixo no nome da rota - `Route::name()->group()`
    - MIDDLEWARE - cria uma barreira para a requisição - `Route::middleware()->group()`
    - O arquivo **app/http/kernel.php** controla as camadas **web/api**. Dentro dele já existem middleware prefixados para essas camadas, ou seja, qualquer requisição para essas camadas já passam por esses middleware por default. Nesse arquivo também contém os middleware default das rotas **($middlewareAliases[])**

-Fallback
    - funciona como um backup, caso ocorra algum erro no sistema. Ou seja, se não conseguirmos acessar uma rota listada no sistema o fallback entra em ação, então, ao invés de termos um erro 404, teremos o que foi passado no fallback - `Route::fallback(function(){})`

-Injeção de dependência
    - Serve para injetarmos uma classe - `Route::get(uri, function(Classe $classe){})` 

-Injeção de model
    - A injeção de model funciona da mesma forma que a de dependência, contudo, na de model estamos injetando um model - `Route::get(uri, function(Model $model){})` 



## MIDDLEWARE
-Middleware
    - Os middleware agem como uma barreira entre a requisição e a aplicação, podendo ou não bloquear essa requisição. Um middleware nem sempre faz uma validação, podemos aplicar alguma rotina, como por exemplo, pegar o user agent do usuário. 

-Criando e aplicando middleware
    - Para criar um middleware usamos o comando `php artisan make:middleware`
    - Depois do arquivo criado, precisamos registrar esse novo middleware para que o laravel saiba de sua existência, o registro de middleware para rotas (esse caso é para quando iremos usar um middleware diretamente em uma rota com o método middleware()) fica dentro de **app/http/kernel.php** no atributo **$middlewareAliases**, já na rota, temos que concatenar o método `middleware('NomeMiddleware')` para que ele seja aplicado, mas esse formato demanda que passemos esse método middleware('NomeMiddleware') em todas as rotas ou grupo de rotas que queremos que ele seja aplicado. 

-Middleware global
    - Podemos declarar um middleware globalmente passando a classe do middleware para o atributo global **$middleware** dentro de **app/http/kernel.php** e a partir disso, todas as requisições passarão por esse middleware sem a necessidade de usarmos o método **middleware()**

-Grupo de middleware
    - Quando estamos utilizando grupos de rotas passando mais de um middleware para as rotas, dependendo da quantidade de middleware, podemos deixar nosso grupo de rotas muito verboso, para minimizar isso, temos a possibilidade de criar um grupo de middleware.
    - Para criar esse grupo, devemos adicionar um novo grupo de middleware dentro do atributo **middlewareGroups[]** do arquivo **app/http/kernel.php**, aqui, a ordem do middleware dentro do grupo importa

-Definindo prioridade de middleware
    - Podemos definir a ordem de aplicação dos middleware de forma global, ou seja, toda aplicação de middleware seguirá essa ordem - diretamente na rota, grupo de middleware e etc - para fazer isso, temos que criar o atributo público **$middlewarePriority** dentro do arquivo **app/http/kernel.php** e passar os middleware na ordem desejada

-Passando parâmetro para dentro do middleware
    - Podemos passar parâmetros pra dentro de um middleware aplicado a uma rota, mas para que isso seja possível, o registro do middleware que vai receber o parâmetro, deve ser feito no atributo **$middlewareAliases**. 
    - A sintaxe para passar um parâmetro para o middleware é **middleware('nomeMiddleware:parâmetro')**. 
    - Dentro do middleware, no método **handle()**, passamos como 3º parâmetro uma variável que vai receber o valor passado para o middleware



## CONTROLLER
- Um controller serve  para controlar a requisição do usuário, fazer o processamento necessário e ao final, devolver esses dados para o usuário

- A convenção de nomenclatura para controllers é o uso do pascal case com a primeira palavra no singular seguido por controller (Ex: UserController)

- Para ligar uma rota a um controller, devemos mudar um pouco a estrutura padrão da rota onde passamos uma função como segundo parâmetro (`Route::get(uri, function)`). Então, no lugar da função, devemos passar um array com a primeira posição sendo ocupada pelo controller e a segunda pelo método que iremos usar desse controller (`Route::get(uri, [NomeController::class, 'nomeMétodo'])`)

- Passando parâmetros pro controller
    - Para passarmos um parâmetro vindo da rota pro controller, primeiro, nesse rota, devemos declarar o parâmetro (`rota/{parâmetro}`) para depois, no método do controller, declararmos esse parâmetro (`public function método($parâmetro)`). 
    - O ideal é que ambos tenham o mesmo nome

- Injeção de dependência no controller
    - Injetando uma classe dentro de um método do controller. 
    - Para fazer isso, dentro do método, basta passar o nome da classe e pendurar seu valor numa variável que será usada dentro do controller (`show(Request $request)`). 
    - Também devemos importar o caminho dessa classe pra dentro do controller (`use Illuminate\Http\Request;`). 
    - Nesse caso, a classe Request nos dará acesso a todas as informações passadas na request.
    - Quando injetarmos um model, teremos acesso ao dados que viram do banco de dados

- Aplicando middleware no controller
    - Para isso, devemos instanciar o método **__construct()** do php e dentro dele chamar o middleware (`__construct($this->middleware())`)
    - A partir disso, todos os métodos dentro desse controller passarão pelo middleware instanciado. 
    - Podemos escolher quais métodos usarão ou não o middleware, isso é possível com o método **only()** - only() aplicará o middleware apenas aos métodos que foram passados como parâmetro - e **except()** - except() aplicará o middleware a todos os métodos , exceto aos que foram passados como parâmetro.
    - Também podemos passar um middleware através de um **closure** (é o mesmo closure que tem dentro do arquivo de middleware). Dessa forma, estamos criando o middleware apenas para esse controller, sem a necessidade de fazer o registro dentro do **kernel.php**. Esse caso é mais usado quando precisamos de um middleware muito específico, que só será usado em um controller e em nenhum outro lugar. Aceita os métodos only() e except() - `$this->middleware(function(){})`

- Controller de ação única (Single Action Controller)
    - Esse tipo de controller é utilizado para quando precisamos executar apenas uma ação. 
    - Ele é invocado automaticamente sem precisarmos passar o seu método para a rota (`Route::get('checkout', CheckoutController::class);`). 
    - Para criar esse tipo de controller usamos o comando `php artisan make:controller NomeController --invokable`. 
    - Dentro do controller de ação única, existe um método default que é o **__invoke**. 
    - Esse método é chamado automaticamente pela rota para invocar o controller 

- Controller resource
    - Para não precisarmos declarar as rotas de um CRUD manualmente, o laravel disponibiliza o controller resource, que trás consigo todos os métodos do CRUD por default. 
    - Para criar esse tipo de controller, temos que usar o comando `php artisan make:controller NomeController --resource`
    - Para chamá-lo na rota, usamos o método resource e a classe do controller, sem a necessidade de especificar o método (`Route::resource(uri, NomeController::class)`). 
    - E o laravel vai além, se adicionarmos outra option ao comando make:controller, o laravel já faz a injeção do model nos métodos do controller - `php artisan make:controller NomeController --resource --model=NomeModel` 

- Personalizando os métodos do controller resource
    - Na rota, ao chamarmos o controller resource, o laravel já cria todas as rotas automaticamente, mas podemos indicar para ele, as rotas que queremos que sejam criadas, para isso, temos dois métodos - **only()** e **except()** - o método only() diz quais rotas queremos que sejam criadas, já o except, cria todas as rotas, menos as passadas como parâmetro
    - `Route::resource('users', UserController::class)->only(['index', 'store']);` apenas as rotas index e store serão criadas
    - `Route::resource('users', UserController::class)->except(['index', 'store']);` todas as rotas serão criadas, menos index e store

- Utilizando mais de um resource
    - Quando temos mais de uma rota resource, para não precisarmos declarar cada uma, tornando o arquivo de rotas verboso, o laravel nos disponibiliza uma maneira de passar vários resources em apenas um método, ou invés de usarmos o **::resource**, utilizaremos o **::resources**
    - `Route::resources(['users' => UserController::class, 'posts' => PostsController::class])` - a desvantagem do método **resources**, é que ele não aceita os métodos only() e except() 

- Utilizando resource para api
    - `Route::apiResource('uri', Controller)` - único resource
    - `Route::apiResources(['uri' => Controller])` - múltiplos resources
    - Quando estamos trabalhando com api, podemos criar o método apiResource() que no geral, faz a mesma coisa que o resource comum, mas agora, as rotas que renderizam views (create e edit) não são criadas, apenas as de consumo e modificação de dados (index, store, show, update e delete)
    - O apiResource seria a mesma coisa que `Route::resource()->except('create', 'edit')`

- Aninhamento de resource (nested resource)
    - Imagine um sistema onde temos usuários e seus comentários, a rota `/users/{users}/comments` irá retornar todos os comentários de um usuário específico 
    - Imagine também que queremos retornar um comentário específico, a rota seria `/users/{users}/comments/{comment}`. O resource desse caso seria `Route::resource('/users/{users}/comments', Controller)` e assim todas as rotas de listagem até a de excluir seriam criadas
    - Mas temos uma alternativa mais limpa para a criação desse padrão de rotas, que seria `Route::resource('users.comments', Controller)` que nos daria o mesmo resultado do exemplo acima, mas agora com um código mais limpo
    - Contudo, existem cenários onde queremos retornar um comentário específico, mas não precisamos do id do usuário, porque o próprio comentário tem seu id único (a rota seria algo como `comments/{comment}`) e para criar esse padrão dentro do resource, temos o método **shallow()** (`Route::resource('users.comments', Controller)->shallow()`) que resultará em duas entidades, termos as rotas **index**, **store** e **create** com o padrão `users/{user}/comments` e as rotas **show**, **update**, **destroy** e **edit** com o padrão `comments/{comment}`

- Renomeando rotas resource
    - Quando usamos `Route::resource()` as rotas que são criadas já tem tem seus nomes definidos, mas se caso precisarmos modificar esses nomes, podemos usar o método **name()** 
    - `Route::resource()->name(['create' => 'criar', 'update' => 'atualizar'])`

- Traduzindo rotas 
    - Podemos traduzir os métodos das rotas do resource, que por default vem em inglês 
    - Para isso, devemos criar uma configuração global no arquivo **app/providers/RouteServiceProvider.php** dentro do método **boot()**
    - No início do método **boot()** devemos declarar `Route::resourceVerbs(['create' => 'criar', 'update' => 'atualizar']);`

- Adicionando rotas extras em uma rota resource
    - Podemos incluir mais rotas dentro da rota resource, basta apenas declararmos normalmente a rota que queremos, mas usando a mesma url da rota resource
    - A rota foto/posts será adicionada as rotas do resource fotos
    - ```
        Route::resource('fotos', UserController::class); 
        Route::get('fotos/posts', [UserController::class, 'posts'])->name(fotos.posts);

      ```