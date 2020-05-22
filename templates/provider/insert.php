	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Fornecedor" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/provider-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- FORMULÁRIO DE CADASTRO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/insert/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="insert-provider" method="post" name="insert-provider" rel="noopener" target="_self">
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="company-name">Razão Social</label>
					<input autofocus class="form-control" form="insert-provider" id="company-name" maxlength="32" minlength="2" name="company-name" placeholder="" required type="text"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="fantasy-name">Nome Fantasia</label>
					<input class="form-control" form="insert-provider" id="fantasy-name" maxlength="64" minlength="2" name="fantasy-name" placeholder="" required type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="state-registration">Inscrição Estadual</label>
					<input class="form-control" form="insert-provider" id="state-registration" maxlength="15" minlength="15" name="state-registration" pattern="[0-9]{3}.[0-9]{3}.[0-9]{3}.[0-9]{3}" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="cnpj">CNPJ</label>
					<input class="form-control" form="insert-provider" id="cnpj" maxlength="18" minlength="18" name="cnpj" pattern="[0-9]{2}.[0-9]{3}.[0-9]{3}/[0-9]{4}-[0-9]{2}" placeholder="" required type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="foundation-date">Data de Fundação</label>
					<input class="form-control" form="insert-provider" id="foundation-date" name="foundation-date" required type="date"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="postal-code">CEP</label>
					<input class="form-control" form="insert-provider" id="postal-code" maxlength="9" minlength="9" name="postal-code" pattern="[0-9]{5}-[0-9]{3}" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="district">Bairro</label>
					<input class="form-control" form="insert-provider" id="district" maxlength="32" minlength="4" name="district" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="city">Cidade</label>
					<input class="form-control" form="insert-provider" id="city" maxlength="64" minlength="4" name="city" placeholder="" required type="text"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="state">Estado</label>
					<select class="form-control" form="insert-provider" id="state" name="state" required>
						<option disabled selected value="">Escolha um estado</option>
						<option value="AC">Acre (AC)</option>
						<option value="AL">Alagoas (AL)</option>
						<option value="AP">Amapá (AP)</option>
						<option value="AM">Amazonas (AM)</option>
						<option value="BA">Bahia (BA)</option>
						<option value="CE">Ceará (CE)</option>
						<option value="DF">Distrito Federal (DF)</option>
						<option value="ES">Espírito Santo (ES)</option>
						<option value="GO">Goiás (GO)</option>
						<option value="MA">Maranhão (MA)</option>
						<option value="MT">Mato Grosso (MT)</option>
						<option value="MS">Mato Grosso do Sul (MS)</option>
						<option value="MG">Minas Gerais (MG)</option>
						<option value="PA">Pará (PA)</option>
						<option value="PB">Paraíba (PB)</option>
						<option value="PR">Paraná (PR)</option>
						<option value="PE">Pernambuco (PE)</option>
						<option value="PI">Piauí (PI)</option>
						<option value="RJ">Rio de Janeiro (RJ)</option>
						<option value="RN">Rio Grande do Norte (RN)</option>
						<option value="RS">Rio Grande do Sul (RS)</option>
						<option value="RO">Rondônia (RO)</option>
						<option value="RR">Roraima (RR)</option>
						<option value="SC">Santa Catarina (SC)</option>
						<option value="SP">São Paulo (SP)</option>
						<option value="SE">Sergipe (SE)</option>
						<option value="TO">Tocantins (TO)</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="address">Endereço</label>
					<input class="form-control" form="insert-provider" id="address" maxlength="64" minlength="4" name="address" placeholder="" required type="text"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="number">Número</label>
					<input class="form-control" form="insert-provider" id="number" maxlength="8" minlength="2" name="number" pattern="[0-9]+" placeholder="" required type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="complement">Complemento</label>
					<input class="form-control" form="insert-provider" id="complement" maxlength="32" minlength="4" name="complement" placeholder="Opcional" type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="email">E-mail</label>
					<input class="form-control" form="insert-provider" id="email" maxlength="32" minlength="4" name="email" placeholder="" required type="email"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="phone">Telefone</label>
					<input class="form-control" form="insert-provider" id="phone" maxlength="14" minlength="14" name="phone" pattern="\([0-9]{2}\)\s[0-9]{4}-[0-9]{4}" placeholder="" required type="tel"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="cell-phone">Celular</label>
					<input class="form-control" form="insert-provider" id="cell-phone" maxlength="15" minlength="15" name="cell-phone" pattern="\([0-9]{2}\)\s[0-9]{5}-[0-9]{4}" placeholder="Opcional" type="tel"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12 form-group">
					<label for="note">Observação</label>
					<textarea class="form-control" form="insert-provider" id="note" maxlength="512" minlength="4" name="note" placeholder="Opcional" rows="3"></textarea>
				</div>
			</div>

			<input class="btn btn-primary" form="insert-provider" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="insert-provider" type="reset" value="Limpar"/>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE CADASTRO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
