	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Funcionário" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/employee-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php require_once LOADING; ?>

		<!-- FORMULÁRIO DE ALTERAÇÃO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/update/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="update-employee" method="post" name="update-employee" rel="noopener" target="_self">
		<input form="update-employee" id="id" name="id" type="hidden" value="<?=$tuple->id?>"/>
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="name">Nome</label>
					<input autofocus class="form-control" form="update-employee" id="name" maxlength="32" minlength="2" name="name" placeholder="" required type="text" value="<?=$tuple->name?>"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="surname">Sobrenome</label>
					<input class="form-control" form="update-employee" id="surname" maxlength="32" minlength="2" name="surname" placeholder="" required type="text" value="<?=$tuple->surname?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="alias">Apelido</label>
					<input class="form-control" form="update-employee" id="alias" maxlength="32" minlength="6" name="alias" placeholder="" required type="text" value="<?=$tuple->alias?>"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="password">Senha</label>
					<input class="form-control" form="update-employee" id="password" minlength="6" name="password" placeholder="Obrigatório" required type="password"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="rg">RG</label>
					<input class="form-control" form="update-employee" id="rg" maxlength="12" minlength="11" name="rg" pattern="[0-9]{2}.[0-9]{3}.[0-9]{3}-[0-9]{1}|[0-9]{1}.[0-9]{3}.[0-9]{3}-[0-9]{1}" placeholder="Opcional" type="text" value="<?=$tuple->rg?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="cpf">CPF</label>
					<input class="form-control" form="update-employee" id="cpf" maxlength="14" minlength="14" name="cpf" pattern="[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}" placeholder="" required type="text" value="<?=$tuple->cpf?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="birthday">Data de Nascimento</label>
					<input class="form-control" form="update-employee" id="birthday" name="birthday" required type="date" value="<?=$tuple->birthday?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="postal-code">CEP</label>
					<input class="form-control" form="update-employee" id="postal-code" maxlength="9" minlength="9" name="postal-code" pattern="[0-9]{5}-[0-9]{3}" placeholder="Opcional" type="text" value="<?=$tuple->postal_code?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="district">Bairro</label>
					<input class="form-control" form="update-employee" id="district" maxlength="32" minlength="4" name="district" placeholder="Opcional" type="text" value="<?=$tuple->district?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="city">Cidade</label>
					<input class="form-control" form="update-employee" id="city" maxlength="64" minlength="4" name="city" placeholder="" required type="text" value="<?=$tuple->city?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="state">Estado</label>
					<select class="form-control" form="update-employee" id="state" name="state" required>
						<option disabled value="">Escolha um estado</option>
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
					<input class="form-control" form="update-employee" id="address" maxlength="64" minlength="4" name="address" placeholder="" required type="text" value="<?=$tuple->address?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="number">Número</label>
					<input class="form-control" form="update-employee" id="number" maxlength="8" minlength="2" name="number" pattern="[0-9]+" placeholder="" required type="text" value="<?=$tuple->number?>"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="complement">Complemento</label>
					<input class="form-control" form="update-employee" id="complement" maxlength="32" minlength="4" name="complement" placeholder="Opcional" type="text" value="<?=$tuple->complement?>"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="email">E-mail</label>
					<input class="form-control" form="update-employee" id="email" maxlength="32" minlength="4" name="email" placeholder="" required type="email" value="<?=$tuple->email?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="phone">Telefone</label>
					<input class="form-control" form="update-employee" id="phone" maxlength="14" minlength="14" name="phone" pattern="\([0-9]{2}\)\s[0-9]{4}-[0-9]{4}" placeholder="Opcional" type="tel" value="<?=$tuple->phone?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="cell-phone">Celular</label>
					<input class="form-control" form="update-employee" id="cell-phone" maxlength="15" minlength="15" name="cell-phone" pattern="\([0-9]{2}\)\s[0-9]{5}-[0-9]{4}" placeholder="Opcional" type="tel" value="<?=$tuple->cell_phone?>"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="sex">Sexo</label>
					<select class="form-control" form="update-employee" id="sex" name="sex" required>
						<option disabled value="">Escolha um sexo</option>
						<option <?=$tuple->sex == 'M' ? 'selected' : ''?> value="M">Masculino</option>
						<option <?=$tuple->sex == 'F' ? 'selected' : ''?> value="F">Feminino</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12 form-group">
					<label for="note">Observação</label>
					<textarea class="form-control" form="update-employee" id="note" maxlength="512" minlength="4" name="note" placeholder="Opcional" rows="3"><?=$tuple->note?></textarea>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12">
					<label for="client">Permissão</label>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->client ?: 'checked'?> class="custom-control-input" id="client" name="permission[]" type="checkbox" value="client"/>
						<label class="custom-control-label" for="client">Clientes</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->purchase ?: 'checked'?> class="custom-control-input" id="purchase" name="permission[]" type="checkbox" value="purchase"/>
						<label class="custom-control-label" for="purchase">Compras</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->provider ?: 'checked'?> class="custom-control-input" id="provider" name="permission[]" type="checkbox" value="provider"/>
						<label class="custom-control-label" for="provider">Fornecedores</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->employee ?: 'checked'?> class="custom-control-input" id="employee" name="permission[]" type="checkbox" value="employee"/>
						<label class="custom-control-label" for="employee">Funcionários</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->setting ?: 'checked'?> class="custom-control-input" id="setting" name="permission[]" type="checkbox" value="setting"/>
						<label class="custom-control-label" for="setting">Opções</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->budget ?: 'checked'?> class="custom-control-input" id="budget" name="permission[]" type="checkbox" value="budget"/>
						<label class="custom-control-label" for="budget">Orçamentos</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->product ?: 'checked'?> class="custom-control-input" id="product" name="permission[]" type="checkbox" value="product"/>
						<label class="custom-control-label" for="product">Produtos</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->record ?: 'checked'?> class="custom-control-input" id="record" name="permission[]" type="checkbox" value="record"/>
						<label class="custom-control-label" for="record">Registros</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->report ?: 'checked'?> class="custom-control-input" id="report" name="permission[]" type="checkbox" value="report"/>
						<label class="custom-control-label" for="report">Relatórios</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->service ?: 'checked'?> class="custom-control-input" id="service" name="permission[]" type="checkbox" value="service"/>
						<label class="custom-control-label" for="service">Serviços</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input <?=!$tuple->permission->sale ?: 'checked'?> class="custom-control-input" id="sale" name="permission[]" type="checkbox" value="sale"/>
						<label class="custom-control-label" for="sale">Vendas</label>
					</div>
				</div>
			</div>

			<input class="btn btn-primary" form="update-employee" type="submit" value="Alterar"/>
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
