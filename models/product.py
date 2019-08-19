from .provider import Provider

class Product():
	def __init__(self, id=0, name='', gross_price=0.0, net_price=0.0, profit_percentage=0.0, provider=Provider()):
		self._id = id
		self._name = name
		self._gross_price = gross_price
		self._net_price = net_price
		self._profit_percentage = profit_percentage
		self._provider = provider

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
	def gross_price(self):
		return self._gross_price

	@gross_price.setter
	def gross_price(self, gross_price):
		self._gross_price = gross_price

	@property
	def net_price(self):
		return self._net_price

	@net_price.setter
	def net_price(self, net_price):
		self._net_price = net_price

	@property
	def profit_percentage(self):
		return self._profit_percentage

	@profit_percentage.setter
	def profit_percentage(self, profit_percentage):
		self._profit_percentage = profit_percentage

	@property
	def provider(self):
		return self._provider

	@provider.setter
	def provider(self, provider):
		self._provider = provider
