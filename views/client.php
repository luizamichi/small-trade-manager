<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\client;

define('HELP', __DIR__ . '/../templates/client/help.php');
define('REMOVE', __DIR__ . '/../templates/client/delete.php');

require_once __DIR__ . '/../controllers/client.php'; // CARREGA O CONTROLADOR DE CLIENTES (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE CLIENTES
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('client')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Clientes');
		define('PAGE_NAME', 'client');

		$query = 'select * from clients order by name asc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			$tuples = array_map('\controller\client\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há clientes cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/client/index.php'; // CARREGA O TEMPLATE DE CLIENTES (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE CLIENTES
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('client')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Cliente');
		define('PAGE_NAME', 'client');

		include_once __DIR__ . '/../templates/client/insert.php'; // CARREGA O TEMPLATE DE CLIENTES (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE CLIENTES
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('client')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from clients where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Cliente');
			define('PAGE_NAME', 'client');

			$tuple = \controller\client\formulate($operation[0]);
			include_once __DIR__ . '/../templates/client/update.php'; // CARREGA O TEMPLATE DE CLIENTES (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO CLIENTE
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('client')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from clients where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Cliente');
			define('PAGE_NAME', 'client');

			$tuple = \controller\client\beautify($operation[0]);
			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/client/view.php'; // CARREGA O TEMPLATE DE CLIENTES (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
