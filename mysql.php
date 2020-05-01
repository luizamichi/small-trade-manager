<?php
//--// ------------------ //--//
//--// DATABASE FUNCTIONS //--//
//--// ------------------ //--//

namespace mysql;

require_once __DIR__ . '/config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (MYSQL_HOST, MYSQL_PASSWORD, MYSQL_SCHEMA, MYSQL_USER)
require_once __DIR__ . '/util.php'; // CARREGA AS FUNÇÕES ÚTEIS (HISTORY)
require_once __DIR__ . '/controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (GET)


$db = null; // VARIÁVEL GLOBAL DA CONEXÃO COM O BANCO DE DADOS


/** ENCERRA A COMUNICAÇÃO COM O BANCO DE DADOS
 * @return void
 */
function close(): void {
	global $db;
	$db = null;
}


/** CONVERTE O OBJETO EM UM VETOR PARA SER UTILIZADO EM CONSULTAS, INSERÇÕES, ALTERAÇÕES E REMOÇÕES NO BANCO DE DADOS
 * @param object $record
 * @return array<string,mixed>
 */
function convert(object $record): array {
	$tuple = [];
	foreach((array) $record as $key => $value) {
		$tuple[':' . $key] = $value;
	}

	return $tuple;
}


/** EXECUTA UMA INSTRUÇÃO NO BANCO DE DADOS MYSQL/MARIADB
 * @param string $query
 * @param ?object &$record
 * @return array<object>|bool
 */
function execute(string $query, ?object &$record=null): array|bool {
	global $db;

	$operation = strtolower(substr($query, 0, 6));
	$tuple = $record ? convert($record) : $record;
	$connection = $db ?? open();
	$stmt = null;
	$error = false;

	if($connection) {
		try {
			if(in_array($operation, ['delete', 'insert', 'update'])) { // INICIA UMA TRANSAÇÃO
				$connection->beginTransaction();
			}

			if($operation == 'delete') { // OPERAÇÃO DE REMOÇÃO
				$queries = explode(';', $query);
				foreach($queries as $query) {
					if(!empty($query)) {
						$stmt = $connection->prepare($query);
						$stmt->execute();
					}
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
				if($operation == 'insert' && $record) {
					$record->id = $connection->lastInsertId() ?: ($record->id ?? 0);
				}

				$commit = $connection->commit();
				return (bool) ($operation == 'delete' ? $commit : $stmt->rowCount());
			}
		}

		catch(\PDOException $e) {
			\util\history($e);

			if($operation == 'select') {
				return [];
			}

			elseif(in_array($operation, ['delete', 'insert', 'update'])) { // DESCARTA AS MODIFICAÇÕES REALIZADAS NA TRANSAÇÃO
				$connection->rollBack();
				return false;
			}

			$error = true;
		}

		finally {
			if($error) {
				$connection = null;
			}
		}
	}

	else { // NÃO FOI POSSÍVEL ESTABELECER CONEXÃO COM O SERVIDOR
		if($operation == 'select') {
			return [];
		}

		elseif(in_array($operation, ['delete', 'insert', 'update'])) {
			if($operation == 'insert' && $record) {
				$record->id ??= 0;
			}
			return false;
		}
	}

	return false;
}


/** REGISTRA UMA ATIVIDADE DE INSERÇÃO, ALTERAÇÃO OU REMOÇÃO
 * @param int $reference
 * @param string $action
 * @param string $entity
 * @param ?string $description
 * @return bool
 */
function log(int $reference, string $action, string $entity, ?string $description=null): bool {
	if($reference <= 0) { // BUSCA O ID DO ÚLTIMO REGISTRO CADASTRADO
		$query = 'select * from ' . $entity . ' where id = (select max(id) from ' . $entity . ');';
		$reference = execute($query)[0]->id ?? 0;
	}

	$employee = \controller\session\get()['user']->id ?? 0; // BUSCA O ID DO FUNCIONÁRIO AUTENTICADO

	// MONTA UM OBJETO PARA CADASTRAR NO BANCO DE DADOS
	$tuple = (object) ['reference' => $reference, 'action' => $action, 'entity' => $entity, 'employee' => $employee, 'description' => $description, 'day' => date('Y-m-d H:i:s')];
	$query = 'insert into records (reference, action, entity, employee, description, day) values (:reference, :action, :entity, :employee, :description, :day);';
	return execute($query, $tuple);
}


/** ESTABELECE UMA CONEXÃO COM O BANCO DE DADOS
 * @return ?\PDO
 */
function open(): ?\PDO {
	global $db;

	if(!$db) {
		try {
			if(TEST && !CONNECT) {
				return null;
			}

			$db = new \PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_SCHEMA, MYSQL_USER, MYSQL_PASSWORD);
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
			return $db;
		}

		catch(\PDOException $e) {
			\util\history($e);
			return null;
		}
	}

	return $db;
}
