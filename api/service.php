<?php
//--// ----------- //--//
//--// SERVICE API //--//
//--// ----------- //--//

namespace api\service;

require_once __DIR__ . '/../controllers/service.php'; // CARREGA O CONTROLADOR DE SERVIÇOS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO SERVIÇO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\service\filter('GET');

	if(!\controller\session\authenticate('service')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR SERVIÇOS
		\api\util\response(401);
	}

	elseif(\controller\service\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODOS OS ORÇAMENTOS COM O SERVIÇO
		$tuples = \mysql\execute('select * from service_budget where service = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from service_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from product_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from budgets where id = ' . $tuple->budget . ';';
		}

		// REMOVE TODAS AS VENDAS COM O SERVIÇO
		$tuples = \mysql\execute('select * from service_sale where service = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from service_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from product_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from sales where id = ' . $tuple->sale . ';';
		}

		// REMOVE O SERVIÇO
		$query .= 'delete from services where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // O SERVIÇO FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'services'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O serviço foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O SERVIÇO, ORÇAMENTOS E VENDAS VINCULADOS AO SERVIÇO
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o serviço.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO SERVIÇO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\service\filter('POST');

	if(!\controller\session\authenticate('service')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR SERVIÇOS
		\api\util\response(401);
	}

	elseif(\controller\service\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\service\convert($values);
		unset($tuple->id);

		if(!\controller\service\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE O SERVIÇO NO BANCO DE DADOS
			$query = 'insert into services (code, name, type, price, workload) values (:code, :name, :type, :price, :workload);';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O SERVIÇO FOI INSERIDO NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'services', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O serviço foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O SERVIÇO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o serviço, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O SERVIÇO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o serviço, verifique os campos (' . implode(', ', \controller\service\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) SERVIÇO(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\service\filter('GET');

	if(!\controller\session\authenticate('service')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR SERVIÇOS
		\api\util\response(401);
	}

	elseif(\controller\service\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from services where id like concat("%", "' . $values['all'] . '", "%") || code like concat("%", "' . $values['all'] . '", "%") || name like concat("%", "' . $values['all'] . '", "%") || type like concat("%", "' . $values['all'] . '", "%") || price like concat("%", "' . $values['all'] . '", "%") || workload like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from services where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS SERVIÇOS NA PESQUISA
			$tuples = array_map('\controller\service\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 serviço.' : 'Foram encontrados ' . count($tuples) . ' serviços.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM SERVIÇO FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum serviço.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO SERVIÇO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\service\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('service')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR SERVIÇOS
		\api\util\response(401);
	}

	elseif(\controller\service\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\service\convert($values);

		if(!\controller\service\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA O SERVIÇO NO BANCO DE DADOS
			$query = 'update services set code = :code, name = :name, type = :type, price = :price, workload = :workload where id = :id;';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O SERVIÇO FOI ALTERADO NA BASE DE DADOS
				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'services', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'O serviço foi alterado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR O SERVIÇO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o serviço, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O SERVIÇO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o serviço, verifique os campos (' . implode(', ', \controller\service\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
