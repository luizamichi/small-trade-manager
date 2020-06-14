	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Venda" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/sale-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- FORMULÁRIO DE CADASTRO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/insert/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="insert-sale" method="post" name="insert-sale" rel="noopener" target="_self">
			<div class="form-row">
				<div class="col-md-12 form-group">
					<label for="client-mask">Cliente</label>
					<input autofocus class="form-control" form="insert-sale" id="client-mask" list="clients" maxlength="32" minlength="2" placeholder="" required type="text"/>
					<input class="form-control" form="insert-sale" id="client" name="client" type="hidden"/>
				</div>
				<datalist id="clients">
					<?php foreach($clients as $client): ?>
					<option value="<?=$client->id?>"><?=$client->name?> <?=$client->surname?></option>
					<?php endforeach; ?>
				</datalist>
			</div>

			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="product-mask">Produtos</label>
					<input class="form-control" form="insert-sale" id="product-mask" list="products" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="insert-sale" id="product" type="hidden"/>
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
					<input class="form-control" form="insert-sale" id="product-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="product"></label>
					<button class="btn btn-success mt-2" id="add-product" type="button">Adicionar</button>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="service-mask">Serviços</label>
					<input class="form-control" form="insert-sale" id="service-mask" list="services" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="insert-sale" id="service" type="hidden"/>
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
					<input class="form-control" form="insert-sale" id="service-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="service"></label>
					<button class="btn btn-success mt-2" id="add-service" type="button">Adicionar</button>
				</div>
			</div>

			<div id="other-products">
			<?php if(isset($tuple)): ?>
				<?php foreach($tuple->products as $product): ?>
				<div class="form-row">
					<div class="col-md-7 form-group">
						<label for="product">Produto</label>
						<input class="form-control" form="insert-sale" readonly type="text" value="<?=$product->name?>"/>
						<input class="form-control" form="insert-sale" name="products[]" type="hidden" value="<?=$product->id?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="price">Preço Unitário</label>
						<input class="form-control" form="insert-sale" name="products-prices[]" readonly type="text" value="<?=$product->unit_price?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="quantity">Quantidade</label>
						<input class="form-control" form="insert-sale" min="1" name="products-quantities[]" type="number" value="<?=$product->quantity?>"/>
					</div>
					<div class="col-md-1 form-group">
						<label for="product"></label>
						<button class="btn btn-danger mt-2" data-remove="remove-product" type="button">Remover</button>
					</div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
			</div>

			<div id="other-services">
			<?php if(isset($tuple)): ?>
				<?php foreach($tuple->services as $service): ?>
				<div class="form-row">
					<div class="col-md-7 form-group">
						<label for="service">Serviço</label>
						<input class="form-control" form="insert-sale" readonly type="text" value="<?=$service->name?>"/>
						<input class="form-control" form="insert-sale" name="services[]" type="hidden" value="<?=$service->id?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="price">Preço Unitário</label>
						<input class="form-control" form="insert-sale" name="services-prices[]" readonly type="text" value="<?=$service->unit_price?>"/>
					</div>
					<div class="col-md-2 form-group">
						<label for="quantity">Quantidade</label>
						<input class="form-control" form="insert-sale" min="1" name="services-quantities[]" type="number" value="<?=$service->quantity?>"/>
					</div>
					<div class="col-md-1 form-group">
						<label for="service"></label>
						<button class="btn btn-danger mt-2" data-remove="remove-service" type="button">Remover</button>
					</div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
			</div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="form-of-payment">Forma de Pagamento</label>
					<select class="form-control" form="insert-sale" id="form-of-payment" name="form-of-payment" required>
						<option disabled value="">Escolha uma forma de pagamento</option>
						<option <?=isset($tuple) ? ($tuple->form_of_payment == 'Dinheiro' ? 'selected' : '') : ''?> value="Dinheiro">Dinheiro</option>
						<option <?=isset($tuple) ? ($tuple->form_of_payment == 'A prazo' ? 'selected' : '') : ''?> value="A prazo">A prazo</option>
						<optgroup label="Transferência Bancária">
							<option <?=isset($tuple) ? ($tuple->form_of_payment == 'DOC' ? 'selected' : '') : ''?> value="DOC">DOC</option>
							<option <?=isset($tuple) ? ($tuple->form_of_payment == 'PIX' ? 'selected' : '') : ''?> value="PIX">PIX</option>
							<option <?=isset($tuple) ? ($tuple->form_of_payment == 'TED' ? 'selected' : '') : ''?> value="TED">TED</option>
						</optgroup>
						<optgroup label="TEF">
							<option <?=isset($tuple) ? ($tuple->form_of_payment == 'Cartão de crédito' ? 'selected' : '') : ''?> value="Cartão de crédito">Cartão de crédito</option>
							<option <?=isset($tuple) ? ($tuple->form_of_payment == 'Cartão de débito' ? 'selected' : '') : ''?> value="Cartão de débito">Cartão de débito</option>
						</optgroup>
					</select>
				</div>
				<div class="col-md-3 form-group">
					<label for="discount">Desconto</label>
					<input class="form-control" form="insert-sale" id="discount" maxlength="6" minlength="4" name="discount" pattern="[0-9]{1},[0-9]{2}|[0-9]{2},[0-9]{2}|[0-9]{3},[0-9]{2}" placeholder="Opcional" type="text" value="<?=number_format(isset($tuple) ? (float) $tuple->discount : 0.0, 2, ',', '.')?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="total">Total</label>
					<input class="form-control" data-value="<?=isset($tuple) ? $tuple->total : 0.0?>" form="insert-sale" id="total" maxlength="12" minlength="1" name="total" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" readonly required type="text" value="<?=isset($tuple) ? $tuple->total : 0.0?>"/>
				</div>
			</div>

			<input class="btn btn-primary" form="insert-sale" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="insert-sale" type="reset" value="Limpar"/>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE CADASTRO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
