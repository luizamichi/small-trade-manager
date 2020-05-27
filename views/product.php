<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\product;

define('HELP', __DIR__ . '/../templates/product/help.php');
define('REMOVE', __DIR__ . '/../templates/product/delete.php');

require_once __DIR__ . '/../controllers/product.php'; // CARREGA O CONTROLADOR DE PRODUTOS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE PRODUTOS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('product')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Produtos');
		define('PAGE_NAME', 'product');

		$query = 'select * from products order by name asc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			foreach($tuples as $tuple) {
				$query = 'select * from providers where id = ' . $tuple->provider . ';';
				$tuple->provider = \mysql\execute($query)[0];
			}
			$tuples = array_map('\controller\product\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há produtos cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/product/index.php'; // CARREGA O TEMPLATE DE PRODUTOS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE PRODUTOS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('product')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Produto');
		define('PAGE_NAME', 'product');

		$query = 'select * from providers order by company_name asc;';
		$providers = \mysql\execute($query);

		include_once __DIR__ . '/../templates/product/insert.php'; // CARREGA O TEMPLATE DE PRODUTOS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE PRODUTOS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('product')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from products where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Produto');
			define('PAGE_NAME', 'product');

			$tuple = \controller\product\formulate($operation[0]);
			$query = 'select * from providers where id = ' . $tuple->provider . ';';
			$tuple->provider = \mysql\execute($query)[0];

			$query = 'select * from providers order by company_name asc;';
			$providers = \mysql\execute($query);

			include_once __DIR__ . '/../templates/product/update.php'; // CARREGA O TEMPLATE DE PRODUTOS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO PRODUTO
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('product')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from products where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Produto');
			define('PAGE_NAME', 'product');

			$tuple = \controller\product\beautify($operation[0]);
			$query = 'select * from providers where id = ' . $tuple->provider . ';';
			$tuple->provider = \mysql\execute($query)[0];

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/product/view.php'; // CARREGA O TEMPLATE DE PRODUTOS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
