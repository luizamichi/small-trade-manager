from flask import flash, redirect, render_template, request, session, url_for
from datetime import datetime
from app import app, locale

from models.product import Product
from models.provider import Provider
from models.user import User

from controllers import persistence

@app.route('/')
@app.route('/index')
def index():
	return render_template('index.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
	if request.method == 'POST':
		if user_validate(request.form['username'], request.form['password']):
			session['user'] = (request.form['username'], request.form['password'])
			flash('Bem-vindo ao sistema.')
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

def user_validate(username='', password=''):
	try:
		_, _ = session['user']
		return True
	except (KeyError, TypeError, ValueError):
		row = persistence.read_one('SELECT * FROM USERS WHERE USERNAME="{}"'.format(username))
		if row and username == row[2] and password == row[3]:
			return True
	return False

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
			providers.append(Provider(id=provider[0], name=provider[1], cnpj=provider[2]))
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
			tuple_id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			gross_price = float(request.form['gross_price'].strip())
			net_price = float(request.form['net_price'].strip())
			profit_percentage = round((net_price - gross_price) / gross_price, 2)
			provider = persistence.read_one('SELECT * FROM PROVIDERS WHERE NAME="{}"'.format(request.form['provider'].strip()))
			if provider and persistence.update('UPDATE PRODUCTS SET NAME="{}", GROSS_PRICE={}, NET_PRICE={}, PROFIT_PERCENTAGE={}, PROVIDER={} WHERE ID={}'.format(name, gross_price, net_price, profit_percentage, provider[0], tuple_id)):
				flash('Produto alterado com sucesso.')
				flash('success')
				log('update', 'PRODUCT: ({}, "{}", {}, {}, {}, {}) -- {} ({})'.format(tuple_id, name, gross_price, net_price, profit_percentage, provider, session['user'][0], datetime.now()))
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
			tuple_id = request.form['id'].strip()
			if persistence.update('DELETE FROM PRODUCTS WHERE ID={}'.format(tuple_id)):
				flash('Produto removido com sucesso.')
				flash('success')
				log('delete', 'PRODUCT: ({}) -- {} ({})'.format(tuple_id, session['user'][0], datetime.now()))
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
			providers.append(Provider(id=provider[0], name=provider[1], cnpj=provider[2]))
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
			if persistence.create('INSERT INTO PROVIDERS (NAME, CNPJ) VALUES ("{}", "{}")'.format(name, cnpj)):
				flash('Fornecedor cadastrado com sucesso.')
				flash('success')
				log('create', 'PROVIDER: ("{}", "{}") -- {} ({})'.format(name, cnpj, session['user'][0], datetime.now()))
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
			tuple_id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			cnpj = request.form['cnpj'].strip()
			if persistence.update('UPDATE PROVIDERS SET NAME="{}", CNPJ="{}" WHERE ID={}'.format(name, cnpj, tuple_id)):
				flash('Fornecedor alterado com sucesso.')
				flash('success')
				log('update', 'PROVIDER: ({}, "{}", "{}") -- {} ({})'.format(tuple_id, name, cnpj, session['user'][0], datetime.now()))
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
			tuple_id = request.form['id'].strip()
			if not persistence.read_one('SELECT * FROM PRODUCTS WHERE PROVIDER={}'.format(tuple_id)) and persistence.update('DELETE FROM PROVIDERS WHERE ID={}'.format(tuple_id)):
				flash('Fornecedor removido com sucesso.')
				flash('success')
				log('delete', 'PROVIDER: ({}) -- {} ({})'.format(tuple_id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o fornecedor.')
				flash('danger')
			return redirect(url_for('providers'))
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
			users.append(User(id=user[0], name=user[1], username=user[2], password=user[3]))
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
			username = request.form['username'].strip()
			password = request.form['password']
			if persistence.create('INSERT INTO USERS (NAME, USERNAME, PASSWORD) VALUES ("{}", "{}", "{}")'.format(name, username, password)):
				flash('Usuário cadastrado com sucesso.')
				flash('success')
				log('create', 'USER: ("{}", "{}", "{}") -- {} ({})'.format(name, username, password, session['user'][0], datetime.now()))
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
			tuple_id = request.form['id'].strip()
			name = request.form['name'].strip().upper()
			username = request.form['username'].strip()
			password = request.form['password']
			if persistence.update('UPDATE USERS SET NAME="{}", USERNAME="{}", PASSWORD="{}" WHERE ID={}'.format(name, username, password, tuple_id)):
				flash('Usuário alterado com sucesso.')
				flash('success')
				log('update', 'USER: ({}, "{}", "{}", "{}") -- {} ({})'.format(tuple_id, name, username, password, session['user'][0], datetime.now()))
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
			tuple_id = request.form['id'].strip()
			if persistence.update('DELETE FROM USERS WHERE ID={}'.format(tuple_id)):
				flash('Usuário removido com sucesso.')
				flash('success')
				log('delete', 'USER: ({}) -- {} ({})'.format(tuple_id, session['user'][0], datetime.now()))
			else:
				flash('Não foi possível remover o usuário.')
				flash('danger')
			return redirect(url_for('users'))
	else:
		return redirect(url_for('login'))
