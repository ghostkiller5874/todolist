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
| GET    | /api/tasks         | Listar tarefas (com filtro) |
| POST   | /api/tasks         | Criar tarefa (com arquivo)  |
| GET    | /api/tasks/{id}    | Ver uma tarefa              |
| PUT    | /api/tasks/{id}    | Atualizar tarefa            |
| DELETE | /api/tasks/{id}    | Soft delete                 |
| GET    | /api/tasks/deleted | Listar tarefas deletadas    |


## Explicações técnicas e observações
> - Autenticação via Sanctum
> - Soft Deletes habilitado