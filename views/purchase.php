<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\purchase;

define('HELP', __DIR__ . '/../templates/purchase/help.php');
define('REMOVE', __DIR__ . '/../templates/purchase/delete.php');

require_once __DIR__ . '/../controllers/purchase.php'; // CARREGA O CONTROLADOR DE COMPRAS (BEAUTIFY, FORMULATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE CONFIGURAÇÕES (LOAD)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/other.php'; // CARREGA AS FUNÇÕES DE OUTRAS VISÕES (AUTHENTICATE)


/** IMPRIME O HTML DA PÁGINA DE COMPRAS
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate('purchase')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Compras');
		define('PAGE_NAME', 'purchase');

		$query = 'select * from purchases order by day desc;';
		$tuples = \mysql\execute($query);
		if($tuples) {
			foreach($tuples as $tuple) {
				$query = 'select * from providers where id = ' . $tuple->provider . ';';
				$tuple->provider = \mysql\execute($query)[0];
			}
			$tuples = array_map('\controller\purchase\beautify', $tuples);
		}
		else {
			$message = 'Ainda não há compras cadastradas no sistema.';
		}

		include_once __DIR__ . '/../templates/purchase/index.php'; // CARREGA O TEMPLATE DE COMPRAS (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INSERÇÃO DE COMPRAS
 * @return bool
 */
function insert(): bool {
	if(!\controller\session\authenticate('purchase')) {
		\view\other\authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Cadastrar Compra');
		define('PAGE_NAME', 'purchase');

		$query = 'select * from products order by name asc;';
		$products = \mysql\execute($query);
		$query = 'select * from providers order by company_name asc;';
		$providers = \mysql\execute($query);

		include_once __DIR__ . '/../templates/purchase/insert.php'; // CARREGA O TEMPLATE DE COMPRAS (INSERT)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ALTERAÇÃO DE COMPRAS
 * @return bool
 */
function update(): bool {
	if(!\controller\session\authenticate('purchase')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from purchases where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Alterar Compra');
			define('PAGE_NAME', 'purchase');

			$tuple = \controller\purchase\formulate($operation[0]);

			$query = 'select * from providers where id = ' . $tuple->provider . ';';
			$tuple->provider = \mysql\execute($query)[0];

			$query = 'select * from product_purchase where purchase = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productPurchase) {
				$query = 'select * from products where id = ' . $productPurchase->product . ';';
				$product = \mysql\execute($query)[0];

				$product->quantity = $productPurchase->quantity;
				$product->unit_price = $productPurchase->unit_price;
				$product->total_price = $productPurchase->unit_price * $productPurchase->quantity;
				array_push($tuple->products, $product);
			}

			$query = 'select * from products order by name asc;';
			$products = \mysql\execute($query);
			$query = 'select * from providers order by company_name asc;';
			$providers = \mysql\execute($query);

			include_once __DIR__ . '/../templates/purchase/update.php'; // CARREGA O TEMPLATE DE COMPRAS (UPDATE)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE DADOS INDIVIDUAIS DA COMPRA
 * @return bool
 */
function view(): bool {
	if(!\controller\session\authenticate('purchase')) {
		\view\other\authenticate();
	}

	else {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$query = 'select * from purchases where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			define('PAGE_TITLE', 'Compra');
			define('PAGE_NAME', 'purchase');

			$tuple = \controller\purchase\beautify($operation[0]);

			$query = 'select * from employees where id = ' . $tuple->employee . ';';
			$tuple->employee = \mysql\execute($query)[0];

			$query = 'select * from providers where id = ' . $tuple->provider . ';';
			$tuple->provider = \mysql\execute($query)[0];

			$query = 'select * from product_purchase where purchase = ' . $tuple->id . ';';
			$products = \mysql\execute($query);
			$tuple->products = [];
			foreach($products as $productPurchase) {
				$query = 'select * from products where id = ' . $productPurchase->product . ';';
				$product = \mysql\execute($query)[0];

				$product->quantity = $productPurchase->quantity;
				$product->unit_price = $productPurchase->unit_price;
				$product->total_price = $productPurchase->unit_price * $productPurchase->quantity;

				$product->unit_price = 'R$ ' . number_format($product->unit_price, 2, ',', '.');
				$product->total_price = 'R$ ' . number_format($product->total_price, 2, ',', '.');
				array_push($tuple->products, $product);
			}

			$setting = \controller\setting\load();
			include_once __DIR__ . '/../templates/purchase/view.php'; // CARREGA O TEMPLATE DE PRODUTOS (VIEW)
			return true;
		}

		else {
			index();
		}
	}

	return false;
}
