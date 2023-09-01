<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\product;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/../util.php'; // CARREGA AS FUNÇÕES ÚTEIS (STRTOFLOAT)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->gross_price = 'R$ ' . number_format($tuple->gross_price, 2, ',', '.'); // FUNÇÃO DESCONTINUADA - MONEY_FORMAT('%.2N', (FLOAT) $TUPLE->GROSS_PRICE)
	$tuple->net_price = 'R$ ' . number_format($tuple->net_price, 2, ',', '.'); // FUNÇÃO DESCONTINUADA - MONEY_FORMAT('%.2N', (FLOAT) $TUPLE->NET_PRICE)
	$tuple->minimum_stock = number_format($tuple->minimum_stock, 0, '.', '.');
	$tuple->maximum_stock = number_format($tuple->maximum_stock, 0, '.', '.');
	$tuple->amount = number_format($tuple->amount, 0, '.', '.');
	$tuple->weigth = number_format($tuple->weigth, 3, ',', '.');
	$tuple->situation = $tuple->situation == 0 ? 'Inativo' : 'Ativo';
	$tuple->source = $tuple->source == 0 ? 'Nacional' : 'Internacional';

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


/** VALIDA SE O PRODUTO POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRO PRODUTO
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$query = 'select * from products where code = "' . $tuple->code . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UM CÓDIGO VINCULADO A OUTRO PRODUTO
		array_push($problems, 'código pertence a outro produto');
	}

	$query = 'select * from providers where id = "' . $tuple->provider . '";';
	$operation = \mysql\execute($query);
	if(!$operation) { // NÃO ENCONTROU UM FORNECEDOR
		array_push($problems, 'fornecedor não existe');
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

	$array['code'] = filter_input($method, 'code', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['code'] = is_numeric($array['code']) ? (int) $array['code'] : null;

	$array['name'] = filter_input($method, 'name', FILTER_DEFAULT);
	$array['provider'] = filter_input($method, 'provider', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['provider'] = is_numeric($array['provider']) ? (int) $array['provider'] : null;

	$array['unity'] = filter_input($method, 'unity', FILTER_DEFAULT);

	$array['gross_price'] = filter_input($method, 'gross-price', FILTER_DEFAULT);
	$array['gross_price'] = \util\strToFloat($array['gross_price']);
	$array['gross_price'] = is_numeric($array['gross_price']) ? $array['gross_price'] : null;

	$array['net_price'] = filter_input($method, 'net-price', FILTER_DEFAULT);
	$array['net_price'] = \util\strToFloat($array['net_price']);
	$array['net_price'] = is_numeric($array['net_price']) ? $array['net_price'] : null;

	$array['minimum_stock'] = filter_input($method, 'minimum-stock', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['minimum_stock'] = is_numeric($array['minimum_stock']) ? (int) $array['minimum_stock'] : null;

	$array['maximum_stock'] = filter_input($method, 'maximum-stock', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['maximum_stock'] = is_numeric($array['maximum_stock']) ? (int) $array['maximum_stock'] : null;

	$array['amount'] = filter_input($method, 'amount', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['amount'] = is_numeric($array['amount']) ? (int) $array['amount'] : null;

	$array['weigth'] = filter_input($method, 'weigth', FILTER_DEFAULT);
	$array['weigth'] = \util\strToFloat($array['weigth']);
	$array['weigth'] = is_numeric($array['weigth']) ? $array['weigth'] : null;

	$array['situation'] = filter_input($method, 'situation', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['situation'] = is_numeric($array['situation']) ? (int) $array['situation'] : null;

	$array['source'] = filter_input($method, 'source', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['source'] = is_numeric($array['source']) ? (int) $array['source'] : null;

	foreach($array as $index => $value) { // CONVERTE TODOS OS VALORES EM STRING
		$array[$index] = trim((string) $value) ?: (is_numeric($array[$index]) ? trim($value) : null);
	}

	return $array;
}


/** FORMATA OS DADOS PARA UM FORMULÁRIO
 * @param object $tuple
 * @return object
 */
