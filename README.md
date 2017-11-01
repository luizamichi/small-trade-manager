# Small Trade Manager
Website desenvolvido utilizando Python e Flask. Planejado para a exibição de informações da empresa de interesse de clientes e fornecedores, além de possuir um sistema de acesso aos produtos da empresa, com a possibilidade de leitura, inserção e alteração de produtos.

### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
./
├── db/
│   ├── data.sql
│   └── product.sql
├── static/
│   ├── bootstrap.min.css
│   ├── bootstrap.min.js
│   ├── dpsjt.jpg
│   ├── jquery.min.js
│   ├── jquery.tablesorter.min.js
│   ├── logo.png
│   ├── logo.psd
│   ├── logo.svg
│   ├── luiz_amichi.svg
│   ├── padlock.svg
│   └── trowel.svg
├── templates/
│    ├── error.html
│    ├── index.html
│    ├── login.html
│    └── system.html
├── app.py
├── product.py
└── README.md
```

### Dependências
Para o funcionamento do software, é necessário possuir o [Python](https://www.python.org/) e o [SQLite](https://sqlite.org/) instalados na máquina. Além do interpretador e da biblioteca, são necessários alguns pacotes ([Flask](https://flask.palletsprojects.com/en/stable/) e [Jinja](https://jinja.palletsprojects.com/en/stable/)) que podem ser instalados através do [PIP](https://pypi.org/project/pip/).

Instale as dependências para iniciar o servidor, abaixo é informado o passo a passo para a preparação do ambiente em Linux.
- Faça download do conteúdo do repositório
- Atualize a lista de pacotes: `sudo apt update`
- Instale o Python, PIP e SQLite: `sudo apt install python3 python3-pip sqlite3`
- Instale o Flask e o Jinja: `pip install -U Flask Jinja2`

### Execução
Feito o procedimento de instalação de dependências, basta acessar a pasta do projeto e inicar no modo *deploy*. Lembrando que a primeira execução do software utiliza um comando diferente, informando o argumento (*initdb*).

Primeira inicialização do software com a base de dados vazia:
```sh
$ python3 app.py initdb
```

Primeira inicialização do software com a base de dados populada com alguns produtos para fins de teste:
```sh
$ python3 app.py initdb populate
```

Para ativar o modo *debug*, basta executar `python3 app.py debug`. Para uma execução normal, basta executar `python3 app.py`.

Caso aconteça algum problema, crie uma ambiente virtual isolado com o VENV e instale as dependências e o software nela.

O processo estará executando na porta 5000 e pode ser acessado utilizando qualquer navegador de internet.

### Caminhos
A tabela informa os endereços acessíveis:

| Rota | Descrição |
| ---- | --------- |
| /<, default, home, index, inicio> | Página inicial (Website) |
| /<about, about-us, sobre, sobre-nos> | Página sobre o depósito |
| /<contact, contato, localizacao, location> | Página de contato e localização |
| /<product, products, produto, produtos> | Página de linha de materiais |
| /login | Página de autenticação |
| /system | Página de gerenciamento de produtos |
