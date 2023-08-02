# Turno App Backend

## Inicio do ambiente docker

```bash
# iniciar submodulo do laradock do projeto
$ git submodule update --init --recursive

# entrar dentro da pasta do submodule
$ cd laradock

# criar o .env local copiando do .env.example do projeto
$ cp env-example .env

# editar o .env nos seguintes locais sobre as configurações dos containers
DATA_PATH_HOST=~/.laradock/data  --> DATA_PATH_HOST=~/.turnobackend/data
COMPOSE_PROJECT_NAME=laradock  --> COMPOSE_PROJECT_NAME=turnobackend

# se for usuário windows, alterar também 
COMPOSE_PATH_SEPARATOR=: --> COMPOSE_PATH_SEPARATOR=;

# se for usuário linux ou tiver instalado um servidor web e/ou mysql na sua máquina, alterar as seguintes portas
NGINX_HOST_HTTP_PORT=80 --> NGINX_HOST_HTTP_PORT=8000 por exemplo
MYSQL_PORT=3306 --> MYSQL_PORT=3307 por exemplo

# configurar os dados do banco
MYSQL_DATABASE=default
MYSQL_USER=default
MYSQL_PASSWORD=secret

# subir os containers necessários para o ambiente de desenvolvimento, nginx e mysql
$ docker-compose up -d nginx mysql

# caso esteja em linux os comandos de docker-compose precisam estar em super usuário. Por exemplo:
$ sudo docker-compose up -d nginx mysql

# entrar dentro do container de área de trabalho
$ docker-compose exec workspace bash
|
```

Para detalhes da documentação do Laradock  [Laradock docs](https://laradock.io/).

## Inicio da aplicação Laravel

```bash
# instalar as dependencias do projeto
$ composer install

# criar o .env 
$ cp .env.example .env

# iniciar a key da aplicação
$ artisan key:generate

# configurar os seguintes locais do .env
APP_NAME=Laravel --> APP_NAME=TurnoBackend
DB_HOST=127.0.0.1 --> DB_HOST=mysql
DB_DATABASE=laravel --> DB_DATABASE=default
DB_USERNAME= --> DB_USERNAME=default
DB_PASSWORD= --> DB_PASSWORD=secret

# rodar as migrations e as seeders do projeto
$ artisan migrate --seed
```

Para detalhes da documentação do Laravel [Laravel docs](https://laravel.com).
