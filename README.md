# Sistema de Biblioteca

Sistema de gerenciamento de biblioteca desenvolvido em Laravel.

## Funcionalidades

- Cadastro de usuários
- Controle de livros
- Empréstimo de livros
- Devolução de livros
- Controle de multas por atraso
- Controle de permissões (Admin, Bibliotecário e Cliente)
- API REST para gerenciamento de livros
- Limite de 5 empréstimos simultâneos por usuário
- Bloqueio de empréstimos para usuários com débitos pendentes

## Requisitos

Antes de iniciar, certifique-se de possuir:

- PHP 8.2 ou superior
- Composer
- MySQL ou MariaDB
- Node.js
- NPM

## Clonando o projeto

```bash
git clone <url-do-repositorio>
cd nome-do-projeto
```

## Instalação das dependências

Instale as dependências do PHP:

```bash
composer install
```

Instale as dependências do frontend:

```bash
npm install
```

## Configuração do ambiente

Copie o arquivo de exemplo:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

## Configuração do banco de dados

Abra o arquivo `.env` e configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados no MySQL com o nome definido em `DB_DATABASE`.

## Executando as migrations

```bash
php artisan migrate
```

Caso o projeto possua seeders:

```bash
php artisan db:seed
```

Ou:

```bash
php artisan migrate --seed
```

## Compilando os arquivos frontend

Modo desenvolvimento:

```bash
npm run dev
```

Modo produção:

```bash
npm run build
```

## Iniciando o servidor

```bash
php artisan serve
```

A aplicação estará disponível em:

```
http://127.0.0.1:8000
```

## API

As rotas da API podem ser acessadas através de:

```
http://127.0.0.1:8000/api
```

## Estrutura de Permissões

### Admin

- Gerencia usuários
- Gerencia livros
- Gerencia empréstimos
- Visualiza e quita multas

### Bibliotecário

- Gerencia livros
- Realiza empréstimos e devoluções
- Visualiza e quita multas

### Cliente

- Consulta livros
- Realiza empréstimos dentro das regras do sistema

## Regras de Negócio

- Máximo de 5 empréstimos simultâneos por usuário.
- Não é permitido emprestar um livro já emprestado.
- Prazo de devolução: 15 dias.
- Multa de R$ 0,50 por dia de atraso.
- Usuários com multas pendentes não podem realizar novos empréstimos.

## Autor

Desenvolvido por Gustavo Dantas das Chagas.