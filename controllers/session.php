<?php
//--// ----------------- //--//
//--// SESSION FUNCTIONS //--//
//--// ----------------- //--//

namespace controller\session;

require_once __DIR__ . '/../config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (BASE_URL, DEBUG, SESSION_TIME)


/** ATIVA A SESSÃO
 * @return bool
 */
function active(): bool {
	if(session_status() !== PHP_SESSION_ACTIVE) { // NENHUMA SESSÃO FOI INICIADA
		session_start(['cookie_lifetime' => SESSION_TIME]);
		return true;
	}

	return false;
}


/** VALIDA SE O USUÁRIO ESTÁ VINCULADO A UMA SESSÃO E/OU POSSUI PERMISSÃO
 * @param string $permission
 * @return bool
 */
function authenticate(string $permission=''): bool {
	active();

	if(TEST) { // MODO DE TESTE ATIVADO (USUÁRIO NÃO PRECISA SE AUTENTICAR, TEM PERMISSÃO PARA QUALQUER ATIVIDADE)
		if(!empty($_SESSION)) { // INICIA UMA NOVA SESSÃO COM DADOS SIMBÓLICOS
			start((object) ['id' => 0, 'permission' => (object) []]);
		}
		return true;
	}

	if(!isset($_SESSION['user'])) { // USUÁRIO NÃO ESTÁ AUTENTICADO NO SISTEMA
		return false;
	}

	elseif(isset($_SESSION['user']) && isset($_SESSION['permission']) && isset($_SESSION['destroy'])) { // USUÁRIO POSSUI PRIVILÉGIO DE GERENCIAMENTO
		if(time() - (int) $_SESSION['destroy'] >= 1800) { // USUÁRIO EXCEDEU O TEMPO DE 30 MINUTOS INATIVO
			session_unset();
			session_destroy();
			return false;
		}

		elseif($permission == '' || \unserialize($_SESSION['permission'])->{$permission}) { // RENOVA O TEMPO DE ACESSO
			$_SESSION['destroy'] = time();
			return true;
		}
	}

	return false;
}


/** RETORNA OS DADOS SALVOS NA SESSÃO
 * @return array<string,mixed>
 */
function get(): array {
	active();

	$session['permission'] = isset($_SESSION['permission']) ? \unserialize($_SESSION['permission']) : [];
	$session['user'] = isset($_SESSION['user']) ? \unserialize($_SESSION['user']) : null;
	$session['destroy'] = isset($_SESSION['destroy']) ? $_SESSION['destroy'] : '';
	$session['messages'] = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
	$session['history'] = isset($_SESSION['history']) ? $_SESSION['history'] : null;

	return $session;
}


/** INICIA UMA SESSÃO COM OS DADOS DO USUÁRIO
 * @param object $user
 * @return void
 */
function start(object $user): void {
	active();

	$_SESSION['permission'] = serialize($user->permission);
	unset($user->permission);
	$_SESSION['user'] = serialize($user);

	$_SESSION['destroy'] = time();
	session_regenerate_id();
}


/** REMOVE TODAS AS CHAVES DA SESSÃO
 * @return bool
 */
function unauthenticate(): bool {
	active();

	if(!isset($_SESSION['user'])) { // USUÁRIO NÃO ESTÁ AUTENTICADO NO SISTEMA
		return false;
	}

	session_unset();
	session_destroy();

	return true;
}
