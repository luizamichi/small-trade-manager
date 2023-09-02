<?php
//--// -------------- //--//
//--// UTIL FUNCTIONS //--//
//--// -------------- //--//

namespace api\util;

require_once __DIR__ . '/../config.php'; // CARREGA AS CONFIGURAÇÕES GLOBAIS (DEBUG)
require_once __DIR__ . '/../controllers/session.php'; // CARREGA O CONTROLADOR DE SESSÕES (GET)


/** ALTERA O CABEÇALHO DO DOCUMENTO PARA DEIXAR O TIPO DO CONTEÚDO COMO JSON
 * @return void
 */
function content(): void {
	// DEFINE O CONTEÚDO DA RESPOSTA (JSON)
	header('Content-Type: application/json; charset=utf-8');

	// DEFINE UMA DATA DE EXPIRAÇÃO DO CONTEÚDO (1 MÊS)
	$expirationDate = date_add(date_create(), date_interval_create_from_date_string('1 month'));
	header('Expires: ' . date_format($expirationDate, 'D, d M Y H:i:s \G\M\T'));

	// HABILITA O COMPARTILHAMENTO DE RECURSOS ENTRE DIFERENTES ORIGENS (CORS)
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header('Access-Control-Allow-Headers: X-Requested-With');
}


/** IMPRIME UM JSON E DEFINE O CÓDIGO DE RESPOSTA
 * @param int $status
 * @param bool $success
 * @param string $message
 * @param array<mixed> $data
 * @return bool
 */
function response(int $status=200, bool $success=true, string $message='', array $data=[]): bool {
	http_response_code($status);
	$success = $success && $status >= 400 ? false : $success;

	if(empty($message)) {
		$message = match($status) {
			400 => 'Requisição mal formulada.',
			401 => 'Usuário não autorizado.',
			default => 'Resposta padrão da API.'
		};
	}

	echo json_encode([
		'status' => $status,
		'success' => $success,
		'message' => $message,
		'data' => $data,
		'error' => DEBUG ? error_get_last() : null,
		'history' => DEBUG ? \controller\session\get()['history'] : null
	]);

	return $success;
}


/** RETORNA O TIPO DE ERRO DE ACORDO COM O CÓDIGO
 * @param int $code
 * @return string
 */
