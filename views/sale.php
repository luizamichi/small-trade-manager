<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\sale;

define('HELP', __DIR__ . '/../templates/sale/help.php');
define('REMOVE', __DIR__ . '/../templates/sale/delete.php');

require_once __DIR__ . '/../controllers/sale.php'; // CARREGA O CONTROLADOR DE VENDAS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE VENDAS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('sale')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Vendas');
		define('PAGE_NAME', 'sale');

		$query = 'select * from sales order by id desc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			foreach($tuples as $tuple) {
				$query = 'select * from clients where id = ' . $tuple->client . ';';
				$tuple->client = \mysql\execute($query)[0];
			}
			$tuples = array_map('\controller\sale\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há vendas cadastradas no sistema.';
		}

		include_once __DIR__ . '/../templates/sale/index.php'; // CARREGA O TEMPLATE DE VENDAS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE VENDAS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('sale')) {
		\view\other\authenticate();
		return false;
	}

	else {
		$id = filter_input(INPUT_GET, 'budget', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from budgets where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		define('PAGE_TITLE', 'Cadastrar Venda');
		define('PAGE_NAME', 'sale');

		if(isset($operation[0])) {
			$tuple = \controller\sale\formulate($operation[0]);

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
		}

		$query = 'select * from clients order by name asc;';
		$clients = \mysql\execute($query);
		$query = 'select * from products order by name asc;';
		$products = \mysql\execute($query);
		$query = 'select * from services order by name asc;';
		$services = \mysql\execute($query);

		include_once __DIR__ . '/../templates/sale/insert.php'; // CARREGA O TEMPLATE DE VENDAS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE VENDAS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('sale')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from sales where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Venda');
			define('PAGE_NAME', 'sale');

			$tuple = \controller\sale\formulate($operation[0]);

			$query = 'select * from clients where id = ' . $tuple->client . ';';
			$tuple->client = \mysql\execute($query)[0];

			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$query = 'select * from product_sale where sale = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productSale) {
				$query = 'select * from products where id = ' . $productSale->product . ';';
				$product = \mysql\execute($query)[0];

				$product->quantity = $productSale->quantity;
				$product->unit_price = $productSale->unit_price;
				$product->total_price = $productSale->unit_price * $productSale->quantity;
				array_push($tuple->products, $product);
			}

			$query = 'select * from service_sale where sale = ' . $tuple->id . ';';
			$services = \mysql\execute($query);
			$tuple->services = [];
			foreach($services as $serviceSale) {
				$query = 'select * from services where id = ' . $serviceSale->service . ';';
				$service = \mysql\execute($query)[0];

				$service->quantity = $serviceSale->quantity;
				$service->unit_price = $serviceSale->unit_price;
				$service->total_price = $serviceSale->unit_price * $serviceSale->quantity;
				array_push($tuple->services, $service);
			}

			$tuple->total = array_sum(array_column($tuple->products, 'total_price')) + array_sum(array_column($tuple->services, 'total_price'));

			$query = 'select * from clients order by name asc;';
			$clients = \mysql\execute($query);
			$query = 'select * from products order by name asc;';
			$products = \mysql\execute($query);
			$query = 'select * from services order by name asc;';
			$services = \mysql\execute($query);

			include_once __DIR__ . '/../templates/sale/update.php'; // CARREGA O TEMPLATE DE VENDAS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DA VENDA
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('sale')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from sales where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Venda');
			define('PAGE_NAME', 'sale');

			$tuple = \controller\sale\beautify($operation[0]);

			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$query = 'select * from clients where id = ' . $tuple->client . ';';
			$tuple->client = \mysql\execute($query)[0];

			$query = 'select * from product_sale where sale = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productSale) {
				$query = 'select * from products where id = ' . $productSale->product . ';';
				$product = \mysql\execute($query)[0];
				$product->quantity = $productSale->quantity;
				$product->unit_price = $productSale->unit_price;
				$product->total_price = $productSale->unit_price * $productSale->quantity;

				$product->unit_price = 'R$ ' . number_format($product->unit_price, 2, ',', '.');
				$product->total_price = 'R$ ' . number_format($product->total_price, 2, ',', '.');
				array_push($tuple->products, $product);
			}

			$query = 'select * from service_sale where sale = ' . $tuple->id . ';';
			$services = \mysql\execute($query);
			$tuple->services = [];
			foreach($services as $serviceSale) {
				$query = 'select * from services where id = ' . $serviceSale->service . ';';
				$service = \mysql\execute($query)[0];
				$service->quantity = $serviceSale->quantity;
				$service->unit_price = $serviceSale->unit_price;
				$service->total_price = $serviceSale->unit_price * $serviceSale->quantity;

				$service->unit_price = 'R$ ' . number_format($service->unit_price, 2, ',', '.');
				$service->total_price = 'R$ ' . number_format($service->total_price, 2, ',', '.');
				array_push($tuple->services, $service);
			}

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/sale/view.php'; // CARREGA O TEMPLATE DE VENDAS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
