<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\service;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/../util.php'; // CARREGA AS FUNÇÕES ÚTEIS (STRTOFLOAT)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->price = 'R$ ' . number_format($tuple->price, 2, ',', '.'); // FUNÇÃO DESCONTINUADA - MONEY_FORMAT('%.2N', (FLOAT) $TUPLE->PRICE)
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


/** VALIDA SE O SERVIÇO POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRO SERVIÇO
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$query = 'select * from services where code = "' . $tuple->code . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UM CÓDIGO VINCULADO A OUTRO SERVIÇO
		array_push($problems, 'código pertence a outro serviço');
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
	$array['type'] = filter_input($method, 'type', FILTER_DEFAULT);

	$array['price'] = filter_input($method, 'price', FILTER_DEFAULT);
	$array['price'] = \util\strToFloat($array['price']);
	$array['price'] = is_numeric($array['price']) ? $array['price'] : null;

	$array['workload'] = filter_input($method, 'workload', FILTER_DEFAULT);

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
	$tuple->price = number_format($tuple->price, 2, ',', '.');
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
	$array['type'] = $values['type'] ?? '';
	$array['price'] = $values['price'] ?? 0.0;
	$array['workload'] = $values['workload'] ?? '00:00';

	return $array;
}


/** VALIDA SE O SERVIÇO ESTÁ ATENDENDO TODOS OS REQUISITOS EXIGIDOS E RETORNA UMA LISTA COM OS PROBLEMAS
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

	if(strlen($tuple->type) < 2 || strlen($tuple->type) > 32) { // INFORMOU UM TIPO COM TAMANHO INVÁLIDO
		array_push($problems, 'tipo');
	}

	if(!is_float($tuple->price + 0.0) || $tuple->price <= 0.0) { // INFORMOU UM PREÇO INVÁLIDO
		array_push($problems, 'preço');
	}

	if(strlen($tuple->workload) != 0 && !preg_match('/^(?:2[0-3]|[01]\d):[0-5]\d$/', $tuple->workload)) { // INFORMOU UMA CARGA DE TRABALHO INVÁLIDA
		array_push($problems, 'carga de trabalho');
	}

	return array_merge($problems, duplicate($tuple));
}
