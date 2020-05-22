	<?php require_once HEADER; ?>

	<div class="container mb-5" id="content">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Fornecedor" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/provider-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once SETTINGS; ?>

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- DADOS DO FORNECEDOR -->
		<div class="row">
			<div class="col-md-3">
				<h3>Razão Social</h3>
				<p><?=$tuple->company_name?></p>
			</div>

			<div class="col-md-3">
				<h3>Nome Fantasia</h3>
				<p><?=$tuple->fantasy_name?></p>
			</div>

			<div class="col-md-3">
				<h3>Data de Fundação</h3>
				<p><?=$tuple->foundation_date?></p>
			</div>

			<?php if($tuple->state_registration): ?>
			<div class="col-md-3">
				<h3>Inscrição Estadual</h3>
				<p><?=$tuple->state_registration?></p>
			</div>
			<?php endif; ?>

			<div class="col-md-3">
				<h3>CNPJ</h3>
				<p><?=$tuple->cnpj?></p>
			</div>

			<div class="col-md-3">
				<h3>E-mail</h3>
				<p><a class="text-dark" href="mailto:<?=$tuple->email?>"><?=$tuple->email?></a></p>
			</div>

			<div class="col-md-3">
				<h3>Telefone</h3>
				<p><a class="text-dark" href="tel:+55<?=$tuple->phone?>"><?=$tuple->phone?></a></p>
			</div>

			<?php if($tuple->cell_phone): ?>
			<div class="col-md-3">
				<h3>Celular</h3>
				<p><a class="text-dark" href="tel:+55<?=$tuple->cell_phone?>"><?=$tuple->cell_phone?></a></p>
			</div>
			<?php endif; ?>

			<div class="col-md-3">
				<h3>Cidade</h3>
				<p><?=$tuple->city?></p>
			</div>

			<div class="col-md-3">
				<h3>Estado</h3>
				<p><?=$tuple->state?></p>
			</div>

			<?php if($tuple->postal_code): ?>
			<div class="col-md-3">
				<h3>CEP</h3>
				<p><a class="text-dark" href="https://www.google.com.br/maps/search/<?=$tuple->postal_code?>" target="_blank"><?=$tuple->postal_code?></a></p>
			</div>
			<?php endif; ?>

			<?php if($tuple->district): ?>
			<div class="col-md-3">
				<h3>Bairro</h3>
				<p><?=$tuple->district?></p>
			</div>
			<?php endif; ?>

			<div class="col-md-3">
				<h3>Endereço</h3>
				<p><?=$tuple->address?></p>
			</div>

			<div class="col-md-3">
				<h3>Número</h3>
				<p><?=$tuple->number?></p>
			</div>

			<div class="col-md-3">
				<?php if($tuple->complement): ?>
				<h3>Complemento</h3>
				<p><?=$tuple->complement?></p>
				<?php endif; ?>
			</div>

			<?php if($tuple->note): ?>
			<div class="col-md-12">
				<h3>Observação</h3>
				<p class="text-justify"><?=nl2br($tuple->note)?></p>
			</div>
			<?php endif; ?>
		</div>
		<!--/ DADOS DO FORNECEDOR -->

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
