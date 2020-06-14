<?php
//--// ------------ //--//
//--// EMPLOYEE API //--//
//--// ------------ //--//

namespace api\employee;

require_once __DIR__ . '/../controllers/employee.php'; // CARREGA O CONTROLADOR DE FUNCIONÁRIOS (BEAUTIFY, CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE, GET, UNAUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A REMOÇÃO DO FUNCIONÁRIO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function delete(): bool {
	$values = \controller\employee\filter('GET');

	if(!\controller\session\authenticate('employee')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FUNCIONÁRIOS
		\api\util\response(401);
	}

	elseif(\controller\employee\null($values) || empty($values['id'])) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A REMOÇÃO
		$id = $values['id'];
		$query = '';

		// REMOVE TODOS OS ORÇAMENTOS REALIZADOS PELO FUNCIONÁRIO
		$tuples = \mysql\execute('select * from budgets where employee = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_budget where budget = ' . $tuple->id . ';';
			$query .= 'delete from service_budget where budget = ' . $tuple->id . ';';
		}
		$query .= 'delete from budgets where employee = ' . $id . ';';

		// REMOVE TODAS AS COMPRAS REALIZADAS PELO FUNCIONÁRIO
		$tuples = \mysql\execute('select * from purchases where employee = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_purchase where purchase = ' . $tuple->id . ';';
		}
		$query .= 'delete from purchases where employee = ' . $id . ';';

		// REMOVE TODOS OS REGISTROS VINCULADOS AO USUÁRIO
		$query .= 'delete from records where employee = ' . $id . ';';

		// REMOVE TODAS AS VENDAS REALIZADAS PELO FUNCIONÁRIO
		$tuples = \mysql\execute('select * from sales where employee = ' . $id . ';');
		foreach($tuples as $tuple) {
			$query .= 'delete from product_sale where sale = ' . $tuple->id . ';';
			$query .= 'delete from service_sale where sale = ' . $tuple->id . ';';
		}
		$query .= 'delete from sales where employee = ' . $id . ';';

		// REMOVE O FUNCIONÁRIO
		$query .= 'delete from employees where id = ' . $id . ';';

		// REMOVE A PERMISSÃO VINCULADA AO FUNCIONÁRIO
		$tuple = \mysql\execute('select * from employees where id = ' . $id . ';');
		if($tuple) {
			$query .= 'delete from permissions where id = ' . $tuple[0]->permission . ';';
		}

		$operation = \mysql\execute($query);

		if($operation) { // O FUNCIONÁRIO FOI REMOVIDO DA BASE DE DADOS
			\mysql\log(reference: $id, action: 'delete', entity: 'employees'); // SALVA O REGISTRO DA REMOÇÃO

			if($id == (\controller\session\get()['user']->id ?? 0)) { // VERIFICA SE O FUNCIONÁRIO FOI REMOVIDO POR SI PRÓPRIO E DESVINCULA DA SESSÃO
				\controller\session\unauthenticate();
			}

			return \api\util\response(
				status: 200,
				success: true,
				message: 'O funcionário foi removido com sucesso.'
			);
		}

		else { // NÃO FOI POSSÍVEL REMOVER O FUNCIONÁRIO, SUA PERMISSÃO, SUAS COMPRAS, ORÇAMENTOS, REGISTROS E VENDAS
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível remover o funcionário.'
			);
		}
	}

	return false;
}


