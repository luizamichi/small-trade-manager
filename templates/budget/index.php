	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Orçamento" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/budget-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/select/" autocomplete="on" class="form-inline justify-content-center" enctype="application/x-www-form-urlencoded" id="search-budget" method="get" name="search-budget" rel="noopener" target="_self">
			<div class="align-items-center form-row">
				<div class="col-auto">
					<label class="sr-only" for="search">Procurar</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">&#128270;</div>
						</div>
						<input class="form-control" form="search-budget" id="search" minlength="1" name="all" placeholder="Procurar" required type="search"/>
					</div>
				</div>
				<div class="col-auto">
					<a class="btn btn-outline-dark mb-2" href="<?=BASE_URL . PAGE_NAME?>/insert/" role="button">Cadastrar</a>
				</div>
			</div>
		</form>

		<!-- LISTA DE ORÇAMENTOS -->
		<div class="table-responsive">
			<table class="table table-hover table-sm">
				<caption></caption>
				<thead class="thead-light">
					<tr>
						<th id="employee" scope="row">Funcionário</th>
						<th id="day" scope="row">Dia</th>
						<th id="discount" scope="row">Desconto</th>
						<th id="total" scope="row">Preço Total</th>
						<th id="manage" scope="row">Gerenciamento</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($tuples as $tuple): ?>
					<tr class="text-truncate" id="<?=$tuple->id?>">
						<td><?=$tuple->employee->name?></td>
						<td><?=$tuple->day?></td>
						<td><?=$tuple->discount?></td>
						<td><?=$tuple->total?></td>
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
		<!--/ LISTA DE ORÇAMENTOS -->
	</div>

	<?php require_once REMOVE; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
