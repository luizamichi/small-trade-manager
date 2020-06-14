<?php
//--// ------------------ //--//
//--// AUXILIAR FUNCTIONS //--//
//--// ------------------ //--//

namespace controller\record;


/** FORMATA OS DADOS DE FORMA MAIS BONITA PARA EXIBIÇÃO EM TELA
 * @param object $tuple
 * @return object
 */
function beautify(object $tuple): object {
	$tuple->action = $tuple->action == 'insert' ? 'Cadastro' : ($tuple->action == 'update' ? 'Alteração' : 'Remoção');
	$tuple->entity = ['budgets' => 'Orçamentos', 'clients' => 'Clientes', 'employees' => 'Funcionários', 'products' => 'Produtos', 'providers' => 'Fornecedores', 'purchases' => 'Compras', 'sales' => 'Vendas', 'settings' => 'Configurações', 'services' => 'Serviços'][$tuple->entity] ?? $tuple->entity;

	if($tuple->description) {
		$description = unserialize($tuple->description);
		$attributes = ['action' => 'Ação', 'address' => 'Endereço', 'alias' => 'Apelido', 'amount' => 'Quantidade', 'birthday' => 'Data de Nascimento', 'budget' => 'Orçamento', 'cart' => 'Carrinho', 'cell_phone' => 'Celular', 'city' => 'Cidade', 'client' => 'Cliente', 'cnpj' => 'CNPJ', 'code' => 'Código', 'company_name' => 'Razão Social', 'complement' => 'Complemento', 'cpf' => 'CPF', 'day' => 'Dia', 'description' => 'Descrição', 'discount' => 'Desconto', 'district' => 'Bairro', 'email' => 'E-mail', 'employee' => 'Funcionário', 'entity' => 'Entidade', 'fantasy_name' => 'Nome Fantasia', 'form_of_payment' => 'Forma de Pagamento', 'foundation_date' => 'Data de Fundação', 'gross_price' => 'Preço Bruto', 'id' => 'ID', 'maximum_stock' => 'Estoque Máximo', 'minimum_stock' => 'Estoque Mínimo', 'name' => 'Nome', 'net_price' => 'Preço Líquido', 'note' => 'Observação', 'number' => 'Número', 'password' => 'Senha', 'permission' => 'Permissão', 'phone' => 'Telefone', 'postal_code' => 'CEP', 'price' => 'Preço', 'product' => 'Produto', 'provider' => 'Fornecedor', 'purchase' => 'Compra', 'quantity' => 'Quantidade', 'record' => 'Registro', 'reference' => 'Referência', 'report' => 'Relatório', 'rg' => 'RG', 'sale' => 'Venda', 'service' => 'Serviço', 'setting' => 'Opção', 'sex' => 'Sexo', 'situation' => 'Situação', 'source' => 'Origem', 'state' => 'Estado', 'state_registration' => 'Inscrição Estadual', 'surname' => 'Sobrenome', 'total' => 'Total', 'type' => 'Tipo', 'unit_price' => 'Preço Unitário', 'unity' => 'Unidade', 'website' => 'Website', 'weigth' => 'Peso', 'workload' => 'Carga de Trabalho'];
		$array = [];

		foreach($description as $key => $value) {
			if(array_key_exists($key, $attributes)) {
				if(is_array($value)) {
					$array[$attributes[$key]] = array_map(function(array $item) {
						$item = (object) array_merge($item, rectify(['description' => serialize($item)]));
						return beautify($item)->description;
					}, $value);
				}
				else {
					$array[$attributes[$key]] = $value;
				}
			}
		}

		$tuple->description = (object) $array;
	}

	$tuple->day = substr($tuple->day, 8, 2) . '/' . substr($tuple->day, 5, 2) . '/' . substr($tuple->day, 0, 4) . ' - ' . \substr($tuple->day, 11);

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


/** FILTRA OS VALORES NECESSÁRIOS PASSADOS POR GET OU POST E RETORNA UM VETOR
 * @param string $method
 * @return array<string,mixed>
 */
function filter(string $method='GET'): array {
	$method = \strtoupper($method) == 'POST' ? INPUT_POST : INPUT_GET;

	$array['all'] = filter_input($method, 'all', FILTER_DEFAULT, FILTER_SANITIZE_ADD_SLASHES);
	$array['id'] = filter_input($method, 'id', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['id'] = is_numeric($array['id']) ? (int) $array['id'] : null;

	$array['reference'] = filter_input($method, 'reference', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['reference'] = is_numeric($array['reference']) ? (int) $array['reference'] : null;

	$array['action'] = filter_input($method, 'action', FILTER_DEFAULT);
	$array['entity'] = filter_input($method, 'entity', FILTER_DEFAULT);

	$array['employee'] = filter_input($method, 'employee', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_URL);
	$array['employee'] = is_numeric($array['employee']) ? (int) $array['employee'] : null;

	$array['description'] = filter_input($method, 'description', FILTER_DEFAULT);
	$array['day'] = filter_input($method, 'day', FILTER_SANITIZE_NUMBER_INT);

	foreach($array as $index => $value) { // CONVERTE TODOS OS VALORES EM STRING
		$array[$index] = trim((string) $value) ?: null;
	}

	return $array;
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
	$array['reference'] = $values['reference'] ?? 0;
	$array['action'] = $values['action'] ?? '';
	$array['entity'] = $values['entity'] ?? '';
	$array['employee'] = $values['employee'] ?? 0;
	$array['description'] = $values['description'] ?? null;
	$array['day'] = $values['day'] ?? date('Y-m-d');

	return $array;
}
