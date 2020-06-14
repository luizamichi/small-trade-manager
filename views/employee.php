<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\employee;

define('HELP', __DIR__ . '/../templates/employee/help.php');
define('REMOVE', __DIR__ . '/../templates/employee/delete.php');

require_once __DIR__ . '/../controllers/employee.php'; // CARREGA O CONTROLADOR DE FUNCIONÁRIOS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE FUNCIONÁRIOS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('employee')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Funcionários');
		define('PAGE_NAME', 'employee');

		$query = 'select * from employees order by name asc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			$tuples = array_map('\controller\employee\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há funcionários cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/employee/index.php'; // CARREGA O TEMPLATE DE FUNCIONÁRIOS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE FUNCIONÁRIOS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('employee')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Funcionário');
		define('PAGE_NAME', 'employee');

		include_once __DIR__ . '/../templates/employee/insert.php'; // CARREGA O TEMPLATE DE FUNCIONÁRIOS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE FUNCIONÁRIOS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('employee')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from employees where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Funcionário');
			define('PAGE_NAME', 'employee');

			$tuple = \controller\employee\formulate($operation[0]);
			$query = 'select * from permissions where id = ' . $tuple->permission . ';';
			$tuple->permission = \mysql\execute($query)[0];
			include_once __DIR__ . '/../templates/employee/update.php'; // CARREGA O TEMPLATE DE FUNCIONÁRIOS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO FUNCIONÁRIO
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('employee')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from employees where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Funcionário');
			define('PAGE_NAME', 'employee');

			$tuple = \controller\employee\beautify($operation[0]);
			$query = 'select * from permissions where id = ' . $tuple->permission . ';';
			$tuple->permission = \mysql\execute($query)[0];

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/employee/view.php'; // CARREGA O TEMPLATE DE FUNCIONÁRIOS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
