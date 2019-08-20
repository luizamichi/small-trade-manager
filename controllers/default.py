from flask import flash, redirect, render_template, request, session, url_for
from app import app, datetime, locale

from models.client import Client
from models.product import Product
from models.provider import Provider
from models.service import Service
from models.user import User

from controllers import persistence


@app.route('/')
@app.route('/index')
def index():
	return render_template('index.html')


@app.route('/login', methods=['GET', 'POST'])
def login():
	if request.method == 'POST':
		if user_validate(request.form['alias'], request.form['password']):
			session['user'] = (request.form['alias'], request.form['password'])
			flash('Bem-vindo ao sistema de gerenciamento de conteúdo.')
			flash('success')
			return redirect(url_for('system'))
		else:
			flash('O usuário ou a senha estão incorretos.')
			flash('danger')
			return redirect(url_for('login'))
	if user_validate():
		return redirect(url_for('system'))
	else:
		return render_template('login.html')


@app.route('/logout')
def logout():
	session['user'] = None
	flash('Você saiu do sistema.')
	flash('success')
	return redirect(url_for('login'))


def log(action, message):
	with open('db/' + action + '.log', 'a', encoding='utf-8') as file:
		file.write(message + '\n')
		file.close()


@app.route('/system')
@app.route('/system/')
def system():
	if user_validate():
		return render_template('system.html')
	else:
		return redirect(url_for('login'))


def user_validate(alias='', password=''):
	try:
		_, _ = session['user']
		return True
	except:
		row = persistence.read_one('SELECT * FROM USERS WHERE ALIAS="{}"'.format(alias))
		if row and alias == row[2] and password == row[3]:
			return True
	return False


############################### CLIENTS
@app.route('/system/clients')
@app.route('/system/clients/')
def clients():
	if user_validate():
		return redirect(url_for('read_client'))
	else:
		return redirect(url_for('login'))


@app.route('/system/clients/read', methods=['GET'])
def read_client():
	if user_validate():
		clients = []
		if request.args.get('name'):
			rows = persistence.read('SELECT * FROM CLIENTS WHERE NAME LIKE "%{}%"'.format(request.args.get('name').strip()))
		else:
			rows = persistence.read('SELECT * FROM CLIENTS')
		for client in rows:
			clients.append(Client(id=client[0], name=client[1], cpf=client[2], address=client[3], neighborhood=client[4], city=client[5], telephone=client[6], cellphone=client[7]))
		if not clients:
			flash('Não foi encontrado nenhum cliente.')
			flash('warning')
		return render_template('clients.html', clients=clients)
	else:
		return redirect(url_for('login'))


