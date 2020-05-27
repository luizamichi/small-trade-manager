	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Produto" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/product-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<form accept-charset="utf-8" class="form-inline justify-content-center" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/select/" autocomplete="on" enctype="application/x-www-form-urlencoded" id="search-product" method="get" name="search-product" rel="noopener" target="_self">
			<div class="form-row align-items-center">
				<div class="col-auto">
					<label class="sr-only" for="search">Procurar</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">&#128270;</div>
						</div>
						<input class="form-control" form="search-product" id="search" minlength="1" name="all" placeholder="Procurar" required type="search"/>
					</div>
				</div>
				<div class="col-auto">
					<a class="btn btn-outline-dark mb-2" href="<?=BASE_URL . PAGE_NAME?>/insert/" role="button">Cadastrar</a>
				</div>
			</div>
		</form>

		<!-- LISTA DE PRODUTOS -->
		<div class="table-responsive">
			<table class="table table-hover table-sm">
				<caption></caption>
				<thead class="thead-light">
					<tr>
						<th id="code" scope="row">Código</th>
						<th id="name" scope="row">Nome</th>
						<th id="provider" scope="row">Fornecedor</th>
						<th id="net_price" scope="row">Preço Líquido</th>
						<th id="manage" scope="row">Gerenciamento</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($tuples as $tuple): ?>
					<tr class="text-truncate" id="<?=$tuple->id?>">
						<td><?=$tuple->code?></td>
						<td><?=$tuple->name?></td>
						<td><?=$tuple->provider->fantasy_name?></td>
						<td><?=$tuple->net_price?></td>
						<td>
							<a href="<?=BASE_URL . PAGE_NAME?>/view/?id=<?=$tuple->id?>"><span class="badge badge-pill badge-success">Visualizar</span></a>
							<a href="<?=BASE_URL . PAGE_NAME?>/update/?id=<?=$tuple->id?>"><span class="badge badge-pill badge-primary">Alterar</span></a>
							<a data-action="remove-btn" data-id="<?=$tuple->id?>" data-model="<?=BASE_URL . 'action/' . PAGE_NAME?>/delete/" data-target="#remove-modal" data-toggle="modal" href="#"><span class="badge badge-pill badge-danger">Remover</span></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!--/ LISTA DE PRODUTOS -->
	</div>

	<?php require_once REMOVE; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
