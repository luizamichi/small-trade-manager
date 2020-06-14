<?php
//--// ---------- //--//
//--// API ROUTES //--//
//--// ---------- //--//

namespace routes;

require_once __DIR__ . '/config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (BASE_URL)


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE ORÇAMENTOS
 * @param string $request
 * @return bool
 */
function budget(string $request): bool {
	return import(__DIR__ . '/api/budget.php', $request, [
		'action/budget/delete' => fn(): bool => \api\budget\delete(), // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (DELETE)
		'action/budget/insert' => fn(): bool => \api\budget\insert(), // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (INSERT)
		'action/budget/select' => fn(): bool => \api\budget\select(), // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (SELECT)
		'action/budget/update' => fn(): bool => \api\budget\update() // CARREGA AS FUNÇÕES DA API DE ORÇAMENTOS (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE CLIENTES
 * @param string $request
 * @return bool
 */
function client(string $request): bool {
	return import(__DIR__ . '/api/client.php', $request, [
		'action/client/delete' => fn(): bool => \api\client\delete(), // CARREGA AS FUNÇÕES DA API DE CLIENTES (DELETE)
		'action/client/insert' => fn(): bool => \api\client\insert(), // CARREGA AS FUNÇÕES DA API DE CLIENTES (INSERT)
		'action/client/select' => fn(): bool => \api\client\select(), // CARREGA AS FUNÇÕES DA API DE CLIENTES (SELECT)
		'action/client/update' => fn(): bool => \api\client\update() // CARREGA AS FUNÇÕES DA API DE CLIENTES (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE FUNCIONÁRIOS
 * @param string $request
 * @return bool
 */
function employee(string $request): bool {
	return import(__DIR__ . '/api/employee.php', $request, [
		'action/employee/delete' => fn(): bool => \api\employee\delete(), // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (DELETE)
		'action/employee/insert' => fn(): bool => \api\employee\insert(), // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (INSERT)
		'action/employee/select' => fn(): bool => \api\employee\select(), // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (SELECT)
		'action/employee/update' => fn(): bool => \api\employee\update() // CARREGA AS FUNÇÕES DA API DE FUNCIONÁRIOS (UPDATE)
	]);
}


/** INCLUI O ARQUIVO SOLICITADO E REALIZA A CHAMADA DA FUNÇÃO SE O ENDPOINT DA REQUISIÇÃO FOR ENCONTRADO
 * @param string $path
 * @param string $request
 * @param array<string,callable> $cases
 * @return bool
 */
function import(string $path, string $request, array $cases): bool {
	$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	foreach($cases as $endpoint => $callback) {
		if(stristr($request, BASE_URL . $endpoint)) {
			require_once $path;
			return $callback();
		}
	}

	return false;
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE ITENS DIVERSOS
 * @param string $request
 * @return bool
 */
function other(string $request): bool {
	return import(__DIR__ . '/api/other.php', $request, [
		'action/setting/manage' => fn(): bool => \api\other\setting(), // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (SETTING)
		'action/user/authenticate' => fn(): bool => \api\other\authenticate(), // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (AUTHENTICATE)
		'action/user/unauthenticate' => fn(): bool => \api\other\unauthenticate() // CARREGA AS FUNÇÕES DA API DE DIVERSIDADES (UNAUTHENTICATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE PRODUTOS
 * @param string $request
 * @return bool
 */
function product(string $request): bool {
	return import(__DIR__ . '/api/product.php', $request, [
		'action/product/delete' => fn(): bool => \api\product\delete(), // CARREGA AS FUNÇÕES DA API DE PRODUTOS (DELETE)
		'action/product/insert' => fn(): bool => \api\product\insert(), // CARREGA AS FUNÇÕES DA API DE PRODUTOS (INSERT)
		'action/product/select' => fn(): bool => \api\product\select(), // CARREGA AS FUNÇÕES DA API DE PRODUTOS (SELECT)
		'action/product/update' => fn(): bool => \api\product\update(), // CARREGA AS FUNÇÕES DA API DE PRODUTOS (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE FORNECEDORES
 * @param string $request
 * @return bool
 */
function provider(string $request): bool {
	return import(__DIR__ . '/api/provider.php', $request, [
		'action/provider/delete' => fn(): bool => \api\provider\delete(), // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (DELETE)
		'action/provider/insert' => fn(): bool => \api\provider\insert(), // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (INSERT)
		'action/provider/select' => fn(): bool => \api\provider\select(), // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (SELECT)
		'action/provider/update' => fn(): bool => \api\provider\update() // CARREGA AS FUNÇÕES DA API DE FORNECEDORES (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE COMPRAS
 * @param string $request
 * @return bool
 */
function purchase(string $request): bool {
	return import(__DIR__ . '/api/purchase.php', $request, [
		'action/purchase/delete' => fn(): bool => \api\purchase\delete(), // CARREGA AS FUNÇÕES DA API DE COMPRAS (DELETE)
		'action/purchase/insert' => fn(): bool => \api\purchase\insert(), // CARREGA AS FUNÇÕES DA API DE COMPRAS (INSERT)
		'action/purchase/select' => fn(): bool => \api\purchase\select(), // CARREGA AS FUNÇÕES DA API DE COMPRAS (SELECT)
		'action/purchase/update' => fn(): bool => \api\purchase\update() // CARREGA AS FUNÇÕES DA API DE COMPRAS (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE REGISTROS
 * @param string $request
 * @return bool
 */
function record(string $request): bool {
	return import(__DIR__ . '/api/record.php', $request, [
		'action/record/select' => fn(): bool => \api\record\select() // CARREGA AS FUNÇÕES DA API DE REGISTROS (SELECT)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE VENDAS
 * @param string $request
 * @return bool
 */
function sale(string $request): bool {
	return import(__DIR__ . '/api/sale.php', $request, [
		'action/sale/delete' => fn(): bool => \api\sale\delete(), // CARREGA AS FUNÇÕES DA API DE VENDAS (DELETE)
		'action/sale/insert' => fn(): bool => \api\sale\insert(), // CARREGA AS FUNÇÕES DA API DE VENDAS (INSERT)
		'action/sale/select' => fn(): bool => \api\sale\select(), // CARREGA AS FUNÇÕES DA API DE VENDAS (SELECT)
		'action/sale/update' => fn(): bool => \api\sale\update() // CARREGA AS FUNÇÕES DA API DE VENDAS (UPDATE)
	]);
}


/** ROTAS PARA A REALIZAÇÃO DO CRUD DE SERVIÇOS
 * @param string $request
 * @return bool
 */
function service(string $request): bool {
	return import(__DIR__ . '/api/service.php', $request, [
		'action/service/delete' => fn(): bool => \api\service\delete(), // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (DELETE)
		'action/service/insert' => fn(): bool => \api\service\insert(), // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (INSERT)
		'action/service/select' => fn(): bool => \api\service\select(), // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (SELECT)
		'action/service/update' => fn(): bool => \api\service\update() // CARREGA AS FUNÇÕES DA API DE SERVIÇOS (UPDATE)
	]);
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
