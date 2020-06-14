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

		<!-- FORMULÁRIO DE ALTERAÇÃO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/update/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="update-budget" method="post" name="update-budget" rel="noopener" target="_self">
			<input form="update-budget" id="id" name="id" type="hidden" value="<?=$tuple->id?>"/>
			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="product-mask">Produtos</label>
					<input class="form-control" form="update-budget" id="product-mask" list="products" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="update-budget" id="product" type="hidden"/>
				</div>
				<datalist id="products">
					<?php foreach($products as $product): ?>
					<option value="<?=$product->id?>"><?=$product->name?></option>
					<?php endforeach; ?>
				</datalist>
				<datalist id="products-prices">
					<?php foreach($products as $product): ?>
					<option value="<?=$product->id?>"><?=$product->net_price?></option>
					<?php endforeach; ?>
				</datalist>
				<div class="col-md-3 form-group">
					<label for="product-quantity">Quantidade</label>
					<input class="form-control" form="update-budget" id="product-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="product"></label>
					<button class="btn btn-success mt-2" id="add-product" type="button">Adicionar</button>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="service-mask">Serviços</label>
					<input class="form-control" form="update-budget" id="service-mask" list="services" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="update-budget" id="service" type="hidden"/>
				</div>
				<datalist id="services">
					<?php foreach($services as $service): ?>
					<option value="<?=$service->id?>"><?=$service->name?></option>
					<?php endforeach; ?>
				</datalist>
				<datalist id="services-prices">
					<?php foreach($services as $service): ?>
					<option value="<?=$service->id?>"><?=$service->price?></option>
					<?php endforeach; ?>
				</datalist>
				<div class="col-md-3 form-group">
					<label for="service-quantity">Quantidade</label>
					<input class="form-control" form="update-budget" id="service-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="service"></label>
					<button class="btn btn-success mt-2" id="add-service" type="button">Adicionar</button>
				</div>
			</div>

			<div id="other-products">
				<?php foreach($tuple->products as $product): ?>
				<div class="form-row">
					<div class="col-md-7 form-group">
						<label for="product">Produto</label>
						<input class="form-control" form="update-budget" readonly type="text" value="<?=$product->name?>"/>
						<input class="form-control" form="update-budget" name="products[]" type="hidden" value="<?=$product->id?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="price">Preço Unitário</label>
						<input class="form-control" form="update-budget" name="products-prices[]" readonly type="text" value="<?=$product->unit_price?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="quantity">Quantidade</label>
						<input class="form-control" form="update-budget" min="1" name="products-quantities[]" type="number" value="<?=$product->quantity?>"/>
					</div>
					<div class="col-md-1 form-group">
						<label for="product"></label>
						<button class="btn btn-danger mt-2" data-remove="remove-product" type="button">Remover</button>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div id="other-services">
				<?php foreach($tuple->services as $service): ?>
				<div class="form-row">
					<div class="col-md-7 form-group">
						<label for="service">Serviço</label>
						<input class="form-control" form="update-budget" readonly type="text" value="<?=$service->name?>"/>
						<input class="form-control" form="update-budget" name="services[]" type="hidden" value="<?=$service->id?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="price">Preço Unitário</label>
						<input class="form-control" form="update-budget" name="services-prices[]" readonly type="text" value="<?=$service->unit_price?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="quantity">Quantidade</label>
						<input class="form-control" form="update-budget" min="1" name="services-quantities[]" type="number" value="<?=$service->quantity?>"/>
					</div>
					<div class="col-md-1 form-group">
						<label for="service"></label>
						<button class="btn btn-danger mt-2" data-remove="remove-service" type="button">Remover</button>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="form-of-payment">Forma de Pagamento</label>
					<select class="form-control" form="update-budget" id="form-of-payment" name="form-of-payment" required>
						<option disabled value="">Escolha uma forma de pagamento</option>
						<option <?=$tuple->form_of_payment == 'Dinheiro' ? 'selected' : ''?> value="Dinheiro">Dinheiro</option>
						<option <?=$tuple->form_of_payment == 'A prazo' ? 'selected' : ''?> value="A prazo">A prazo</option>
						<optgroup label="Transferência Bancária">
							<option <?=$tuple->form_of_payment == 'DOC' ? 'selected' : ''?> value="DOC">DOC</option>
							<option <?=$tuple->form_of_payment == 'PIX' ? 'selected' : ''?> value="PIX">PIX</option>
							<option <?=$tuple->form_of_payment == 'TED' ? 'selected' : ''?> value="TED">TED</option>
						</optgroup>
						<optgroup label="TEF">
							<option <?=$tuple->form_of_payment == 'Cartão de crédito' ? 'selected' : ''?> value="Cartão de crédito">Cartão de crédito</option>
							<option <?=$tuple->form_of_payment == 'Cartão de débito' ? 'selected' : ''?> value="Cartão de débito">Cartão de débito</option>
						</optgroup>
					</select>
				</div>
				<div class="col-md-3 form-group">
					<label for="discount">Desconto</label>
					<input class="form-control" form="update-budget" id="discount" maxlength="6" minlength="4" name="discount" pattern="[0-9]{1},[0-9]{2}|[0-9]{2},[0-9]{2}|[0-9]{3},[0-9]{2}" placeholder="Opcional" type="text" value="<?=$tuple->discount?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="total">Total</label>
					<input class="form-control" data-value="<?=$tuple->total?>" form="update-budget" id="total" maxlength="12" minlength="1" name="total" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" readonly required type="text" value="<?=$tuple->total?>"/>
				</div>
			</div>

			<input class="btn btn-primary" form="update-budget" type="submit" value="Alterar"/>
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
