<?php
//--// ---------- //--//
//--// BUDGET API //--//
//--// ---------- //--//

namespace api\budget;

require_once __DIR__ . '/../controllers/budget.php'; // CARREGA O CONTROLADOR DE ORÇAMENTOS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO ORÇAMENTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\budget\filter('GET');

	if(!\controller\session\authenticate('budget')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR ORÇAMENTOS
		\api\util\response(401);
	}

	elseif(\controller\budget\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];

		// REMOVE TODOS OS PRODUTOS E SERVIÇOS DO ORÇAMENTO
		$query = 'delete from product_budget where budget = ' . $id . ';';
		$query .= 'delete from service_budget where budget = ' . $id . ';';

		// REMOVE O ORÇAMENTO
		$query .= 'delete from budgets where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // O ORÇAMENTO FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'budgets'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O orçamento foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O ORÇAMENTO
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o orçamento.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO ORÇAMENTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\budget\filter('POST');

	if(!\controller\session\authenticate('budget')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR ORÇAMENTOS
		\api\util\response(401);
	}

	elseif(\controller\budget\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\budget\convert($values);
		unset($tuple->id);

		if(!\controller\budget\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE O ORÇAMENTO NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->products_quantities, $copy->products_prices, $copy->services, $copy->services_quantities, $copy->services_prices, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'insert into budgets (employee, day, form_of_payment, discount, total) values (:employee, :day, :form_of_payment, :discount, :total);';
			$operation = \mysql\execute($query, $copy);
			$tuple->id = $copy->id;

			foreach($tuple->products as $index => $product) { // INSERE OS PRODUTOS DO ORÇAMENTO NO BANCO DE DADOS
				$query = 'insert into product_budget (product, budget, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->products_quantities[$index] . ', ' . $tuple->products_prices[$index] . ');';
				$operation = \mysql\execute($query);
			}

			foreach($tuple->services as $index => $service) { // INSERE OS SERVIÇOS DO ORÇAMENTO NO BANCO DE DADOS
				$query = 'insert into service_budget (service, budget, quantity, unit_price) values (' . $service . ', ' . $tuple->id . ', ' . $tuple->services_quantities[$index] . ', ' . $tuple->services_prices[$index] . ');';
				$operation = \mysql\execute($query);
			}

			if($operation) { // O ORÇAMENTO FOI INSERIDO NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'budgets', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O orçamento foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O ORÇAMENTO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o orçamento, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O ORÇAMENTO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o orçamento, verifique os campos (' . implode(', ', \controller\budget\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) ORÇAMENTO(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\budget\filter('GET');

	if(!filter_input(INPUT_GET, 'employee', FILTER_SANITIZE_NUMBER_INT)) {
		unset($values['employee']);
	}

	if(!\controller\session\authenticate('budget')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR ORÇAMENTOS
		\api\util\response(401);
	}

	elseif(\controller\budget\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from budgets where id like concat("%", "' . $values['all'] . '", "%") || employee like concat("%", "' . $values['all'] . '", "%") || day like concat("%", "' . $values['all'] . '", "%") || form_of_payment like concat("%", "' . $values['all'] . '", "%") || discount like concat("%", "' . $values['all'] . '", "%") || total like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from budgets where' . implode(' &&', $values) . ';';
		}

		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS ORÇAMENTOS NA PESQUISA
			$tuples = array_map('\controller\budget\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 orçamento.' : 'Foram encontrados ' . count($tuples) . ' orçamentos.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM ORÇAMENTO FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum orçamento.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO ORÇAMENTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\budget\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('budget')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR ORÇAMENTOS
		\api\util\response(401);
	}

	elseif(\controller\budget\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\budget\convert($values);

		if(!\controller\budget\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA O ORÇAMENTO NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->products_quantities, $copy->products_prices, $copy->services, $copy->services_quantities, $copy->services_prices, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'update budgets set employee = :employee, day = :day, form_of_payment = :form_of_payment, discount = :discount, total = :total where id = :id;';
			$operation = \mysql\execute($query, $copy);

			if($operation) { // O ORÇAMENTO FOI ALTERADO NA BASE DE DADOS
				// REMOVE TODOS OS PRODUTOS E SERVIÇOS DO ORÇAMENTO
				$query = 'delete from product_budget where budget = ' . $tuple->id . ';';
				$query .= 'delete from service_budget where budget = ' . $tuple->id . ';';

				foreach($tuple->products as $index => $product) { // INSERE OS NOVOS PRODUTOS DO ORÇAMENTO NO BANCO DE DADOS
					$query .= 'insert into product_budget (product, budget, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->products_quantities[$index] . ', ' . $tuple->products_prices[$index] . ');';
				}

				foreach($tuple->services as $index => $service) { // INSERE OS NOVOS SERVIÇOS DO ORÇAMENTO NO BANCO DE DADOS
					$query .= 'insert into service_budget (service, budget, quantity, unit_price) values (' . $service . ', ' . $tuple->id . ', ' . $tuple->services_quantities[$index] . ', ' . $tuple->services_prices[$index] . ');';
				}

				\mysql\execute($query);

				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'budgets', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'O orçamento foi alterado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR O ORÇAMENTO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o orçamento, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O ORÇAMENTO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o orçamento, verifique os campos (' . implode(', ', \controller\budget\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
