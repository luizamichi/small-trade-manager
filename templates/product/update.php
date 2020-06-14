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

		<!-- FORMULÁRIO DE ALTERAÇÃO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/update/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="update-product" method="post" name="update-product" rel="noopener" target="_self">
			<input form="update-product" id="id" name="id" type="hidden" value="<?=$tuple->id?>"/>
			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="code">Código</label>
					<input autofocus class="form-control" form="update-product" id="code" name="code" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->code?>"/>
				</div>
				<div class="col-md-8 form-group">
					<label for="name">Nome</label>
					<input class="form-control" form="update-product" id="name" maxlength="32" minlength="2" name="name" placeholder="" required type="text" value="<?=$tuple->name?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="provider-mask">Fornecedor</label>
					<input class="form-control" form="update-product" id="provider-mask" list="providers" maxlength="32" minlength="2" placeholder="" required type="text" value="<?=$tuple->provider->fantasy_name?>"/>
					<input class="form-control" form="update-product" id="provider" name="provider" type="hidden" value="<?=$tuple->provider->id?>"/>
				</div>
				<datalist id="providers">
					<?php foreach($providers as $provider): ?>
					<option value="<?=$provider->id?>"><?=$provider->fantasy_name?></option>
					<?php endforeach; ?>
				</datalist>
				<div class="col-md-3 form-group">
					<label for="unity">Unidade</label>
					<select class="form-control" form="update-product" id="unity" name="unity" required>
						<option disabled value="">Escolha uma unidade</option>
						<option <?=$tuple->unity == 'cm' ? 'selected' : ''?> value="cm">cm</option>
						<option <?=$tuple->unity == 'cm2' ? 'selected' : ''?> value="cm2">cm2</option>
						<option <?=$tuple->unity == 'CT' ? 'selected' : ''?> value="CT">CT</option>
						<option <?=$tuple->unity == 'CX' ? 'selected' : ''?> value="CX">CX</option>
						<option <?=$tuple->unity == 'DZ' ? 'selected' : ''?> value="DZ">DZ</option>
						<option <?=$tuple->unity == 'g' ? 'selected' : ''?> value="g">g</option>
						<option <?=$tuple->unity == 'GS' ? 'selected' : ''?> value="GS">GS</option>
						<option <?=$tuple->unity == 'kg' ? 'selected' : ''?> value="kg">kg</option>
						<option <?=$tuple->unity == 'l' ? 'selected' : ''?> value="l">l</option>
						<option <?=$tuple->unity == 'm' ? 'selected' : ''?> value="m">m</option>
						<option <?=$tuple->unity == 'm2' ? 'selected' : ''?> value="m2">m2</option>
						<option <?=$tuple->unity == 'm3' ? 'selected' : ''?> value="m3">m3</option>
						<option <?=$tuple->unity == 'ml' ? 'selected' : ''?> value="ml">ml</option>
						<option <?=$tuple->unity == 'PA' ? 'selected' : ''?> value="PA">PA</option>
						<option <?=$tuple->unity == 'PÇ' ? 'selected' : ''?> value="PÇ">PÇ</option>
						<option <?=$tuple->unity == 'PR' ? 'selected' : ''?> value="PR">PR</option>
						<option <?=$tuple->unity == 'PT' ? 'selected' : ''?> value="PT">PT</option>
						<option <?=$tuple->unity == 'RL' ? 'selected' : ''?> value="RL">RL</option>
						<option <?=$tuple->unity == 'UN' ? 'selected' : ''?> value="UN">UN</option>
					</select>
				</div>
				<div class="col-md-2 form-group">
					<label for="gross-price">Preço Bruto</label>
					<input class="form-control" form="update-product" id="gross-price" maxlength="12" minlength="1" name="gross-price" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text" value="<?=$tuple->gross_price?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="net-price">Preço Líquido</label>
					<input class="form-control" form="update-product" id="net-price" maxlength="12" minlength="1" name="net-price" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text" value="<?=$tuple->net_price?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="minimum-stock">Estoque Mínimo</label>
					<input class="form-control" form="update-product" id="minimum-stock" name="minimum-stock" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->minimum_stock?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="maximum-stock">Estoque Máximo</label>
					<input class="form-control" form="update-product" id="maximum-stock" name="maximum-stock" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->maximum_stock?>"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="amount">Quantidade</label>
					<input class="form-control" form="update-product" id="amount" min="0" name="amount" placeholder="" required type="number" value="<?=$tuple->amount?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="weigth">Peso</label>
					<input class="form-control" form="update-product" id="weigth" maxlength="32" minlength="2" name="weigth" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" required type="text" value="<?=$tuple->weigth?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="situation">Situação</label>
					<select class="form-control" form="update-product" id="situation" name="situation" required>
						<option disabled value="">Escolha uma situação</option>
						<option <?=$tuple->situation == '0' ? 'selected' : ''?> value="0">Inativo</option>
						<option <?=$tuple->situation == '1' ? 'selected' : ''?> value="1">Ativo</option>
					</select>
				</div>
				<div class="col-md-2 form-group">
					<label for="source">Origem</label>
					<select class="form-control" form="update-product" id="source" name="source" required>
						<option disabled value="">Escolha uma origem</option>
						<option <?=$tuple->source == '0' ? 'selected' : ''?> value="0">Nacional</option>
						<option <?=$tuple->source == '1' ? 'selected' : ''?> value="1">Internacional</option>
					</select>
				</div>
			</div>

			<input class="btn btn-primary" form="update-product" type="submit" value="Alterar"/>
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
