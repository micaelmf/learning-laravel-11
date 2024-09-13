# Projeto Laravel de Gerenciamento de Tarefas

Este é um projeto de exemplo de um sistema de gerenciamento de tarefas (To-Do List) desenvolvido com o framework Laravel. O objetivo deste projeto é demonstrar a aplicação de conceitos fundamentais do Laravel, como autenticação de usuários, operações CRUD, validação de formulários, roteamento, Eloquent ORM, migrations, templates Blade e middleware.

## Funcionalidades

### v0.1.0

- [ ] Registro e login de usuários \
- [x] Criação, leitura, atualização e exclusão de tarefas \
- [x]Marcar tarefas como concluídas \
- [ ]Lixeira para recuperar tarefas excluídas \
- [x]Validação de dados de entrada \
- [x]Interface amigável com templates Blade e Bootstrap

## Tecnologias Utilizadas

- Laravel
- Docker
- Composer
- MySQL
- Blade Templates

## Como executar

1. Clone o repositório

   ```bash
   git clone https://github.com/seu-usuario/laravel-project.git
   ```

   ```bash
   cd laravel-project
   ```

2. Copie o arquivo .env.example para .env e atualize as configurações conforme necessário.
3. Construa e inicie os contêineres Docker:

   ```bash
   docker-compose up -d
   ```

4. Instale as dependências do Composer:

   ```bash
   docker-compose exec app composer install
   ```

5. Execute as migrações do banco de dados:

   ```bash
   docker-compose exec app php artisan migrate
   ```

6. Acesse o projeto no navegador:

   ```bash
   http://localhost:8000/
   ```

## Passos utilizados para a criação do projeto

1. No wsl2, criar a pasta do projeto e entrar
2. Instalar o laravel via composer
3. Configurar o Dockerfile e docker-compose.yml
4. Atualizar as constentes do banco de dados no .env
5. Rodar as migrações

   ```bash
   docker-compose exec app php artisan migrate
   ```

6. Instalar api

   ```bash
   docker-compose exec app php artisan install:api
   ```

7. Definir as permissões do arquivos e pastas

   ```bash
   sudo chown -R $USER:$USER /home/micael/projects/laravel-project
   ```
