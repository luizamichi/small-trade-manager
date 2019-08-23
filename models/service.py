class Service():
	def __init__(self, id=0, name='', price=0.0):
		self._id = id
		self._name = name
		self._price = price

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
	def price(self):
		return self._price

	@price.setter
	def price(self, price):
		self._price = price
