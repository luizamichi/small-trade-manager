<?php
//--// ------------------ //--//
//--// DATABASE FUNCTIONS //--//
//--// ------------------ //--//

namespace mysql;

require_once(__DIR__ . '/config.php'); // CARREGA AS CONFIGURAÇÕES GLOBAIS (MYSQL_HOST, MYSQL_PASSWORD, MYSQL_SCHEMA, MYSQL_USER)
require_once(__DIR__ . '/controllers/session.php'); // CARREGA O CONTROLADOR DE SESSÕES (GET)


// VARIÁVEL GLOBAL DA CONEXÃO COM O BANCO DE DADOS
$db = null;


/** ENCERRA A COMUNICAÇÃO COM O BANCO DE DADOS
 * @return void
 */
function close() {
	global $db;
	$db = null;
}


/** CONVERTE O OBJETO EM UM VETOR PARA SER UTILIZADO EM CONSULTAS, INSERÇÕES, ALTERAÇÕES E REMOÇÕES NO BANCO DE DADOS
 * @param object $register
 * @return array
 */
function convert(object $register): array {
	$tuple = [];
	foreach((array) $register as $key => $value)
		$tuple[':' . $key] = $value;
	return $tuple;
}


/** EXECUTA UMA INSTRUÇÃO NO BANCO DE DADOS MYSQL/MARIADB
 * @param string $query
 * @param object &$register
 * @return array|bool
 */
function execute(string $query, object &$register=null) {
	global $bd;

	$operation = substr($query, 0, 6);
	$tuple = $register ? convert($register) : $register;
	$connection = $bd ?? open();

	if($connection) {
		try {
			if(in_array($operation, ['delete', 'insert', 'update'])) // INICIA UMA TRANSAÇÃO
				$connection->beginTransaction();

			if($operation == 'delete') { // OPERAÇÃO DE REMOÇÃO
				$queries = explode(';', $query);
				foreach($queries as $query) {
					if(!empty($query))
						$connection->exec($query);
				}
			}

			elseif(in_array($operation, ['insert', 'select', 'update'])) { // PREPARA O STATEMENT PARA A INSERÇÃO, CONSULTA OU ALTERAÇÃO
				$stmt = $connection->prepare($query);
				$stmt->execute($tuple);
			}

			if($operation == 'select') { // RETORNA UM VETOR DE OBJETOS (OPERAÇÃO DE CONSULTA)
				return $stmt->fetchAll(\PDO::FETCH_OBJ);
			}

			elseif(in_array($operation, ['delete', 'insert', 'update'])) { // RETORNA SE A OPERAÇÃO FOI BEM SUCEDIDA (OPERAÇÕES DE REMOÇÃO, INSERÇÃO OU ALTERAÇÃO)
				if($operation == 'insert' && $register)
					$register->id = $connection->lastInsertId() ?: ($register->id ?? 0);

				$commit = $connection->commit();
				return (bool) ($operation == 'delete' ? $commit : $stmt->rowCount());
			}
		}

		catch(\PDOException $e) {
			if($operation == 'select')
				return [];

			elseif(in_array($operation, ['delete', 'insert', 'update'])) { // DESCARTA AS MODIFICAÇÕES REALIZADAS NA TRANSAÇÃO
				$connection->rollBack();
				return false;
			}
		}

		finally {
			$connection = null;
		}
	}

	else { // NÃO FOI POSSÍVEL ESTABELECER CONEXÃO COM O SERVIDOR
		if($operation == 'select')
			return [];

		elseif(in_array($operation, ['delete', 'insert', 'update'])) {
			if($operation == 'insert' && $register)
				$register->id = $operation == 'insert' ? ($register->id ?? 0) : 0;
			return false;
		}
	}
}


/** REGISTRA UMA ATIVIDADE DE INSERÇÃO, ALTERAÇÃO OU REMOÇÃO
 * @param int $reference
 * @param string $action
 * @param string $entity
 * @param string $description
 * @return bool
*/
function log(int $reference, string $action, string $entity, string $description=null): bool {
	if($reference <= 0) { // BUSCA O ID DO ÚLTIMO REGISTRO CADASTRADO
		$query = 'select * from ' . $entity . ' where id=(select max(id) from ' . $entity . ');';
		$reference = execute($query)[0]->id ?? 0;
	}

	// BUSCA O ID DO FUNCIONÁRIO AUTENTICADO
	$employee = \controller\session\get()['user']->id ?? 0;

	// MONTA UM OBJETO PARA CADASTRAR NO BANCO DE DADOS
	$tuple = (object) ['reference' => $reference, 'action' => $action, 'entity' => $entity, 'employee' => $employee, 'description' => $description, 'day' => date('Y-m-d H:i:s')];
	$query = 'insert into records (reference, action, entity, employee, description, day) values (:reference, :action, :entity, :employee, :description, :day);';
	return execute($query, $tuple);
}


/** ESTABELECE UMA CONEXÃO COM O BANCO DE DADOS
 * @return object
 */
function open(): ?object {
	global $db;

	if(!$db) {
		try {
			$db = new \PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_SCHEMA, MYSQL_USER, MYSQL_PASSWORD);
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
			return $db;
		}
	
		catch(\PDOException $e) {
			return null;
		}
	}

	return $db;
}