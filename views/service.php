<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\service;

define('HELP', __DIR__ . '/../templates/service/help.php');
define('REMOVE', __DIR__ . '/../templates/service/delete.php');

require_once __DIR__ . '/../controllers/service.php'; // CARREGA O CONTROLADOR DE SERVIÇOS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE SERVIÇOS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('service')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Serviços');
		define('PAGE_NAME', 'service');

		$query = 'select * from services order by name asc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			$tuples = array_map('\controller\service\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há serviços cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/service/index.php'; // CARREGA O TEMPLATE DE SERVIÇOS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE SERVIÇOS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('service')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Serviço');
		define('PAGE_NAME', 'service');

		include_once __DIR__ . '/../templates/service/insert.php'; // CARREGA O TEMPLATE DE SERVIÇOS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE SERVIÇOS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('service')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from services where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Serviço');
			define('PAGE_NAME', 'service');

			$tuple = \controller\service\formulate($operation[0]);
			include_once __DIR__ . '/../templates/service/update.php'; // CARREGA O TEMPLATE DE SERVIÇOS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO SERVIÇO
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('service')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from services where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Serviço');
			define('PAGE_NAME', 'service');

			$tuple = \controller\service\beautify($operation[0]);
			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/service/view.php'; // CARREGA O TEMPLATE DE SERVIÇOS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
