	<?php require_once HEADER; ?>

	<div class="container mb-5" id="content">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Venda" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/sale-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once SETTINGS; ?>

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- DADOS DA VENDA -->
		<div class="row">
			<div class="col-md-3">
				<h3>Cliente</h3>
				<p><a class="text-dark" href="<?=BASE_URL?>client/view/?id=<?=$tuple->client->id?>"><?=$tuple->client->name?> <?=$tuple->client->surname?></a></p>
			</div>

			<div class="col-md-3">
				<h3>Funcionário</h3>
				<p><a class="text-dark" href="<?=BASE_URL?>employee/view/?id=<?=$tuple->employee->id?>"><?=$tuple->employee->name?> <?=$tuple->employee->surname?></a></p>
			</div>

			<div class="col-md-3">
				<h3>Dia</h3>
				<p><?=$tuple->day?></p>
			</div>

			<div class="col-md-3">
				<h3>Forma de Pagamento</h3>
				<p><?=$tuple->form_of_payment?></p>
			</div>

			<div class="col-md-3">
				<h3>Desconto</h3>
				<p><?=$tuple->discount?></p>
			</div>

			<div class="col-md-3">
				<h3>Total</h3>
				<p><?=$tuple->total?></p>
			</div>

			<?php if($tuple->products): ?>
			<div class="col-md-12">
				<h3>Produtos</h3>
				<table class="table table-sm">
					<caption></caption>
					<thead>
						<tr>
							<th scope="col">Quantidade</th>
							<th scope="col">Descrição</th>
							<th scope="col">Preço Unitário</th>
							<th scope="col">Preço Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tuple->products as $product): ?>
						<tr>
							<td><?=$product->quantity?></td>
							<td><a class="text-dark" href="<?=BASE_URL?>product/view/?id=<?=$product->id?>"><?=$product->name?></a></td>
							<td><?=$product->unit_price?></td>
							<td><?=$product->total_price?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>

			<?php if($tuple->services): ?>
			<div class="col-md-12">
				<h3>Serviços</h3>
				<table class="table table-sm">
					<caption></caption>
					<thead>
						<tr>
							<th scope="col">Quantidade</th>
							<th scope="col">Descrição</th>
							<th scope="col">Preço Unitário</th>
							<th scope="col">Preço Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tuple->services as $service): ?>
						<tr>
							<td><?=$service->quantity?></td>
							<td><a class="text-dark" href="<?=BASE_URL?>service/view/?id=<?=$service->id?>"><?=$service->name?></a></td>
							<td><?=$service->unit_price?></td>
							<td><?=$service->total_price?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
		</div>
		<!--/ DADOS DA VENDA -->

		<div class="pb-3 row" id="buttons">
			<div class="col-md-2 mb-2">
				<a class="btn btn-block btn-primary" href="<?=BASE_URL . PAGE_NAME?>/update/?id=<?=$tuple->id?>">Alterar</a>
			</div>
			<div class="col-md-2 mb-2">
				<button class="btn btn-block btn-danger" data-target="#remove-modal" data-toggle="modal" type="button">Remover</button>
			</div>
			<div class="col-md-2 mb-2">
				<button class="btn btn-block btn-outline-dark" id="print-content">Imprimir</button>
			</div>
		</div>
	</div>

	<?php require_once REMOVE; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