/** REALIZA A INSERÇÃO DO FUNCIONÁRIO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function insert(): bool {
	$values = \controller\employee\filter('POST');

	if(!\controller\session\authenticate('employee')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FUNCIONÁRIOS
		\api\util\response(401);
	}

	elseif(\controller\employee\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO
		$tuple = \controller\employee\convert($values);
		unset($tuple->id);

		if(!\controller\employee\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// INSERE UMA PERMISSÃO E CAPTURA O ID PARA VINCULÁ-LO AO USUÁRIO
			$query = 'insert into permissions (budget, client, employee, product, provider, purchase, record, report, sale, setting, service) values (:budget, :client, :employee, :product, :provider, :purchase, :record, :report, :sale, :setting, :service);';
			\mysql\execute($query, $tuple->permission);
			$permission = clone $tuple->permission;

			// INSERE O FUNCIONÁRIO NO BANCO DE DADOS
			$tuple->permission = (int) $tuple->permission->id;
			$query = 'insert into employees (name, surname, alias, password, rg, cpf, birthday, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, sex, note, permission) values (:name, :surname, :alias, :password, :rg, :cpf, :birthday, :postal_code, :district, :city, :state, :address, :number, :complement, :email, :phone, :cell_phone, :sex, :note, :permission);';
			$operation = \mysql\execute($query, $tuple);
			if($operation) { // O FUNCIONÁRIO FOI INSERIDO NA BASE DE DADOS
				$tuple->permission = $permission;

				// SALVA O REGISTRO DA INSERÇÃO
				\mysql\log(reference: $tuple->id, action: 'insert', entity: 'employees', description: serialize($tuple));

				return \api\util\response(
					status: 201,
					success: true,
					message: 'O funcionário foi cadastrado com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR O FUNCIONÁRIO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível cadastrar o funcionário, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU O FUNCIONÁRIO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível cadastrar o funcionário, verifique os campos (' . implode(', ', \controller\employee\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA A BUSCA DO(S) FUNCIONÁRIO(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\employee\filter('GET');

	if(!\controller\session\authenticate('employee')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FUNCIONÁRIOS
		\api\util\response(401);
	}

	elseif(\controller\employee\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from employees where name like concat("%", "' . $values['all'] . '", "%") || surname like concat("%", "' . $values['all'] . '", "%") || rg like concat("%", "' . $values['all'] . '", "%") || cpf like concat("%", "' . $values['all'] . '", "%") || birthday like concat("%", "' . $values['all'] . '", "%") || postal_code like concat("%", "' . $values['all'] . '", "%") || district like concat("%", "' . $values['all'] . '", "%") || city like concat("%", "' . $values['all'] . '", "%") || state like concat("%", "' . $values['all'] . '", "%") || address like concat("%", "' . $values['all'] . '", "%") || number like concat("%", "' . $values['all'] . '", "%") || complement like concat("%", "' . $values['all'] . '", "%") || email like concat("%", "' . $values['all'] . '", "%") || phone like concat("%", "' . $values['all'] . '", "%") || cell_phone like concat("%", "' . $values['all'] . '", "%") || sex like concat("%", "' . $values['all'] . '", "%") || note like concat("%", "' . $values['all'] . '", "%");';
		}

		else { // REALIZA UMA CONSULTA APENAS NOS CAMPOS INFORMADOS
			unset($values['permission']);

			foreach($values as $index => $value) {
				if($value) {
					$values[$index] = ' ' . $index . ' like concat("%", "' . $value . '", "%")';
				}
				else {
					unset($values[$index]);
				}
			}
			$query = 'select * from employees where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS FUNCIONÁRIOS NA PESQUISA
			$tuples = array_map('\controller\employee\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 funcionário.' : 'Foram encontrados ' . count($tuples) . ' funcionários.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM FUNCIONÁRIO FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum funcionário.'
			);
		}
	}

	return false;
}


/** REALIZA A ALTERAÇÃO DO FUNCIONÁRIO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function update(): bool {
	$values = \controller\employee\filter('POST');
	unset($values['all']);

	if(!\controller\session\authenticate('employee')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR FUNCIONÁRIOS
		\api\util\response(401);
	}

	elseif(\controller\employee\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A ALTERAÇÃO
		$tuple = \controller\employee\convert($values);

		if(!\controller\employee\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			// ALTERA AS PERMISSÕES DO FUNCIONÁRIO NO BANCO DE DADOS
			$query = 'select p.* from permissions as p inner join employees as e on p.id = e.permission where e.id = ' . $tuple->id . ';';
			$operation = \mysql\execute($query);

			if(!empty($operation)) {
				$query = 'update permissions set budget = :budget, client = :client, employee = :employee, product = :product, provider = :provider, purchase = :purchase, record = :record, report = :report, sale = :sale, setting = :setting, service = :service where id = ' . $operation[0]->id . ';';
				$operation1 = \mysql\execute($query, $tuple->permission);
				unset($tuple->permission);

				// ALTERA O FUNCIONÁRIO NO BANCO DE DADOS
				$query = 'update employees set name = :name, surname = :surname, alias = :alias, password = :password, rg = :rg, cpf = :cpf, birthday = :birthday, postal_code = :postal_code, district = :district, city = :city, state = :state, address = :address, number = :number, complement = :complement, email = :email, phone = :phone, cell_phone = :cell_phone, sex = :sex, note = :note where id = :id;';
				$operation2 = \mysql\execute($query, $tuple);

				if($operation1 || $operation2) { // O FUNCIONÁRIO FOI ALTERADO NA BASE DE DADOS
					// SALVA O REGISTRO DA ALTERAÇÃO
					\mysql\log(reference: $tuple->id, action: 'update', entity: 'clients', description: serialize($tuple));

					return \api\util\response(
						status: 200,
						success: true,
						message: 'O funcionário foi alterado com sucesso.'
					);
				}

				else { // NÃO FOI POSSÍVEL ALTERAR O FUNCIONÁRIO
					\api\util\response(
						status: 500,
						success: false,
						message: 'Não foi possível alterar o funcionário, ocorreu um problema com o banco de dados.'
					);
				}
			}

			else { // NÃO FOI ENCONTRADO O FUNCIONÁRIO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível alterar o funcionário, nenhum registro foi encontrado no banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO ALTEROU O FUNCIONÁRIO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível alterar o funcionário, verifique os campos (' . implode(', ', \controller\employee\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
