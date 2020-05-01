<?php
//--// -------------- //--//
//--// UTIL FUNCTIONS //--//
//--// -------------- //--//

namespace util;

require_once __DIR__ . '/config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (DEBUG)
require_once __DIR__ . '/controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (ACTIVE)


/** RETORNA PARA A PÁGINA ANTERIOR OU PARA O ENDEREÇO INFORMADO
 * @param ?string $location
 * @return void
 */
function back(?string $location=null): void {
	$location = $location ?? $_SERVER['HTTP_REFERER'] ?? 'javascript:history.go(-1)';
	header('Location: ' . $location);
	exit;
}


/** IMPRIME O TEXTO SEM APLICAR OS EFEITOS DAS TAGS HTML (CONSOLE LOG)
 * @param string $text
 * @return void
 */
function cl(string $text): void {
	echo (php_sapi_name() == 'cli' ? $text : '<pre>' . htmlentities($text) . '</pre>') . PHP_EOL;
}


/** COLOCA COR NO TEXTO PARA SER IMPRESSO NO CONSOLE
 * @param string $text
 * @param string $color
 * @return string
 */
function color(string $text, string $color='NORMAL'): string {
	$colors = [
		'NORMAL' => '[0m',
		'BOLD' => '[1m',
		'UNDERSCORE' => '[4m',
		'REVERSE' => '[7m',
		'BLACK' => '[0;30m',
		'RED' => '[0;31m',
		'GREEN' => '[0;32m',
		'YELLOW' => '[0;33m',
		'BLUE' => '[0;34m',
		'PURPLE' => '[0;35m',
		'CYAN' => '[0;36m',
		'WHITE' => '[0;37m',
		'BLACK_BOLD' => '[1;30m',
		'RED_BOLD' => '[1;31m',
		'GREEN_BOLD' => '[1;32m',
		'YELLOW_BOLD' => '[1;33m',
		'BLUE_BOLD' => '[1;34m',
		'PURPLE_BOLD' => '[1;35m',
		'CYAN_BOLD' => '[1;36m',
		'WHITE_BOLD' => '[1;37m',
		'BLACK_UNDERSCORE' => '[4;30m',
		'RED_UNDERSCORE' => '[4;31m',
		'GREEN_UNDERSCORE' => '[4;32m',
		'YELLOW_UNDERSCORE' => '[4;33m',
		'BLUE_UNDERSCORE' => '[4;34m',
		'PURPLE_UNDERSCORE' => '[4;35m',
		'CYAN_UNDERSCORE' => '[4;36m',
		'WHITE_UNDERSCORE' => '[4;37m',
		'BACKGROUND_BLACK' => '[40m',
		'BACKGROUND_RED' => '[41m',
		'BACKGROUND_GREEN' => '[42m',
		'BACKGROUND_YELLOW' => '[43m',
		'BACKGROUND_BLUE' => '[44m',
		'BACKGROUND_PURPLE' => '[45m',
		'BACKGROUND_CYAN' => '[46m',
		'BACKGROUND_WHITE' => '[47m',
		'HIGH_BLACK' => '[0;90m',
		'HIGH_RED' => '[0;91m',
		'HIGH_GREEN' => '[0;92m',
		'HIGH_YELLOW' => '[0;93m',
		'HIGH_BLUE' => '[0;94m',
		'HIGH_PURPLE' => '[0;95m',
		'HIGH_CYAN' => '[0;96m',
		'HIGH_WHITE' => '[0;97m',
		'BOLD_HIGH_BLACK' => '[1;90m',
		'BOLD_HIGH_RED' => '[1;91m',
		'BOLD_HIGH_GREEN' => '[1;92m',
		'BOLD_HIGH_YELLOW' => '[1;93m',
		'BOLD_HIGH_BLUE' => '[1;94m',
		'BOLD_HIGH_PURPLE' => '[1;95m',
		'BOLD_HIGH_CYAN' => '[1;96m',
		'BOLD_HIGH_WHITE' => '[1;97m',
		'HIGH_BACKGROUND_BLACK' => '[0;100m',
		'HIGH_BACKGROUND_RED' => '[0;101m',
		'HIGH_BACKGROUND_GREEN' => '[0;102m',
		'HIGH_BACKGROUND_YELLOW' => '[0;103m',
		'HIGH_BACKGROUND_BLUE' => '[0;104m',
		'HIGH_BACKGROUND_PURPLE' => '[0;105m',
		'HIGH_BACKGROUND_CYAN' => '[0;106m',
		'HIGH_BACKGROUND_WHITE' => '[0;107m'
	];

	$color = \strtoupper($color);
	$color = array_key_exists($color, $colors) ? $colors[$color] : $colors['NORMAL'];
	return chr(27) . $color . $text . chr(27) . chr(27) . '[0m' . chr(27);
}