function formulate(object $tuple): object {
	$tuple->gross_price = number_format($tuple->gross_price, 2, ',', '.');
	$tuple->net_price = number_format($tuple->net_price, 2, ',', '.');
	$tuple->minimum_stock = number_format($tuple->minimum_stock, 0, '.', '.');
	$tuple->maximum_stock = number_format($tuple->maximum_stock, 0, '.', '.');
	$tuple->amount = number_format($tuple->amount, 0, '.', '.');
	$tuple->weigth = number_format($tuple->weigth, 3, ',', '.');

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
	$array['code'] = $values['code'] ?? 0;
	$array['name'] = $values['name'] ?? '';
	$array['provider'] = $values['provider'] ?? 0;
	$array['unity'] = $values['unity'] ?? '';
	$array['gross_price'] = $values['gross_price'] ?? 0.0;
	$array['net_price'] = $values['net_price'] ?? 0.0;
	$array['minimum_stock'] = $values['minimum_stock'] ?? 0;
	$array['maximum_stock'] = $values['maximum_stock'] ?? 0;
	$array['amount'] = $values['amount'] ?? 0;
	$array['weigth'] = $values['weigth'] ?? 0.0;
	$array['situation'] = $values['situation'] ?? 0;
	$array['source'] = $values['source'] ?? 0;

	return $array;
}


/** VALIDA SE O PRODUTO ESTÁ ATENDENDO TODOS OS REQUISITOS EXIGIDOS E RETORNA UMA LISTA COM OS PROBLEMAS
 * @param object $tuple
 * @return array<string>
 */
function validate(object $tuple): array {
	$problems = [];
	if(!is_int($tuple->code + 0) || $tuple->code <= 0) { // INFORMOU UM CÓDIGO INVÁLIDO
		array_push($problems, 'código');
	}

	if(strlen($tuple->name) < 2 || strlen($tuple->name) > 32) { // INFORMOU UM NOME COM TAMANHO INVÁLIDO
		array_push($problems, 'nome');
	}

	if(!is_int($tuple->provider + 0) || $tuple->provider <= 0) { // INFORMOU UM FORNECEDOR INVÁLIDO
		array_push($problems, 'fornecedor');
	}

	if(strlen($tuple->unity) < 1 || strlen($tuple->unity) > 4) { // INFORMOU UMA UNIDADE COM TAMANHO INVÁLIDO
		array_push($problems, 'unidade');
	}

	if(!is_float($tuple->gross_price + 0.0) || $tuple->gross_price <= 0.0 || $tuple->gross_price > $tuple->net_price) { // INFORMOU UM PREÇO BRUTO INVÁLIDO
		array_push($problems, 'preço bruto');
	}

	if(!is_float($tuple->net_price + 0.0) || $tuple->net_price <= 0.0 || $tuple->net_price < $tuple->gross_price) { // INFORMOU UM PREÇO LÍQUIDO INVÁLIDO
		array_push($problems, 'preço líquido');
	}

	if(!is_int($tuple->minimum_stock + 0) || $tuple->minimum_stock < 0) { // INFORMOU UM ESTOQUE MÍNIMO INVÁLIDO
		array_push($problems, 'estoque mínimo');
	}

	if(!is_int($tuple->maximum_stock + 0) || $tuple->maximum_stock < 0) { // INFORMOU UM ESTOQUE MÁXIMO INVÁLIDO
		array_push($problems, 'estoque máximo');
	}

	if(!is_int($tuple->amount + 0) || $tuple->amount < 0) { // INFORMOU UMA QUANTIDADE INVÁLIDA
		array_push($problems, 'quantidade');
	}

	if(!is_float($tuple->weigth + 0.0) || $tuple->weigth <= 0) { // INFORMOU UM PESO INVÁLIDO
		array_push($problems, 'peso');
	}

	if(!in_array($tuple->situation, [0, 1])) { // INFORMOU UMA SITUAÇÃO INVÁLIDA
		array_push($problems, 'situação');
	}

	if(!in_array($tuple->source, [0, 1])) { // INFORMOU UMA ORIGEM INVÁLIDA
		array_push($problems, 'origem');
	}

	return array_merge($problems, duplicate($tuple));
}
