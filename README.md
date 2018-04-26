# Small Trade Manager
Website desenvolvido utilizando Python e Flask. Planejado para a exibição de informações da empresa de interesse de clientes e fornecedores, além de possuir um sistema de gerenciamento de fornecedores, produtos e usuários.

### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
./
├── controllers/
│   ├── __init__.py
│   ├── default.py
│   └── persistence.py
├── db/
│   ├── create.log
│   ├── delete.log
│   ├── inserts.sql
│   ├── tables.sql
│   └── update.log
├── models/
│   ├── __init__.py
│   ├── product.py
│   ├── provider.py
│   └── user.py
├── static/
│   ├── css/
│   │   └── bootstrap.min.css
│   ├── img/
│   │   ├── cms.svg
│   │   ├── dpsjt.jpg
│   │   ├── logo.png
│   │   ├── logo.psd
│   │   ├── luiz_amichi.svg
│   │   ├── padlock.svg
│   │   └── trowel.svg
│   └── js/
│       ├── bootstrap.min.js
│       ├── jquery.min.js
│       └── jquery.tablesorter.min.js
├── templates/
│   ├── base.html
│   ├── base_system.html
│   ├── index.html
│   ├── login.html
│   ├── products.html
│   ├── providers.html
│   ├── system.html
│   └── users.html
├── app.py
├── config.py
└── README.md
```

### Dependências
Para o funcionamento do software, é necessário possuir o [Python](https://www.python.org/) e o [SQLite](https://www.sqlite.org/) instalados na máquina. Além do interpretador e da biblioteca, são necessários alguns pacotes ([Flask](https://flask.palletsprojects.com/en/1.1.x/) e [Jinja](https://jinja.palletsprojects.com/en/2.11.x/)) que podem ser instalados através do [PIP](https://pypi.org/project/pip/).

Instale as dependências para iniciar o servidor, abaixo é informado o passo a passo para a preparação do ambiente em Linux.
- [Faça download do ZIP](https://github.com/luizamichi/small-trade-manager/archive/refs/tags/v2.1.zip)
- Atualize a lista de pacotes: `sudo apt update`
- Instale o Python, PIP e SQLite: `sudo apt install python3 python3-pip sqlite3`
- Instale o Flask e o Jinja: `pip install -U Flask Jinja2`

### Execução
Feito o procedimento de instalação de dependências, basta acessar a pasta do projeto e inicar no modo *deploy*. Lembrando que a primeira execução do software utiliza um comando diferente, informando o argumento (*initdb*).

Primeira inicialização do software com a base de dados vazia e usuário *default* (dpsjt):
```sh
$ python3 app.py initdb
```

Primeira inicialização do software com a base de dados populada com alguns fornecedores e produtos (simbólicos) para fins de teste:
```sh
$ python3 app.py initdb populate
```

Por padrão, o modo *debug* está ativado, caso deseje desativar, basta alterar a *flag* DEBUG no arquivo `config.py`.

Caso aconteça algum problema, crie uma ambiente virtual isolado com o VENV e instale as dependências e o software nela.

O processo estará executando na porta 5000 e pode ser acessado utilizando qualquer navegador de internet.

### Caminhos
A tabela informa os endereços acessíveis:

| Rota | Descrição |
| ---- | --------- |
| /<, index> | Página inicial (Website) |
| /login | Página de autenticação |
| /system | Página de gerenciamento de conteúdo |
