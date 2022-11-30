# SOBRE O PROJETO

Devido ao prazo dado de uma semana, o projeto foi construindo com componentes customizados e unicos.
Eu criei um Datapicker, um Calendar, um Input e um select próprio para maior integração do aplicativo.

## TECNOLOGIAS UTILIZADAS

No front-end utilizei Vue 3 com typescript com composition-api e os plugins: Vue-Router e Vue-Use; Para estilo utilizei Scss.

Os icones utilizados foram baixados da internet e utilizados diretamente.

No back-end foi utilizado apenas laravel.

## Rotas do front-end

## /users

> Exibe a lista dos usuários cadastrados e permite filtros

## /user/create

> Exibe a tela de criação de usuário

## /user/:id/edit

> Exibe a tela de edição do respectivo usuário

## /user/:id/details

> Exibe a tela de detalhes do respectivo usuário

## Rotas do back-end

## GET /api/users/

> Exibe a lista de usuários cadastrados completa

> Retorna o usuário e status 200

## GET /api/user/:id

> Exibe o respectivo usuário cadastrado

> Retorna o usuário e status 200

## POST /api/user/

> Cria um novo usuário

> Recebe {name: string,cpf: string,email: string,role_id: integer,addresses: [{logradouro: string, cep: string}]}

> Retorna o usuário e status 201

## PUT /api/user/{id}

> Edita um usuário do sistema

> Recebe {name: string,cpf: string,email: string,role_id: integer,addresses: [{logradouro: string, cep: string}]}

> Parametro id: integer

> Retorna o usuário e status 201

## DELETE /api/user/{id}

> Deleta um usuário do sistema

> Parametro id: integer

> Retorna uma mensagem e status 201

## DELETE /api/address/{id}

> Deleta um endereço do sistema

> Parametro id: integer

> Retorna uma mensagem e status 201
