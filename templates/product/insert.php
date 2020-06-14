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

		<!-- FORMULÁRIO DE CADASTRO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/insert/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="insert-product" method="post" name="insert-product" rel="noopener" target="_self">
			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="code">Código</label>
					<input autofocus class="form-control" form="insert-product" id="code" name="code" pattern="[0-9]+" placeholder="" required type="text"/>
				</div>
				<div class="col-md-8 form-group">
					<label for="name">Nome</label>
					<input class="form-control" form="insert-product" id="name" maxlength="32" minlength="2" name="name" placeholder="" required type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="provider-mask">Fornecedor</label>
					<input class="form-control" form="insert-product" id="provider-mask" list="providers" maxlength="32" minlength="2" placeholder="" required type="text"/>
					<input class="form-control" form="insert-product" id="provider" name="provider" type="hidden"/>
				</div>
				<datalist id="providers">
					<?php foreach($providers as $provider): ?>
					<option value="<?=$provider->id?>"><?=$provider->fantasy_name?></option>
					<?php endforeach; ?>
				</datalist>
				<div class="col-md-3 form-group">
					<label for="unity">Unidade</label>
					<select class="form-control" form="insert-product" id="unity" name="unity" required>
						<option disabled selected value="">Escolha uma unidade</option>
						<option value="cm">cm</option>
						<option value="cm2">cm2</option>
						<option value="CT">CT</option>
						<option value="CX">CX</option>
						<option value="DZ">DZ</option>
						<option value="g">g</option>
						<option value="GS">GS</option>
						<option value="kg">kg</option>
						<option value="l">l</option>
						<option value="m">m</option>
						<option value="m2">m2</option>
						<option value="m3">m3</option>
						<option value="ml">ml</option>
						<option value="PA">PA</option>
						<option value="PÇ">PÇ</option>
						<option value="PR">PR</option>
						<option value="PT">PT</option>
						<option value="RL">RL</option>
						<option value="UN">UN</option>
					</select>
				</div>
				<div class="col-md-2 form-group">
					<label for="gross-price">Preço Bruto</label>
					<input class="form-control" form="insert-product" id="gross-price" maxlength="12" minlength="1" name="gross-price" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="net-price">Preço Líquido</label>
					<input class="form-control" form="insert-product" id="net-price" maxlength="12" minlength="1" name="net-price" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="minimum-stock">Estoque Mínimo</label>
					<input class="form-control" form="insert-product" id="minimum-stock" name="minimum-stock" pattern="[0-9]+" placeholder="" required type="text"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="maximum-stock">Estoque Máximo</label>
					<input class="form-control" form="insert-product" id="maximum-stock" name="maximum-stock" pattern="[0-9]+" placeholder="" required type="text"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="amount">Quantidade</label>
					<input class="form-control" form="insert-product" id="amount" min="0" name="amount" placeholder="" required type="number"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="weigth">Peso</label>
					<input class="form-control" form="insert-product" id="weigth" maxlength="32" minlength="2" name="weigth" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="situation">Situação</label>
					<select class="form-control" form="insert-product" id="situation" name="situation" required>
						<option disabled selected value="">Escolha uma situação</option>
						<option value="0">Inativo</option>
						<option value="1">Ativo</option>
					</select>
				</div>
				<div class="col-md-2 form-group">
					<label for="source">Origem</label>
					<select class="form-control" form="insert-product" id="source" name="source" required>
						<option disabled selected value="">Escolha uma origem</option>
						<option value="0">Nacional</option>
						<option value="1">Internacional</option>
					</select>
				</div>
			</div>

			<input class="btn btn-primary" form="insert-product" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="insert-product" type="reset" value="Limpar"/>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE CADASTRO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
