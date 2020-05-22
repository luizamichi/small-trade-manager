<?php
//--// ---------- //--//
//--// CLIENT API //--//
//--// ---------- //--//

namespace api\client;

require_once __DIR__ . '/../controllers/client.php'; // CARREGA O CONTROLADOR DE CLIENTES (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO CLIENTE NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\client\filter('GET');

	if(!\controller\session\authenticate('client')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR CLIENTES
		\api\util\response(401);
	}

	elseif(\controller\client\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODAS AS VENDAS FEITAS AO CLIENTE
		$tuples = \mysql\execute('select * from sales where client = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_sale where sale = ' . $tuple->id . ';';
			$query .= 'delete from service_sale where sale = ' . $tuple->id . ';';
		}
		$query .= 'delete from sales where client = ' . $id . ';';

		// REMOVE O CLIENTE
		$query .= 'delete from clients where id = ' . $id . ';';
		$operation = \mysql\execute($query);

		if($operation) { // O CLIENTE FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'clients'); // SALVA O REGISTRO DA REMOÇÃO

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O cliente foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O CLIENTE E AS VENDAS VINCULADAS AO CLIENTE
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o cliente.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO CLIENTE NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\client\filter('POST');

	if(!\controller\session\authenticate('client')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR CLIENTES
		\api\util\response(401);
	}

	elseif(\controller\client\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\client\convert($values);
		unset($tuple->id);

		if(!\controller\client\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE O CLIENTE NO BANCO DE DADOS
			$query = 'insert into clients (name, surname, rg, cpf, birthday, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, sex, note) values (:name, :surname, :rg, :cpf, :birthday, :postal_code, :district, :city, :state, :address, :number, :complement, :email, :phone, :cell_phone, :sex, :note);';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O CLIENTE FOI INSERIDO NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'clients', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O cliente foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O CLIENTE
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o cliente, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O CLIENTE
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o cliente, verifique os campos (' . implode(', ', \controller\client\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) CLIENTE(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\client\filter('GET');

	if(!\controller\session\authenticate('client')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR CLIENTES
		\api\util\response(401);
	}

	elseif(\controller\client\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from clients where id like concat("%", "' . $values['all'] . '", "%") || name like concat("%", "' . $values['all'] . '", "%") || surname like concat("%", "' . $values['all'] . '", "%") || rg like concat("%", "' . $values['all'] . '", "%") || cpf like concat("%", "' . $values['all'] . '", "%") || birthday like concat("%", "' . $values['all'] . '", "%") || postal_code like concat("%", "' . $values['all'] . '", "%") || district like concat("%", "' . $values['all'] . '", "%") || city like concat("%", "' . $values['all'] . '", "%") || state like concat("%", "' . $values['all'] . '", "%") || address like concat("%", "' . $values['all'] . '", "%") || number like concat("%", "' . $values['all'] . '", "%") || complement like concat("%", "' . $values['all'] . '", "%") || email like concat("%", "' . $values['all'] . '", "%") || phone like concat("%", "' . $values['all'] . '", "%") || cell_phone like concat("%", "' . $values['all'] . '", "%") || sex like concat("%", "' . $values['all'] . '", "%") || note like concat("%", "' . $values['all'] . '", "%");';
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

			$query = 'select * from clients where' . implode(' &&', $values) . ';';
		}

		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS CLIENTES NA PESQUISA
			$tuples = array_map('\controller\client\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 cliente.' : 'Foram encontrados ' . count($tuples) . ' clientes.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM CLIENTE FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum cliente.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO CLIENTE NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\client\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('client')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR CLIENTES
		\api\util\response(401);
	}

	elseif(\controller\client\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\client\convert($values);

		if(!\controller\client\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA O CLIENTE NO BANCO DE DADOS
			$query = 'update clients set name = :name, surname = :surname, rg = :rg, cpf = :cpf, birthday = :birthday, postal_code = :postal_code, district = :district, city = :city, state = :state, address = :address, number = :number, complement = :complement, email = :email, phone = :phone, cell_phone = :cell_phone, sex = :sex, note = :note where id = :id;';
			$operation = \mysql\execute($query, $tuple);

			if($operation) { // O CLIENTE FOI ALTERADO NA BASE DE DADOS
				// SALVA O REGISTRO DA ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: 'update', entity: 'clients', description: serialize($tuple));

				return \api\util\response(
					status: 200,
					success: true,
					message: 'O cliente foi alterado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL ALTERAR O CLIENTE
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o cliente, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O CLIENTE
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o cliente, verifique os campos (' . implode(', ', \controller\client\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
