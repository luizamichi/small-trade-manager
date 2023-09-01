<?php
//--// ---------- //--//
//--// RECORD API //--//
//--// ---------- //--//

namespace api\record;

require_once __DIR__ . '/../controllers/record.php'; // CARREGA O CONTROLADOR DE REGISTROS (BEAUTIFY, FILTER, NULL)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (AUTHENTICATE)
require_once __DIR__ . '/../mysql.php'; // CARREGA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS (EXECUTE)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS DA API (CONTENT, RESPONSE)


/** REALIZA A BUSCA DO(S) REGISTRO(S) NA BASE DE DADOS E IMPRIME UM JSON
 * @uses $_GET
 * @return bool
 */
function select(): bool {
	$values = \controller\record\filter('GET');

	if(!\controller\session\authenticate('record')) { // O USUÁRIO NÃO ESTÁ AUTENTICADO E VINCULADO A UMA SESSÃO, OU ESTÁ AUTENTICADO E NÃO POSSUI PERMISSÃO PARA GERENCIAR REGISTROS
		\api\util\response(401);
	}

	elseif(\controller\record\null($values)) { // INFORMOU PARÂMETROS INVÁLIDOS NA REQUISIÇÃO
		\api\util\response(400);
	}

	else { // INFORMOU OS PARÂMETROS CORRETOS PARA A PESQUISA
		if($values['all']) { // REALIZA UMA CONSULTA EM TODOS OS CAMPOS DA TABELA
			$query = 'select * from records where id like concat("%", "' . $values['all'] . '", "%") || reference like concat("%", "' . $values['all'] . '", "%") || action like concat("%", "' . $values['all'] . '", "%") || entity like concat("%", "' . $values['all'] . '", "%") || employee like concat("%", "' . $values['all'] . '", "%") || description like concat("%", "' . $values['all'] . '", "%") || day like concat("%", "' . $values['all'] . '", "%");';
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
			$query = 'select * from records where' . implode(' &&', $values) . ';';
		}
		$operation = \mysql\execute($query);

		if($operation) { // FORAM ENCONTRADOS REGISTROS NA PESQUISA
			$tuples = array_map('\controller\record\beautify', $operation);
			return \api\util\response(
				status: 200,
				success: true,
				message: (count($tuples) == 1 ? 'Foi encontrado 1 registro.' : 'Foram encontrados ' . count($tuples) . ' registros.'),
				data: $tuples
			);
		}

		else { // A PESQUISA FOI REALIZADA COM SUCESSO, MAS NENHUM REGISTRO FOI ENCONTRADO
			\api\util\response(
				status: 404,
				success: false,
				message: 'Não foi encontrado nenhum registro.'
			);
		}
	}

	return false;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

\api\util\content();
