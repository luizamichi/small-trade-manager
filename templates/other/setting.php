	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Opção" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/setting-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- FORMULÁRIO DE GERENCIAMENTO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/manage/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="manage-setting" method="post" name="update-setting" rel="noopener" target="_self">
			<input form="manage-setting" id="id" name="id" type="hidden" value="<?=$tuple->id ?? ''?>"/>
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="company-name">Razão Social</label>
					<input autofocus class="form-control" form="manage-setting" id="company-name" maxlength="32" minlength="2" name="company-name" placeholder="" required type="text" value="<?=$tuple->company_name?>"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="fantasy-name">Nome Fantasia</label>
					<input class="form-control" form="manage-setting" id="fantasy-name" maxlength="64" minlength="2" name="fantasy-name" placeholder="" required type="text" value="<?=$tuple->fantasy_name?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="cnpj">CNPJ</label>
					<input class="form-control" form="manage-setting" id="cnpj" maxlength="18" minlength="18" name="cnpj" pattern="[0-9]{2}.[0-9]{3}.[0-9]{3}/[0-9]{4}-[0-9]{2}" placeholder="" required type="text" value="<?=$tuple->cnpj?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="email">E-mail</label>
					<input class="form-control" form="manage-setting" id="email" maxlength="32" minlength="4" name="email" placeholder="Opcional" type="email" value="<?=$tuple->email?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="phone">Telefone</label>
					<input class="form-control" form="manage-setting" id="phone" maxlength="14" minlength="14" name="phone" pattern="\([0-9]{2}\)\s[0-9]{4}-[0-9]{4}" placeholder="" required type="tel" value="<?=$tuple->phone?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="postal-code">CEP</label>
					<input class="form-control" form="manage-setting" id="postal-code" maxlength="9" minlength="9" name="postal-code" pattern="[0-9]{5}-[0-9]{3}" placeholder="" required type="text" value="<?=$tuple->postal_code?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="district">Bairro</label>
					<input class="form-control" form="manage-setting" id="district" maxlength="32" minlength="4" name="district" placeholder="" required type="text" value="<?=$tuple->district?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="city">Cidade</label>
					<input class="form-control" form="manage-setting" id="city" maxlength="64" minlength="4" name="city" placeholder="" required type="text" value="<?=$tuple->city?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="state">Estado</label>
					<select class="form-control" form="manage-setting" id="state" name="state" required>
						<option disabled <?=$tuple->state == '' ? 'selected' : ''?> value="">Escolha um estado</option>
						<option <?=$tuple->state == 'AC' ? 'selected' : ''?> value="AC">Acre (AC)</option>
						<option <?=$tuple->state == 'AL' ? 'selected' : ''?> value="AL">Alagoas (AL)</option>
						<option <?=$tuple->state == 'AP' ? 'selected' : ''?> value="AP">Amapá (AP)</option>
						<option <?=$tuple->state == 'AM' ? 'selected' : ''?> value="AM">Amazonas (AM)</option>
						<option <?=$tuple->state == 'BA' ? 'selected' : ''?> value="BA">Bahia (BA)</option>
						<option <?=$tuple->state == 'CE' ? 'selected' : ''?> value="CE">Ceará (CE)</option>
						<option <?=$tuple->state == 'DF' ? 'selected' : ''?> value="DF">Distrito Federal (DF)</option>
						<option <?=$tuple->state == 'ES' ? 'selected' : ''?> value="ES">Espírito Santo (ES)</option>
						<option <?=$tuple->state == 'GO' ? 'selected' : ''?> value="GO">Goiás (GO)</option>
						<option <?=$tuple->state == 'MA' ? 'selected' : ''?> value="MA">Maranhão (MA)</option>
						<option <?=$tuple->state == 'MT' ? 'selected' : ''?> value="MT">Mato Grosso (MT)</option>
						<option <?=$tuple->state == 'MS' ? 'selected' : ''?> value="MS">Mato Grosso do Sul (MS)</option>
						<option <?=$tuple->state == 'MG' ? 'selected' : ''?> value="MG">Minas Gerais (MG)</option>
						<option <?=$tuple->state == 'PA' ? 'selected' : ''?> value="PA">Pará (PA)</option>
						<option <?=$tuple->state == 'PB' ? 'selected' : ''?> value="PB">Paraíba (PB)</option>
						<option <?=$tuple->state == 'PR' ? 'selected' : ''?> value="PR">Paraná (PR)</option>
						<option <?=$tuple->state == 'PE' ? 'selected' : ''?> value="PE">Pernambuco (PE)</option>
						<option <?=$tuple->state == 'PI' ? 'selected' : ''?> value="PI">Piauí (PI)</option>
						<option <?=$tuple->state == 'RJ' ? 'selected' : ''?> value="RJ">Rio de Janeiro (RJ)</option>
						<option <?=$tuple->state == 'RN' ? 'selected' : ''?> value="RN">Rio Grande do Norte (RN)</option>
						<option <?=$tuple->state == 'RS' ? 'selected' : ''?> value="RS">Rio Grande do Sul (RS)</option>
						<option <?=$tuple->state == 'RO' ? 'selected' : ''?> value="RO">Rondônia (RO)</option>
						<option <?=$tuple->state == 'RR' ? 'selected' : ''?> value="RR">Roraima (RR)</option>
						<option <?=$tuple->state == 'SC' ? 'selected' : ''?> value="SC">Santa Catarina (SC)</option>
						<option <?=$tuple->state == 'SP' ? 'selected' : ''?> value="SP">São Paulo (SP)</option>
						<option <?=$tuple->state == 'SE' ? 'selected' : ''?> value="SE">Sergipe (SE)</option>
						<option <?=$tuple->state == 'TO' ? 'selected' : ''?> value="TO">Tocantins (TO)</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="address">Endereço</label>
					<input class="form-control" form="manage-setting" id="address" maxlength="64" minlength="4" name="address" placeholder="" required type="text" value="<?=$tuple->address?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="number">Número</label>
					<input class="form-control" form="manage-setting" id="number" maxlength="8" minlength="2" name="number" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->number?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="website">Website</label>
					<input class="form-control" form="manage-setting" id="website" maxlength="32" minlength="8" name="website" placeholder="Opcional" type="url" value="<?=$tuple->website?>"/>
				</div>
			</div>

			<?php if(!$tuple->company_name || !$tuple->fantasy_name || !$tuple->cnpj || !$tuple->phone || !$tuple->postal_code || !$tuple->district || !$tuple->city || !$tuple->state || !$tuple->address || !$tuple->number): ?>
			<input class="btn btn-primary" form="manage-setting" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="manage-setting" type="reset" value="Limpar"/>
			<?php else: ?>
			<input class="btn btn-primary" form="manage-setting" type="submit" value="Alterar"/>
			<?php endif; ?>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE GERENCIAMENTO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
