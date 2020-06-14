<?php
//--// --------- //--//
//--// OTHER API //--//
//--// --------- //--//

namespace api\other;

require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE, START, UNAUTHENTICATE)
require_once __DIR__ . '/../controllers/setting.php'; // CARREGA O CONTROLADOR DE OPÇÕES (CONVERT, ERRORS, FILTER, NULL, VALIDATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE, LOG, OPEN)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A AUTENTICAÇÃO DO USUÁRIO NO SISTEMA E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function authenticate(): bool {
	$alias = filter_input(INPUT_POST, 'alias', FILTER_DEFAULT);
	$password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

	if($alias && $password) { // INFORMOU OS PARÂMETROS E DADOS CORRETOS NA REQUISIÇÃO
		$user = \mysql\execute('select * from employees where (alias = "' . $alias . '" || email = "' . $alias . '") && password = "' . md5($password) . '";');

		if(isset($user[0])) { // ENCONTROU O USUÁRIO SOLICITADO
			$permission = \mysql\execute('select * from permissions where id = ' . $user[0]->permission . ';');
			$user[0]->permission = $permission[0];

			\controller\session\start($user[0]);

			return \api\util\response(
				status: 200,
				success: true,
				message: 'Usuário autenticado com sucesso.'
			);
		}

		elseif(!\mysql\open()) { // CONEXÃO COM O BANCO DE DADOS NÃO FOI ESTABELECIDA
			\api\util\response(
				status: 500,
				success: false,
				message: 'Não foi possível estabelecer conexão com o banco de dados.'
			);
		}

		else { // USUÁRIO OU SENHA INVÁLIDOS
			\api\util\response(
				status: 403,
				success: false,
				message: 'Usuário ou senha inválidos.'
			);
		}
	}

	else { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	return false;
}


/** REALIZA A INSERÇÃO OU ALTERAÇÃO DA OPÇÃO NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_POST
 * @return bool
 */
function setting(): bool {
	$values = \controller\setting\filter('POST');

	if(!\controller\session\authenticate('setting')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR OPÇÕES
		\api\util\response(401);
	}

	elseif(\controller\setting\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A INSERÇÃO/ALTERAÇÃO
		$tuple = \controller\setting\convert($values);
		$action = 'insert';

		if(!\controller\setting\errors($tuple)) { // NENHUM ERRO ENCONTRADO NOS VALORES
			$operation = \controller\setting\load(); // VERIFICA SE HÁ UM REGISTRO CADASTRADO

			if(!$operation) { // INSERE UM NOVO REGISTRO NO BANCO DE DADOS
				$query = 'insert into settings (company_name, fantasy_name, cnpj, postal_code, district, city, state, address, number, email, phone, website) values (:company_name, :fantasy_name, :cnpj, :postal_code, :district, :city, :state, :address, :number, :email, :phone, :website);';
				unset($tuple->id);
				$action = 'insert';
			}

			else { // ALTERA A OPÇÃO NO BANCO DE DADOS
				$query = 'update settings set company_name = :company_name, fantasy_name = :fantasy_name, cnpj = :cnpj, postal_code = :postal_code, district = :district, city = :city, state = :state, address = :address, number = :number, email = :email, phone = :phone, website = :website where id = :id;';
				$tuple->id = $operation->id;
				$action = 'update';
			}

			$operation = \mysql\execute($query, $tuple);
			if($operation) { // A OPÇÃO FOI INSERIDA/ALTERADA NA BASE DE DADOS
				// SALVA O REGISTRO DA INSERÇÃO/ALTERAÇÃO
				\mysql\log(reference: $tuple->id, action: $action, entity: 'settings', description: serialize($tuple));

				return \api\util\response(
					status: ($action == 'insert' ? 201 : 200),
					success: true,
					message: 'A opção foi ' . ($action == 'insert' ? 'cadastrada' : 'alterada') . ' com sucesso.'
				);
			}

			else { // NÃO FOI POSSÍVEL INSERIR/ALTERAR A OPÇÃO
				\api\util\response(
					status: 500,
					success: false,
					message: 'Não foi possível ' . ($action == 'insert' ? 'cadastrar' : 'alterar') . ' a opção, ocorreu um problema com o banco de dados.'
				);
			}
		}

		else { // ENCONTROU ERROS NOS VALORES E NÃO INSERIU/ALTEROU A OPÇÃO
			\api\util\response(
				status: 406,
				success: false,
				message: 'Não foi possível ' . ($action == 'insert' ? 'cadastrar' : 'alterar') . ' a opção, verifique os campos (' . implode(', ', \controller\setting\validate($tuple)) . ') que estão inválidos.'
			);
		}
	}

	return false;
}


/** REALIZA O LOGOUT DO USUÁRIO NO SISTEMA E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function unauthenticate(): bool {
	if(!\controller\session\authenticate()) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO
		return \api\util\response(401);
	}

	else { // DESVINCULA O USUÁRIO DA SESSÃO
		\controller\session\unauthenticate();

		return \api\util\response(
			status: 200,
			success: true,
			message: 'Usuário desconectado com sucesso.'
		);
	}
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