@app.route('/system/clients/create', methods=['POST'])
def create_client():
	if user_validate():
		if request.method == 'POST':
			name = request.form['name'].strip().upper()
			cpf = request.form['cpf'].strip()
			address = request.form['address'].strip().upper()
			neighborhood = request.form['neighborhood'].strip().upper()
			city = request.form['city'].strip().upper()
			telephone = request.form['telephone'].strip()
			cellphone = request.form['cellphone'].strip()
			if persistence.create('INSERT INTO CLIENTS (NAME, CPF, ADDRESS, NEIGHBORHOOD, CITY, TELEPHONE, CELLPHONE) VALUES ("{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(name, cpf, address, neighborhood, city, telephone, cellphone)):
				flash('Cliente cadastrado com sucesso.')
				flash('success')
				log('create', 'CLIENT: ("{}", "{}", "{}", "{}", "{}", "{}", "{}") -- {} ({})'.format(name, cpf, address, neighborhood, city, telephone, cellphone, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível cadastrar o cliente.')
				flash('danger')
			return redirect(url_for('clients'))
	else:
		return redirect(url_for('login'))


@app.route('/system/clients/update', methods=['POST'])
def update_client():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			cpf = request.form['cpf'].strip()
			address = request.form['address'].strip().upper()
			neighborhood = request.form['neighborhood'].strip().upper()
			city = request.form['city'].strip().upper()
			telephone = request.form['telephone'].strip()
			cellphone = request.form['cellphone'].strip()
			if persistence.update('UPDATE CLIENTS SET NAME="{}", CPF="{}", ADDRESS="{}", NEIGHBORHOOD="{}", CITY="{}", TELEPHONE="{}", CELLPHONE="{}" WHERE ID={}'.format(name, cpf, address, neighborhood, city, telephone, cellphone, id)):
				flash('Cliente alterado com sucesso.')
				flash('success')
				log('update', 'CLIENT: ({}, "{}", "{}", "{}", "{}", "{}", "{}", "{}") -- {} ({})'.format(id, name, cpf, address, neighborhood, city, telephone, cellphone, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível alterar o cliente.')
				flash('danger')
			return redirect(url_for('clients'))
	else:
		return redirect(url_for('login'))


@app.route('/system/clients/delete', methods=['POST'])
def delete_client():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			if persistence.delete('DELETE FROM CLIENTS WHERE ID={}'.format(id)):
				flash('Cliente removido com sucesso.')
				flash('success')
				log('delete', 'CLIENT: ({}) -- {} ({})'.format(id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o cliente.')
				flash('danger')
			return redirect(url_for('clients'))
	else:
		return redirect(url_for('login'))


############################## PRODUCTS
@app.route('/system/products')
@app.route('/system/products/')
def products():
	if user_validate():
		return redirect(url_for('read_product'))
	else:
		return redirect(url_for('login'))


@app.route('/system/products/read', methods=['GET'])
def read_product():
	if user_validate():
		products = []
		providers = []
		if request.args.get('name'):
			rows = persistence.read('SELECT * FROM PRODUCTS WHERE NAME LIKE "%{}%"'.format(request.args.get('name').strip()))
		else:
			rows = persistence.read('SELECT * FROM PRODUCTS')
		for product in rows:
			provider = persistence.read_one('SELECT * FROM PROVIDERS WHERE ID={}'.format(product[5]))
			products.append(Product(id=product[0], name=product[1], gross_price=locale.currency(product[2], grouping=True, symbol=True), net_price=locale.currency(product[3], grouping=True, symbol=True), profit_percentage='{:.2%}'.format(product[4]), provider=Provider(id=provider[0], name=provider[1], cnpj=provider[2])))
		rows = persistence.read('SELECT * FROM PROVIDERS')
		for provider in rows:
			providers.append(Provider(id=provider[0], name=provider[1], cnpj=provider[2], email=provider[3]))
		if not products:
			flash('Não foi encontrado nenhum produto.')
			flash('warning')
		return render_template('products.html', products=products, providers=providers)
	else:
		return redirect(url_for('login'))


@app.route('/system/products/create', methods=['POST'])
def create_product():
	if user_validate():
		if request.method == 'POST':
			name = request.form['name'].strip().upper()
			gross_price = float(request.form['gross_price'].strip())
			net_price = float(request.form['net_price'].strip())
			profit_percentage = round((net_price - gross_price) / gross_price, 2)
			provider = persistence.read_one('SELECT * FROM PROVIDERS WHERE NAME="{}"'.format(request.form['provider'].strip()))
			if provider and persistence.create('INSERT INTO PRODUCTS (NAME, GROSS_PRICE, NET_PRICE, PROFIT_PERCENTAGE, PROVIDER) VALUES ("{}", {}, {}, {}, {})'.format(name, gross_price, net_price, profit_percentage, provider[0])):
				flash('Produto cadastrado com sucesso.')
				flash('success')
				log('create', 'PRODUCT: ("{}", {}, {}, {}, {}) -- {} ({})'.format(name, gross_price, net_price, profit_percentage, provider, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível cadastrar o produto.')
				flash('danger')
			return redirect(url_for('products'))
	else:
		return redirect(url_for('login'))


@app.route('/system/products/update', methods=['POST'])
def update_product():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			gross_price = float(request.form['gross_price'].strip())
			net_price = float(request.form['net_price'].strip())
			profit_percentage = round((net_price - gross_price) / gross_price, 2)
			provider = persistence.read_one('SELECT * FROM PROVIDERS WHERE NAME="{}"'.format(request.form['provider'].strip()))
			if provider and persistence.update('UPDATE PRODUCTS SET NAME="{}", GROSS_PRICE={}, NET_PRICE={}, PROFIT_PERCENTAGE={}, PROVIDER={} WHERE ID={}'.format(name, gross_price, net_price, profit_percentage, provider[0], id)):
				flash('Produto alterado com sucesso.')
				flash('success')
				log('update', 'PRODUCT: ({}, "{}", {}, {}, {}, {}) -- {} ({})'.format(id, name, gross_price, net_price, profit_percentage, provider[0], session['user'][0], datetime.now()))
			else:
				flash('Não foi possível alterar o produto.')
				flash('danger')
			return redirect(url_for('products'))
	else:
		return redirect(url_for('login'))


@app.route('/system/products/delete', methods=['POST'])
def delete_product():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			if persistence.delete('DELETE FROM PRODUCTS WHERE ID={}'.format(id)):
				flash('Produto removido com sucesso.')
				flash('success')
				log('delete', 'PRODUCT: ({}) -- {} ({})'.format(id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o produto.')
				flash('danger')
			return redirect(url_for('products'))
	else:
		return redirect(url_for('login'))


############################### PROVIDERS
@app.route('/system/providers')
@app.route('/system/providers/')
def providers():
	if user_validate():
		return redirect(url_for('read_provider'))
	else:
		return redirect(url_for('login'))


@app.route('/system/providers/read', methods=['GET'])
def read_provider():
	if user_validate():
		providers = []
		if request.args.get('name'):
			rows = persistence.read('SELECT * FROM PROVIDERS WHERE NAME LIKE "%{}%"'.format(request.args.get('name').strip()))
		else:
			rows = persistence.read('SELECT * FROM PROVIDERS')
		for provider in rows:
			providers.append(Provider(id=provider[0], name=provider[1], cnpj=provider[2], email=provider[3]))
		if not providers:
			flash('Não foi encontrado nenhum fornecedor.')
			flash('warning')
		return render_template('providers.html', providers=providers)
	else:
		return redirect(url_for('login'))


@app.route('/system/providers/create', methods=['POST'])
def create_provider():
	if user_validate():
		if request.method == 'POST':
			name = request.form['name'].strip().upper()
			cnpj = request.form['cnpj'].strip()
			email = request.form['email'].strip().lower()
			if persistence.create('INSERT INTO PROVIDERS (NAME, CNPJ, EMAIL) VALUES ("{}", "{}", "{}")'.format(name, cnpj, email)):
				flash('Fornecedor cadastrado com sucesso.')
				flash('success')
				log('create', 'PROVIDER: ("{}", "{}", "{}") -- {} ({})'.format(name, cnpj, email, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível cadastrar o fornecedor.')
				flash('danger')
			return redirect(url_for('providers'))
	else:
		return redirect(url_for('login'))


@app.route('/system/providers/update', methods=['POST'])
def update_provider():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			cnpj = request.form['cnpj'].strip()
			email = request.form['email'].strip()
			if persistence.update('UPDATE PROVIDERS SET NAME="{}", CNPJ="{}", EMAIL="{}" WHERE ID={}'.format(name, cnpj, email, id)):
				flash('Fornecedor alterado com sucesso.')
				flash('success')
				log('update', 'PROVIDER: ({}, "{}", "{}", "{}") -- {} ({})'.format(id, name, cnpj, email, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível alterar o fornecedor.')
				flash('danger')
			return redirect(url_for('providers'))
	else:
		return redirect(url_for('login'))


@app.route('/system/providers/delete', methods=['POST'])
def delete_provider():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			if not persistence.read_one('SELECT * FROM PRODUCTS WHERE PROVIDER={}'.format(id)) and persistence.delete('DELETE FROM PROVIDERS WHERE ID={}'.format(id)):
				flash('Fornecedor removido com sucesso.')
				flash('success')
				log('delete', 'PROVIDER: ({}) -- {} ({})'.format(id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o fornecedor.')
				flash('danger')
			return redirect(url_for('providers'))
	else:
		return redirect(url_for('login'))


############################### SERVICES
@app.route('/system/services')
@app.route('/system/services/')
def services():
	if user_validate():
		return redirect(url_for('read_service'))
	else:
		return redirect(url_for('login'))


@app.route('/system/services/read', methods=['GET'])
def read_service():
	if user_validate():
		services = []
		if request.args.get('name'):
			rows = persistence.read('SELECT * FROM SERVICES WHERE NAME LIKE "%{}%"'.format(request.args.get('name').strip()))
		else:
			rows = persistence.read('SELECT * FROM SERVICES')
		for service in rows:
			services.append(Service(id=service[0], name=service[1], price=locale.currency(service[2], grouping=True, symbol=True)))
		if not services:
			flash('Não foi encontrado nenhum serviço.')
			flash('warning')
		return render_template('services.html', services=services)
	else:
		return redirect(url_for('login'))


@app.route('/system/services/create', methods=['POST'])
def create_service():
	if user_validate():
		if request.method == 'POST':
			name = request.form['name'].strip().upper()
			price = float(request.form['price'].strip())
			if persistence.create('INSERT INTO SERVICES (NAME, PRICE) VALUES ("{}", {})'.format(name, price)):
				flash('Serviço cadastrado com sucesso.')
				flash('success')
				log('create', 'SERVICE: ("{}", {}) -- {} ({})'.format(name, price, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível cadastrar o serviço.')
				flash('danger')
			return redirect(url_for('services'))
	else:
		return redirect(url_for('login'))


@app.route('/system/services/update', methods=['POST'])
def update_service():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			price = request.form['price'].strip()
			if persistence.update('UPDATE SERVICES SET NAME="{}", PRICE={} WHERE ID={}'.format(name, price, id)):
				flash('Serviço alterado com sucesso.')
				flash('success')
				log('update', 'SERVICE: ({}, "{}", {}) -- {} ({})'.format(id, name, price, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível alterar o serviço.')
				flash('danger')
			return redirect(url_for('services'))
	else:
		return redirect(url_for('login'))


@app.route('/system/services/delete', methods=['POST'])
def delete_service():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			if persistence.delete('DELETE FROM SERVICES WHERE ID={}'.format(id)):
				flash('Serviço removido com sucesso.')
				flash('success')
				log('delete', 'SERVICE: ({}) -- {} ({})'.format(id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o serviço.')
				flash('danger')
			return redirect(url_for('services'))
	else:
		return redirect(url_for('login'))


########################### USERS
@app.route('/system/users')
@app.route('/system/users/')
def users():
	if user_validate():
		return redirect(url_for('read_user'))
	else:
		return redirect(url_for('login'))


@app.route('/system/users/read', methods=['GET'])
def read_user():
	if user_validate():
		users = []
		if request.args.get('name'):
			rows = persistence.read('SELECT * FROM USERS WHERE NAME LIKE "%{}%"'.format(request.args.get('name').strip()))
		else:
			rows = persistence.read('SELECT * FROM USERS')
		for user in rows:
			users.append(User(id=user[0], name=user[1], alias=user[2], password=user[3]))
		if not users:
			flash('Não foi encontrado nenhum usuário.')
			flash('warning')
		return render_template('users.html', users=users)
	else:
		return redirect(url_for('login'))


@app.route('/system/users/create', methods=['POST'])
def create_user():
	if user_validate():
		if request.method == 'POST':
			name = request.form['name'].strip().upper()
			alias = request.form['alias'].strip()
			password = request.form['password']
			if persistence.create('INSERT INTO USERS (NAME, ALIAS, PASSWORD) VALUES ("{}", "{}", "{}")'.format(name, alias, password)):
				flash('Usuário cadastrado com sucesso.')
				flash('success')
				log('create', 'USER: ("{}", "{}", "{}") -- {} ({})'.format(name, alias, password, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível cadastrar o usuário.')
				flash('danger')
			return redirect(url_for('users'))
	else:
		return redirect(url_for('login'))


@app.route('/system/users/update', methods=['POST'])
def update_user():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			alias = request.form['alias'].strip()
			password = request.form['password']
			if persistence.update('UPDATE USERS SET NAME="{}", ALIAS="{}", PASSWORD="{}" WHERE ID={}'.format(name, alias, password, id)):
				flash('Usuário alterado com sucesso.')
				flash('success')
				log('update', 'USER: ({}, "{}", "{}", "{}") -- {} ({})'.format(id, name, alias, password, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível alterar o usuário.')
				flash('danger')
			return redirect(url_for('users'))
	else:
		return redirect(url_for('login'))


@app.route('/system/users/delete', methods=['POST'])
def delete_user():
	if user_validate():
		if request.method == 'POST':
			id = request.form['id'].strip()
			if persistence.delete('DELETE FROM USERS WHERE ID={}'.format(id)):
				flash('Usuário removido com sucesso.')
				flash('success')
				log('delete', 'USER: ({}) -- {} ({})'.format(id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o usuário.')
				flash('danger')
			return redirect(url_for('users'))
	else:
		return redirect(url_for('login'))
