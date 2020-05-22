<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\provider;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->state_registration = isset($tuple->state_registration) ? substr($tuple->state_registration, 0, 3) . '.' . substr($tuple->state_registration, 3, 3) . '.' . substr($tuple->state_registration, 6, 3) . '.' . substr($tuple->state_registration, 9, 3) : null;
	$tuple->cnpj = substr($tuple->cnpj, 0, 2) . '.' . substr($tuple->cnpj, 2, 3) . '.' . substr($tuple->cnpj, 5, 3) . '/' . substr($tuple->cnpj, 8, 4) . '-' . substr($tuple->cnpj, 12, 2);
	$tuple->postal_code = isset($tuple->postal_code) ? substr($tuple->postal_code, 0, 5) . '-' . substr($tuple->postal_code, 5, 3) : null;
	$tuple->phone = '(' . substr($tuple->phone, 0, 2) . ') ' . substr($tuple->phone, 2, 4) . '-' . substr($tuple->phone, 6, 4);
	$tuple->cell_phone = isset($tuple->cell_phone) ? '(' . substr($tuple->cell_phone, 0, 2) . ') ' . substr($tuple->cell_phone, 2, 5) . '-' . substr($tuple->cell_phone, 7, 4) : null;
	$tuple->foundation_date = substr($tuple->foundation_date, 8, 2) . '/' . substr($tuple->foundation_date, 5, 2) . '/' . substr($tuple->foundation_date, 0, 4);

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


