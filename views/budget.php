<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\budget;

define('HELP', __DIR__ . '/../templates/budget/help.php');
define('REMOVE', __DIR__ . '/../templates/budget/delete.php');

require_once __DIR__ . '/../controllers/budget.php'; // CARREGA O CONTROLADOR DE ORÇAMENTOS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE ORÇAMENTOS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('budget')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Orçamentos');
		define('PAGE_NAME', 'budget');

		$query = 'select * from budgets order by id desc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			foreach($tuples as $tuple) {
				$query = 'select * from employees where id = ' . $tuple->employee . ';';
				$tuple->employee = \mysql\execute($query)[0];
			}
			$tuples = array_map('\controller\budget\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há orçamentos cadastrados no sistema.';
		}

		include_once __DIR__ . '/../templates/budget/index.php'; // CARREGA O TEMPLATE DE ORÇAMENTOS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE ORÇAMENTOS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('budget')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Orçamento');
		define('PAGE_NAME', 'budget');

		$query = 'select * from products order by name asc;';
		$products = \mysql\execute($query);
		$query = 'select * from services order by name asc;';
		$services = \mysql\execute($query);

		include_once __DIR__ . '/../templates/budget/insert.php'; // CARREGA O TEMPLATE DE ORÇAMENTOS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE ORÇAMENTOS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('budget')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from budgets where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Orçamento');
			define('PAGE_NAME', 'budget');

			$tuple = \controller\budget\formulate($operation[0]);

			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$query = 'select * from product_budget where budget = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productBudget) {
				$query = 'select * from products where id = ' . $productBudget->product . ';';
				$product = \mysql\execute($query)[0];

				$product->quantity = $productBudget->quantity;
				$product->unit_price = $productBudget->unit_price;
				$product->total_price = $productBudget->unit_price * $productBudget->quantity;
				array_push($tuple->products, $product);
			}

			$query = 'select * from service_budget where budget = ' . $tuple->id . ';';
			$services = \mysql\execute($query);
			$tuple->services = [];
			foreach($services as $serviceBudget) {
				$query = 'select * from services where id = ' . $serviceBudget->service . ';';
				$service = \mysql\execute($query)[0];

				$service->quantity = $serviceBudget->quantity;
				$service->unit_price = $serviceBudget->unit_price;
				$service->total_price = $serviceBudget->unit_price * $serviceBudget->quantity;
				array_push($tuple->services, $service);
			}

			$tuple->total = array_sum(array_column($tuple->products, 'total_price')) + array_sum(array_column($tuple->services, 'total_price'));

			$query = 'select * from products order by name asc;';
			$products = \mysql\execute($query);
			$query = 'select * from services order by name asc;';
			$services = \mysql\execute($query);

			include_once __DIR__ . '/../templates/budget/update.php'; // CARREGA O TEMPLATE DE ORÇAMENTOS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DO ORÇAMENTO
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('budget')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from budgets where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Orçamento');
			define('PAGE_NAME', 'budget');

			$tuple = \controller\budget\beautify($operation[0]);

			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$query = 'select * from product_budget where budget = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productBudget) {
				$query = 'select * from products where id = ' . $productBudget->product . ';';
				$product = \mysql\execute($query)[0];

				$product->quantity = $productBudget->quantity;
				$product->unit_price = $productBudget->unit_price;
				$product->total_price = $productBudget->unit_price * $productBudget->quantity;

				$product->unit_price = 'R$ ' . number_format($product->unit_price, 2, ',', '.');
				$product->total_price = 'R$ ' . number_format($product->total_price, 2, ',', '.');
				array_push($tuple->products, $product);
			}

			$query = 'select * from service_budget where budget = ' . $tuple->id . ';';
			$services = \mysql\execute($query);
			$tuple->services = [];
			foreach($services as $serviceBudget) {
				$query = 'select * from services where id = ' . $serviceBudget->service . ';';
				$service = \mysql\execute($query)[0];

				$service->quantity = $serviceBudget->quantity;
				$service->unit_price = $serviceBudget->unit_price;
				$service->total_price = $serviceBudget->unit_price * $serviceBudget->quantity;

				$service->unit_price = 'R$ ' . number_format($service->unit_price, 2, ',', '.');
				$service->total_price = 'R$ ' . number_format($service->total_price, 2, ',', '.');
				array_push($tuple->services, $service);
			}

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/budget/view.php'; // CARREGA O TEMPLATE DE ORÇAMENTOS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
