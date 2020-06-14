<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\provider;

define('HELP', __DIR__ . '/../templates/provider/help.php');
define('REMOVE', __DIR__ . '/../templates/provider/delete.php');

require_once __DIR__ . '/../controllers/provider.php'; // CARREGA O CONTROLADOR DE FORNECEDORES (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE FORNECEDORES
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('provider')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Fornecedores');
		define('PAGE_NAME', 'provider');

		$query = 'select * from providers order by company_name asc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			$tuples = array_map('\controller\provider\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há fornecedores cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/provider/index.php'; // CARREGA O TEMPLATE DE FORNECEDORES (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE FORNECEDORES
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('provider')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Fornecedor');
		define('PAGE_NAME', 'provider');

		include_once __DIR__ . '/../templates/provider/insert.php'; // CARREGA O TEMPLATE DE FORNECEDORES (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE FORNECEDORES
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('provider')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from providers where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Fornecedor');
			define('PAGE_NAME', 'provider');

			$tuple = \controller\provider\formulate($operation[0]);
			include_once __DIR__ . '/../templates/provider/update.php'; // CARREGA O TEMPLATE DE FORNECEDORES (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO FORNECEDOR
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('provider')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from providers where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Fornecedor');
			define('PAGE_NAME', 'provider');

			$tuple = \controller\provider\beautify($operation[0]);
			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/provider/view.php'; // CARREGA O TEMPLATE DE FORNECEDORES (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
