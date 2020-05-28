<?php
//--// ------------ //--//
//--// PURCHASE API //--//
//--// ------------ //--//

namespace api\purchase;

require_once __DIR__ . '/../controllers/purchase.php'; // CARREGA O CONTROLADOR DE COMPRAS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DA COMPRA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\purchase\filter('GET');

	if(!\controller\session\authenticate('purchase')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR COMPRAS
		\api\util\response(401);
	}

	elseif(\controller\purchase\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODOS OS PRODUTOS DA COMPRA
		$query .= 'delete from product_purchase where purchase = ' . $id . ';';

		// REMOVE A COMPRA
		$query .= 'delete from purchases where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // A COMPRA FOI REMOVIDA DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'purchases'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'A compra foi removida com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER A COMPRA
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover a compra.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DA COMPRA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\purchase\filter('POST');

	if(!\controller\session\authenticate('purchase')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR COMPRAS
		\api\util\response(401);
	}

	elseif(\controller\purchase\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\purchase\convert($values);
		unset($tuple->id);

		if(!\controller\purchase\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE A COMPRA NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'insert into purchases (provider, employee, day, form_of_payment, discount, total) values (:provider, :employee, :day, :form_of_payment, :discount, :total);';
			$operation = \mysql\execute($query, $copy);
			$tuple->id = $copy->id;

			foreach($tuple->products as $index => $product) { // INSERE OS PRODUTOS DA COMPRA NO BANCO DE DADOS
				$query = 'insert into product_purchase (product, purchase, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->quantities[$index] . ', ' . $tuple->prices[$index] . ');';
				$operation = \mysql\execute($query);
			}

			if($operation) { // A COMPRA FOI INSERIDA NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'purchases', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'A compra foi cadastrada com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR A COMPRA
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar a compra, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU A COMPRA
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar a compra, verifique os campos (' . implode(', ', \controller\purchase\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DA(S) COMPRA(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\purchase\filter('GET');

	if(!filter_input(INPUT_GET, 'employee', FILTER_SANITIZE_NUMBER_INT)) {
		unset($values['employee']);
	}

	if(!\controller\session\authenticate('purchase')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR COMPRAS
		\api\util\response(401);
	}

	elseif(\controller\purchase\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from purchases where id like concat("%", "' . $values['all'] . '", "%") || provider like concat("%", "' . $values['all'] . '", "%") || employee like concat("%", "' . $values['all'] . '", "%") || day like concat("%", "' . $values['all'] . '", "%") || form_of_payment like concat("%", "' . $values['all'] . '", "%") || discount like concat("%", "' . $values['all'] . '", "%") || total like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from purchases where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADAS COMPRAS NA PESQUISA
			$tuples = array_map('\controller\purchase\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrada 1 compra.' : 'Foram encontradas ' . count($tuples) . ' compras.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUMA COMPRA FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrada nenhuma compra.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DA COMPRA NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\purchase\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('purchase')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR COMPRAS
		\api\util\response(401);
	}

	elseif(\controller\purchase\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\purchase\convert($values);

		if(!\controller\purchase\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA A COMPRA NO BANCO DE DADOS
			$copy = clone $tuple;
			unset($copy->products, $copy->quantities, $copy->prices, $copy->cart);
			$query = 'update purchases set provider = :provider, employee = :employee, day = :day, form_of_payment = :form_of_payment, discount = :discount, total = :total where id = :id;';
			$operation = \mysql\execute($query, $copy);

			if($operation) { // A COMPRA FOI ALTERADA NA BASE DE DADOS
				// REMOVE TODOS OS PRODUTOS DA COMPRA
				$query = 'delete from product_purchase where purchase = ' . $tuple->id . ';';

				foreach($tuple->products as $index => $product) { // INSERE OS NOVOS PRODUTOS DA COMPRA NO BANCO DE DADOS
					$query .= 'insert into product_purchase (product, purchase, quantity, unit_price) values (' . $product . ', ' . $tuple->id . ', ' . $tuple->quantities[$index] . ', ' . $tuple->prices[$index] . ');';
				}
				\mysql\execute($query);

				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'purchases', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'A compra foi alterada com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR A COMPRA
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar a compra, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU A COMPRA
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar a compra, verifique os campos (' . implode(', ', \controller\purchase\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
