<?php
//--// -------------------- //--//
//--// CONSTANTS DEFINITION //--//
//--// -------------------- //--//

namespace config;


// DEFINIÇÃO DE IDIOMA, FUSO HORÁRIO E CODIFICAÇÃO
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese', 'pt_BR.iso-8859-1');
date_default_timezone_set('America/Sao_Paulo');
ini_set('default_charset', 'UTF-8');

// MODO DE DEPURAÇÃO
define('DEBUG', true);
define('DELAY', 0.0);

// MODO DE TESTE (NÃO É NECESSÁRIO REALIZAR AUTENTICAÇÃO)
define('TEST', false);

// HABILITA/DESABILITA A CONEXÃO COM O BANCO DE DADOS (APENAS PARA O MODO DE TESTES)
define('CONNECT', true);

// NOME DO COOKIE PARA A SESSÃO
session_name('SMALL_TRADE_MANAGER');
session_set_cookie_params(['samesite' => 'Lax']);

// CAMINHO NO SERVIDOR PARA O SISTEMA
define('BASE_URL', '/');

// TEMPO DE DURAÇÃO DA SESSÃO
define('SESSION_TIME', 3600);

// CAMINHOS DOS TEMPLATES DE CABEÇALHO E RODAPÉ
define('HEADER', __DIR__ . '/templates/header.php');
define('FOOTER', __DIR__ . '/templates/footer.php');

// CAMINHOS DOS TEMPLATES DE MENSAGEM E CARREGAMENTO
define('FLASH', __DIR__ . '/templates/flash.php');
define('LOADING', __DIR__ . '/templates/loading.php');

// CAMINHO PARA O TEMPLATE DE CONFIGURAÇÕES DO ESTABELECIMENTO
define('SETTINGS', __DIR__ . '/templates/settings.php');

// ENDEREÇO DO HOST DO MYSQL
define('MYSQL_HOST', '192.168.0.112');

// NOME DO BANCO DE DADOS MYSQL
define('MYSQL_SCHEMA', 'stm');

// USUÁRIO DO BANCO DE DADOS MYSQL
define('MYSQL_USER', 'pi');

// SENHA DO BANCO DE DADOS MYSQL
define('MYSQL_PASSWORD', 'mjolnir');

// TEMPO DE EXECUÇÃO MÁXIMO DE UM SCRIPT
ini_set('max_execution_time', 30);

// TAMANHO MÁXIMO DE MEMÓRIA PARA PROCESSAMENTO
ini_set('memory_limit', '64M');

// TAMANHO MÁXIMO DE ARQUIVO PARA UPLOAD
ini_set('upload_max_filesize', '2M');

// TAMANHO MÁXIMO DE CONTEÚDO DE UMA REQUISIÇÃO POST
ini_set('post_max_size', '8M');
