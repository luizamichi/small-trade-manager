create index budget_id
on budgets (id);

create index client_id
on clients (id);

create index employee_id
on employees (id);

create index permission_id
on permissions (id);

create index product_id
on products (id);

create index provider_id
on providers (id);

create index purchase_id
on purchases (id);

create index record_id
on records (id);

create index sale_id
on sales (id);

create index service_id
on services (id);

create index setting_id
on settings (id);

create index product_budget_id
on product_budget (product, budget);

create index product_purchase_id
on product_purchase (product, purchase);

create index product_sale_id
on product_sale (product, sale);

create index service_budget_id
on service_budget (service, budget);

create index service_sale_id
on service_sale (service, sale);
