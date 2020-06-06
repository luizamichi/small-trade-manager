	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Registro" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/record-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/select/" autocomplete="on" class="form-inline justify-content-center" enctype="application/x-www-form-urlencoded" id="search-record" method="get" name="search-record" rel="noopener" target="_self">
			<div class="form-row align-items-center">
				<div class="col-auto">
					<label class="sr-only" for="search">Procurar</label>
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">&#128270;</div>
						</div>
						<input class="form-control" form="search-record" id="search" minlength="1" name="all" placeholder="Procurar" required type="search"/>
					</div>
				</div>
			</div>
		</form>

		<!-- LISTA DE REGISTROS -->
		<div class="table-responsive">
			<table class="table table-hover table-sm">
				<caption></caption>
				<thead class="thead-light">
					<tr>
						<th id="reference" scope="row">Referência</th>
						<th id="action" scope="row">Ação</th>
						<th id="entity" scope="row">Entidade</th>
						<th id="employee" scope="row">Funcionário</th>
						<th id="view" scope="row">Gerenciamento</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($tuples as $tuple): ?>
					<tr class="text-truncate">
						<td><?=$tuple->reference?></td>
						<td><?=$tuple->action?></td>
						<td><?=$tuple->entity?></td>
						<td><?=$tuple->employee->name?></td>
						<td>
							<a href="<?=BASE_URL . PAGE_NAME?>/view/?id=<?=$tuple->id?>"><span class="badge badge-pill badge-success">Visualizar</span></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!--/ LISTA DE REGISTROS -->
	</div>

	<?php require_once FOOTER; ?>

</body>

</html>
