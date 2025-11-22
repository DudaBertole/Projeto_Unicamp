# Rotas da API

Observação sobre base path

- No arquivo public/index.php há a variável `$basePath = '/BitMemory/public'`.  
  Dependendo de como o servidor está configurado, as URIs abaixo podem precisar ser prefixadas com este base path.  
  Exemplo completo: `http://localhost/BitMemory/public/user/create`  
  Exemplo relativo (sem prefixo): `/user/create`

1. Health

- Método: GET
- URI: /health
- Descrição: Verifica se a API está no ar.
- Exemplo de resposta:
  {
  "status": "ok"
  }

2. Criar usuário

- Método: POST
- URI: /user/create
- Body (JSON):
  {
  "full_name": "João Silva",
  "birth_date": "1990-10-25", // formato Y-m-d
  "cpf": "12345678909", // apenas dígitos ou formatado
  "phone": "11999998888", // DDD + 9 + número (11 dígitos)
  "username": "joaosilva",
  "email": "joao@example.com",
  "password": "senha123"
  }
- Sucesso: 201, mensagem de criação.

3. Atualizar usuário

- Método: PATCH
- URI: /user/update
- Body (JSON): deve conter ao menos o cpf para localizar, e os campos a atualizar:
  {
  "cpf": "12345678909",
  "full_name": "João da Silva",
  "phone": "11988887777"
  }
- Sucesso: 200, mensagem de atualização.

4. Autenticar usuário

- Método: POST
- URI: /user/authenticate
- Body (JSON):
  {
  "username": "joaosilva",
  "password": "senha123"
  }
- Respostas: 200 (autenticado) ou 401 (credenciais inválidas).

5. Buscar usuário por ID

- Método: GET
- URI: /user/findByID?id={id}
- Exemplo: `/user/findByID?id=1`
- Resposta: objeto do usuário (sem senha) ou 404 se não existir.

6. Buscar usuário por CPF

- Método: GET
- URI: /user/findByCPF?cpf={cpf}
- Exemplo: `/user/findByCPF?cpf=12345678909`
- Resposta: objeto do usuário (sem senha) ou 404 se não existir.

7. Criar jogo

- Método: POST
- URI: /game/create
- Body (JSON):
  {
  "user_id": 1,
  "board_size": 4,
  "moves_count": 20,
  "mode": "classic", // 'classic' ou 'turbo'
  "duration_seconds": 120,
  "result": "W", // 'W' (win) ou 'L' (loss)
  "datetime": "2025-01-01 12:00:00" // opcional, se omitido usa "now"
  }
- Sucesso: 201

8. Listar todos os jogos

- Método: GET
- URI: /game/listAll
- Retorna: array de jogos ordenados por data.

9. Listar por modo de jogo

- Método: GET
- URI: /game/listByGameMode?game_mode={mode}
- Exemplo: `/game/listByGameMode?game_mode=classic`
- Retorna: array de jogos daquele modo.

10. Listar por usuário

- Método: GET
- URI: /game/listByUser?user_id={id}[&game_mode={mode}]
- Exemplos:
  - `/game/listByUser?user_id=1`
  - `/game/listByUser?user_id=1&game_mode=turbo`
- Retorna: array de jogos do usuário (filtrado por modo se fornecido).

11. Buscar jogo por ID

- Método: GET
- URI: /game/findByID?id={id}
- Exemplo: `/game/findByID?id=10`
- Retorna: objeto do jogo ou 404 se não existir.

Formato das respostas

- As respostas são JSON e os controllers retornam código HTTP apropriado (200, 201, 400, 404, 500, etc).

Erros comuns

- Campos obrigatórios ausentes retornam 400 com mensagem de erro.
- Formatos inválidos (email, cpf, phone, datetime) retornam 400 com descrição do problema. Para entender melhor esses formatos, consulte o diretório "ValueObjects'"
