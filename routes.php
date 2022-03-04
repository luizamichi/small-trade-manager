<?php
//--// ---------- //--//
//--// API ROUTES //--//
//--// ---------- //--//

namespace routes;

require_once(__DIR__ . '/config.php'); // CARREGA AS CONFIGURAÇÕES GLOBAIS (BASE_URL)


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE ORÇAMENTOS
 * @param string $request
 * @return bool
 */
function budget(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/budget/delete'):
			require_once(__DIR__ . '/api/budget.php'); // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (DELETE)
			return \api\budget\delete();

		case stristr($request, BASE_URL . 'action/budget/insert'):
			require_once(__DIR__ . '/api/budget.php'); // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (INSERT)
			return \api\budget\insert();

		case stristr($request, BASE_URL . 'action/budget/select'):
			require_once(__DIR__ . '/api/budget.php'); // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (SELECT)
			return \api\budget\select();

		case stristr($request, BASE_URL . 'action/budget/update'):
			require_once(__DIR__ . '/api/budget.php'); // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (UPDATE)
			return \api\budget\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE CLIENTES
 * @param string $request
 * @return bool
 */
function client(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/client/delete'):
			require_once(__DIR__ . '/api/client.php'); // CARREGA AS FUNÇÕES DA API DE CLIENTES (DELETE)
			return \api\client\delete();

		case stristr($request, BASE_URL . 'action/client/insert'):
			require_once(__DIR__ . '/api/client.php'); // CARREGA AS FUNÇÕES DA API DE CLIENTES (INSERT)
			return \api\client\insert();

		case stristr($request, BASE_URL . 'action/client/select'):
			require_once(__DIR__ . '/api/client.php'); // CARREGA AS FUNÇÕES DA API DE CLIENTES (SELECT)
			return \api\client\select();

		case stristr($request, BASE_URL . 'action/client/update'):
			require_once(__DIR__ . '/api/client.php'); // CARREGA AS FUNÇÕES DA API DE CLIENTES (UPDATE)
			return \api\client\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE FUNCIONÁRIOS
 * @param string $request
 * @return bool
 */
function employee(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/employee/delete'):
			require_once(__DIR__ . '/api/employee.php'); // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (DELETE)
			return \api\employee\delete();

		case stristr($request, BASE_URL . 'action/employee/insert'):
			require_once(__DIR__ . '/api/employee.php'); // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (INSERT)
			return \api\employee\insert();

		case stristr($request, BASE_URL . 'action/employee/select'):
			require_once(__DIR__ . '/api/employee.php'); // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (SELECT)
			return \api\employee\select();

		case stristr($request, BASE_URL . 'action/employee/update'):
			require_once(__DIR__ . '/api/employee.php'); // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (UPDATE)
			return \api\employee\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE ITENS DIVERSOS
 * @param string $request
 * @return bool
 */
function other(string $request): bool {
	switch(true) {
		case strstr($request, BASE_URL . 'action/setting/manage'):
			require_once(__DIR__ . '/api/other.php'); // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (SETTING)
			return \api\other\setting();

		case strstr($request, BASE_URL . 'action/user/authenticate'):
			require_once(__DIR__ . '/api/other.php'); // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (AUTHENTICATE)
			return \api\other\authenticate();

		case strstr($request, BASE_URL . 'action/user/unauthenticate'):
			require_once(__DIR__ . '/api/other.php'); // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (UNAUTHENTICATE)
			return \api\other\unauthenticate();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE PRODUTOS
 * @param string $request
 * @return bool
 */
function product(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/product/delete'):
			require_once(__DIR__ . '/api/product.php'); // CARREGA AS FUNÇÕES DA API DE PRODUTOS (DELETE)
			return \api\product\delete();

		case stristr($request, BASE_URL . 'action/product/insert'):
			require_once(__DIR__ . '/api/product.php'); // CARREGA AS FUNÇÕES DA API DE PRODUTOS (INSERT)
			return \api\product\insert();

		case stristr($request, BASE_URL . 'action/product/select'):
			require_once(__DIR__ . '/api/product.php'); // CARREGA AS FUNÇÕES DA API DE PRODUTOS (SELECT)
			return \api\product\select();

		case stristr($request, BASE_URL . 'action/product/update'):
			require_once(__DIR__ . '/api/product.php'); // CARREGA AS FUNÇÕES DA API DE PRODUTOS (UPDATE)
			return \api\product\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE FORNECEDORES
 * @param string $request
 * @return bool
 */
function provider(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/provider/delete'):
			require_once(__DIR__ . '/api/provider.php'); // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (DELETE)
			return \api\provider\delete();

		case stristr($request, BASE_URL . 'action/provider/insert'):
			require_once(__DIR__ . '/api/provider.php'); // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (INSERT)
			return \api\provider\insert();

		case stristr($request, BASE_URL . 'action/provider/select'):
			require_once(__DIR__ . '/api/provider.php'); // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (SELECT)
			return \api\provider\select();

		case stristr($request, BASE_URL . 'action/provider/update'):
			require_once(__DIR__ . '/api/provider.php'); // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (UPDATE)
			return \api\provider\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE COMPRAS
 * @param string $request
 * @return bool
 */
function purchase(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/purchase/delete'):
			require_once(__DIR__ . '/api/purchase.php'); // CARREGA AS FUNÇÕES DA API DE COMPRAS (DELETE)
			return \api\purchase\delete();

		case stristr($request, BASE_URL . 'action/purchase/insert'):
			require_once(__DIR__ . '/api/purchase.php'); // CARREGA AS FUNÇÕES DA API DE COMPRAS (INSERT)
			return \api\purchase\insert();

		case stristr($request, BASE_URL . 'action/purchase/select'):
			require_once(__DIR__ . '/api/purchase.php'); // CARREGA AS FUNÇÕES DA API DE COMPRAS (SELECT)
			return \api\purchase\select();

		case stristr($request, BASE_URL . 'action/purchase/update'):
			require_once(__DIR__ . '/api/purchase.php'); // CARREGA AS FUNÇÕES DA API DE COMPRAS (UPDATE)
			return \api\purchase\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE REGISTROS
 * @param string $request
 * @return bool
 */
function record(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/record/select'):
			require_once(__DIR__ . '/api/record.php'); // CARREGA AS FUNÇÕES DA API DE REGISTROS (SELECT)
			return \api\record\select();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE VENDAS
 * @param string $request
 * @return bool
 */
function sale(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/sale/delete'):
			require_once(__DIR__ . '/api/sale.php'); // CARREGA AS FUNÇÕES DA API DE VENDAS (DELETE)
			return \api\sale\delete();

		case stristr($request, BASE_URL . 'action/sale/insert'):
			require_once(__DIR__ . '/api/sale.php'); // CARREGA AS FUNÇÕES DA API DE VENDAS (INSERT)
			return \api\sale\insert();

		case stristr($request, BASE_URL . 'action/sale/select'):
			require_once(__DIR__ . '/api/sale.php'); // CARREGA AS FUNÇÕES DA API DE VENDAS (SELECT)
			return \api\sale\select();

		case stristr($request, BASE_URL . 'action/sale/update'):
			require_once(__DIR__ . '/api/sale.php'); // CARREGA AS FUNÇÕES DA API DE VENDAS (UPDATE)
			return \api\sale\update();
	}
	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE SERVIÇOS
 * @param string $request
 * @return bool
 */
function service(string $request): bool {
	switch(true) {
		case stristr($request, BASE_URL . 'action/service/delete'):
			require_once(__DIR__ . '/api/service.php'); // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (DELETE)
			return \api\service\delete();

		case stristr($request, BASE_URL . 'action/service/insert'):
			require_once(__DIR__ . '/api/service.php'); // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (INSERT)
			return \api\service\insert();

		case stristr($request, BASE_URL . 'action/service/select'):
			require_once(__DIR__ . '/api/service.php'); // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (SELECT)
			return \api\service\select();

		case stristr($request, BASE_URL . 'action/service/update'):
			require_once(__DIR__ . '/api/service.php'); // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (UPDATE)
			return \api\service\update();
	}
	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

budget($_SERVER['REQUEST_URI'] ?? '');
client($_SERVER['REQUEST_URI'] ?? '');
employee($_SERVER['REQUEST_URI'] ?? '');
other($_SERVER['REQUEST_URI'] ?? '');
product($_SERVER['REQUEST_URI'] ?? '');
provider($_SERVER['REQUEST_URI'] ?? '');
purchase($_SERVER['REQUEST_URI'] ?? '');
record($_SERVER['REQUEST_URI'] ?? '');
sale($_SERVER['REQUEST_URI'] ?? '');
service($_SERVER['REQUEST_URI'] ?? '');