/** IMPRIME OS DETALHES DA VARIÁVEL E ENCERRA A EXECUÇÃO (DIE DUMP)
 * @param mixed ...$variable
 * @return void
 */
function dd(mixed ...$variable): void {
	foreach($variable as $value) {
		var_dump($value);
	}

	exit;
}


/** IMPRIME OS DETALHES DA VARIÁVEL E ENCERRA A EXECUÇÃO (DIE ECHO)
 * @param mixed ...$variable
 * @return void
 */
function de(mixed ...$variable): void {
	foreach($variable as $value) {
		if(is_array($value)) {
			print_r($value);
		}
		elseif(is_bool($value)) {
			echo ($value ? 'true' : 'false') . PHP_EOL;
		}
		elseif(is_object($value)) {
			echo json_encode($value, JSON_PRETTY_PRINT) . PHP_EOL;
		}
		else {
			echo $value . PHP_EOL;
		}
	}

	exit;
}


/** ATIVA A REPORTAÇÃO DE ERROS E EXCEÇÕES
 * @param bool $active
 * @return void
 */
function debug(bool $active=false): void {
	if(DEBUG || $active) {
		ini_set('display_errors', 1);
		ini_set('display_startup_erros', 1);
		error_reporting(E_ALL);

		sleep(DELAY);
	}
	else {
		ini_set('log_errors', 1);
		ini_set('error_log', __DIR__ . '/errors.log');
	}
}


/** SALVA MENSAGENS NA SESSÃO QUE PODEM SER UTILIZADAS POSTERIORMENTE
 * @param string ...$messages
 * @return void
 */
function flash(string ...$messages): void {
	\controller\session\active();

	if(isset($_SESSION['messages']) && is_array($_SESSION['messages'])) {
		$_SESSION['messages'] = array_merge($_SESSION['messages'], $messages);
	}
	else {
		$_SESSION['messages'] = $messages;
	}
}


/** SALVA ERROS CAPTURADOS E TRATADOS
 * @param \Exception $exception
 * @return void
 */
function history(\Exception $exception): void {
	\controller\session\active();

	$_SESSION['history'] = [
		'type' => $exception->getCode(),
		'message' => $exception->getMessage(),
		'file' => $exception->getFile(),
		'line' => $exception->getLine(),
		'trace' => $exception->getTraceAsString()
	];
}


/** REQUISIÇÃO GET
 * @param string $url
 * @param array<string,mixed> $params
 * @return array<string,mixed>
 */
function get(string $url, array $params=[]): array {
	$params = http_build_query($params);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_URL, $url . $params);

	$data = curl_exec($curl);
	$error = curl_error($curl);
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	return ['data' => $data, 'error' => $error, 'code' => $code];
}


/** RETORNA AS MENSAGENS SALVAS NA SESSÃO
 * @return array<string>
 */
function messages(): array {
	\controller\session\active();

	$messages = [];
	if(isset($_SESSION['messages']) && is_array($_SESSION['messages'])) {
		$messages = $_SESSION['messages'];
		unset($_SESSION['messages']);
	}

	return $messages;
}


/** REQUISIÇÃO POST
 * @param string $url
 * @param array<string,mixed> $params
 * @return array<string,mixed>
 */
function post(string $url, array $params=[]): array {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($curl, CURLOPT_URL, $url);

	$data = curl_exec($curl);
	$error = curl_error($curl);
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	return ['data' => $data, 'error' => $error, 'code' => $code];
}


/** REMOVE TAGS HTML, ESPAÇOS ADICIONAIS E ADICIONA BARRAS INVERSAS ANTES DAS ASPAS
 * @param string $text
 * @return string
 */
function sanitize(string $text): string {
	$quotationMarks = addslashes($text);
	$html = filter_var($quotationMarks, FILTER_DEFAULT);
	return trim($html);
}


/** CONVERTE UM TEXTO COM UM VALOR MONETÁRIO EM UM PONTO FLUTUANTE (DO TIPO TEXTO)
 * @param ?string $number
 * @return string
 */
function strToFloat(?string $number=null): string {
	if(str_contains((string) $number, ',')) {
		return str_replace(',', '.', str_replace('.', '', (string) $number));
	}
	return (string) $number;
}


/** RETORNA O VALOR DA VARIÁVEL OU UM VALOR PADRÃO PARA VARIÁVEIS INEXISTENTES (VARIABLE EXISTS)
 * @param mixed &$variable
 * @param mixed $default
 * @return mixed
 */
function ve(mixed &$variable, mixed $default=null): mixed {
	return isset($variable) ? $variable : $default;
}


//--// -------------- //--//
//--// FUNCTION CALLS //--//
//--// -------------- //--//

debug();
