	<?php require_once HEADER; ?>

	<div class="container mb-5" id="content">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Produto" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/product-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once SETTINGS; ?>

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- DADOS DO PRODUTO -->
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
				<h3>Fornecedor</h3>
				<p><a class="text-dark" href="<?=BASE_URL?>provider/view/?id=<?=$tuple->provider->id?>"><?=$tuple->provider->fantasy_name?></a></p>
			</div>

			<div class="col-md-3">
				<h3>Unidade</h3>
				<p><?=$tuple->unity?></p>
			</div>

			<div class="col-md-3">
				<h3>Preço Bruto</h3>
				<p><?=$tuple->gross_price?></p>
			</div>

			<div class="col-md-3">
				<h3>Preço Líquido</h3>
				<p><?=$tuple->net_price?></p>
			</div>

			<div class="col-md-3">
				<h3>Estoque Mínimo</h3>
				<p><?=$tuple->minimum_stock?></p>
			</div>

			<div class="col-md-3">
				<h3>Estoque Máximo</h3>
				<p><?=$tuple->maximum_stock?></p>
			</div>

			<div class="col-md-3">
				<h3>Quantidade</h3>
				<p><?=$tuple->amount?></p>
			</div>

			<div class="col-md-3">
				<h3>Peso</h3>
				<p><?=$tuple->weigth?> kg</p>
			</div>

			<div class="col-md-3">
				<h3>Situação</h3>
				<p><?=$tuple->situation?></p>
			</div>

			<div class="col-md-3">
				<h3>Origem</h3>
				<p><?=$tuple->source?></p>
			</div>
		</div>
		<!--/ DADOS DO PRODUTO -->

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
