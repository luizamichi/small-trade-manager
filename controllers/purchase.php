<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\purchase;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/../util.php'; // CARREGA AS FUNÇÕES ÚTEIS (STRTOFLOAT)
require_once __DIR__ . '/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (GET)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->total = 'R$ ' . number_format((float) $tuple->total - ((float) $tuple->total * (float) $tuple->discount), 2, ',', '.'); // FUNÇÃO DESCONTINUADA - MONEY_FORMAT('%.2N', (FLOAT) $TUPLE->TOTAL)
	$tuple->discount = number_format($tuple->discount * 100, 2, ',', '.') . '%';
	$tuple->day = substr($tuple->day, 8, 2) . '/' . substr($tuple->day, 5, 2) . '/' . substr($tuple->day, 0, 4) . ' às ' . substr($tuple->day, 11, 8);

	return $tuple;
}


/** CONVERTE O VETOR EM OBJETO
 * @param array<string,mixed> $values
 * @return object
 */
function convert(array $values): object {
	$array = rectify($values);
	return (object) $array;
}


/** VALIDA SE A COMPRA POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRA COMPRA
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$query = 'select * from providers where id = "' . $tuple->provider . '";';
	$operation = \mysql\execute($query);
	if(!$operation) { // NÃO ENCONTROU UM FORNECEDOR
		array_push($problems, 'fornecedor não existe');
	}

	else {
		$query = 'select * from products where provider = ' . $tuple->provider . ';';
		$operation = \mysql\execute($query);
		if($operation) {
			$errors = 0;
			$products = array_column($operation, "id");

			foreach($tuple->products as $product) {
				if(!in_array($product, $products)) {
					$errors += 1;
				}
			}

			if($errors != 0) {
				array_push($problems, $errors . ' produto(s) não pertence(m) ao fornecedor');
			}
		}

		else { // NÃO ENCONTROU PRODUTOS VINCULADOS AO FORNECEDOR
			array_push($problems, 'fornecedor não possui produtos cadastrados');
		}
	}

	if($tuple->id == -1) { // REMOVE O ID, CASO O OBJETO NÃO TINHA QUANDO ENTROU NA FUNÇÃO
		unset($tuple->id);
	}

	return $problems;
}


/** VERIFICA SE HÁ ALGUM ERRO NOS VALORES
 * @param object $tuple
 * @return bool
 */
function errors(object $tuple): bool {
	return (bool) count(validate($tuple));
}


/** FILTRA OS VALORES NECESSÁRIOS PASSADOS POR GET OU POST E RETORNA UM VETOR
 * @param string $method
 * @return array<string,mixed>
 */
