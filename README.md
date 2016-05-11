# Small Trade Manager
Website desenvolvido utilizando Python e Flask. Planejado para a exibição de dados da empresa de interesse de clientes e fornecedores, além de possuir um sistema de acesso aos produtos da empresa, com a possibilidade de leitura e inserção de produtos.

### Diretório
No download, você encontrará os seguintes arquivos:
```
.
├── app.py
├── css3.py
├── html5.py
├── produtos.txt
└── README.md
```

### Instalação
Para o funcionamento do software, é necessário possuir o [Python](https://www.python.org/). Além do interpretador, é necessário ter o [Flask](https://flask.palletsprojects.com/en/stable/), que pode ser obtido através do [PIP](https://pypi.org/project/pip/).

Instale as dependências para iniciar o servidor, abaixo é informado o passo a passo para a preparação do ambiente em Linux.
- Faça download do conteúdo do repositório
- Atualize a lista de pacotes: `sudo apt update`
- Instale o Python e o Flask: `sudo apt install python3 python3-flask`

Feito o procedimento de instalação de dependências, basta acessar a pasta do projeto e inicar no modo *deploy*.

```sh
$ python3 -m flask run
```

Caso queira ativar o *debug*, basta iniciar o software com o seguinte comando:

```sh
$ python3 app.py debug
```

Caso aconteça algum problema, crie um ambiente virtual isolado com o VENV e instale o programa e suas dependências.

O sistema estará executando na porta 5000.

### Caminhos
A tabela informa os endereços acessíveis pelo navegador, lembrando de especificar a porta para o navegador (ex. http://localhost:5000).

| Rota | Descrição |
| ---- | --------- |
| / <br/> /incio | Página inicial (portfólio) |
| /location <br/> /localizacao | Página com mapa de localização do Google Maps integrado |
| /login <br/> /entrar | Página de autenticação / Página de gerenciamento de produtos |
