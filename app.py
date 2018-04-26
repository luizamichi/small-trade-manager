import locale, sys
from flask import Flask

locale.setlocale(locale.LC_MONETARY, 'pt_BR.UTF-8')

app = Flask(__name__)
app.config.from_object('config')

from controllers.default import *
from controllers import persistence

if __name__ == '__main__':
	if len(sys.argv) > 1 and sys.argv[1].lower() == 'initdb':
		persistence.matter('db/tables.sql')
		if len(sys.argv) > 2 and sys.argv[2].lower() == 'populate':
			persistence.matter('db/inserts.sql')
	elif len(sys.argv) > 2 and sys.argv[1].lower() == 'exportdb':
		persistence.export(sys.argv[2])
	else:
		app.run()