function status(int $code): string {
	switch($code) {
		// RESPOSTAS DE INFORMAÇÃO
		case 100: // TUDO OCORREU BEM ATÉ AGORA, O CLIENTE DEVE CONTINUAR COM A REQUISIÇÃO OU IGNORAR SE JÁ CONCLUIU
			return 'Continue';
		case 101: // O SERVIDOR ESTÁ ALTERANDO O PROTOCOLO
			return 'Switching Protocol';
		case 103: // O AGENTE DEVE INICIAR UM PRÉ-CARREGAMENTO, POIS O SERVIDOR PREPARA UMA RESPOSTA
			return 'Early Hints';

		// RESPOSTAS DE SUCESSO
		case 200: // REQUISIÇÃO BEM SUCEDIDA
			return 'OK';
		case 201: // A REQUISIÇÃO FOI BEM SUCEDIDA E UM NOVO RECURSO FOI CRIADO COMO RESULTADO
			return 'Created';
		case 202: // A REQUISIÇÃO FOI RECEBIDA, MAS NENHUMA AÇÃO FOI TOMADA SOBRE ELA
			return 'Accepted';
		case 203: // O CONJUNTO DE META-INFORMAÇÕES RETORNADAS NÃO É O CONJUNTO EXATO DISPONÍVEL NO SERVIDOR DE ORIGEM, MAS COLETADO DE UMA CÓPIA LOCAL OU DE TERCEIROS
			return 'Non-Authoritative Information';
		case 204: // NÃO HÁ CONTEÚDO PARA ENVIAR PARA ESTA SOLICITAÇÃO
			return 'No Content';
		case 205: // O SERVIDOR ATENDEU À SOLICITAÇÃO E DESEJA QUE O AGENTE DO USUÁRIO REDEFINA A "VISUALIZAÇÃO DO DOCUMENTO", QUE CAUSOU O ENVIO DA SOLICITAÇÃO, AO ESTADO ORIGINAL RECEBIDO DO SERVIDOR DE ORIGEM
			return 'Reset Content';
		case 206: // O CABEÇALHO DE INTERVALO ENVIADO PELO CLIENTE IRÁ SEPARAR O DOWNLOAD EM VÁRIOS FLUXOS
			return 'Partial Content';

		// MENSAGENS DE REDIRECIONAMENTO
		case 300: // A REQUISIÇÃO TEM MAIS DE UMA RESPOSTA POSSÍVEL
			return 'Multiple Choice';
		case 301: // A URI DO RECURSO REQUERIDO MUDOU
			return 'Moved Permanently';
		case 302: // A URI DO RECURSO REQUERIDO FOI MUDADA TEMPORARIAMENTE
			return 'Found';
		case 303: // O SERVIDOR MANDA ESSA RESPOSTA PARA INSTRUIR AO CLIENTE BUSCAR O RECURSO REQUISITADO EM OUTRA URI COM UMA REQUISIÇÃO GET
			return 'See Other';
		case 304: // DIZ AO CLIENTE QUE A RESPOSTA NÃO FOI MODIFICADA (ESSA RESPOSTA É USADA PARA QUESTÕES DE CACHE)
			return 'Not Modified';
		case 307: // O SERVIDOR ENVIA ESTA RESPOSTA DIRECIONANDO O CLIENTE A BUSCAR O RECURSO REQUISITADO EM OUTRA URI COM O MESMO MÉTODO QUE FOI UTILIZADO NA REQUISIÇÃO ORIGINAL
			return 'Temporary Redirect';
		case 308: // O RECURSO ESTÁ PERMANENTEMENTE LOCALIZADO EM OUTRA URI, ESPECIFICADA PELO CABEÇALHO DE RESPOSTA "LOCATION"
			return 'Permanent Redirect';

		// RESPOSTAS DE ERRO DO CLIENTE
		case 400: // O SERVIDOR NÃO ENTENDEU A SOLICITAÇÃO DEVIDO A SINTAXE INVÁLIDA
			return 'Bad Request';
		case 401: // O CLIENTE DEVE SE AUTENTICAR PARA OBTER A RESPOSTA
			return 'Unauthorized';
		case 403: // O CLIENTE NÃO POSSUI DIRETO DE ACESSO AO CONTEÚDO
			return 'Forbidden';
		case 404: // NÃO ENCONTROU O RECURSO SOLICITADO
			return 'Not Found';
		case 405: // O MÉTODO DE SOLICITAÇÃO É CONHECIDO PELO SERVIDOR, MAS FOI DESATIVADO E NÃO PODE SER USADO
			return 'Method Not Allowed';
		case 406: // NÃO ENCONTROU NENHUM CONTEÚDO QUE ESTEJA DE ACORDO COM OS CRITÉRIOS RECEBIDOS
			return 'Not Acceptable';
		case 407: // SEMELHANTE AO 401, POREM É NECESSÁRIO QUE A AUTENTICAÇÃO SEJA FEITA POR UM PROXY
			return 'Proxy Authentication Required';
		case 408: // A CONEXÃO ESTÁ OCIOSA, MESMO SEM QUALQUER REQUISIÇÃO PRÉVIA PELO CLIENTE
			return 'Request Timeout';
		case 409: // A REQUISIÇÃO CONFLITOU COM O ESTADO ATUAL DO SERVIDOR
			return 'Conflict';
		case 410: // O CONTEÚDO REQUISITADO FOI PERMANENTEMENTE DELETADO DO SERVIDOR, SEM NENHUM ENDEREÇO DE REDIRECIONAMENTO
			return 'Gone';
		case 411: // O SERVIDOR REJEITOU A REQUISIÇÃO PORQUE O CAMPO "CONTENT-LENGTH" DO CABEÇALHO NÃO ESTÁ DEFINIDO E O SERVIDOR O REQUER
			return 'Length Required';
		case 412: // O CLIENTE INDICOU NOS SEUS CABEÇALHOS PRÉ-CONDIÇÕES QUE O SERVIDOR NÃO ATENDE
			return 'Precondition Failed';
		case 413: // A REQUISIÇÃO É MAIOR DO QUE OS LIMITES DEFINIDOS PELO SERVIDOR
			return 'Payload Too Large';
		case 414: // A URI REQUISITADA PELO CLIENTE É MAIOR DO QUE O SERVIDOR ACEITA PARA INTERPRETAR
			return 'URI Too Long';
		case 415: // O FORMATO DE MÍDIA DOS DADOS REQUISITADOS NÃO É SUPORTADO PELO SERVIDOR
			return 'Unsupported Media Type';
		case 416: // O TRECHO ESPECIFICADO PELO CAMPO "RANGE" DO CABEÇALHO NA REQUISIÇÃO NÃO PODE SER PREENCHIDO (É POSSÍVEL QUE O TRECHO ESTEJA FORA DO TAMANHO DOS DADOS DA URI ALVO)
			return 'Requested Range Not Satisfiable';
		case 417: // A EXPECTATIVA INDICADA PELO CAMPO "EXPECT" DO CABEÇALHO DA REQUISIÇÃO NÃO PODE SER SATISFEITA PELO SERVIDOR
			return 'Expectation Failed';
		case 418: // O SERVIDOR RECUSA A TENTATIVA DE COAR CAFÉ NUM BULE DE CHÁ
			return 'I\'m a teapot';
		case 422: // A REQUISIÇÃO ESTÁ BEM FORMADA MAS INABILITADA PARA SER SEGUIDA DEVIDO A ERROS SEMÂNTICOS
			return 'Unprocessable Entity';
		case 425: // O SERVIDOR NÃO ESTÁ DISPOSTO A ARRISCAR PROCESSAR UMA REQUISIÇÃO QUE PODE SER REFEITA
			return 'Too Early';
		case 426: // O SERVIDOR SE RECUSA A EXECUTAR A REQUISIÇÃO USANDO O PROTOCOLO CORRENTE, MAS ESTARÁ PRONTO A FAZÊ-LO APÓS O CLIENTE ATUALIZAR PARA UM PROTOCOLO DIFERENTE
			return 'Upgrade Required';
		case 428: // O SERVIDOR DE ORIGEM REQUER QUE A RESPOSTA SEJA CONDICIONAL
			return 'Precondition Required';
		case 429: // O USUÁRIO ENVIOU MUITAS REQUISIÇÕES NUM DADO TEMPO (LIMITAÇÃO DE FREQUÊNCIA)
			return 'Too Many Requests';
		case 431: // O SERVIDOR NÃO QUER PROCESSAR A REQUISIÇÃO PORQUE OS CAMPOS DE CABEÇALHO SÃO MUITO GRANDES
			return 'Request Header Fields Too Large';
		case 451: // O USUÁRIO REQUISITOU UM RECURSO ILEGAL, TAL COMO UMA PÁGINA CENSURADA POR UM GOVERNO
			return 'Unavailable For Legal Reasons';

		// RESPOSTAS DE ERRO DO SERVIDOR
		case 500: // O SERVIDOR ENCONTROU UMA SITUAÇÃO COM A QUAL NÃO SABE LIDAR
			return 'Internal Server Error';
		case 501: // O MÉTODO DA REQUISIÇÃO NÃO É SUPORTADO PELO SERVIDOR E NÃO PODE SER MANIPULADO
			return 'Not Implemented';
		case 502: // O SERVIDOR, AO TRABALHAR COMO UM GATEWAY A FIM DE OBTER UMA RESPOSTA NECESSÁRIA PARA MANIPULAR A REQUISIÇÃO, OBTEVE UMA RESPOSTA INVÁLIDA
			return 'Bad Gateway';
		case 503: // O SERVIDOR NÃO ESTÁ PRONTO PARA MANIPULAR A REQUISIÇÃO.
			return 'Service Unavailable';
		case 504: // O SERVIDOR ESTÁ ATUANDO COMO UM GATEWAY E NÃO OBTÉM UMA RESPOSTA A TEMPO
			return 'Gateway Timeout';
		case 505: // A VERSÃO HTTP USADA NA REQUISIÇÃO NÃO É SUPORTADA PELO SERVIDOR
			return 'HTTP Version Not Supported';
		case 506: // O SERVIDOR TEM UM ERRO DE CONFIGURAÇÃO INTERNO (A NEGOCIAÇÃO TRANSPARENTE DE CONTEÚDO PARA A REQUISIÇÃO RESULTA EM UMA REFERÊNCIA CIRCULAR)
			return 'Variant Also Negotiates';
		case 507: // O SERVIDOR TEM UM ERRO INTERNO DE CONFIGURAÇÃO (O RECURSO VARIANTE ESCOLHIDO ESTÁ CONFIGURADO PARA ENTRAR EM NEGOCIAÇÃO TRANSPARENTE DE CONTEÚDO COM ELE MESMO, E PORTANTO NÃO É UMA PONTA VÁLIDA NO PROCESSO DE NEGOCIAÇÃO)
			return 'Insufficient Storage';
		case 508: // O SERVIDOR DETECTOU UM LOOPING INFINITO AO PROCESSAR A REQUISIÇÃO
			return 'Loop Detected';
		case 510: // EXIGEM-SE EXTENÇÕES POSTERIORES À REQUISIÇÃO PARA O SERVIDOR ATENDÊ-LA
			return 'Not Extended';
		case 511: // O CÓDIGO DE STATUS 511 INDICA QUE O CLIENTE PRECISA SE AUTENTICAR PARA GANHAR ACESSO À REDE
			return 'Network Authentication Required';

		default: // INFORMOU UM CÓDIGO INVÁLIDO
			return 'Invalid Code';
	}
}
