import sqlite3
from config import DATABASE_URI

# Inserir tupla na tabela
def create(query):
	with sqlite3.connect(DATABASE_URI) as connection:
		cursor = connection.cursor()
		try:
			cursor.execute(query)
			connection.commit()
			return cursor.rowcount
		except:
			return False

# Procurar tuplas na tabela
def read(query):
	with sqlite3.connect(DATABASE_URI) as connection:
		cursor = connection.cursor()
		cursor.execute(query)
		return cursor.fetchall()

# Procurar tupla na tabela
def read_one(query):
	with sqlite3.connect(DATABASE_URI) as connection:
		cursor = connection.cursor()
		cursor.execute(query)
		return cursor.fetchone()

# Alterar tupla da tabela
def update(query):
	with sqlite3.connect(DATABASE_URI) as connection:
		cursor = connection.cursor()
		try:
			cursor.execute(query)
			connection.commit()
			return cursor.rowcount
		except:
			return False

# Remover tupla da tabela
def delete(query):
	with sqlite3.connect(DATABASE_URI) as connection:
		cursor = connection.cursor()
		try:
			cursor.execute(query)
			return cursor.rowcount
		except:
			return False

# Exportar tabelas a partir de um arquivo DB
def export(filename):
	with open(filename, 'w', encoding='utf-8') as file:
		connection = sqlite3.connect(DATABASE_URI)
		for row in connection.iterdump():
			file.write('%s\n' % row)

# Importar tabelas a partir de um arquivo SQL
def matter(filename):
	with open(filename, 'r', encoding='utf-8') as file:
		connection = sqlite3.connect(DATABASE_URI)
		cursor = connection.cursor()
		cursor.executescript(file.read())
		connection.commit()