/** VALIDA SE O FORNECEDOR POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRO FORNECEDOR
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$query = 'select * from providers where state_registration = "' . $tuple->state_registration . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UMA INSCRIÇÃO ESTADUAL VINCULADA A OUTRO FORNECEDOR
		array_push($problems, 'inscrição estadual pertence a outro fornecedor');
	}

	$query = 'select * from providers where cnpj = "' . $tuple->cnpj . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UM CNPJ VINCULADO A OUTRO FORNECEDOR
		array_push($problems, 'CNPJ pertence a outro fornecedor');
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

	$array['company_name'] = filter_input($method, 'company-name', FILTER_DEFAULT);
	$array['fantasy_name'] = filter_input($method, 'fantasy-name', FILTER_DEFAULT);

	$array['state_registration'] = str_replace('.', '', (string) filter_input($method, 'state-registration', FILTER_SANITIZE_NUMBER_INT));
	$array['state_registration'] = is_numeric($array['state_registration']) ? $array['state_registration'] : null;

	$array['cnpj'] = str_replace('.', '', str_replace('-', '', str_replace('/', '', (string) filter_input($method, 'cnpj', FILTER_SANITIZE_NUMBER_INT))));
	$array['cnpj'] = is_numeric($array['cnpj']) ? $array['cnpj'] : null;

	$array['foundation_date'] = filter_input($method, 'foundation-date', FILTER_SANITIZE_NUMBER_INT);
	$array['postal_code'] = str_replace('-', '', (string) filter_input($method, 'postal-code', FILTER_SANITIZE_NUMBER_INT));
	$array['district'] = filter_input($method, 'district', FILTER_DEFAULT);
	$array['city'] = filter_input($method, 'city', FILTER_DEFAULT);
	$array['state'] = filter_input($method, 'state', FILTER_DEFAULT);
	$array['address'] = filter_input($method, 'address', FILTER_DEFAULT);

	$array['number'] = filter_input($method, 'number', FILTER_SANITIZE_NUMBER_INT);
	$array['number'] = is_numeric($array['number']) ? (int) $array['number'] : null;

	$array['complement'] = filter_input($method, 'complement', FILTER_DEFAULT);
	$array['email'] = filter_input($method, 'email', FILTER_DEFAULT, FILTER_SANITIZE_ADD_SLASHES);

	$array['phone'] = str_replace('(', '', str_replace(')', '', str_replace('-', '', (string) filter_input($method, 'phone', FILTER_SANITIZE_NUMBER_INT))));
	$array['phone'] = is_numeric($array['phone']) ? $array['phone'] : null;

	$array['cell_phone'] = str_replace('(', '', str_replace(')', '', str_replace('-', '', (string) filter_input($method, 'cell-phone', FILTER_SANITIZE_NUMBER_INT))));
	$array['cell_phone'] = is_numeric($array['cell_phone']) ? $array['cell_phone'] : null;

	$array['note'] = filter_input($method, 'note', FILTER_DEFAULT);

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
	$tuple->state_registration = isset($tuple->state_registration) ? substr($tuple->state_registration, 0, 3) . '.' . substr($tuple->state_registration, 3, 3) . '.' . substr($tuple->state_registration, 6, 3) . '.' . substr($tuple->state_registration, 9, 3) : null;
	$tuple->cnpj = isset($tuple->cnpj) ? substr($tuple->cnpj, 0, 2) . '.' . substr($tuple->cnpj, 2, 3) . '.' . substr($tuple->cnpj, 5, 3) . '/' . substr($tuple->cnpj, 8, 4) . '-' . substr($tuple->cnpj, 12, 2) : null;
	$tuple->postal_code = isset($tuple->postal_code) ? substr($tuple->postal_code, 0, 5) . '-' . substr($tuple->postal_code, 5, 3) : null;
	$tuple->phone = isset($tuple->phone) ? '(' . substr($tuple->phone, 0, 2) . ') ' . substr($tuple->phone, 2, 4) . '-' . substr($tuple->phone, 6, 4) : null;
	$tuple->cell_phone = isset($tuple->cell_phone) ? '(' . substr($tuple->cell_phone, 0, 2) . ') ' . substr($tuple->cell_phone, 2, 5) . '-' . substr($tuple->cell_phone, 7, 4) : null;

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
	$array['company_name'] = $values['company_name'] ?? '';
	$array['fantasy_name'] = $values['fantasy_name'] ?? '';
	$array['state_registration'] = $values['state_registration'] ?? null;
	$array['cnpj'] = $values['cnpj'] ?? '';
	$array['foundation_date'] = $values['foundation_date'] ?? date('Y-m-d');
	$array['postal_code'] = $values['postal_code'] ?? null;
	$array['district'] = $values['district'] ?? null;
	$array['city'] = $values['city'] ?? '';
	$array['state'] = $values['state'] ?? '';
	$array['address'] = $values['address'] ?? '';
	$array['number'] = $values['number'] ?? 0;
	$array['complement'] = $values['complement'] ?? null;
	$array['email'] = $values['email'] ?? '';
	$array['phone'] = $values['phone'] ?? '';
	$array['cell_phone'] = $values['cell_phone'] ?? null;
	$array['note'] = $values['note'] ?? null;

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

	if(strlen((string) $tuple->state_registration) != 0 && (strlen($tuple->state_registration) != 12 || !is_numeric($tuple->state_registration))) { // INFORMOU UMA INSCRIÇÃO ESTADUAL COM TAMANHO INVÁLIDO
		array_push($problems, 'inscrição estadual');
	}

	if(strlen($tuple->cnpj) != 0 && (strlen($tuple->cnpj) != 14 || !is_numeric($tuple->cnpj))) { // INFORMOU UM CNPJ COM TAMANHO INVÁLIDO
		array_push($problems, 'CNPJ');
	}

	elseif(strlen($tuple->cnpj) == 14) { // INFORMOU UM CNPJ INVÁLIDO
		$problem = false;
		if(preg_match('/(\d)\1{10}/', $tuple->cnpj)) {
			$problem = true;
		}

		for($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
			$soma += $tuple->cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;
		if($tuple->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
			$problem = true;
		}

		for($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
			$soma += $tuple->cnpj[$i] * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;
		if($tuple->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto)) {
			$problem = false;
		}

		!$problem ?: array_push($problems, 'CNPJ');
	}

	if(!checkdate((int) substr($tuple->foundation_date, 5, 2), (int) substr($tuple->foundation_date, 8, 2), (int) substr($tuple->foundation_date, 0, 4))) { // INFORMOU UMA DATA DE FUNDAÇÃO INVÁLIDA
		array_push($problems, 'data de fundação');
	}

	if(strlen((string) $tuple->postal_code) != 0 && (strlen($tuple->postal_code) != 8 || !is_numeric($tuple->postal_code))) { // INFORMOU UM CEP COM TAMANHO INVÁLIDO
		array_push($problems, 'CEP');
	}

	if(strlen((string) $tuple->district) != 0 && (strlen($tuple->district) < 4 || strlen($tuple->district) > 32)) { // INFORMOU UM BAIRRO COM TAMANHO INVÁLIDO
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

	if(strlen((string) $tuple->complement) != 0 && (strlen($tuple->complement) < 4 || strlen($tuple->complement) > 32)) { // INFORMOU UM COMPLEMENTO COM TAMANHO INVÁLIDO
		array_push($problems, 'complemento');
	}

	if(strlen($tuple->email) < 4 || strlen($tuple->email) > 32 || !filter_var($tuple->email, FILTER_VALIDATE_EMAIL)) { // INFORMOU UM E-MAIL INVÁLIDO
		array_push($problems, 'e-mail');
	}

	if(strlen($tuple->phone) != 10 || !is_numeric($tuple->phone)) { // INFORMOU UM TELEFONE INVÁLIDO
		array_push($problems, 'telefone');
	}

	if(strlen((string) $tuple->cell_phone) != 0 && (strlen($tuple->cell_phone) != 11 || !is_numeric($tuple->cell_phone))) { // INFORMOU UM CELULAR INVÁLIDO
		array_push($problems, 'celular');
	}

	if(strlen((string) $tuple->note) != 0 && (strlen($tuple->note) < 4 || strlen($tuple->note) > 512)) { // INFORMOU UMA OBSERVAÇÃO COM TAMANHO INVÁLIDO
		array_push($problems, 'observação');
	}

	return array_merge($problems, duplicate($tuple));
}
