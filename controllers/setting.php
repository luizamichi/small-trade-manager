<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\setting;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->cnpj = substr($tuple->cnpj, 0, 2) . '.' . substr($tuple->cnpj, 2, 3) . '.' . substr($tuple->cnpj, 5, 3) . '/' . substr($tuple->cnpj, 8, 4) . '-' . substr($tuple->cnpj, 12, 2);
	$tuple->postal_code = isset($tuple->postal_code) ? substr($tuple->postal_code, 0, 5) . '-' . substr($tuple->postal_code, 5, 3) : null;
	$tuple->phone = '(' . substr($tuple->phone, 0, 2) . ') ' . substr($tuple->phone, 2, 4) . '-' . substr($tuple->phone, 6, 4);

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


/** VALIDA SE A OPÇÃO POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRA OPÇÃO
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$operation = load();
	if($operation && $tuple->id > 0 && $tuple->id != $operation->id) { // ENCONTROU UMA OPÇÃO COM O ID DIFERENTE
		array_push($problems, 'identificador inexistente');
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

	$array['id'] = filter_input($method, 'id', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['id'] = is_numeric($array['id']) ? (int) $array['id'] : null;

	$array['company_name'] = filter_input($method, 'company-name', FILTER_DEFAULT);
	$array['fantasy_name'] = filter_input($method, 'fantasy-name', FILTER_DEFAULT);

	$array['cnpj'] = str_replace('.', '', str_replace('-', '', str_replace('/', '', (string) filter_input($method, 'cnpj', FILTER_SANITIZE_NUMBER_INT))));
	$array['cnpj'] = is_numeric($array['cnpj']) ? $array['cnpj'] : null;

	$array['postal_code'] = str_replace('-', '', (string) filter_input($method, 'postal-code', FILTER_SANITIZE_NUMBER_INT));
	$array['district'] = filter_input($method, 'district', FILTER_DEFAULT);
	$array['city'] = filter_input($method, 'city', FILTER_DEFAULT);
	$array['state'] = filter_input($method, 'state', FILTER_DEFAULT);
	$array['address'] = filter_input($method, 'address', FILTER_DEFAULT);

	$array['number'] = filter_input($method, 'number', FILTER_SANITIZE_NUMBER_INT);
	$array['number'] = is_numeric($array['number']) ? (int) $array['number'] : null;

	$array['email'] = filter_input($method, 'email', FILTER_DEFAULT, FILTER_SANITIZE_ADD_SLASHES);

	$array['phone'] = str_replace('(', '', str_replace(')', '', str_replace('-', '', (string) filter_input($method, 'phone', FILTER_SANITIZE_NUMBER_INT))));
	$array['phone'] = is_numeric($array['phone']) ? (int) $array['phone'] : null;

	$array['website'] = filter_input($method, 'website', FILTER_DEFAULT, FILTER_SANITIZE_URL);

	foreach($array as $index => $value) { // CONVERTE TODOS OS VALORES EM STRING
		$array[$index] = trim((string) $value) ?: null;
	}

	return $array;
}


/** FORMATA OS DADOS PARA UM FORMULÁRIO
 * @param object $tuple
 * @return object
 */
function formulate(object $tuple): object {
	$tuple->cnpj = isset($tuple->cnpj) ? substr($tuple->cnpj, 0, 2) . '.' . substr($tuple->cnpj, 2, 3) . '.' . substr($tuple->cnpj, 5, 3) . '/' . substr($tuple->cnpj, 8, 4) . '-' . substr($tuple->cnpj, 12, 2) : null;
	$tuple->postal_code = isset($tuple->postal_code) ? substr($tuple->postal_code, 0, 5) . '-' . substr($tuple->postal_code, 5, 3) : null;
	$tuple->phone = isset($tuple->phone) ? '(' . substr($tuple->phone, 0, 2) . ') ' . substr($tuple->phone, 2, 4) . '-' . substr($tuple->phone, 6, 4) : null;

	return $tuple;
}


/** BUSCA A ÚLTIMA TUPLA NO BANCO DE DADOS
 * @return ?object
 */
