<?php
//--// ----------- //--//
//--// PRODUCT API //--//
//--// ----------- //--//

namespace api\product;

require_once __DIR__ . '/../controllers/product.php'; // CARREGA O CONTROLADOR DE PRODUTOS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO PRODUTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\product\filter('GET');

	if(!\controller\session\authenticate('product')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR PRODUTOS
		\api\util\response(401);
	}

	elseif(\controller\product\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODAS AS COMPRAS FEITAS DO PRODUTO
		$tuples = \mysql\execute('select * from product_purchase where product = ' . $id . ';');
		$query .= 'delete from product_purchase where product = ' . $id . ';';
		foreach($tuples as $tuple) {
			$query .= 'delete from purchases where id = ' . $tuple->purchase . ';';
		}

		// REMOVE TODOS OS ORÇAMENTOS COM O PRODUTO
		$tuples = \mysql\execute('select * from product_budget where product = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from service_budget where budget = ' . $tuple->budget . ';';
			$query .= 'delete from budgets where id = ' . $tuple->budget . ';';
		}

		// REMOVE TODAS AS VENDAS FEITAS DO PRODUTO
		$tuples = \mysql\execute('select * from product_sale where product = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from service_sale where sale = ' . $tuple->sale . ';';
			$query .= 'delete from sales where id = ' . $tuple->sale . ';';
		}

		// REMOVE O PRODUTO
		$query .= 'delete from products where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // O PRODUTO FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'products'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O produto foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O PRODUTO, COMPRAS, ORÇAMENTOS E VENDAS VINCULADOS AO PRODUTO
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o produto.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO PRODUTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\product\filter('POST');

	if(!\controller\session\authenticate('product')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR PRODUTOS
		\api\util\response(401);
	}

	elseif(\controller\product\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\product\convert($values);
		unset($tuple->id);

		if(!\controller\product\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE O PRODUTO NO BANCO DE DADOS
			$query = 'insert into products (code, name, provider, unity, gross_price, net_price, minimum_stock, maximum_stock, amount, weigth, situation, source) values (:code, :name, :provider, :unity, :gross_price, :net_price, :minimum_stock, :maximum_stock, :amount, :weigth, :situation, :source);';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O PRODUTO FOI INSERIDO NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'products', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O produto foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O PRODUTO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o produto, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O PRODUTO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o produto, verifique os campos (' . implode(', ', \controller\product\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) PRODUTO(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\product\filter('GET');

	if(!\controller\session\authenticate('product')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR PRODUTOS
		\api\util\response(401);
	}

	elseif(\controller\product\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from products where id like concat("%", "' . $values['all'] . '", "%") || code like concat("%", "' . $values['all'] . '", "%") || name like concat("%", "' . $values['all'] . '", "%") || provider like concat("%", "' . $values['all'] . '", "%") || unity like concat("%", "' . $values['all'] . '", "%") || gross_price like concat("%", "' . $values['all'] . '", "%") || net_price like concat("%", "' . $values['all'] . '", "%") || minimum_stock like concat("%", "' . $values['all'] . '", "%") || maximum_stock like concat("%", "' . $values['all'] . '", "%") || amount like concat("%", "' . $values['all'] . '", "%") || weigth like concat("%", "' . $values['all'] . '", "%") || situation like concat("%", "' . $values['all'] . '", "%") || source like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from products where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS PRODUTOS NA PESQUISA
			$tuples = array_map('\controller\product\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 produto.' : 'Foram encontrados ' . count($tuples) . ' produtos.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM PRODUTO FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum produto.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO PRODUTO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\product\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('product')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR PRODUTOS
		\api\util\response(401);
	}

	elseif(\controller\product\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\product\convert($values);

		if(!\controller\product\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA O PRODUTO NO BANCO DE DADOS
			$query = 'update products set code = :code, name = :name, provider = :provider, unity = :unity, gross_price = :gross_price, net_price = :net_price, minimum_stock = :minimum_stock, maximum_stock = :maximum_stock, amount = :amount, weigth = :weigth, situation = :situation, source = :source where id = :id;';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O PRODUTO FOI ALTERADO NA BASE DE DADOS
				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'products', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'O produto foi alterado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR O PRODUTO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o produto, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O PRODUTO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o produto, verifique os campos (' . implode(', ', \controller\product\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
