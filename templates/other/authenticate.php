<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link href="<?=BASE_URL?>img/index-color.svg" hreflang="pt-br" rel="icon" type="image/svg+xml"/>
	<link href="<?=BASE_URL?>css/bootstrap.css" hreflang="en" rel="stylesheet" type="text/css"/>
	<meta charset="utf-8"/>
	<meta content="Luiz Joaquim Aderaldo Amichi" name="author"/>
	<meta content="Gerenciador de clientes, compras, fornecedores, funcionários, orçamentos, produtos, serviços e vendas" name="description"/>
	<meta content="Cliente, CMS, comércio, compra, controle, estoque, fornecedor, funcionário, gerenciador, orçamento, produto, relatório, serviço, venda" name="keywords"/>
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<title>Small Trade Manager &#8211; <?=PAGE_TITLE?></title>

	<style>
		body {
			font-family: "Lato", sans-serif;
		}

		.main-head {
			height: 150px;
			background: #fff;
		}

		.sidenav {
			height: 100%;
			background-color: #000;
			overflow-x: hidden;
			padding-top: 20px;
		}

		.main {
			padding: 0px 10px;
		}

		@media screen and (max-height: 450px) {
			.sidenav {
				padding-top: 15px;
			}
		}

		@media screen and (max-width: 450px) {
			.login-form {
				margin-top: 10%;
			}

			.register-form {
				margin-top: 10%;
			}
		}

		@media screen and (max-width: 768px) {
			.login-main-text {
				margin-top: 10%;
				padding: 20px;
			}

			.login-main-text img {
				width: 120px;
				height: auto;
				margin-bottom: 20px;
			}

			.login-main-text h1 {
				font-size: 1.5rem;
			}
		}

		@media screen and (min-width: 768px) {
			.main {
				margin-left: 40%;
			}

			.sidenav {
				width: 40%;
				position: fixed;
				z-index: 1;
				top: 0;
				left: 0;
			}

			.login-form {
				margin-top: 80%;
			}

			.register-form {
				margin-top: 20%;
			}
		}

		.login-main-text {
			margin-top: 20%;
			padding: 60px;
			color: #fff;
		}

		.login-main-text img {
			filter: invert(100%);
		}

		.btn-black {
			background-color: #000 !important;
			color: white;
		}

		.btn-black:hover {
			background-color: #2f343a !important;
			color: #f8f9fa;
		}
	</style>
</head>

<body class="bg-light">

	<div class="sidenav">
		<div class="login-main-text">
			<img alt="Small Trade Manager" class="img-fluid" height="175" src="<?=BASE_URL?>img/index.svg" width="175"/>
			<h1 class="display-3">Small Trade Manager</h1>
			<p class="lead">Autentique-se para acessar o sistema.</p>
		</div>
	</div>

	<div class="main">
		<div class="col-md-6 col-sm-12">
			<div class="login-form">
				<!-- FORMULÁRIO DE AUTENTICAÇÃO -->
				<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/authenticate/" autocomplete="on" class="mb-3" enctype="application/x-www-form-urlencoded" id="authenticate" method="post" name="authenticate" rel="noopener" target="_self">
					<div class="form-group">
						<label for="alias">Login</label>
						<input autofocus class="form-control" form="authenticate" id="alias" maxlength="32" minlength="2" name="alias" placeholder="" required type="text"/>
					</div>

					<div class="form-group">
						<label for="password">Senha</label>
						<input class="form-control" form="authenticate" id="password" minlength="2" name="password" placeholder="" required type="password"/>
					</div>

					<input class="btn btn-block btn-black" form="authenticate" type="submit" value="Entrar"/>
				</form>
				<!--/ FORMULÁRIO DE AUTENTICAÇÃO -->

				<?php require_once FLASH; ?>

				<?php require_once LOADING; ?>

			</div>
		</div>
	</div>

	<!-- SCRIPTS -->
	<script src="<?=BASE_URL?>js/jquery.js"></script>
	<script src="<?=BASE_URL?>js/bootstrap.js"></script>

	<script>
		$(document).ready(function () {
			$("#flash-message").hide();
			$("#loading-operation").hide();

			$("form[method='post']").submit(function () {
				$.ajax({
					type: "post",
					dataType: "json",
					url: $("form[method='post']").attr("action"),
					async: true,
					data: $("form[method='post']").serialize(),
					beforeSend: function () {
						$("#flash-message").hide();
						$("#loading-operation").show();
						$("input").prop("disabled", true);
					},
					complete: function () {
						$("#loading-operation").hide();
						$("input").prop("disabled", false);
					},
					success: function (response) {
						$("#flash-message").text(response.message);
						if(response.success) {
							$("#flash-message").addClass("alert-success").removeClass("alert-danger");
							$("#flash-message").show();
							window.location.href = "<?=BASE_URL?>";
						}
						else {
							$("#flash-message").addClass("alert-danger");
							$("#flash-message").show();
						}
					},
					error: function (jqXHR, _, _) {
						$("#flash-message").text(jqXHR?.responseJSON?.message || "Não foi possível efetuar a autenticação, houve um erro de comunicação com o servidor.");
						$("#flash-message").addClass("alert-danger");
						$("#flash-message").show();
					}
				});
				return false;
			});
		});
	</script>

</body>

</html>
