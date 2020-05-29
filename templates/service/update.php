	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Serviço" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/service-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- FORMULÁRIO DE ALTERAÇÃO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/update/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="update-service" method="post" name="update-service" rel="noopener" target="_self">
			<input form="update-service" id="id" name="id" type="hidden" value="<?=$tuple->id?>"/>
			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="code">Código</label>
					<input autofocus class="form-control" form="update-service" id="code" name="code" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->code?>"/>
				</div>
				<div class="col-md-8 form-group">
					<label for="name">Nome</label>
					<input class="form-control" form="update-service" id="name" maxlength="32" minlength="2" name="name" placeholder="" required type="text" value="<?=$tuple->name?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="type">Tipo</label>
					<input class="form-control" form="update-service" id="type" maxlength="32" minlength="2" name="type" placeholder="" required type="text" value="<?=$tuple->type?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="price">Preço</label>
					<input class="form-control" form="update-service" id="price" maxlength="12" minlength="1" name="price" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text" value="<?=$tuple->price?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="workload">Carga de Trabalho</label>
					<input class="form-control" form="update-service" id="workload" name="workload" required type="time" value="<?=$tuple->workload?>"/>
				</div>
			</div>

			<input class="btn btn-primary" form="update-service" type="submit" value="Alterar"/>
			<button class="btn btn-danger" data-target="#remove-modal" data-toggle="modal" type="button">Remover</button>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE ALTERAÇÃO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once REMOVE; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
