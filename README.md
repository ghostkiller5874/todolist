# **API To-do List**
### API RESTfull para gerenciamento de tarefas, com upload de arquivos para AWS

## Instruções para rodar o projeto de forma local

- Instale as dependências
    > - composer install
    > - npm install
- Configure o .env: definindo a o Banco de Dados, credenciais aws e gerando a key laravel
    > - DB_DATABASE= ??
    > - php artisan key:generate
- Suba as migrations *O SQL AQUI, JÁ TEM AS MIGRATIONS, MAS CASO QUEIRA UM NOVO, FICA A CRITÉRIO*
    > - php artisan migrate
- E inicie o serve
    > - php artisan serve
    
## Configurações AWS

> - AWS_ACCESS_KEY_ID= ??
> - AWS_SECRET_ACCESS_KEY= ??
> - AWS_DEFAULT_REGION=us-east-1
> - AWS_BUCKET= ??
> - AWS_USE_PATH_STYLE_ENDPOINT=false
> - AWS_URL= ??



## Utilização dos endpoints

| Método | Endpoint           | Descrição                   |
| ------ | ------------------ | --------------------------- |
| POST   | /api/login         | Login de usuário            |
| POST   | /api/register      | Cadastro de usuário         |
| POST   | /api/logout        | Logout do usuário           |
| GET    | /api/tasks         | Listar tarefas              |
| POST   | /api/tasks         | Criar tarefa (com arquivo)  |
| GET    | /api/tasks/{id}    | Ver uma tarefa              |
| PUT    | /api/tasks/{id}    | Atualizar tarefa            |
| DELETE | /api/tasks/{id}    | Soft delete                 |

__legenda__: base_url = http://localhost:8000

** **OBRIGATÓRIO** **
- Accept = application/json
- Authorization = Bearer {token do usuário}
- Para inserção de arquivos = form data
- Para inserção sem arquivos = form url encoded

## No postman/insominia faça os seguintes passos -- necessário estar autenticado
- ### Login de usuário --  necessidade de parâmetro
    > url: base_url/api/login  <br>
    > parametros/body: email = ?? , password = ??

- ### Cadastro de usuário --  necessidade de parâmetro
    > url: base_url/api/register  <br>
    > parametros/body: name = ??, email = ?? , password = ??

- ### Logout do usuário  -- sem necessidade de parâmetro
    > url: base_url/api/logout 

- ### Criar tarefa (com arquivo - opcional) -- necessidade de parâmetros
    > url: base_url/api/tasks <br>
    > parametros/body: title = ?? , description = ??, arquivo = ??

- ### Ver uma tarefa -- apenas o id na url
    > url: base_url/api/tasks/{id} 

- ### Atualizar tarefa -- necessidade de parâmetros
    > url: base_url/api/tasks/{id} <br>
    > parametros/body: title = ?? , description = ??, arquivo = ??

- ### Remoção da tarefa (Soft Delete) -- necessidade de parâmetros
    > url: base_url/api/tasks/{id}

- ### Listar tarefas deletadas -- necessidade de parâmetros
    > url: base_url/api/tasks/deleted

## Explicações técnicas e observações
> - Autenticação via Sanctum
> - Soft Deletes habilitado
