class User():
	def __init__(self, id=0, name='', alias='', password=''):
		self._id = id
		self._name = name
		self._alias = alias
		self._password = password

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
	def alias(self):
		return self._alias

	@alias.setter
	def alias(self, alias):
		self._alias = alias

	@property
	def password(self):
		return self._password

	@password.setter
	def password(self, password):
		self._password = password
