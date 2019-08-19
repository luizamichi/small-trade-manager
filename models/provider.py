class Provider():
	def __init__(self, id=0, name='', cnpj='', email=None):
		self._id = id
		self._name = name
		self._cnpj = cnpj
		self._email = email

	@property
	def id(self):
		return self._id

	@id.setter
	def id(self, id):
		self._id = id

	@property
	def name(self):
		return self._name

	@name.setter
	def name(self, name):
		self._name = name

	@property
	def cnpj(self):
		return self._cnpj

	@cnpj.setter
	def cnpj(self, cnpj):
		self._cnpj = cnpj

	@property
	def email(self):
		return self._email

	@email.setter
	def email(self, email):
		self._email = email
