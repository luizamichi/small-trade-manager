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

		<!-- FORMULÁRIO DE CADASTRO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/insert/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="insert-budget" method="post" name="insert-budget" rel="noopener" target="_self">
			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="product-mask">Produtos</label>
					<input class="form-control" form="insert-budget" id="product-mask" list="products" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="insert-budget" id="product" type="hidden"/>
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
					<input class="form-control" form="insert-budget" id="product-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="product"></label>
					<button class="btn btn-success mt-2" id="add-product" type="button">Adicionar</button>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-8 form-group">
					<label for="service-mask">Serviços</label>
					<input class="form-control" form="insert-budget" id="service-mask" list="services" maxlength="32" minlength="2" placeholder="" type="text"/>
					<input class="form-control" form="insert-budget" id="service" type="hidden"/>
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
					<input class="form-control" form="insert-budget" id="service-quantity" min="1" placeholder="" type="number"/>
				</div>
				<div class="col-md-1 form-group">
					<label for="service"></label>
					<button class="btn btn-success mt-2" id="add-service" type="button">Adicionar</button>
				</div>
			</div>

			<div id="other-products"></div>

			<div id="other-services"></div>

			<div class="form-row">
				<div class="col-md-5 form-group">
					<label for="form-of-payment">Forma de Pagamento</label>
					<select class="form-control" form="insert-budget" id="form-of-payment" name="form-of-payment" required>
						<option disabled selected value="">Escolha uma forma de pagamento</option>
						<option value="Dinheiro">Dinheiro</option>
						<option value="A prazo">A prazo</option>
						<optgroup label="Transferência Bancária">
							<option value="DOC">DOC</option>
							<option value="PIX">PIX</option>
							<option value="TED">TED</option>
						</optgroup>
						<optgroup label="TEF">
							<option value="Cartão de crédito">Cartão de crédito</option>
							<option value="Cartão de débito">Cartão de débito</option>
						</optgroup>
					</select>
				</div>
				<div class="col-md-3 form-group">
					<label for="discount">Desconto</label>
					<input class="form-control" form="insert-budget" id="discount" maxlength="6" minlength="4" name="discount" pattern="[0-9]{1},[0-9]{2}|[0-9]{2},[0-9]{2}|[0-9]{3},[0-9]{2}" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="total">Total</label>
					<input class="form-control" data-value="0.0" form="insert-budget" id="total" maxlength="12" minlength="1" name="total" pattern="^(\d+|\d{1,3}(\.\d{3})*)(,\d+)?$" placeholder="" readonly required type="text"/>
				</div>
			</div>

			<input class="btn btn-primary" form="insert-budget" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="insert-budget" type="reset" value="Limpar"/>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE CADASTRO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
