class User():
	def __init__(self, id=0, name='', username='', password=''):
		self._id = id
		self._name = name
		self._username = username
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
	def username(self):
		return self._username

	@username.setter
	def username(self, username):
		self._username = username

	@property
	def password(self):
		return self._password

	@password.setter
	def password(self, password):
		self._password = password
