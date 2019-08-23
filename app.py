import locale, sys
from datetime import datetime
from flask import Flask, render_template

locale.setlocale(locale.LC_MONETARY, 'pt_BR.UTF-8')

app = Flask(__name__)
app.config.from_object('config')

from models import product, provider, user

from controllers.default import *
from controllers import persistence

if __name__ == '__main__':
	if len(sys.argv) > 1 and sys.argv[1].lower() == 'initdb':
		persistence.matter('db/tables.sql')
		if len(sys.argv) > 2 and sys.argv[2].lower() == 'populate':
			persistence.matter('db/inserts.sql')
	elif len(sys.argv) == 2 and sys.argv[1].lower() == 'populate':
		persistence.matter('db/inserts.sql')
	elif len(sys.argv) > 2 and sys.argv[1].lower() == 'exportdb':
		persistence.export(sys.argv[2])
	else:
		app.run()
