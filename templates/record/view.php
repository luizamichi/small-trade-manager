<?php require_once HEADER; ?>

<div class="container mb-5" id="content">
	<!-- CABEÇALHO -->
	<div class="my-3 text-center">
		<img alt="Cliente" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/record-color.svg" width="75"/>
		<h2><?=PAGE_TITLE?></h2>
		<hr/>
	</div>
	<!--/ CABEÇALHO -->

	<?php require_once SETTINGS; ?>

	<!-- DADOS DO REGISTRO -->
	<div class="row">
		<div class="col-md-3">
			<h3>Referência</h3>
			<p><?=$tuple->reference?></p>
		</div>

		<div class="col-md-3">
			<h3>Ação</h3>
			<p><?=$tuple->action?></p>
		</div>

		<div class="col-md-3">
			<h3>Entidade</h3>
			<p><?=$tuple->entity?></p>
		</div>

		<div class="col-md-3">
			<h3>Funcionário</h3>
			<p><a class="text-dark" href="<?=BASE_URL?>employee/view/?id=<?=$tuple->employee->id?>"><?=$tuple->employee->name?> <?=$tuple->employee->surname?></a></p>
		</div>

		<?php if($tuple->description): ?>
		<div class="col-md-12">
			<h3>Descrição</h3>
			<?php foreach($tuple->description as $key => $value): ?>
				<p><strong><?=$key?>:</strong> <?=is_array($value) || is_object($value) ? json_encode($value, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : $value?></p>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="col-md-3">
			<h3>Dia</h3>
			<p><?=$tuple->day?></p>
		</div>
	</div>
	<!--/ DADOS DO REGISTRO -->

	<div class="pb-3 row" id="buttons">
		<div class="col-md-2 mb-2">
			<button class="btn btn-block btn-outline-dark" id="print-content">Imprimir</button>
		</div>
	</div>
</div>

<?php require_once FOOTER; ?>

</body>

</html>
