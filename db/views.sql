create view view_clients as
	select clients.id client_id, clients.name client_name, clients.surname client_surname, clients.rg client_rg, clients.cpf client_cpf, clients.birthday client_birthday, clients.postal_code client_postal_code, clients.district client_district, clients.city client_city, clients.state client_state, clients.address client_address, clients.number client_number, clients.complement client_complement, clients.email client_email, clients.phone client_phone, clients.cell_phone client_cell_phone, clients.sex client_sex, clients.note client_note
	from clients;

create view view_permissions as
	select permissions.id permission_id, permissions.budget permission_budget, permissions.client permission_client, permissions.employee permission_employee, permissions.product permission_product, permissions.provider permission_provider, permissions.purchase permission_purchase, permissions.record permission_record, permissions.report permission_report, permissions.sale permission_sale, permissions.setting permission_setting, permissions.service permission_service
	from permissions;

create view view_employees as
	select employees.id employee_id, employees.name employee_name, employees.surname employee_surname, employees.alias employee_alias, employees.password employee_password, employees.rg employee_rg, employees.cpf employee_cpf, employees.birthday employee_birthday, employees.postal_code employee_postal_code, employees.district employee_district, employees.city employee_city, employees.state employee_state, employees.address employee_address, employees.number employee_number, employees.complement employee_complement, employees.email employee_email, employees.phone employee_phone, employees.cell_phone employee_cell_phone, employees.sex employee_sex, employees.note employee_note,
	view_permissions.*
	from employees, view_permissions
	where employees.permission = view_permissions.permission_id;

create view view_budgets as
	select budgets.id budget_id, budgets.day budget_day, budgets.form_of_payment budget_form_of_payment, budgets.discount budget_discount, budgets.total budget_total,
	view_employees.*
	from budgets, view_employees
	where budgets.employee = view_employees.employee_id;

create view view_providers as
	select providers.id provider_id, providers.company_name provider_company_name, providers.fantasy_name provider_fantasy_name, providers.state_registration provider_state_registration, providers.cnpj provider_cnpj, providers.foundation_date provider_foundation_date, providers.postal_code provider_postal_code, providers.district provider_district, providers.city provider_city, providers.state provider_state, providers.address provider_address, providers.number provider_number, providers.complement provider_complement, providers.email provider_email, providers.phone provider_phone, providers.cell_phone provider_cell_phone, providers.note provider_note
	from providers;

create view view_products as
	select products.id product_id, products.code product_code, products.name product_name, products.unity product_unity, products.gross_price product_gross_price, products.net_price product_net_price, products.minimum_stock product_minimum_stock, products.maximum_stock product_maximum_stock, products.amount product_amount, products.weigth product_weigth, products.situation product_situation, products.source product_source,
	view_providers.*
	from products, view_providers
	where products.provider = view_providers.provider_id;

create view view_purchases as
	select purchases.id purchase_id, purchases.day purchase_day, purchases.form_of_payment purchase_form_of_payment, purchases.discount purchase_discount, purchases.total purchase_total,
	view_providers.*,
	view_employees.*
	from purchases, view_providers, view_employees
	where purchases.provider = view_providers.provider_id
	and purchases.employee = view_employees.employee_id;

create view view_records as
	select records.id record_id, records.reference record_reference, records.action record_action, records.entity record_entity, records.description record_description, records.day record_day,
	view_employees.*
	from records, view_employees
	where records.employee = view_employees.employee_id;

create view view_sales as
	select sales.id sale_id, sales.day sale_day, sales.form_of_payment sale_form_of_payment, sales.discount sale_discount, sales.total sale_total,
	view_clients.*,
	view_employees.*
	from sales, view_clients, view_employees
	where sales.client = view_clients.client_id
	and sales.employee = view_employees.employee_id;

create view view_services as
	select services.id service_id, services.code service_code, services.name service_name, services.type service_type, services.price service_price, services.workload service_workload
	from services;

create view view_settings as
	select settings.id setting_id, settings.company_name setting_company_name, settings.fantasy_name setting_fantasy_name, settings.cnpj setting_cnpj, settings.postal_code setting_postal_code, settings.district setting_district, settings.city setting_city, settings.state setting_state, settings.address setting_address, settings.number setting_number, settings.email setting_email, settings.phone setting_phone, settings.website setting_website
	from settings;

create view view_product_budget as
	select product_budget.id product_budget_id, product_budget.quantity product_budget_quantity, product_budget.unit_price product_budget_unit_price,
	view_products.*,
	view_budgets.*
	from product_budget, view_products, view_budgets
	where product_budget.product = view_products.product_id
	and product_budget.budget = view_budgets.budget_id;

create view view_product_purchase as
	select product_purchase.id product_purchase_id, product_purchase.quantity product_purchase_quantity, product_purchase.unit_price product_purchase_unit_price,
	view_products.*,
	purchases.id purchase_id, purchases.day purchase_day, purchases.form_of_payment purchase_form_of_payment, purchases.discount purchase_discount, purchases.total purchase_total
	from product_purchase, view_products, purchases
	where product_purchase.product = view_products.product_id
	and product_purchase.purchase = purchases.id;

create view view_product_sale as
	select product_sale.id product_sale_id, product_sale.quantity product_sale_quantity, product_sale.unit_price product_sale_unit_price,
	view_products.*,
	view_sales.*
	from product_sale, view_products, view_sales
	where product_sale.product = view_products.product_id
	and product_sale.sale = view_sales.sale_id;

create view view_service_budget as
	select service_budget.id service_budget_id, service_budget.quantity service_budget_quantity, service_budget.unit_price service_budget_unit_price,
	view_services.*,
	view_budgets.*
	from service_budget, view_services, view_budgets
	where service_budget.service = view_services.service_id
	and service_budget.budget = view_budgets.budget_id;

create view view_service_sale as
	select service_sale.id service_sale_id, service_sale.quantity service_sale_quantity, service_sale.unit_price service_sale_unit_price,
	view_services.*,
	view_sales.*
	from service_sale, view_services, view_sales
	where service_sale.service = view_services.service_id
	and service_sale.sale = view_sales.sale_id;
