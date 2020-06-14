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

		<!-- FORMULÁRIO DE CADASTRO -->
		<form accept-charset="utf-8" action="<?=BASE_URL . 'action/' . PAGE_NAME?>/insert/" autocomplete="on" class="pb-3" enctype="application/x-www-form-urlencoded" id="insert-employee" method="post" name="insert-employee" rel="noopener" target="_self">
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="name">Nome</label>
					<input autofocus class="form-control" form="insert-employee" id="name" maxlength="32" minlength="2" name="name" placeholder="" required type="text"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="surname">Sobrenome</label>
					<input class="form-control" form="insert-employee" id="surname" maxlength="32" minlength="2" name="surname" placeholder="" required type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="alias">Apelido</label>
					<input class="form-control" form="insert-employee" id="alias" maxlength="32" minlength="6" name="alias" placeholder="" required type="text"/>
				</div>
				<div class="col-md-6 form-group">
					<label for="password">Senha</label>
					<input class="form-control" form="insert-employee" id="password" minlength="6" name="password" placeholder="" required type="password"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="rg">RG</label>
					<input class="form-control" form="insert-employee" id="rg" maxlength="12" minlength="11" name="rg" pattern="[0-9]{2}.[0-9]{3}.[0-9]{3}-[0-9]{1}|[0-9]{1}.[0-9]{3}.[0-9]{3}-[0-9]{1}" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="cpf">CPF</label>
					<input class="form-control" form="insert-employee" id="cpf" maxlength="14" minlength="14" name="cpf" pattern="[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}" placeholder="" required type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="birthday">Data de Nascimento</label>
					<input class="form-control" form="insert-employee" id="birthday" name="birthday" required type="date"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-2 form-group">
					<label for="postal-code">CEP</label>
					<input class="form-control" form="insert-employee" id="postal-code" maxlength="9" minlength="9" name="postal-code" pattern="[0-9]{5}-[0-9]{3}" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="district">Bairro</label>
					<input class="form-control" form="insert-employee" id="district" maxlength="32" minlength="4" name="district" placeholder="Opcional" type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="city">Cidade</label>
					<input class="form-control" form="insert-employee" id="city" maxlength="64" minlength="4" name="city" placeholder="" required type="text"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="state">Estado</label>
					<select class="form-control" form="insert-employee" id="state" name="state" required>
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
					<input class="form-control" form="insert-employee" id="address" maxlength="64" minlength="4" name="address" placeholder="" required type="text"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="number">Número</label>
					<input class="form-control" form="insert-employee" id="number" maxlength="8" minlength="2" name="number" pattern="[0-9]+" placeholder="" required type="text"/>
				</div>
				<div class="col-md-4 form-group">
					<label for="complement">Complemento</label>
					<input class="form-control" form="insert-employee" id="complement" maxlength="32" minlength="4" name="complement" placeholder="Opcional" type="text"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 form-group">
					<label for="email">E-mail</label>
					<input class="form-control" form="insert-employee" id="email" maxlength="32" minlength="4" name="email" placeholder="" required type="email"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="phone">Telefone</label>
					<input class="form-control" form="insert-employee" id="phone" maxlength="14" minlength="14" name="phone" pattern="\([0-9]{2}\)\s[0-9]{4}-[0-9]{4}" placeholder="Opcional" type="tel"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="cell-phone">Celular</label>
					<input class="form-control" form="insert-employee" id="cell-phone" maxlength="15" minlength="15" name="cell-phone" pattern="\([0-9]{2}\)\s[0-9]{5}-[0-9]{4}" placeholder="Opcional" type="tel"/>
				</div>
				<div class="col-md-2 form-group">
					<label for="sex">Sexo</label>
					<select class="form-control" form="insert-employee" id="sex" name="sex" required>
						<option disabled selected value="">Escolha um sexo</option>
						<option value="M">Masculino</option>
						<option value="F">Feminino</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12 form-group">
					<label for="note">Observação</label>
					<textarea class="form-control" form="insert-employee" id="note" maxlength="512" minlength="4" name="note" placeholder="Opcional" rows="3"></textarea>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12">
					<label for="client">Permissão</label>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="client" name="permission[]" type="checkbox" value="client"/>
						<label class="custom-control-label" for="client">Clientes</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="purchase" name="permission[]" type="checkbox" value="purchase"/>
						<label class="custom-control-label" for="purchase">Compras</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="provider" name="permission[]" type="checkbox" value="provider"/>
						<label class="custom-control-label" for="provider">Fornecedores</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="employee" name="permission[]" type="checkbox" value="employee"/>
						<label class="custom-control-label" for="employee">Funcionários</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="setting" name="permission[]" type="checkbox" value="setting"/>
						<label class="custom-control-label" for="setting">Opções</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="budget" name="permission[]" type="checkbox" value="budget"/>
						<label class="custom-control-label" for="budget">Orçamentos</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="product" name="permission[]" type="checkbox" value="product"/>
						<label class="custom-control-label" for="product">Produtos</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="record" name="permission[]" type="checkbox" value="record"/>
						<label class="custom-control-label" for="record">Registros</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="report" name="permission[]" type="checkbox" value="report"/>
						<label class="custom-control-label" for="report">Relatórios</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="service" name="permission[]" type="checkbox" value="service"/>
						<label class="custom-control-label" for="service">Serviços</label>
					</div>
				</div>
				<div class="col-6 col-md-2 form-group">
					<div class="custom-control custom-checkbox">
						<input class="custom-control-input" id="sale" name="permission[]" type="checkbox" value="sale"/>
						<label class="custom-control-label" for="sale">Vendas</label>
					</div>
				</div>
			</div>

			<input class="btn btn-primary" form="insert-employee" type="submit" value="Cadastrar"/>
			<input class="btn btn-dark" form="insert-employee" type="reset" value="Limpar"/>
			<button class="btn btn-outline-info float-right" data-target="#help-modal" data-toggle="modal" type="button">Ajuda</button>
		</form>
		<!--/ FORMULÁRIO DE CADASTRO -->
	</div>

	<?php require_once HELP; ?>

	<?php require_once FOOTER; ?>

</body>

</html>
