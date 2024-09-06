# Passos utilizados para a criação do projeto

1. No wsl2, criar a pasta do projeto e entrar
2. Instalar o laravel via composer
3. Configurar o Dockerfile e docker-compose.yml
4. Atualizar as constentes do banco de dados no .env
5. Rodar as migrações \
   docker-compose exec app php artisan migrate
6. Instalar api \
   docker-compose exec app php artisan install:api
7. Definir as permissões do arquivos e pastas \
   sudo chown -R $USER:$USER /home/micael/projects/laravel-project
