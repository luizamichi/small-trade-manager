<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\client;

require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->rg = isset($tuple->rg) ? (strlen($tuple->rg) == 8 ? substr($tuple->rg, 0, 1) . '.' . substr($tuple->rg, 1, 3) . '.' . substr($tuple->rg, 4, 3) . '-' . substr($tuple->rg, 7, 2) : substr($tuple->rg, 0, 2) . '.' . substr($tuple->rg, 2, 3) . '.' . substr($tuple->rg, 5, 3) . '-' . substr($tuple->rg, 8, 2)) : null;
	$tuple->cpf = substr($tuple->cpf, 0, 3) . '.' . substr($tuple->cpf, 3, 3) . '.' . substr($tuple->cpf, 6, 3) . '-' . substr($tuple->cpf, 9, 2);
	$tuple->postal_code = isset($tuple->postal_code) ? substr($tuple->postal_code, 0, 5) . '-' . substr($tuple->postal_code, 5, 3) : null;
	$tuple->phone = isset($tuple->phone) ? '(' . substr($tuple->phone, 0, 2) . ') ' . substr($tuple->phone, 2, 4) . '-' . substr($tuple->phone, 6, 4) : null;
	$tuple->cell_phone = isset($tuple->cell_phone) ? '(' . substr($tuple->cell_phone, 0, 2) . ') ' . substr($tuple->cell_phone, 2, 5) . '-' . substr($tuple->cell_phone, 7, 4) : null;
	$tuple->birthday = substr($tuple->birthday, 8, 2) . '/' . substr($tuple->birthday, 5, 2) . '/' . substr($tuple->birthday, 0, 4);
	$tuple->sex = $tuple->sex == 'M' ? 'Masculino' : ($tuple->sex == 'F' ? 'Feminino' : 'Indefinido');

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


/** VALIDA SE O CLIENTE POSSUI ALGUM ATRIBUTO ÚNICO VINCULADO A OUTRO CLIENTE
 * @param object $tuple
 * @return array<string>
 */
function duplicate(object $tuple): array {
	$problems = [];
	$tuple->id ??= -1;

	$query = 'select * from clients where rg = "' . $tuple->rg . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UM RG VINCULADO A OUTRO CLIENTE
		array_push($problems, 'RG pertence a outro cliente');
	}

	$query = 'select * from clients where cpf = "' . $tuple->cpf . '"' . ($tuple->id > 0 ? ' && id != ' . $tuple->id : '') . ';';
	$operation = \mysql\execute($query);
	if($operation) { // ENCONTROU UM CPF VINCULADO A OUTRO CLIENTE
		array_push($problems, 'CPF pertence a outro cliente');
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

	$array['name'] = filter_input($method, 'name', FILTER_DEFAULT);
	$array['surname'] = filter_input($method, 'surname', FILTER_DEFAULT);

	$array['rg'] = str_replace('.', '', str_replace('-', '', (string) filter_input($method, 'rg', FILTER_SANITIZE_NUMBER_INT)));
	$array['rg'] = is_numeric($array['rg']) ? $array['rg'] : null;

	$array['cpf'] = str_replace('.', '', str_replace('-', '', (string) filter_input($method, 'cpf', FILTER_SANITIZE_NUMBER_INT)));
	$array['cpf'] = is_numeric($array['cpf']) ? $array['cpf'] : null;

	$array['birthday'] = filter_input($method, 'birthday', FILTER_SANITIZE_NUMBER_INT);
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

	$array['sex'] = filter_input($method, 'sex', FILTER_DEFAULT, FILTER_SANITIZE_ADD_SLASHES);
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
	$tuple->rg = isset($tuple->rg) ? (strlen($tuple->rg) == 8 ? substr($tuple->rg, 0, 1) . '.' . substr($tuple->rg, 1, 3) . '.' . substr($tuple->rg, 4, 3) . '-' . substr($tuple->rg, 7, 2) : substr($tuple->rg, 0, 2) . '.' . substr($tuple->rg, 2, 3) . '.' . substr($tuple->rg, 5, 3) . '-' . substr($tuple->rg, 8, 2)) : null;
	$tuple->cpf = substr($tuple->cpf, 0, 3) . '.' . substr($tuple->cpf, 3, 3) . '.' . substr($tuple->cpf, 6, 3) . '-' . substr($tuple->cpf, 9, 2);
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
	$array['name'] = $values['name'] ?? '';
	$array['surname'] = $values['surname'] ?? '';
	$array['rg'] = $values['rg'] ?? null;
	$array['cpf'] = $values['cpf'] ?? '';
	$array['birthday'] = $values['birthday'] ?? date('Y-m-d');
	$array['postal_code'] = $values['postal_code'] ?? null;
	$array['district'] = $values['district'] ?? null;
	$array['city'] = $values['city'] ?? '';
	$array['state'] = $values['state'] ?? '';
	$array['address'] = $values['address'] ?? '';
	$array['number'] = $values['number'] ?? 0;
	$array['complement'] = $values['complement'] ?? null;
	$array['email'] = $values['email'] ?? null;
	$array['phone'] = $values['phone'] ?? null;
	$array['cell_phone'] = $values['cell_phone'] ?? null;
	$array['sex'] = $values['sex'] ?? '';
	$array['note'] = $values['note'] ?? null;

	return $array;
}


