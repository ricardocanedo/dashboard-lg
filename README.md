# Dashboard LG

Projeto Laravel 7 com MySQL 8 usando Docker.

## Requisitos

- [Docker](https://docs.docker.com/get-docker/)
  - **Windows/Mac**: Docker Desktop já inclui o Docker Compose V2
  - **Linux**: Instalar também o [Docker Compose Plugin](https://docs.docker.com/compose/install/linux/)

## Instalação

1. Clone o repositório e acesse a pasta do projeto:
```bash
git clone <link-do-repositorio-no-github>
cd Dashboard-LG
```

2. Construa e suba os containers (Laravel será instalado automaticamente):
```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

3. Aguarde o MySQL estar pronto (10-15 segundos) e configure o Laravel:
```bash
# Copiar configurações do .env
docker compose exec app cp .env.example .env

# Gerar chave da aplicação
docker compose exec app php artisan key:generate

# Limpar cache e reiniciar
docker compose exec app php artisan config:clear
docker compose restart app

# Executar migrações
docker compose exec app php artisan migrate
```

## Uso

A aplicação estará disponível em: http://localhost:8000

## Tecnologias

- Laravel 7
- PHP 7.4
- MySQL 8.0
- Docker
- Docker Compose V2

# Sobre o desenvolvimento

### Docker

O projeto roda com docker para facilitar a utilização das versões específicas de tecnologias aplicadas a este sistema.

### Banco de dados

O diagrama do Banco de Dados ficou com a seguinte estrutura. 

![alt text](DER.png)

O objetivo de ter a tabela plant é simular a acomodação de um possível crescimento do sistema em que se é possível adicionar novas plantas apenas incluindo um novo registro nessa tabela.