function filter(string $method='GET'): array {
	$method = \strtoupper($method) == 'POST' ? INPUT_POST : INPUT_GET;

	$array['all'] = filter_input($method, 'all', FILTER_DEFAULT, FILTER_SANITIZE_ADD_SLASHES);
	$array['id'] = filter_input($method, 'id', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['id'] = is_numeric($array['id']) ? (int) $array['id'] : null;

	$array['provider'] = filter_input($method, 'provider', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['provider'] = is_numeric($array['provider']) ? (int) $array['provider'] : null;

	$array['employee'] = filter_input($method, 'employee', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['employee'] = is_numeric($array['employee']) ? (int) $array['employee'] : (\controller\session\get()['user']->id ?? 0);

	$array['products'] = filter_input($method, 'products', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	$array['products'] = is_array($array['products']) ? $array['products'] : [];

	$array['prices'] = filter_input($method, 'products-prices', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	$array['prices'] = is_array($array['prices']) ? $array['prices'] : [];

	$array['quantities'] = filter_input($method, 'products-quantities', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	$array['quantities'] = is_array($array['quantities']) ? $array['quantities'] : [];

	$array['day'] = filter_input($method, 'day', FILTER_SANITIZE_NUMBER_INT);
	$array['form_of_payment'] = filter_input($method, 'form-of-payment', FILTER_DEFAULT);

	$array['discount'] = filter_input($method, 'discount', FILTER_DEFAULT);
	$array['discount'] = \util\strToFloat($array['discount']);
	$array['discount'] = is_numeric($array['discount']) ? $array['discount'] / 100 : null;

	$array['total'] = filter_input($method, 'total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$array['total'] = \util\strToFloat($array['total']);
	$array['total'] = is_numeric($array['total']) ? $array['total'] : null;

	foreach($array as $index => $value) { // CONVERTE TODOS OS VALORES EM STRING
		if(is_array($value)) {
			$array[$index] = array_map(function(string $item): ?string {
				return trim($item) ?: null;
			}, $value);
		}
		else {
			$array[$index] = trim((string) $value) ?: (is_numeric($array[$index]) ? trim($value) : (is_array($array[$index]) ? $value : null));
		}
	}

	return $array;
}


/** FORMATA OS DADOS PARA UM FORMULÁRIO
 * @param object $tuple
 * @return object
 */
function formulate(object $tuple): object {
	$tuple->discount = number_format($tuple->discount * 100, 2, ',', '.') . '%';
	return $tuple;
}


/** VERIFICA SE HÁ ALGUM VALOR VÁLIDO E RETORNA UM BOOLEANO
 * @param array<string,mixed> $values
 * @return bool
 */
function null(array $values): bool {
	$null = 0;
	foreach($values as $index => $_) {
		if($values[$index] != null) {
			$null++;
		}
	}

	return !$null;
}


/** CORRIGE O VETOR, COMPLEMENTANDO OS ATRIBUTOS QUE ESTÃO FALTANDO
 * @param array<string,mixed> $values
 * @return array<string,mixed>
 */
function rectify(array $values): array {
	$array['id'] = $values['id'] ?? 0;
	$array['provider'] = $values['provider'] ?? 0;
	$array['employee'] = $values['employee'] ?? \controller\session\get()['user']->id ?? 0;
	$array['products'] = $values['products'] ?? [];
	$array['quantities'] = $values['quantities'] ?? [];
	$array['prices'] = $values['prices'] ?? [];
	$array['day'] = $values['day'] ?? date('Y-m-d H:i:s');
	$array['form_of_payment'] = $values['form_of_payment'] ?? '';
	$array['discount'] = $values['discount'] ?? 0.0;
	$array['total'] = $values['total'] ?? 0.0;
	$array = reduce($array);

	return $array;
}


/** UNIFICA AS CHAVES (PRODUTOS) REPETIDAS
 * @param array<string,mixed> $array
 * @return array<string,mixed>
 */
function reduce(array $array): array {
	$products = $quantities = $prices = [];
	$count = min(count($array['products']), count($array['quantities']), count($array['prices']));

	for($i = 0; $i < $count; $i++) {
		if(in_array($array['products'][$i], $products)) {
			$index = array_search($array['products'][$i], $array['products']);
			$quantities[$index] += (int) $array['quantities'][$i];
		}
		else {
			array_push($products, (int) $array['products'][$i]);
			array_push($quantities, (int) $array['quantities'][$i]);
			array_push($prices, (int) $array['prices'][$i]);
		}
	}

	$array['products'] = $products;
	$array['quantities'] = $quantities;
	$array['prices'] = $prices;
	$array['cart'] = array_map(function(int $product, int $quantity, float $price): array {
		return ['product' => $product, 'quantity' => $quantity, 'price' => $price];
	}, $products, $quantities, $prices);

	return $array;
}


/** VALIDA SE A COMPRA ESTÁ ATENDENDO TODOS OS REQUISITOS EXIGIDOS E RETORNA UMA LISTA COM OS PROBLEMAS
 * @param object $tuple
 * @return array<string>
 */
function validate(object $tuple): array {
	$problems = [];
	if(!is_int($tuple->provider + 0) || $tuple->provider <= 0) { // INFORMOU UM FORNECEDOR INVÁLIDO
		array_push($problems, 'fornecedor');
	}

	if(!is_int($tuple->employee + 0) || $tuple->employee <= 0) { // INFORMOU UM FUNCIONÁRIO INVÁLIDO
		array_push($problems, 'funcionário');
	}

	if(!is_array($tuple->products) || count($tuple->products) == 0) { // INFORMOU UM PRODUTO INVÁLIDO
		array_push($problems, 'produtos');
	}

	if(!is_array($tuple->quantities) || count($tuple->quantities) == 0) { // INFORMOU UMA QUANTIDADE INVÁLIDA
		array_push($problems, 'quantidades');
	}

	if(!is_array($tuple->prices) || count($tuple->prices) == 0) { // INFORMOU UM PREÇO INVÁLIDO
		array_push($problems, 'preços');
	}

	if(!checkdate((int) substr($tuple->day, 5, 2), (int) substr($tuple->day, 8, 2), (int) substr($tuple->day, 0, 4))) { // INFORMOU UMA DATA INVÁLIDA
		array_push($problems, 'data');
	}

	if(!in_array($tuple->form_of_payment, ['A prazo', 'Cartão de crédito', 'Cartão de débito', 'Dinheiro', 'DOC', 'PIX', 'TED'])) { // INFORMOU UMA FORMA DE PAGAMENTO INVÁLIDA
		array_push($problems, 'forma de pagamento');
	}

	if(!is_float($tuple->discount + 0.0) || $tuple->discount < 0.0 || $tuple->discount > 100.0) { // INFORMOU UM DESCONTO INVÁLIDO
		array_push($problems, 'desconto');
	}

	if(!is_float($tuple->total + 0.0) || $tuple->total <= 0.0) { // INFORMOU UM TOTAL INVÁLIDO
		array_push($problems, 'total');
	}

	return array_merge($problems, duplicate($tuple));
}
