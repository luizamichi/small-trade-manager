<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link href="<?=BASE_URL?>img/logo-color.svg" hreflang="pt-br" rel="icon" type="image/svg+xml" />
	<link href="<?=BASE_URL?>css/bootstrap.css" hreflang="en" rel="stylesheet" type="text/css" />
	<meta charset="utf-8" />
	<meta content="Luiz Joaquim Aderaldo Amichi" name="author" />
	<meta content="Gerenciador de clientes, compras, fornecedores, funcionários, orçamentos, produtos, serviços e vendas" name="description" />
	<meta content="Cliente, CMS, comércio, compra, controle, estoque, fornecedor, funcionário, gerenciador, orçamento, produto, relatório, serviço, venda" name="keywords" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<title>Small Trade Manager &#8211; <?= PAGE_TITLE ?></title>
	<style>
		.d-flex.flex-column.min-vh-100 {
			min-height: 100vh;
		}

		/* Faz o main crescer para ocupar todo espaço disponível */
		.flex-grow-1 {
			flex-grow: 1;
		}
	</style>
</head>

<body class="bg-light">
	<div class="d-flex flex-column min-vh-100">
		<!-- MENU SUPERIOR -->
		<header class="mb-5 pb-4">
			<nav class="bg-dark fixed-top navbar navbar-dark navbar-expand-lg">
				<a class="navbar-brand" href="javascript:void(0)">
					<img alt="Small Trade Manager" class="align-top d-inline-block mx-3" height="30" src="<?=BASE_URL?>img/<?= PAGE_NAME ?>.svg" style="filter: invert(1);" width="30" />
					Small Trade Manager
				</a>
				<button aria-controls="top-menu" aria-expanded="false" aria-label="Menu de navegação" class="navbar-toggler" data-target="#top-menu" data-toggle="collapse" type="button">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="top-menu">
					<ul class="ml-auto navbar-nav">
						<li class="<?=PAGE_NAME != 'index' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>" target="_self">Início</a>
						</li>
						<li class="<?=PAGE_NAME != 'client' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>client/" target="_self">Clientes</a>
						</li>
						<li class="<?=PAGE_NAME != 'purchase' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>purchase/" target="_self">Compras</a>
						</li>
						<li class="<?=PAGE_NAME != 'provider' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>provider/" target="_self">Fornecedores</a>
						</li>
						<li class="<?=PAGE_NAME != 'employee' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>employee/" target="_self">Funcionários</a>
						</li>
						<li class="<?=PAGE_NAME != 'setting' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>setting/" target="_self">Opções</a>
						</li>
						<li class="<?=PAGE_NAME != 'budget' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>budget/" target="_self">Orçamentos</a>
						</li>
						<li class="<?=PAGE_NAME != 'product' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>product/" target="_self">Produtos</a>
						</li>
						<li class="<?=PAGE_NAME != 'record' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>record/" target="_self">Registros</a>
						</li>
						<li class="<?=PAGE_NAME != 'report' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>report/" target="_self">Relatórios</a>
						</li>
						<li class="<?=PAGE_NAME != 'service' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>service/" target="_self">Serviços</a>
						</li>
						<li class="<?=PAGE_NAME != 'sale' ?: 'active'?> nav-item">
							<a class="nav-link" href="<?=BASE_URL?>sale/" target="_self">Vendas</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=BASE_URL?>exit/" target="_self">Sair</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<!--/ MENU SUPERIOR -->

		<!-- CONTEÚDO -->
		<main class="flex-grow-1">