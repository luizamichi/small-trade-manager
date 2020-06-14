	<?php require_once HEADER; ?>

	<div class="container mb-5" id="content">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Serviço" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/service-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once SETTINGS; ?>

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- DADOS DO SERVIÇO -->
		<div class="row">
			<div class="col-md-3">
				<h3>Código</h3>
				<p><?=$tuple->code?></p>
			</div>

			<div class="col-md-3">
				<h3>Nome</h3>
				<p><?=$tuple->name?></p>
			</div>

			<div class="col-md-3">
				<h3>Tipo</h3>
				<p><?=$tuple->type?></p>
			</div>

			<div class="col-md-3">
				<h3>Preço</h3>
				<p><?=$tuple->price?></p>
			</div>

			<div class="col-md-3">
				<h3>Carga de Trabalho</h3>
				<p><?=$tuple->workload?></p>
			</div>
		</div>
		<!--/ DADOS DO SERVIÇO -->

		<div class="pb-3 row" id="buttons">
			<div class="col-md-2 mb-2">
				<a class="btn btn-block btn-primary" href="<?=BASE_URL . PAGE_NAME?>/update/?id=<?=$tuple->id?>">Alterar</a>
			</div>
			<div class="col-md-2 mb-2">
				<button class="btn btn-block btn-danger" data-target="#remove-modal" data-toggle="modal" type="button">Remover</button>
			</div>
			<div class="col-md-2 mb-2">
				<button class="btn btn-block btn-outline-dark" id="print-content">Imprimir</button>
			</div>
		</div>
	</div>

	<?php require_once REMOVE; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
