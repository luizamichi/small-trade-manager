<?php
//--// ------------ //--//
//--// PROVIDER API //--//
//--// ------------ //--//

namespace api\provider;

require_once __DIR__ . '/../controllers/provider.php'; // CARREGA O CONTROLADOR DE FORNECEDORES (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO FORNECEDOR NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\provider\filter('GET');

	if(!\controller\session\authenticate('provider')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FORNECEDORES
		\api\util\response(401);
	}

	elseif(\controller\provider\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODAS AS COMPRAS FEITAS DO FORNECEDOR
		$tuples = \mysql\execute('select * from purchases where provider = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_purchase where purchase = ' . $tuple->id . ';';
		}
		$query .= 'delete from purchases where provider = ' . $id . ';';

		$products = \mysql\execute('select * from products where provider = ' . $id . ';');

		// REMOVE TODOS OS ORÇAMENTOS COM PRODUTOS DO FORNECEDOR
		$tuples = [];
		if(count($products) > 0) {
			$tuples = \mysql\execute('select * from product_budget where product in (' . implode(', ', array_map(fn(object $tuple): int => $tuple->id, $products)) . ');');
		}
		foreach($tuples as $tuple) {
			$query .= 'delete from product_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from service_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from budgets where id = ' . $tuple->budget . ';';
		}

		// REMOVE TODAS AS VENDAS COM PRODUTOS DO FORNECEDOR
		$tuples = [];
		if(count($products) > 0) {
			$tuples = \mysql\execute('select * from product_sale where product in (' . implode(', ', array_map(fn(object $tuple): int => $tuple->id, $products)) . ');');
		}
		foreach($tuples as $tuple) {
			$query .= 'delete from product_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from service_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from sales where id = ' . $tuple->sale . ';';
		}

		// REMOVE TODOS OS PRODUTOS COMPRADOS DO FORNECEDOR
		$query .= 'delete from products where provider = ' . $id . ';';

		// REMOVE O FORNECEDOR
		$query .= 'delete from providers where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // O FORNECEDOR FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'providers'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O fornecedor foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O FORNECEDOR, PRODUTOS FORNECIDOS, COMPRAS, ORÇAMENTOS E VENDAS DE PRODUTOS ABASTECIDOS PELO FORNECEDOR
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o fornecedor.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO FORNECEDOR NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\provider\filter('POST');

	if(!\controller\session\authenticate('provider')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FORNECEDORES
		\api\util\response(401);
	}

	elseif(\controller\provider\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\provider\convert($values);
		unset($tuple->id);

		if(!\controller\provider\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE O FORNECEDOR NO BANCO DE DADOS
			$query = 'insert into providers (company_name, fantasy_name, state_registration, cnpj, foundation_date, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, note) values (:company_name, :fantasy_name, :state_registration, :cnpj, :foundation_date, :postal_code, :district, :city, :state, :address, :number, :complement, :email, :phone, :cell_phone, :note);';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O FORNECEDOR FOI INSERIDO NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'providers', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O fornecedor foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O FORNECEDOR
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o fornecedor, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O FORNECEDOR
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o fornecedor, verifique os campos (' . implode(', ', \controller\provider\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) FORNECEDOR(ES) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\provider\filter('GET');

	if(!\controller\session\authenticate('provider')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FORNECEDORES
		\api\util\response(401);
	}

	elseif(\controller\provider\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from providers where company_name like concat("%", "' . $values['all'] . '", "%") || fantasy_name like concat("%", "' . $values['all'] . '", "%") || state_registration like concat("%", "' . $values['all'] . '", "%") || cnpj like concat("%", "' . $values['all'] . '", "%") || foundation_date like concat("%", "' . $values['all'] . '", "%") || postal_code like concat("%", "' . $values['all'] . '", "%") || district like concat("%", "' . $values['all'] . '", "%") || city like concat("%", "' . $values['all'] . '", "%") || state like concat("%", "' . $values['all'] . '", "%") || address like concat("%", "' . $values['all'] . '", "%") || number like concat("%", "' . $values['all'] . '", "%") || complement like concat("%", "' . $values['all'] . '", "%") || email like concat("%", "' . $values['all'] . '", "%") || phone like concat("%", "' . $values['all'] . '", "%") || cell_phone like concat("%", "' . $values['all'] . '", "%") || note like concat("%", "' . $values['all'] . '", "%");';
		}

		else { // REALIZA UMA CONSULTA APENAS NOS CAMPOS INFORMADOS
			foreach($values as $index => $value) {
				if($value) {
					$values[$index] = ' ' . $index . ' like concat("%", "' . $value . '", "%")';
				}
				else {
					unset($values[$index]);
				}
			}
			$query = 'select * from providers where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS FORNECEDORES NA PESQUISA
			$tuples = array_map('\controller\provider\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 fornecedor.' : 'Foram encontrados ' . count($tuples) . ' fornecedores.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM FORNECEDOR FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum fornecedor.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO FORNECEDOR NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\provider\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('provider')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FORNECEDORES
		\api\util\response(401);
	}

	elseif(\controller\provider\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\provider\convert($values);

		if(!\controller\provider\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA O FORNECEDOR NO BANCO DE DADOS
			$query = 'update providers set company_name = :company_name, fantasy_name = :fantasy_name, state_registration = :state_registration, cnpj = :cnpj, foundation_date = :foundation_date, postal_code = :postal_code, district = :district, city = :city, state = :state, address = :address, number = :number, complement = :complement, email = :email, phone = :phone, cell_phone = :cell_phone, note = :note where id = :id;';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O FORNECEDOR FOI ALTERADO NA BASE DE DADOS
				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'providers', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'O fornecedor foi alterado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR O FORNECEDOR
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o fornecedor, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O FORNECEDOR
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o fornecedor, verifique os campos (' . implode(', ', \controller\provider\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
