$(document).ready(function () {

	//--// ------------------------- //--//
	//--// FUNCTION RECONFIGURATIONS //--//
	//--// ------------------------- //--//

	// REMOÇÃO DA VISIBILIDADE DO CAMPO DE MENSAGEM E CARREGAMENTO PÓS COMPLETAÇÃO DO FORMULÁRIO
	if ($("#flash-message").text().length == 0)
		$("#flash-message").hide();
	$("#loading-operation").hide();

	// RECONFIGURAÇÃO DO INPUT DATE PARA FUNCIONAR EM TODOS OS NAVEGADORES
	if ($("[type='date']").length > 0 && $("[type='date']").prop("type") != "date")
		$("[type='date']").datepicker();

	// FUNÇÃO PARA EXIBIR O NOME DO ARQUIVO NO CAMPO DE ENTRADA
	$("input[type='file']").change(function () {
		let name = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(name);
	});


	//--// ------------------ //--//
	//--// AUXILIAR FUNCTIONS //--//
	//--// ------------------ //--//

	// REMOÇÃO DO REGISTRO A PARTIR DO MODAL
	$(document).on("click", "a[data-action='remove-btn']", function () {
		$("a[id='confirm-remove']").attr("href", $(this).attr("data-model") + "?id=" + $(this).attr("data-id"));
		$("a[id='confirm-remove']").attr("data-id", $(this).attr("data-id"));
	});
	$("a[id='confirm-remove']").click(function (e) {
		e.preventDefault();
		let id = $(this).attr("data-id");
		$.ajax({
			type: "get",
			dataType: "json",
			url: $("a[id='confirm-remove']").attr("href"),
			async: true,
			encode: true,
			beforeSend: function () {
				$("div[id='remove-modal']").modal("hide");
				$("#loading-operation").show();
			},
			complete: function () {
				$("#loading-operation").hide();
				$("#flash-message").show();
			},
			success: function (response) {
				$("#flash-message").text(response.message);
				if (response.success) {
					$("#flash-message").addClass("alert-success").removeClass("alert-danger alert-primary alert-warning");
					if ($("tr[id='" + id + "']").length > 0)
						$("tr[id='" + id + "']").remove();
					else {
						$("body").append("<div style='z-index: 9999; position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: block; text-align: center; background: rgba(18, 23, 37, 0.25) no-repeat center;'></div>");
						setTimeout(function () {
							location.reload();
						}, 3000);
					}
				}
				else
					$("#flash-message").addClass("alert-danger").removeClass("alert-success alert-primary alert-warning");
			},
			error: function () {
				$("#flash-message").text("Não foi possível efetuar a operação, houve um erro de comunicação com o servidor.");
				$("#flash-message").addClass("alert-danger").removeClass("alert-success alert-primary alert-warning");
			}
		});
	});

	// IMPRESSÃO DO CONTEÚDO DA PÁGINA
	$("button[id='print-content']").click(function () {
		$("#content").children().not("#buttons").printThis({
			printDelay: 0,
			header: $("#setting").html(),
			pageTitle: $("#setting-website").html()
		});
	});

	// ATUALIZAÇÃO DO PREÇO COM O DESCONTO
	$("input[name='total']").val(($("input[name='total']").length && $("input[name='discount']") ? $("input[name='total']").val().length > 0 && $("input[name='discount']").val().length > 0 ? (parseFloat($("input[name='total']").val().replace(',', '.')) - (parseFloat($("input[name='total']").val().replace(',', '.')) * parseFloat($("input[name='discount']").val().replace(',', '.')) / 100)).toFixed(2) : "" : ''));


	//--// ---------------------- //--//
	//--// VALIDATION EXPRESSIONS //--//
	//--// ---------------------- //--//

	// VALIDAÇÃO DE ENDEREÇO, CIDADE E RAZÃO SOCIAL
	$("input[name='address'], input[name='city'], input[name='company-name']").focusout(function () {
		if ($(this).val().trim().length >= 4 && $(this).val().trim().length <= 64)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE APELIDO
	$("input[name='alias']").focusout(function () {
		if ($(this).val().trim().length >= 6 && $(this).val().trim().length <= 32)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE QUANTIDADE, ESTOQUE MÍNIMO E ESTOQUE MÁXIMO
	$("input[name='amount'], input[name='minimum-stock'], input[name='maximum-stock']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()) >= 0)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE DATA DE ANIVERSÁRIO E DATA DE FUNDAÇÃO
	$("input[name='birthday'], input[name='foundation-date']").focusout(function () {
		let date = new Date($(this).val());
		if (date != "Invalid Date" && !isNaN(date) && date < new Date())
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CELULAR
	$("input[name='cell-phone']").focusout(function () {
		if ($(this).val().replace(/\D/gim, "").length == 11)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CLIENTE
	$("input[id='client-mask']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()) && $("datalist[id='clients'] option").map(function () { return $(this).val(); }).get().includes($(this).val().trim())) {
			$("input[id='client']").val($(this).val());
			$(this).val($("datalist[id='clients'] option[value='" + $(this).val() + "']").text());
			$(this).addClass("is-valid").removeClass("is-invalid");
		}
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CÓDIGO
	$("input[name='code']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()))
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE COMPLEMENTO
	$("input[name='complement']").focusout(function () {
		if ($(this).val().trim().length >= 4 && $(this).val().trim().length <= 32)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CPF
	$("input[name='cpf']").focusout(function () {
		let cpf = $(this).val().replace(/\D/gim, "");
		let erro = false;
		if (cpf.length != 11)
			erro = true;
		if (cpf.split(cpf[0]).length == 12)
			erro = true;
		let i, j, k;
		for (i = 9; i < 11; i++) {
			for (j = 0, k = 0; k < i; k++)
				j += cpf[k] * ((i + 1) - k);
			j = ((10 * j) % 11) % 10;
			if (cpf[k] != j) {
				erro = true;
				break;
			}
		}
		if (erro)
			$(this).addClass("is-invalid").removeClass("is-valid");
		else
			$(this).addClass("is-valid").removeClass("is-invalid");
	});

	// VALIDAÇÃO DE CNPJ
	$("input[name='cnpj']").focusout(function () {
		let cnpj = $(this).val().replace(/\D/gim, "");
		let erro = false;
		if (cnpj.length != 14)
			erro = true;
		if (cnpj == "00000000000000" || cnpj == "11111111111111" || cnpj == "22222222222222" || cnpj == "33333333333333" || cnpj == "44444444444444" || cnpj == "55555555555555" || cnpj == "66666666666666" || cnpj == "77777777777777" || cnpj == "88888888888888" || cnpj == "99999999999999")
			erro = true;
		let tamanho = cnpj.length - 2;
		let numeros = cnpj.substring(0, tamanho);
		let digitos = cnpj.substring(tamanho);
		let soma = 0;
		let pos = tamanho - 7;
		for (let i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
			erro = true;
		tamanho = tamanho + 1;
		numeros = cnpj.substring(0, tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (let i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
			erro = true;
		if (erro)
			$(this).addClass("is-invalid").removeClass("is-valid");
		else
			$(this).addClass("is-valid").removeClass("is-invalid");
	});

	// VALIDAÇÃO DE DESCONTO
	$("input[name='discount']").keyup(function () {
		if ($(this).val().trim().length >= 1 && parseFloat($(this).val().trim()) >= 0 && parseFloat($(this).val().trim()) <= 100) {
			$(this).addClass("is-valid").removeClass("is-invalid");
			$("input[name='total']").val((parseFloat($("input[name='total']").data("value")) - (parseFloat($("input[name='total']").data("value")) * parseFloat($(this).val().replace(',', '.')) / 100)).toFixed(2));
		}
		else if ($(this).val().length == 0 && !$(this).prop("required")) {
			$("input[name='total']").val(parseFloat($("input[name='total']").data("value")).toFixed(2));
			$(this).removeClass("is-invalid is-valid");
		}
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE BAIRRO
	$("input[name='district']").focusout(function () {
		if ($(this).val().trim().length >= 4 && $(this).val().trim().length <= 32)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE E-MAIL
	$("input[name='email']").focusout(function () {
		if (/\S+@\S+\.\S+/.test($(this).val()) && $(this).val().length >= 4 && $(this).val().length <= 32)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE FORMA DE PAGAMENTO, SEXO, SITUAÇÃO, ORIGEM, ESTADO E UNIDADE
	$("select[name='form-of-payment'], select[name='sex'], select[name='situation'], select[name='source'], select[name='state'], select[name='unity']").click(function () {
		if ($(this).val() || $(this).children("optgroup").children("option:selected").val())
			$(this).addClass("is-valid").removeClass("is-invalid");
	});
	$("select[name='form-of-payment'], select[name='sex'], select[name='situation'], select[name='source'], select[name='state'], select[name='unity']").focusout(function () {
		if ($(this).val() || $(this).children("optgroup").children("option:selected").val())
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE PREÇO BRUTO, PREÇO LÍQUIDO, PREÇO E PESO
	$("input[name='gross-price'], input[name='net-price'], input[name='price'], input[name='weigth']").focusout(function () {
		if ($(this).val().trim().length >= 1 && parseFloat($(this).val().trim().replace(',', '.')))
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE NOME, SOBRENOME, NOME FANTASIA E TIPO
	$("input[name='name'], input[name='surname'], input[name='fantasy-name'], input[name='type']").focusout(function () {
		if ($(this).val().trim().length >= 2 && $(this).val().trim().length <= 32)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE OBSERVAÇÃO
	$("textarea[name='note']").focusout(function () {
		if ($(this).val().trim().length >= 4 && $(this).val().trim().length <= 512)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE NÚMERO
	$("input[name='number']").focusout(function () {
		if ($(this).val().trim().length >= 2 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()))
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE SENHA
	$("input[name='password']").focusout(function () {
		if ($(this).val().trim().length >= 6)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE TELEFONE
	$("input[name='phone']").focusout(function () {
		if ($(this).val().replace(/\D/gim, "").length == 10)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CEP
	// COMPLETAÇÃO AUTOMÁTICA DOS CAMPOS DE BAIRRO, CIDADE, COMPLEMENTO, ENDEREÇO E ESTADO A PARTIR DO CEP
	$("input[name='postal-code']").focusout(function () {
		let status;
		$.ajax({
			url: "https://viacep.com.br/ws/" + $(this).val().replace(/\D/, "") + "/json/",
			dataType: "jsonp",
			crossDomain: true,
			contentType: "application/json",
			statusCode: {
				200: function () { status = 200; }, // OK
				400: function () { status = 400; }, // FALHA NA REQUISIÇÃO
				404: function () { status = 404; } // NÃO ENCONTROU
			},
			success: function (data) {
				if (!data.erro) {
					$("#address").val(data.logradouro);
					$("#complement").val(data.complemento);
					$("#district").val(data.bairro);
					$("#city").val(data.localidade);
					$("#state").val(data.uf);
					if (data.cep.length > 0)
						$("#postal-code").addClass("is-valid").removeClass("is-invalid");
					if (data.logradouro.length > 0)
						$("#address").addClass("is-valid").removeClass("is-invalid");
					else if ($("#address").val().length === 0)
						$("#address").addClass("is-invalid").removeClass("is-valid");
					if (data.complemento.length > 0)
						$("#complement").addClass("is-valid").removeClass("is-invalid");
					else if ($("#complement").val().length === 0)
						$("#complement").removeClass("is-valid");
					if (data.bairro.length > 0)
						$("#district").addClass("is-valid").removeClass("is-invalid");
					else if ($("#district").val().length === 0)
						$("#district").removeClass("is-valid");
					if (data.localidade.length > 0)
						$("#city").addClass("is-valid").removeClass("is-invalid");
					if (data.uf.length > 0)
						$("#state").addClass("is-valid").removeClass("is-invalid");
				}
			}
		});
		if (status == 200)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE PRODUTO
	$("input[id='product-mask']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()) && $("datalist[id='products'] option").map(function () { return $(this).val(); }).get().includes($(this).val().trim())) {
			$("input[id='product']").val($(this).val());
			$(this).val($("datalist[id='products'] option[value='" + $(this).val() + "']").text());
			$(this).addClass("is-valid").removeClass("is-invalid");
		}
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE QUANTIDADE DE PRODUTOS E QUANTIDADE DE SERVIÇOS
	$("input[id='product-quantity'], input[id='service-quantity']").on("click focusout", function () {
		if ($(this).val().trim().length >= 1 && parseInt($(this).val().trim()) >= 1)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE FORNECEDOR
	$("input[id='provider-mask']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()) && $("datalist[id='providers'] option").map(function () { return $(this).val(); }).get().includes($(this).val().trim())) {
			$("input[id='provider']").val($(this).val());
			$(this).val($("datalist[id='providers'] option[value='" + $(this).val() + "']").text());
			$(this).addClass("is-valid").removeClass("is-invalid");
		}
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE RG
	$("input[name='rg']").focusout(function () {
		if ($(this).val().replace(/\D/gim, "").length == 8 || $(this).val().replace(/\D/gim, "").length == 9)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE SERVIÇO
	$("input[id='service-mask']").focusout(function () {
		if ($(this).val().trim().length >= 1 && $(this).val().trim().length <= 8 && parseInt($(this).val().trim()) && $("datalist[id='services'] option").map(function () { return $(this).val(); }).get().includes($(this).val().trim())) {
			$("input[id='service']").val($(this).val());
			$(this).val($("datalist[id='services'] option[value='" + $(this).val() + "']").text());
			$(this).addClass("is-valid").removeClass("is-invalid");
		}
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE INSCRIÇÃO ESTADUAL
	$("input[name='state-registration']").focusout(function () {
		if ($(this).val().replace(/\D/gim, "").length == 12)
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE WEBSITE
	$("input[name='website']").focusout(function () {
		let regex = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
		if ($(this).val().trim().length >= 8 && $(this).val().trim().length <= 32 && regex.test($(this).val().trim()))
			$(this).addClass("is-valid").removeClass("is-invalid");
		else if ($(this).val().length == 0 && !$(this).prop("required"))
			$(this).removeClass("is-invalid is-valid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});

	// VALIDAÇÃO DE CARGA DE TRABALHO
	$("input[name='workload']").focusout(function () {
		if ($(this).val().trim().length == 5 && /^([0-1]?\d|2[0-4]):([0-5]\d)(:[0-5]\d)?$/.test($(this).val().trim()))
			$(this).addClass("is-valid").removeClass("is-invalid");
		else
			$(this).addClass("is-invalid").removeClass("is-valid");
	});


	//--// ------------------- //--//
	//--// REGULAR EXPRESSIONS //--//
	//--// ------------------- //--//

	// EXPRESSÃO REGULAR PARA MASCARAR O APELIDO
	$("input[name='alias']").mask("ABBBBB##########################", {
		translation: {
			"#": {
				pattern: /[a-z0-9]/,
				optional: true
			},
			"A": {
				pattern: /[a-z]/
			},
			"B": {
				pattern: /[a-z0-9]/
			}
		}
	});

	// EXPRESSÃO REGULAR PARA MASCARAR A QUANTIDADE, CÓDIGO, ESTOQUE MÍNIMO, ESTOQUE MÁXIMO E FORNECEDOR
	$("input[name='amount'], input[name='code'], input[name='minimum-stock'], input[name='maximum-stock']").mask("00000000");

	// EXPRESSÃO REGULAR PARA MASCARAR O NÚMERO DE CELULAR
	$("input[name='cell-phone']").mask("(00) 00000-0000", { placeholder: $("input[name='cell-phone']").prop("required") ? "(__) _____-____" : "Opcional" });

	// EXPRESSÃO REGULAR PARA MASCARAR O CNPJ
	$("input[name='cnpj']").mask("00.000.000/0000-00", { placeholder: "__.___.___/____-__" });

	// EXPRESSÃO REGULAR PARA MASCARAR O CPF
	$("input[name='cpf']").mask("000.000.000-00", { placeholder: "___.___.___-__" });

	// EXPRESSÃO REGULAR PARA MASCARAR O DESCONTO
	$("input[name='discount']").mask("ZZZ,00", {
		reverse: true,
		translation: {
			"Z": {
				pattern: /\d/,
				optional: true
			}
		}
	});

	// EXPRESSÃO REGULAR PARA MASCARAR O PREÇO BRUTO, PREÇO LÍQUIDO E PREÇO
	$("input[name='gross-price'], input[name='net-price'], input[name='price']").mask("#.##0,00", { reverse: true });

	// EXPRESSÃO REGULAR PARA MASCARAR O NÚMERO
	$("input[name='number']").mask("00000000");

	// EXPRESSÃO REGULAR PARA MASCARAR A QUANTIDADE DE PRODUTOS E QUANTIDADE DE SERVIÇOS
	$("input[id='product-quantity'], input[id='service-quantity']").mask("00000000");

	// EXPRESSÃO REGULAR PARA MASCARAR O NÚMERO DE TELEFONE
	$("input[name='phone']").mask("(00) 0000-0000", { placeholder: $("input[name='phone']").prop("required") ? "(__) ____-____" : "Opcional" });

	// EXPRESSÃO REGULAR PARA MASCARAR O CEP
	$("input[name='postal-code']").mask("00000-000", { placeholder: $("input[name='postal-code']").prop("required") ? "_____-___" : "Opcional" });

	// EXPRESSÃO REGULAR PARA MASCARAR O RG
	$("input[name='rg']").mask("0Z.000.000-0", {
		reverse: true,
		translation: {
			"Z": {
				pattern: /\d/,
				optional: true
			}
		}
	});

	// EXPRESSÃO REGULAR PARA MASCARAR A INSCRIÇÃO ESTADUAL
	$("input[name='state-registration']").mask("000.000.000.000", { placeholder: $("input[name='state-registration']").prop("required") ? "___.___.___.___" : "Opcional" });

	// EXPRESSÃO REGULAR PARA MASCARAR O PESO
	$("input[name='weigth']").mask("#.##0,000", { reverse: true });

	// EXPRESSÃO REGULAR PARA MASCARAR A CARGA DE TRABALHO
	$("input[name='workload']").mask("00:00");


	//--// -------------- //--//
	//--// FORM FUNCTIONS //--//
	//--// -------------- //--//

	// CRIAÇÃO DE NOVOS CAMPOS PARA PRODUTOS
	$("button[id='add-product']").click(function () {
		if (parseInt($("input[id='product-quantity']").val().trim()) >= 1 && $("datalist[id='products'] option").map(function () { return $(this).val(); }).get().includes($("input[id='product']").val().trim()) && !$("input[name='products[]']").map(function () { return $(this).val(); }).get().includes($("input[id='product']").val().trim())) {
			let priceValue = parseFloat($("datalist[id='products-prices'] option[value='" + $("input[id='product']").val() + "']").text());
			let quantityValue = parseInt($("input[id='product-quantity']").val());
			let totalValue = parseFloat($("input[name='total']").val());
			let product = "<div class='col-md-7 form-group'><label>Produto</label><input class='form-control' readonly type='text' value='" + $("input[id='product-mask']").val() + "'/><input class='form-control' name='products[]' type='hidden' value='" + $("input[id='product']").val() + "'/></div>";
			let price = "<div class='col-md-2 form-group'><label>Preço Unitário</label><input class='form-control' name='products-prices[]' readonly type='text' value='" + priceValue + "'/></div>";
			let quantity = "<div class='col-md-2 form-group'><label>Quantidade</label><input class='form-control' name='products-quantities[]' min='1' type='number' value='" + $("input[id='product-quantity']").val() + "'/></div>";
			let remove = "<div class='col-md-1 form-group'><label for='product'></label><button class='btn btn-danger mt-2' data-remove='remove-product' type='button'>Remover</button></div>";
			$("div[id='other-products']").prepend("<div class='form-row'></div>");
			$("div[id='other-products']").children().first().prepend(product, price, quantity, remove);
			$("input[id='product'], input[id='product-mask'], input[id='product-quantity']").val("");
			$("input[name='total']").val((totalValue ? totalValue + (priceValue * quantityValue) : priceValue * quantityValue).toFixed(2));
			$("input[name='total']").data("value", parseFloat($("input[name='total']").data("value")) + (priceValue * quantityValue));
			$("input[id='product-mask']").removeClass("is-valid");
			$("input[id='product-quantity']").removeClass("is-invalid is-valid");
		}
	});

	// CRIAÇÃO DE NOVOS CAMPOS PARA SERVIÇOS
	$("button[id='add-service']").click(function () {
		if (parseInt($("input[id='service-quantity']").val().trim()) >= 1 && $("datalist[id='services'] option").map(function () { return $(this).val(); }).get().includes($("input[id='service']").val().trim()) && !$("input[name='services[]']").map(function () { return $(this).val(); }).get().includes($("input[id='service']").val().trim())) {
			let priceValue = parseFloat($("datalist[id='services-prices'] option[value='" + $("input[id='service']").val() + "']").text());
			let quantityValue = parseInt($("input[id='service-quantity']").val());
			let totalValue = parseFloat($("input[name='total']").val());
			let service = "<div class='col-md-7 form-group'><label>Serviço</label><input class='form-control' readonly type='text' value='" + $("input[id='service-mask']").val() + "'/><input class='form-control' name='services[]' type='hidden' value='" + $("input[id='service']").val() + "'/></div>";
			let price = "<div class='col-md-2 form-group'><label>Preço Unitário</label><input class='form-control' name='services-prices[]' readonly type='text' value='" + priceValue + "'/></div>";
			let quantity = "<div class='col-md-2 form-group'><label>Quantidade</label><input class='form-control' name='services-quantities[]' min='1' type='number' value='" + $("input[id='service-quantity']").val() + "'/></div>";
			let remove = "<div class='col-md-1 form-group'><label for='service'></label><button class='btn btn-danger mt-2' data-remove='remove-service' type='button'>Remover</button></div>";
			$("div[id='other-services']").prepend("<div class='form-row'></div>");
			$("div[id='other-services']").children().first().prepend(service, price, quantity, remove);
			$("input[id='service'], input[id='service-mask'], input[id='service-quantity']").val("");
			$("input[name='total']").val((totalValue ? totalValue + (priceValue * quantityValue) : priceValue * quantityValue).toFixed(2));
			$("input[name='total']").data("value", parseFloat($("input[name='total']").data("value")) + (priceValue * quantityValue));
			$("input[id='service-mask']").removeClass("is-valid");
			$("input[id='service-quantity']").removeClass("is-invalid is-valid");
		}
	});

	// REMOÇÃO DOS CAMPOS DE PRODUTOS
	$(document).on("click", "button[data-remove='remove-product']", function () {
		let unitPrice = parseFloat($(this).parent().parent().find("input[name='products-prices[]']").val());
		let productQuantity = parseFloat($(this).parent().parent().find("input[name='products-quantities[]']").val());
		let discount = parseFloat($("input[name='discount']").val().replace(',', '.'));
		$("input[name='total']").data("value", parseFloat($("input[name='total']").data("value")) - (unitPrice * productQuantity));
		$("input[name='total']").val(parseFloat($("input[name='total']").data("value")) - (parseFloat($("input[name='total']").data("value")) * (discount ? discount / 100 : 1)));
		$(this).parent().parent().remove();
	});

	// REMOÇÃO DOS CAMPOS DE SERVIÇOS
	$(document).on("click", "button[data-remove='remove-service']", function () {
		let unitPrice = parseFloat($(this).parent().parent().find("input[name='services-prices[]']").val());
		let serviceQuantity = parseFloat($(this).parent().parent().find("input[name='services-quantities[]']").val());
		let discount = parseFloat($("input[name='discount']").val().replace(',', '.'));
		$("input[name='total']").data("value", parseFloat($("input[name='total']").data("value")) - (unitPrice * serviceQuantity));
		$("input[name='total']").val(parseFloat($("input[name='total']").data("value")) - (parseFloat($("input[name='total']").data("value")) * (discount ? discount / 100 : 1)));
		$(this).parent().parent().remove();
	});

	// ALTERAÇÃO DE PREÇO AO MUDAR A QUANTIDADE DE PRODUTOS
	$(document).on("click keyup", "input[name='products-quantities[]']", function () {
		let total = 0.0;
		$("input[name='products-quantities[]']").each(function () {
			let unitPrice = parseFloat($(this).parent().parent().find("input[name='products-prices[]']").val());
			let productQuantity = parseFloat($(this).val());
			total += unitPrice * productQuantity;
		});
		$("input[name='services-quantities[]']").each(function () {
			let unitPrice = parseFloat($(this).parent().parent().find("input[name='services-prices[]']").val());
			let serviceQuantity = parseFloat($(this).val());
			total += unitPrice * serviceQuantity;
		});
		let discount = parseFloat($("input[name='discount']").val().replace(',', '.'));
		$("input[name='total']").data("value", total);
		$("input[name='total']").val((total - (discount ? total * discount / 100 : 0)).toFixed(2));
	});

	// ALTERAÇÃO DE PREÇO AO MUDAR A QUANTIDADE DE SERVIÇOS
	$(document).on("click keyup", "input[name='services-quantities[]']", function () {
		let total = 0.0;
		$("input[name='services-quantities[]']").each(function () {
			let unitPrice = parseFloat($(this).parent().parent().find("input[name='services-prices[]']").val());
			let serviceQuantity = parseFloat($(this).val());
			total += unitPrice * serviceQuantity;
		});
		$("input[name='products-quantities[]']").each(function () {
			let unitPrice = parseFloat($(this).parent().parent().find("input[name='products-prices[]']").val());
			let productQuantity = parseFloat($(this).val());
			total += unitPrice * productQuantity;
		});
		let discount = parseFloat($("input[name='discount']").val().replace(',', '.'));
		$("input[name='total']").data("value", total);
		$("input[name='total']").val((total - (discount ? total * discount / 100 : 0)).toFixed(2));
	});

	// LIMPEZA DE TODOS OS CAMPOS DO FORMULÁRIO
	$("input[type='reset']").click(function () { reset(true); });
	function reset(message = false) {
		if ($("form").attr("method") == "post" && $("form[method='post']").attr("name").substring(0, 6) == "insert") {
			$("form").get(0).reset();
			$("div[id='other-products']").empty();
			$("div[id='other-services']").empty();
		}
		$("input").removeClass("is-valid is-invalid");
		$("select").removeClass("is-valid is-invalid");
		$("textarea").removeClass("is-valid is-invalid");
		if (message)
			$("#flash-message").hide();
		// $("input[type='date'], input[type='text']").val("");
		// $("select").prop("selectedindex", 0);
		// $("textarea").html("");
		// $("input[type='file']").val("");
		// $("input").removeattr("checked value");
	}

	// PESQUISA AO VIVO (LIVE SEARCH)
	$("input[type='search']").keyup(function () {
		let value = $(this).val().toLowerCase();
		$("tbody tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	// VALIDAÇÃO DE TODOS OS CAMPOS PREENCHIDOS DO FORMULÁRIO E ENVIO PARA O SERVIDOR
	$("form[method='post']").submit(function () {
		let error = false;
		$("input, textarea").each(function () { // VALIDAÇÃO DE TODOS OS CAMPOS "INPUT, TEXTAREA"
			if ($(this).hasClass("is-invalid"))
				error = true;
			if ($(this).prop("required") && ($(this).val().length == 0 || $(this).val().length < $(this).attr("minlength") || $(this).val().length > $(this).attr("maxlength"))) {
				$(this).addClass("is-invalid");
				error = true;
			}
		});
		$("select").each(function () { // VALIDAÇÃO DE TODOS OS CAMPOS "SELECT"
			if ($(this).prop("required") && !$(this).val()) {
				$(this).addClass("is-invalid");
				error = true;
			}
		});
		if (error) {
			$("#flash-message").text("Não foi possível efetuar " + ($("form[method='post']").attr("name").substring(0, 6) == "insert" ? "o cadastro" : "a alteração") + ", preencha corretamente todos os campos obrigatórios.");
			$("#flash-message").addClass("alert-danger").removeClass("alert-success alert-primary alert-warning");
			$("#flash-message").show();
			return false;
		}

		$.ajax({
			type: "post",
			dataType: "json",
			url: $("form[method='post']").attr("action"),
			async: true,
			data: $("form[method='post']").serialize(),
			encode: true,
			beforeSend: function () {
				$("form input, form select, form textarea").prop("disabled", true);
				$("#flash-message").hide();
				$("#loading-operation").show();
			},
			complete: function () {
				$("form input, form select, form textarea").prop("disabled", false);
				$("#loading-operation").hide();
				$("#flash-message").show();
			},
			success: function (response) {
				$("#flash-message").text(response.message);
				if (response.success) {
					$("#flash-message").addClass("alert-success").removeClass("alert-danger alert-primary alert-warning");
					reset();
				}
				else
					$("#flash-message").addClass("alert-danger").removeClass("alert-success alert-primary alert-warning");
			},
			error: function (jqXHR, _, __) {
				const message = jqXHR?.responseJSON?.message || "Não foi possível efetuar a operação, houve um erro de comunicação com o servidor.";
				$("#flash-message").addClass(jqXHR.status >= 500 ? "alert-danger" : "alert-warning").removeClass("alert-success alert-primary" + (jqXHR.status >= 500 ? "alert-danger" : "alert-warning"));
				$("#flash-message").text(message);
			}
		});
		return false;
	});

	// CONSULTA DE DADOS PARA SEREM EXIBIDOS EM TABELA
	$("form[method='get']").submit(function () {
		$.ajax({
			type: "get",
			dataType: "json",
			url: $("form[method='get']").attr("action"),
			async: true,
			data: $("form[method='get']").serialize(),
			encode: true,
			beforeSend: function () {
				$("form input").prop("disabled", true);
				$("#flash-message").hide();
				$("#loading-operation").show();
			},
			complete: function () {
				$("form input").prop("disabled", false);
				$("form input").val("");
				$("#loading-operation").hide();
				$("#flash-message").show();
			},
			success: function (response) {
				$("#flash-message").text(response.message);
				if (response.success) {
					$("#flash-message").addClass("alert-primary").removeClass("alert-danger alert-warning");
					const pathname = window.location.pathname.split("/").filter(e => e);
					const page = pathname[1] || "";
					const base = "/" + pathname[0] + (page ? "/" : "");
					let attributes = $("table thead tr th").map(function (_) {
						return $(this).attr("id");
					});
					if (response.data)
						$("table tbody").empty();
					response.data.forEach(function (tuple) {
						let row = "<tr id='" + tuple['id'] + "'>";
						attributes.each(function (_, attribute) {
							if (["view", "update", "delete", "manage"].includes(attribute)) {
								if(["view", "manage"].includes(attribute))
									row += "<td><a href='" + base + page + "/view/?id=" + tuple['id'] + "'><span class='badge badge-pill badge-success'>Visualizar</span></a> ";
								if(["update", "manage"].includes(attribute))
									row += "<a href='" + base + page + "/update/?id=" + tuple['id'] + "'><span class='badge badge-pill badge-primary'>Alterar</span></a> ";
								if(["delete", "manage"].includes(attribute))
									row += "<a data-action='remove-btn' data-id='" + tuple['id'] + "' data-model='" + base + "action/" + page + "/delete/' data-target='#remove-modal' data-toggle='modal' href='#'><span class='badge badge-pill badge-danger'>Remover</span></a></td>";
							}
							else
								row += "<td>" + tuple[attribute] + "</td>";
						});
						row += "</tr>";
						$("table tbody").append(row);
					});
				}
				else
					$("#flash-message").addClass("alert-warning").removeClass("alert-success alert-primary");
			},
			error: function (jqXHR, _, __) {
				const message = jqXHR?.responseJSON?.message || "Não foi possível efetuar a operação, houve um erro de comunicação com o servidor.";
				$("#flash-message").text(message);
				$("#flash-message").addClass(jqXHR.status >= 500 ? "alert-danger" : "alert-warning").removeClass("alert-success alert-primary" + (jqXHR.status >= 500 ? "alert-danger" : "alert-warning"));
			}
		});
		return false;
	});
});
