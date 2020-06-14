<?php
//--// -------- //--//
//--// SALE API //--//
//--// -------- //--//

namespace api\sale;

require_once __DIR__ . '/../controllers/sale.php'; // CARREGA O CONTROLADOR DE VENDAS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DA VENDA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\sale\filter('GET');

	if(!\controller\session\authenticate('sale')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR VENDAS
		\api\util\response(401);
	}

	elseif(\controller\sale\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODOS OS PRODUTOS E SERVIÇOS DA VENDA
		$query .= 'delete from product_sale where sale = ' . $id . ';';
		$query .= 'delete from service_sale where sale = ' . $id . ';';

		// REMOVE A VENDA
		$query .= 'delete from sales where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // A VENDA FOI REMOVIDA DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'sales'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'A venda foi removida com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER A VENDA
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover a venda.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DA VENDA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\sale\filter('POST');

	if(!\controller\session\authenticate('sale')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR VENDAS
		\api\util\response(401);
	}

	elseif(\controller\sale\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\sale\convert($values);
		unset($tuple->id);

		if(!\controller\sale\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE A VENDA NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->products_quantities, $copy->products_prices, $copy->services, $copy->services_quantities, $copy->services_prices, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'insert into sales (client, employee, day, form_of_payment, discount, total) values (:client, :employee, :day, :form_of_payment, :discount, :total);';
			$operation = \mysql\execute($query, $copy);
			$tuple->id = $copy->id;

			foreach($tuple->products as $index => $product) { // INSERE OS PRODUTOS DA VENDA NO BANCO DE DADOS
				$query = 'insert into product_sale (product, sale, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->products_quantities[$index] . ', ' . $tuple->products_prices[$index] . ');';
				$operation = \mysql\execute($query);
			}

			foreach($tuple->services as $index => $service) { // INSERE OS SERVIÇOS DA VENDA NO BANCO DE DADOS
				$query = 'insert into service_sale (service, sale, quantity, unit_price) values (' . $service . ', ' . $tuple->id . ', ' . $tuple->services_quantities[$index] . ', ' . $tuple->services_prices[$index] . ');';
				$operation = \mysql\execute($query);
			}

			if($operation) { // A VENDA FOI INSERIDA NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'sales', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'A venda foi cadastrada com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR A VENDA
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar a venda, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU A VENDA
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar a venda, verifique os campos (' . implode(', ', \controller\sale\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) VENDA(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\sale\filter('GET');

	if(!filter_input(INPUT_GET, 'employee', FILTER_SANITIZE_NUMBER_INT)) {
		unset($values['employee']);
	}

	if(!\controller\session\authenticate('sale')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR VENDAS
		\api\util\response(401);
	}

	elseif(\controller\sale\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from sales where id like concat("%", "' . $values['all'] . '", "%") || client like concat("%", "' . $values['all'] . '", "%") || employee like concat("%", "' . $values['all'] . '", "%") || day like concat("%", "' . $values['all'] . '", "%") || form_of_payment like concat("%", "' . $values['all'] . '", "%") || discount like concat("%", "' . $values['all'] . '", "%") || total like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from sales where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADAS VENDAS NA PESQUISA
			$tuples = array_map('\controller\sale\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrada 1 venda.' : 'Foram encontradas ' . count($tuples) . ' vendas.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUMA VENDA FOI ENCONTRADA
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrada nenhuma venda.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DA VENDA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\sale\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('sale')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR VENDAS
		\api\util\response(401);
	}

	elseif(\controller\sale\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\sale\convert($values);

		if(!\controller\sale\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA A VENDA NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->products_quantities, $copy->products_prices, $copy->services, $copy->services_quantities, $copy->services_prices, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'update sales set client = :client, employee = :employee, day = :day, form_of_payment = :form_of_payment, discount = :discount, total = :total where id = :id;';
			$operation = \mysql\execute($query, $copy);

			if($operation) { // A VENDA FOI ALTERADA NA BASE DE DADOS
				// REMOVE TODOS OS PRODUTOS DA VENDA
				$query = 'delete from product_sale where sale = ' . $tuple->id . ';';

				// REMOVE TODOS OS SERVIÇOS DA VENDA
				$query .= 'delete from service_sale where sale = ' . $tuple->id . ';';

				foreach($tuple->products as $index => $product) { // INSERE OS NOVOS PRODUTOS DA VENDA NO BANCO DE DADOS
					$query .= 'insert into product_sale (product, sale, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->products_quantities[$index] . ', ' . $tuple->products_prices[$index] . ');';
				}

				foreach($tuple->services as $index => $service) { // INSERE OS NOVOS SERVIÇOS DA VENDA NO BANCO DE DADOS
					$query .= 'insert into service_sale (service, sale, quantity, unit_price) values (' . $service . ', ' . $tuple->id . ', ' . $tuple->services_quantities[$index] . ', ' . $tuple->services_prices[$index] . ');';
				}

				\mysql\execute($query);

				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'sales', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'A venda foi alterada com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR A VENDA
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar a venda, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU A VENDA
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar a venda, verifique os campos (' . implode(', ', \controller\sale\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
