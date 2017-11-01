import locale, sqlite3
from flask import flash, Flask, g, redirect, render_template, request, session, url_for
from product import Product
from sys import argv

locale.setlocale(locale.LC_MONETARY, 'pt_BR.UTF-8')

app = Flask('dpsjt')
app.config['SECRET_KEY'] = 'Depósito de Materiais de Construção São Judas Tadeu'

DATABASE = 'db/product.db'
USERNAME = 'dpsjt'
PASSWORD = 'dpsjt'

@app.route('/')
@app.route('/<page>')
def index(page=''):
	if page.lower() in ['', 'default', 'home', 'index', 'inicio']:
		return redirect(url_for('home'))
	elif page.lower() in ['about', 'about-us', 'sobre', 'sobre-nos']:
		return redirect(url_for('about'))
	elif page.lower() in ['contact', 'contato', 'localizacao', 'location']:
		return redirect(url_for('contact'))
	elif page.lower() in ['product', 'products', 'produto', 'produtos']:
		return redirect(url_for('products'))
	else:
		return redirect(url_for('error'))

@app.route('/products')
def products():
	return render_template('index.html', page='products')

@app.route('/about')
def about():
	return render_template('index.html', page='about')

@app.route('/authenticate', methods=['POST'])
def authenticate():
	if request.method == 'POST':
		username = request.form['username']
		password = request.form['password']
		if username == USERNAME and password == PASSWORD:
			session['user'] = (username, password)
			return redirect(url_for('system'))
	flash('O usuário ou a senha estão incorretos.')
	return redirect(url_for('login'))

@app.teardown_appcontext
def close_connection(_):
	db = getattr(g, '_database', None)
	if db is not None:
		db.close()

@app.route('/contact')
def contact():
	return render_template('index.html', page='contact')

@app.route('/error')
def error():
	return render_template('error.html')

def get_db():
	db = getattr(g, '_database', None)
	if db is None:
		db = g._database = sqlite3.connect(DATABASE)
	return db

@app.route('/home')
def home():
	return render_template('index.html', page='home')

def init_db():
	with app.app_context():
		db = get_db()
		with app.open_resource('db/product.sql', mode='r', encoding='utf-8') as f:
			db.cursor().executescript(f.read())
		db.commit()

@app.route('/insert', methods=['POST'])
def insert():
	try:
		username, password = session['user']
		if username == USERNAME and password == PASSWORD:
			sql_insert(Product(name=request.form['name'], price=request.form['price']))
			flash('Produto cadastrado com sucesso.')
			return redirect(url_for('system'))
		else:
			return redirect(url_for('login'))
	except:
		flash('Houve um erro no cadastro do produto.')
		return redirect(url_for('system'))

@app.route('/login')
def login():
	try:
		username, password = session['user']
		if username == USERNAME and password == PASSWORD:
			return redirect(url_for('system'))
		else:
			return render_template('login.html')
	except:
		return render_template('login.html')

@app.route('/logout')
def logout():
	session['user'] = None
	return redirect(url_for('login'))

def populate_db():
	with app.app_context():
		db = get_db()
		with app.open_resource('db/data.sql', mode='r', encoding='utf-8') as f:
			db.cursor().executescript(f.read())
		db.commit()

@app.route('/system')
def system():
	try:
		username, password = session['user']
		if username == USERNAME and password == PASSWORD:
			return render_template('system.html', products=sql_all())
		else:
			return redirect(url_for('login'))
	except:
		return redirect(url_for('login'))

@app.route('/search', methods=['POST'])
def search():
	try:
		username, password = session['user']
		if username == USERNAME and password == PASSWORD:
			products = sql_search(request.form['name'])
			if not products:
				flash('Não foi encontrado nenhum produto com o nome informado.')
				return redirect(url_for('system'))
			else:
				return render_template('system.html', products=products)
		else:
			return redirect(url_for('login'))
	except:
		flash('Houve um erro na consulta do produto.')
		return redirect(url_for('system'))

def sql_all():
	cursor = get_db().cursor()
	cursor.execute('SELECT * FROM PRODUCT')
	products = []
	for product in cursor.fetchall():
		products.append(Product(id=product[0], name=product[1], price=locale.currency(product[2], grouping=True, symbol=True)))
	return products

def sql_insert(product):
	db = get_db()
	cursor = db.cursor()
	cursor.execute('INSERT INTO PRODUCT (NAME, PRICE) VALUES ("{}", {})'.format(product.name.upper(), product.price))
	db.commit()

def sql_search(name):
	cursor = get_db().cursor()
	cursor.execute('SELECT * FROM PRODUCT WHERE NAME LIKE "%{}%"'.format(name))
	products = []
	for product in cursor.fetchall():
		products.append(Product(id=product[0], name=product[1], price=locale.currency(product[2], grouping=True, symbol=True)))
	return products

def sql_update(product):
	db = get_db()
	cursor = db.cursor()
	cursor.execute('UPDATE PRODUCT SET NAME="{}", PRICE={} WHERE ID={}'.format(product.name.upper(), product.price, product.id))
	db.commit()
	return cursor.rowcount

@app.route('/update', methods=['POST'])
def update():
	try:
		username, password = session['user']
		if username == USERNAME and password == PASSWORD:
			if sql_update(Product(id=request.form['id'], name=request.form['name'], price=request.form['price'])) > 0:
				flash('Produto alterado com sucesso.')
			else:
				flash('O produto não foi alterado, o ID informado não é válido.')
			return redirect(url_for('system'))
		else:
			return redirect(url_for('login'))
	except:
		flash('Houve um erro na alteração do produto.')
		return redirect(url_for('system'))

if __name__ == '__main__':
	if len(argv) > 1 and argv[1].lower() == 'debug':
		app.run(debug=True, use_reloader=True)
	elif len(argv) > 1 and argv[1].lower() == 'initdb':
		init_db()
		if len(argv) > 2 and argv[2].lower() == 'populate':
			populate_db()
	else:
		app.run()
