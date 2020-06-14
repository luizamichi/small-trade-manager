<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\record;

define('HELP', __DIR__ . '/../templates/record/help.php');
define('REMOVE', __DIR__ . '/../templates/record/delete.php');

require_once __DIR__ . '/../controllers/record.php'; // CARREGA O CONTROLADOR DE REGITROS (BEAUTIFY)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE REGISTROS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('record')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Registros');
		define('PAGE_NAME', 'record');

		$query = 'select * from records order by id desc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			foreach($tuples as $tuple) {
				$query = 'select * from employees where id = ' . $tuple->employee . ';';
				$tuple->employee = \mysql\execute($query)[0];
			}
			$tuples = array_map('\controller\record\beautify', $tuples);
		}
		else {
			$message = 'Ainda não foram realizadas atividades no sistema.';
		}

		include_once __DIR__ . '/../templates/record/index.php'; // CARREGA O TEMPLATE DE REGISTROS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO REGISTRO
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('record')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from records where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Registro');
			define('PAGE_NAME', 'record');

			$tuple = \controller\record\beautify($operation[0]);
			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/record/view.php'; // CARREGA O TEMPLATE DE REGISTROS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
