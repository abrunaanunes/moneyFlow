# Money Flow

## Sobre
Este projeto baseia-se no conceito de uma carteira digital. Possui dois tipos de usuários (client e shopkeeper). ambos têm carteira com dinheiro e realizam transferências entre eles. Entretanto, o usuário do tipo shopkeeper pode apenas receber transferências, enquanto o usuário do tipo client pode receber de outros usuários deste tipo e transferir para ambos os tipos.

As operações de transferência possuem dois status e sofrem modificações ao longo do processo. Quando iniciada, a transação encontra-se em análise (status 1). Após a aprovação do serviço autorizador externo e finalização da operação sem a presença de inconsistências, a transação receberá o status pago (status 2) e o dinheiro sairá da conta de origem para a conta do recebedor.

Para um maior controle de entradas e saídas, a operação de transferência é salva no banco no formato de um objeto do tipo transaction, onde há o valor da transferência (amount), o ID da conta do usuário que enviou o valor (account_id) e o ID do usuário que recebeu o valor (user_id).

## Pré Requisitos
- Instalar um Rest Client instalado em sua máquina (recomendamos o Postman)
- Instalar e configurar o ambiente Docker em sua máquina
- Instalar e configurar o banco MySQL em sua máquina

## Instalação
- Fazer o download do projeto no repositório
- Criar um banco de dados para conectar ao projeto
- Duplicar o arquivo .env.example e criar o .env
- Altera informações da configuração do banco e port no arquivo .env localizado na raiz do diretório do projeto.
- Executar o comando ``docker-compose build up`` (em caso de erros, consultar a documentação do Docker)

## Rotas

- POST localhost:{port}/api/auth/register
Para criar um registro novo de usuário, acesse o Postman e com o método POST, acesse a rota localhost:{port}/api/auth/register. Na aba Headers, preencha o key value com ['Accept' = > 'application/json'] para receber todos os retornos na estrutura JSON.
Logo após, na aba Body, selecione a opção raw JSON e crie um JSON de acordo com o exemplo abaixo.
``{
    "name" : "Bruna Nunes (CLIENTE)",
    "role" : "client",
    "cpf" : "397.988.878-55",
    "cnpj" null,
    "email" : "brunacliente@htail.com",
    "password" : "Bruna4321"
}``
O retorno será o status da requisição, token de autenticação (caso não ocorram falhas) e o status de erro. Para acessar as demais rotas protegidas basta acessar a aba Headers e preencher o key value com ['Autorization' => 'Bearer {token}'] ou acessar a aba Authorization, selecionar o type Bearer Token e colar o token no campo ao lado direito.
### Observação
No caso do registro de um usuário tipo client, o CPF deve ser preenchido e, no caso do registro de um usuário tipo shopkeeper, o CNPJ deve ser preenchido.

- POST localhost:{port}/api/auth/login
Para criar o token de autenticação através do login, acesse o Postman e com o método POST, acesse a rota localhost:{port}/api/auth/login. Na aba Headers, preencha o key value com ['Accept' = > 'application/json'] para receber todos os retornos na estrutura JSON.
Logo após, na aba Body, selecione a opção raw JSON, crie um JSON para informar as credenciais de acordo com o exemplo abaixo e cole no textarea.
``{
    "email" : "mariaeduarda@gmail.com",
    "password" : "M4tr1x123"
}``
O retorno será o status da requisição, token de autenticação (caso não ocorram falhas) e o status de erro. Para acessar as demais rotas protegidas basta acessar a aba Headers e preencher o key value com ['Autorization' => 'Bearer {token}'] ou acessar a aba Authorization, selecionar o type Bearer Token e colar o token no campo ao lado direito.

### Observação
Caso o token de autenticação não seja indicado nas rotas protegidas, a mensagem recebida será:
``{
    "message": "Unauthenticated."
}``

- GET localhost:{port}/api/protected/user (index)
Acesse a rota localhost:{port}/api/protected/user com o método GET e faça a autenticação com o token retornado no login ou registro. O retorno terá o status da requisição, um array com a listagem dos usuários e seus respectivos atributos (caso não ocorram falhas) e o status de erro.

- PUT localhost:{port}/api/protected/user/{id} (update)
Acesse a rota localhost:{port}/api/protected/user/{id} com o método PUT e, com a autenticação feita, cole o JSON abaixo no body da requisição e indique as alterações que deseja fazer. O retorno terá o status da requisição, o objeto que possui o ID indicado no parâmetro da url com os dados após a alteração e o status de erro.
``{
    "name": "Bruna Nunes (CLIENTE)",
    "role": "client",
    "cpf": "583.194.430-16",
    "cnpj" : null,
    "email": "brunasente@gmil.com"
}``

- GET localhost:{port}/api/protected/user/{id} (show)
Acesse a rota localhost:{port}/api/protected/user/{id} com o método GET e com a autenticação feita. O retorno terá o status da requisição, o objeto que possui o ID indicado no parâmetro da url com os dados após a alteração e o status de erro.

- GET localhost:{port}/api/protected/account (get balance)
Acesse a rota localhost:{port}/api/protected/account com o método GET e com a autenticação feita. O retorno terá o status da requisição, o objeto da classe Account que possui o user_id do usuário que possui o token de autenticação e o status de erro.

- POST localhost:{port}/api/protected/ (do transaction)
Acesse a rota localhost:{port}/api/protected/user/{id} com o método POST e, com a autenticação feita, cole o JSON abaixo no body da requisição e informe o valor a ser enviado e o ID do usuário que irá receber a transferência. O retorno terá o status da requisição, o objeto que possui o ID indicado no parâmetro da url com os dados após a realização da operação e, por fim, o status de erro.
{
    "value" : (valor da transaão),
    "payee" : (usuário recebedor)
}

- POST localhost:{port}/api/protected/user/logout (logout)
Acesse a rota localhost:{port}/api/protected/user/logout com o método POST e com a autenticação feita. Esta rota irá deletar os tokens criados para o usuário que possui o token informado na autenticação.