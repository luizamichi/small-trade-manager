class Client():
	def __init__(self, id=0, name='', cpf='', address='', neighborhood=None, city='', telephone=None, cellphone=None):
		self._id = id
		self._name = name
		self._cpf = cpf
		self._address = address
		self._neighborhood = neighborhood
		self._city = city
		self._telephone = telephone
		self._cellphone = cellphone

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
	def cpf(self):
		return self._cpf

	@cpf.setter
	def cpf(self, cpf):
		self._cpf = cpf

	@property
	def address(self):
		return self._address

	@address.setter
	def address(self, address):
		self._address = address

	@property
	def neighborhood(self):
		return self._neighborhood

	@neighborhood.setter
	def neighborhood(self, neighborhood):
		self._neighborhood = neighborhood

	@property
	def city(self):
		return self._city

	@city.setter
	def city(self, city):
		self._city = city

	@property
	def telephone(self):
		return self._telephone

	@telephone.setter
	def telephone(self, telephone):
		self._telephone = telephone

	@property
	def cellphone(self):
		return self._cellphone

	@cellphone.setter
	def cellphone(self, cellphone):
		self._cellphone = cellphone