/** VALIDA SE O CLIENTE ESTÁ ATENDENDO TODOS OS REQUISITOS EXIGIDOS E RETORNA UMA LISTA COM OS PROBLEMAS
 * @param object $tuple
 * @return array<string>
 */
function validate(object $tuple): array {
	$problems = [];
	if(strlen($tuple->name) < 2 || strlen($tuple->name) > 32) { // INFORMOU UM NOME COM TAMANHO INVÁLIDO
		array_push($problems, 'nome');
	}

	if(strlen($tuple->surname) < 2 || strlen($tuple->surname) > 32) { // INFORMOU UM SOBRENOME COM TAMANHO INVÁLIDO
		array_push($problems, 'sobrenome');
	}

	if(strlen((string) $tuple->rg) != 0 && (!in_array(strlen($tuple->rg), [8, 9]) || !is_numeric($tuple->rg))) { // INFORMOU UM RG COM TAMANHO INVÁLIDO
		array_push($problems, 'RG');
	}

	if(strlen($tuple->cpf) != 11 || !is_numeric($tuple->cpf)) { // INFORMOU UM CPF COM TAMANHO INVÁLIDO
		array_push($problems, 'CPF');
	}

	elseif(strlen($tuple->cpf) == 11) { // INFORMOU UM CPF INVÁLIDO
		$problem = false;
		if(preg_match('/(\d)\1{10}/', $tuple->cpf)) {
			$problem = true;
		}

		for($t = 9; $t < 11; $t++) {
			for($d = 0, $c = 0; $c < $t; $c++) {
				$d += (int) $tuple->cpf[$c] * (($t + 1) - $c);
			}

			$d = ((10 * $d) % 11) % 10;

			if($tuple->cpf[$c] != $d) {
				$problem = true;
			}
		}

		!$problem ?: array_push($problems, 'CPF');
	}

	if(!checkdate((int) substr($tuple->birthday, 5, 2), (int) substr($tuple->birthday, 8, 2), (int) substr($tuple->birthday, 0, 4)) || $tuple->birthday > date('Y-m-d')) { // INFORMOU UMA DATA DE NASCIMENTO INVÁLIDA
		array_push($problems, 'data de nascimento');
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

	if(strlen((string) $tuple->email) != 0 && (strlen($tuple->email) < 4 || strlen($tuple->email) > 32 || !filter_var($tuple->email, FILTER_VALIDATE_EMAIL))) { // INFORMOU UM E-MAIL INVÁLIDO
		array_push($problems, 'e-mail');
	}

	if(strlen((string) $tuple->phone) != 0 && (strlen($tuple->phone) != 10 || !is_numeric($tuple->phone))) { // INFORMOU UM TELEFONE INVÁLIDO
		array_push($problems, 'telefone');
	}

	if(strlen((string) $tuple->cell_phone) != 0 && (strlen($tuple->cell_phone) != 11 || !is_numeric($tuple->cell_phone))) { // INFORMOU UM CELULAR INVÁLIDO
		array_push($problems, 'celular');
	}

	if(!in_array($tuple->sex, ['F', 'M'])) { // INFORMOU UM SEXO INVÁLIDO
		array_push($problems, 'sexo');
	}

	if(strlen((string) $tuple->note) != 0 && (strlen($tuple->note) < 4 || strlen($tuple->note) > 512)) { // INFORMOU UMA OBSERVAÇÃO COM TAMANHO INVÁLIDO
		array_push($problems, 'observação');
	}

	return array_merge($problems, duplicate($tuple));
}