function load(int $id=0): ?object {
	$query = 'select * from settings ' . ($id > 0 ? 'where id = ' . $id : '') . ' order by id desc;';
	$operation = \mysql\execute($query);

	return isset($operation[0]) ? beautify($operation[0]) : null;
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
	$array['company_name'] = $values['company_name'] ?? '';
	$array['fantasy_name'] = $values['fantasy_name'] ?? '';
	$array['cnpj'] = $values['cnpj'] ?? '';
	$array['postal_code'] = $values['postal_code'] ?? '';
	$array['district'] = $values['district'] ?? '';
	$array['city'] = $values['city'] ?? '';
	$array['state'] = $values['state'] ?? '';
	$array['address'] = $values['address'] ?? '';
	$array['number'] = $values['number'] ?? 0;
	$array['email'] = $values['email'] ?? null;
	$array['phone'] = $values['phone'] ?? '';
	$array['website'] = $values['website'] ?? null;

	return $array;
}


/** VALIDA SE O FORNECEDOR ESTÁ ATENDENDO TODOS OS REQUISITOS EXIGIDOS E RETORNA UMA LISTA COM OS PROBLEMAS
 * @param object $tuple
 * @return array<string>
 */
function validate(object $tuple): array {
	$problems = [];
	if(strlen($tuple->company_name) < 2 || strlen($tuple->company_name) > 32) { // INFORMOU UMA RAZÃO SOCIAL COM TAMANHO INVÁLIDO
		array_push($problems, 'razão social');
	}

	if(strlen($tuple->fantasy_name) < 2 || strlen($tuple->fantasy_name) > 64) { // INFORMOU UM NOME FANTASIA COM TAMANHO INVÁLIDO
		array_push($problems, 'nome fantasia');
	}

	if(strlen($tuple->cnpj) != 14 || !is_numeric($tuple->cnpj)) { // INFORMOU UM CNPJ COM TAMANHO INVÁLIDO
		array_push($problems, 'CNPJ');
	}

	elseif(strlen($tuple->cnpj) == 14) { // INFORMOU UM CNPJ INVÁLIDO
		$problem = false;
		if(preg_match('/(\d)\1{10}/', $tuple->cnpj)) {
			$problem = true;
		}

		for($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
			$sum += (int) $tuple->cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}
		$resto = $sum % 11;
		if($tuple->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
			$problem = true;
		}

		for($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
			$sum += (int) $tuple->cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $sum % 11;
		if($tuple->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto)) {
			$problem = false;
		}
		!$problem ?: array_push($problems, 'CNPJ');
	}

	if(strlen($tuple->postal_code) != 8 || !is_numeric($tuple->postal_code)) { // INFORMOU UM CEP COM TAMANHO INVÁLIDO
		array_push($problems, 'CEP');
	}

	if(strlen($tuple->district) < 4 || strlen($tuple->district) > 32) { // INFORMOU UM BAIRRO COM TAMANHO INVÁLIDO
		array_push($problems, 'bairro');
	}

	if(strlen($tuple->city) < 4 || strlen($tuple->city) > 64) { // INFORMOU UMA CIDADE COM TAMANHO INVÁLIDO
		array_push($problems, 'cidade');
	}

	if(!in_array($tuple->state, ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'])) { // INFORMOU UM ESTADO INVÁLIDO
		array_push($problems, 'estado');
	}

	if(strlen($tuple->address) < 4 || strlen($tuple->address) > 64) { // INFORMOU UM ENDEREÇO COM TAMANHO INVÁLIDO
		array_push($problems, 'endereço');
	}

	if(strlen($tuple->number) < 2 || strlen($tuple->number) > 8 || !is_numeric($tuple->number)) { // INFORMOU UM NÚMERO INVÁLIDO
		array_push($problems, 'número');
	}

	if(strlen((string) $tuple->email) != 0 && (strlen($tuple->email) < 4 || strlen($tuple->email) > 32 || !filter_var($tuple->email, FILTER_VALIDATE_EMAIL))) { // INFORMOU UM E-MAIL INVÁLIDO
		array_push($problems, 'e-mail');
	}

	if(strlen($tuple->phone) != 10 || !is_numeric($tuple->phone)) { // INFORMOU UM TELEFONE INVÁLIDO
		array_push($problems, 'telefone');
	}

	if(strlen((string) $tuple->website) != 0 && (strlen($tuple->website) < 8 || strlen($tuple->website) > 32 || !filter_var($tuple->website, FILTER_VALIDATE_URL))) { // INFORMOU UM E-MAIL INVÁLIDO
		array_push($problems, 'website');
	}

	return array_merge($problems, duplicate($tuple));
}
