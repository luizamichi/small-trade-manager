<?php
//--// ---- //--//
//--// MAIN //--//
//--// ---- //--//

namespace main;

ob_start();

require_once __DIR__ . '/config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (BASE_URL)
require_once __DIR__ . '/routes.php'; // CARREGA AS ROTAS DA API (BUDGET, CLIENT, EMPLOYEE, OTHER, PRODUCT, PROVIDER, PURCHASE, RECORD, SALE, SERVICE)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS (DEBUG)


$request = (string) strtok($_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? '', '?'); // VARIÁVEL COM O URI REQUERIDO


// VARIÁVEIS DE CAMINHO
$budgetView = __DIR__ . '/views/budget.php';
$clientView = __DIR__ . '/views/client.php';
$employeeView = __DIR__ . '/views/employee.php';
$otherView = __DIR__ . '/views/other.php';
$productView = __DIR__ . '/views/product.php';
$providerView = __DIR__ . '/views/provider.php';
$purchaseView = __DIR__ . '/views/purchase.php';
$saleView = __DIR__ . '/views/sale.php';
$serviceView = __DIR__ . '/views/service.php';


// CARREGAMENTO DE VISÕES
switch($request) {
	// ROTAS DIVERSAS
	case BASE_URL:
		include_once $otherView; // CARREGA AS VIEWS DIVERSAS (INDEX)
		\view\other\index();
		break;

	case BASE_URL . 'authenticate':
	case BASE_URL . 'authenticate/':
	case BASE_URL . 'login':
	case BASE_URL . 'login/':
		include_once $otherView; // CARREGA AS VIEWS DIVERSAS (AUTHENTICATE)
		\view\other\authenticate();
		break;

	case BASE_URL . 'exit':
	case BASE_URL . 'exit/':
	case BASE_URL . 'unauthenticate':
	case BASE_URL . 'unauthenticate/':
		include_once $otherView; // CARREGA AS VIEWS DIVERSAS (UNAUTHENTICATE)
		\view\other\unauthenticate();
		break;

	case BASE_URL . 'report':
	case BASE_URL . 'report/':
		include_once $otherView; // CARREGA AS VIEWS DIVERSAS (REPORT)
		\view\other\report();
		break;

	case BASE_URL . 'setting':
	case BASE_URL . 'setting/':
		include_once $otherView; // CARREGA AS VIEWS DIVERSAS (SETTING)
		\view\other\setting();
		break;

	// VIEWS DE ORÇAMENTOS
	case BASE_URL . 'budget':
	case BASE_URL . 'budget/':
		include_once $budgetView; // CARREGA AS VIEWS DE ORÇAMENTOS (INDEX)
		\view\budget\index();
		break;

	case BASE_URL . 'budget/insert':
	case BASE_URL . 'budget/insert/':
		include_once $budgetView; // CARREGA AS VIEWS DE ORÇAMENTOS (INSERT)
		\view\budget\insert();
		break;

	case BASE_URL . 'budget/update':
	case BASE_URL . 'budget/update/':
		include_once $budgetView; // CARREGA AS VIEWS DE ORÇAMENTOS (UPDATE)
		\view\budget\update();
		break;

	case BASE_URL . 'budget/view':
	case BASE_URL . 'budget/view/':
		include_once $budgetView; // CARREGA AS VIEWS DE ORÇAMENTOS (VIEW)
		\view\budget\view();
		break;

	// VIEWS DE CLIENTES
	case BASE_URL . 'client':
	case BASE_URL . 'client/':
		include_once $clientView; // CARREGA AS VIEWS DE CLIENTES (INDEX)
		\view\client\index();
		break;

	case BASE_URL . 'client/insert':
	case BASE_URL . 'client/insert/':
		include_once $clientView; // CARREGA AS VIEWS DE CLIENTES (INSERT)
		\view\client\insert();
		break;

	case BASE_URL . 'client/update':
	case BASE_URL . 'client/update/':
		include_once $clientView; // CARREGA AS VIEWS DE CLIENTES (UPDATE)
		\view\client\update();
		break;

	case BASE_URL . 'client/view':
	case BASE_URL . 'client/view/':
		include_once $clientView; // CARREGA AS VIEWS DE CLIENTES (VIEW)
		\view\client\view();
		break;

	// VIEWS DE FUNCIONÁRIOS
	case BASE_URL . 'employee':
	case BASE_URL . 'employee/':
		include_once $employeeView; // CARREGA AS VIEWS DE FUNCIONÁRIOS (INDEX)
		\view\employee\index();
		break;

	case BASE_URL . 'employee/insert':
	case BASE_URL . 'employee/insert/':
		include_once $employeeView; // CARREGA AS VIEWS DE FUNCIONÁRIOS (INSERT)
		\view\employee\insert();
		break;

	case BASE_URL . 'employee/update':
	case BASE_URL . 'employee/update/':
		include_once $employeeView; // CARREGA AS VIEWS DE FUNCIONÁRIOS (UPDATE)
		\view\employee\update();
		break;

	case BASE_URL . 'employee/view':
	case BASE_URL . 'employee/view/':
		include_once $employeeView; // CARREGA AS VIEWS DE FUNCIONÁRIOS (VIEW)
		\view\employee\view();
		break;

	// VIEWS DE PRODUTOS
	case BASE_URL . 'product':
	case BASE_URL . 'product/':
		include_once $productView; // CARREGA AS VIEWS DE PRODUTOS (INDEX)
		\view\product\index();
		break;

	case BASE_URL . 'product/insert':
	case BASE_URL . 'product/insert/':
		include_once $productView; // CARREGA AS VIEWS DE PRODUTOS (INSERT)
		\view\product\insert();
		break;

	case BASE_URL . 'product/update':
	case BASE_URL . 'product/update/':
		include_once $productView; // CARREGA AS VIEWS DE PRODUTOS (UPDATE)
		\view\product\update();
		break;

	case BASE_URL . 'product/view':
	case BASE_URL . 'product/view/':
		include_once $productView; // CARREGA AS VIEWS DE PRODUTOS (VIEW)
		\view\product\view();
		break;

	// VIEWS DE FORNECEDORES
	case BASE_URL . 'provider':
	case BASE_URL . 'provider/':
		include_once $providerView; // CARREGA AS VIEWS DE FORNECEDORES (INDEX)
		\view\provider\index();
		break;

	case BASE_URL . 'provider/insert':
	case BASE_URL . 'provider/insert/':
		include_once $providerView; // CARREGA AS VIEWS DE FORNECEDORES (INSERT)
		\view\provider\insert();
		break;

	case BASE_URL . 'provider/update':
	case BASE_URL . 'provider/update/':
		include_once $providerView; // CARREGA AS VIEWS DE FORNECEDORES (UPDATE)
		\view\provider\update();
		break;

	case BASE_URL . 'provider/view':
	case BASE_URL . 'provider/view/':
		include_once $providerView; // CARREGA AS VIEWS DE FORNECEDORES (VIEW)
		\view\provider\view();
		break;

	// VIEWS DE COMPRAS
	case BASE_URL . 'purchase':
	case BASE_URL . 'purchase/':
		include_once $purchaseView; // CARREGA AS VIEWS DE COMPRAS (INDEX)
		\view\purchase\index();
		break;

	case BASE_URL . 'purchase/insert':
	case BASE_URL . 'purchase/insert/':
		include_once $purchaseView; // CARREGA AS VIEWS DE COMPRAS (INSERT)
		\view\purchase\insert();
		break;

	case BASE_URL . 'purchase/update':
	case BASE_URL . 'purchase/update/':
		include_once $purchaseView; // CARREGA AS VIEWS DE COMPRAS (UPDATE)
		\view\purchase\update();
		break;

	case BASE_URL . 'purchase/view':
	case BASE_URL . 'purchase/view/':
		include_once $purchaseView; // CARREGA AS VIEWS DE COMPRAS (VIEW)
		\view\purchase\view();
		break;

	// VIEWS DE REGISTROS
	case BASE_URL . 'record':
	case BASE_URL . 'record/':
		include_once __DIR__ . '/views/record.php'; // CARREGA AS VIEWS DE REGISTROS (INDEX)
		\view\record\index();
		break;

	case BASE_URL . 'record/view':
	case BASE_URL . 'record/view/':
		include_once __DIR__ . '/views/record.php'; // CARREGA AS VIEWS DE REGISTROS (VIEW)
		\view\record\view();
		break;

	// VIEWS DE VENDAS
	case BASE_URL . 'sale':
	case BASE_URL . 'sale/':
		include_once $saleView; // CARREGA AS VIEWS DE VENDAS (INDEX)
		\view\sale\index();
		break;

	case BASE_URL . 'sale/insert':
	case BASE_URL . 'sale/insert/':
		include_once $saleView; // CARREGA AS VIEWS DE VENDAS (INSERT)
		\view\sale\insert();
		break;

	case BASE_URL . 'sale/update':
	case BASE_URL . 'sale/update/':
		include_once $saleView; // CARREGA AS VIEWS DE VENDAS (UPDATE)
		\view\sale\update();
		break;

	case BASE_URL . 'sale/view':
	case BASE_URL . 'sale/view/':
		include_once $saleView; // CARREGA AS VIEWS DE VENDAS (VIEW)
		\view\sale\view();
		break;

	// VIEWS DE SERVIÇOS
	case BASE_URL . 'service':
	case BASE_URL . 'service/':
		include_once $serviceView; // CARREGA AS VIEWS DE SERVIÇOS (INDEX)
		\view\service\index();
		break;

	case BASE_URL . 'service/insert':
	case BASE_URL . 'service/insert/':
		include_once $serviceView; // CARREGA AS VIEWS DE SERVIÇOS (INSERT)
		\view\service\insert();
		break;

	case BASE_URL . 'service/update':
	case BASE_URL . 'service/update/':
		include_once $serviceView; // CARREGA AS VIEWS DE SERVIÇOS (UPDATE)
		\view\service\update();
		break;

	case BASE_URL . 'service/view':
	case BASE_URL . 'service/view/':
		include_once $serviceView; // CARREGA AS VIEWS DE SERVIÇOS (VIEW)
		\view\service\view();
		break;

	// VIEW DE ERRO
	default:
		if(!stristr($request, BASE_URL . 'action/')) {
			include_once $otherView; // CARREGA AS VIEWS DIVERSAS (ERROR)
			\view\other\error();
		}
		elseif(!ob_get_contents()) { // NENHUMA ROTA FOI ENCONTRADA
			http_response_code(404);
		}
		break;
}
