<?php
//--// -------------- //--//
//--// VIEW FUNCTIONS //--//
//--// -------------- //--//

namespace view\other;

require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE, UNAUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE OPÇÕES (FORMULATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)


/** IMPRIME O HTML DA PÁGINA DE AUTENTICAÇÃO DO USUÁRIO (LOGIN)
 * @return bool
 */
function authenticate(): bool {
	if(!\controller\session\authenticate()) {
		define('PAGE_TITLE', 'Autenticação');
		define('PAGE_NAME', 'user');
		include_once __DIR__ . '/../templates/other/authenticate.php'; // CARREGA O TEMPLATE DE AUTENTICAÇÃO (AUTHENTICATE)
		return true;
	}

	else {
		index();
		return false;
	}
}


/** IMPRIME O HTML DA PÁGINA DE ERRO
 * @return bool
 */
function error(): bool {
	if(!\controller\session\authenticate()) {
		authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Erro');
		include_once __DIR__ . '/../templates/other/error.php'; // CARREGA O TEMPLATE DE ERRO (ERROR)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE INÍCIO
 * @return bool
 */
function index(): bool {
	if(!\controller\session\authenticate()) {
		authenticate();
		return false;
	}

	else {
		define('PAGE_TITLE', 'Início');
		define('PAGE_NAME', 'index');
		include_once __DIR__ . '/../templates/other/index.php'; // CARREGA O TEMPLATE DE INÍCIO (INDEX)
		return true;
	}
}


/** IMPRIME O HTML DA PÁGINA DE RELATÓRIOS
 * @return bool
 */
function report(): bool {
	if(!\controller\session\authenticate('report')) {
		authenticate();
	}

	else {
		define('PAGE_TITLE', 'Relatórios');
		define('PAGE_NAME', 'report');

		$query = 'select purchases.*, providers.fantasy_name from purchases inner join providers on purchases.provider = providers.id where day >= "' . date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 month')) . '";';
		$purchases = \mysql\execute($query);

		$query = 'select sales.*, clients.name, clients.surname from sales inner join clients on sales.client = clients.id where day >= "' . date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 month')) . '";';
		$sales = \mysql\execute($query);

		if($purchases) { // EXISTE AO MENOS UMA COMPRA REALIZADA NOS ÚLTIMOS 30 DIAS
			$providers = array_map(function(object $purchase): string {
				return $purchase->fantasy_name;
			}, $purchases);
			sort($providers);

			$query = 'select date(day) day, sum(total) total from purchases where day >= "' . date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 month')) . '" group by date(day);';
			$purchases = \mysql\execute($query);
			foreach($purchases as $index => $purchase) {
				$purchases[$index]->day = date_format(date_create($purchase->day), 'd/m/Y');
			}

			$days = array_map(function(object $purchase): string {
				return '"' . $purchase->day . '"';
			}, $purchases);

			$values = array_map(function(object $purchase): float {
				return $purchase->total;
			}, $purchases);
			$total = array_sum($values);

			$purchases = (object) ['days' => implode(', ', $days), 'providers' => implode(', ', array_unique($providers)), 'values' => implode(', ', $values), 'total' => 'R$ ' . number_format($total, 2, ',', '.')];
		}

		if($sales) { // EXISTE AO MENOS UMA VENDA REALIZADA NOS ÚLTIMOS 30 DIAS
			$clients = array_map(function(object $sale): string {
				return $sale->name . ' ' . $sale->surname;
			}, $sales);
			sort($clients);

			$query = 'select date(day) day, sum(total) total from sales where day >= "' . date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 month')) . '" group by date(day);';
			$sales = \mysql\execute($query);
			foreach($sales as $index => $sale) {
				$sales[$index]->day = date_format(date_create($sale->day), 'd/m/Y');
			}

			$days = array_map(function(object $sale): string {
				return '"' . $sale->day . '"';
			}, $sales);

			$values = array_map(function(object $sale): float {
				return $sale->total;
			}, $sales);
			$total = array_sum($values);

			$sales = (object) ['days' => implode(', ', $days), 'clients' => implode(', ', array_unique($clients)), 'values' => implode(', ', $values), 'total' => 'R$ ' . number_format($total, 2, ',', '.')];
		}

		$query = 'select (select count(*) from purchases) purchases, (select count(*) from sales) sales from dual;';
		$count = \mysql\execute($query)[0] ?? (object) ['purchases' => [], 'sales' => []];
		if(!$purchases && !$sales && ($count->purchases > 0 || $count->sales > 0)) {
			$message = 'Ainda não foram realizadas compras ou vendas nos últimos 30 dias.';
		}

		elseif(!$purchases && !$sales) {
			$message = 'Ainda não foram realizadas compras ou vendas no sistema.';
		}

		include_once __DIR__ . '/../templates/other/report.php'; // CARREGA O TEMPLATE DE RELATÓRIOS (REPORT)
		return true;
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE OPÇÕES
 * @return bool
 */
function setting(): bool {
	if(!\controller\session\authenticate('setting')) {
		\view\other\authenticate();
	}

	else {
		define('PAGE_TITLE', 'Opções');
		define('PAGE_NAME', 'setting');
		define('HELP', __DIR__ . '/../templates/other/help.php');

		$query = 'select * from settings;';
		$operation = \mysql\execute($query);

		if(isset($operation[0])) {
			$tuple = \controller\setting\formulate($operation[0]);
		}
		else {
			$tuple = (object) ['company_name' => '', 'fantasy_name' => '', 'cnpj' => '', 'email' => '', 'phone' => '', 'postal_code' => '', 'district' => '', 'city' => '', 'state' => '', 'address' => '', 'number' => '', 'website' => ''];
		}

		include_once __DIR__ . '/../templates/other/setting.php'; // CARREGA O TEMPLATE DE OPÇÕES (SETTING)
		return true;
	}

	return false;
}


/** IMPRIME O HTML DA PÁGINA DE AUTENTICAÇÃO DO USUÁRIO OU DA PÁGINA DE INÍCIO
 * @return bool
 */
function unauthenticate(): bool {
	\controller\session\unauthenticate();
	authenticate();
	return true;
}
