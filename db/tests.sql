truncate table settings;
insert into settings (id, company_name, fantasy_name, cnpj, postal_code, district, city, state, address, number, email, phone, website) values
(1, 'Ciência da Computação Ltda', 'CC UEM', '27149569000170', '87020900', 'Zona 7', 'Maringá', 'PR', 'Avenida Colombo', '5790', 'financeiro@cc.br', '6138637861', 'https://www.cc.br');

truncate table permissions;
insert into permissions (id, budget, client, employee, product, provider, purchase, record, report, sale, setting, service) values
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1),
(3, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0),
(4, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0),
(5, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1);

truncate table employees;
insert into employees (id, name, surname, alias, password, rg, cpf, birthday, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, sex, note, permission) values
(1, 'Luiz Joaquim', 'Aderaldo Amichi', 'luizamichi', md5('luizamichi'), '252216015', '01234567890', '1996-11-24', '87020260', 'Zona 7', 'Maringá', 'PR', 'Avenida Doutor Mario Clapier Urbinati', 395, 'Apartamento 205', 'eu@luizamichi.com.br', '4430370105', '44998453312', 'M', null, 1),
(2, 'Tatiane Tânia', 'Eliane Galvão', 'tatiane', md5('tatiane'), '472910899', '20421175281', '2003-08-23', '68911358', 'Muruci', 'Macapá', 'AP', 'Travessa Floriano Ferreira', 508, 'Casa', 'tatiane_tania@owlti.com.br', '9636305672', '96999470679', 'F', 'Mãe: Antonella Brenda Beatriz\nPai: Cláudio Manuel Caleb Galvão\nAltura: 1,72\nPeso: 50\nTipo sanguíneo: AB-\nCor favorita: verde\nSenha sugerida: g801c7UC8d', 2),
(3, 'Giovanni Iago', 'da Paz', 'giovanni', md5('giovanni'), '285631287', '61998534740', '1974-01-19', '69316299', 'Operário', 'Boa Vista', 'RR', 'Rua Advogado Alceu da Silva', 434, null, 'giovanni_dapaz@unitau.com.br', null, '95997512967', 'M', null, 3),
(4, 'Heloisa Sara', 'Sebastiana Santos', 'heloisa', md5('heloisa'), null, '69331060203', '1989-04-06', null, null, 'João Pessoa', 'PB', 'Rua Antônio Sávio Cavalcanti de Lima', 187, null, 'heloisa.sara@cartovale.com.br', null, null, 'F', null, 4),
(5, 'Emanuel Benjamin', 'Caleb Melo', 'emanuel', md5('emanuel'), '232058003', '50023677040', '1993-05-03', '60356374', null, 'Fortaleza', 'CE', 'Travessa Evandro Luz', 728, null, 'emanuel@santosdumont.com', '8536366614', null, 'M', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia purus tellus. Maecenas ultricies tempor justo, ac ultricies lorem mattis convallis. Ut at blandit neque. Sed efficitur felis enim, a auctor orci semper nec. Praesent at sollicitudin erat. Nulla et sollicitudin enim, a tempor ante. Pellentesque vel egestas massa.', 5);

truncate table services;
insert into services (id, code, name, type, price, workload) values
(1, '1001', 'Montagem de extensão elétrica', 'Elétrica em geral', 15, '00:10:00'),
(2, '2002', 'Formatação com Windows 11', 'Serviço técnico de informática', 100, '06:00:00'),
(3, '3003', 'Backup de dados', 'Serviço técnico de informática', 50, '03:00:00'),
(4, '4004', 'Instalação de chuveiro elétrico', 'Elétrica em geral', 45, '01:00:00'),
(5, '5005', 'Limpeza de computador', 'Serviço técnico de informática', 55, '01:00:00');

truncate table providers;
insert into providers (id, company_name, fantasy_name, state_registration, cnpj, foundation_date, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, note) values
(1, 'Caio e Raimunda Telas Ltda', 'Cara Telas', '966605984773', '31009752000147', '2018-01-10', '14808622', 'Jardim Athenas', 'Araraquara', 'SP', 'Avenida Professora Maria Kina', 261, null, 'qualidade@caioeraimunda.com.br', '1628899565', '16988568600', 'www.caioeraimundatelasLtda.com.br'),
(2, 'Bragança Bernardes Tintas ME', 'Tintas Bragança', '139696832938', '39141360000149', '1941-06-18', '65637576', 'Bela Vista', 'Timon', 'MA', 'Beco Dezenove', 30, null, 'tintas@braganca.com.br', '6324837462', '63974676838', 'Dados gerados com o Gerador NV (https://geradornv.com.br/gerador-empresas)'),
(3, 'Conceição Zago Tatuagens ME', 'Tatuagens Conceição', null, '58050139000120', '1944-05-15', '76870218', 'Áreas Especiais', 'Ariquemes', 'RO', 'Rua Piquira', 72, 'Sala 1020', 'tatuagens@conceicao.com.br', '6938347864', null, null),
(4, 'Camacho Guedes Indústria ME', 'Indústria Camacho', '147524070536', '26797708000109', '2016-12-10', '06764120', 'Jardim Maria Rosa', 'Taboão da Serra', 'SP', 'Rua Joaquim Rodrigues dos Santos', 506, null, 'industria@camacho.com.br', '1137160072', '11996554853', 'Dados gerados pelo website https://www.4devs.com.br/gerador_de_empresas'),
(5, 'Mozer Guedes Pizzaria Ltda', 'Pizzaria Mozer', '849504630454', '31907726000136', '2014-01-23', '13214651', 'Jardim Sales', 'Jundiaí', 'SP', 'Via Sete C', 790, 'Próximo ao Dogão do Ferdinando', 'pizzaria@mozer.com.br', '1139930148', '11985869244', null);

truncate table products;
insert into products (id, code, name, provider, unity, gross_price, net_price, minimum_stock, maximum_stock, amount, weigth, situation, source) values
(1, '1102', 'One Jeffrey', 1, 'cm', 10.25, 25.99, 5, 10, 10, 1.255, 1, 0),
(2, '2313', 'Two Jenkins', 3, 'CX', 54.85, 130, 10, 25, 25, 2.525, 1, 1),
(3, '3455', 'Three Jerry', 2, 'g', 5.31, 6.7, 5, 30, 30, 1.375, 1, 0),
(4, '4690', 'Four Manning', 4, 'UN', 3.5, 5, 10, 40, 40, 1.331, 1, 1),
(5, '5201', 'Five Heather', 1, 'kg', 1500, 5000, 15, 55, 55, 2.117, 1, 0),
(6, '6978', 'Six Greene', 5, 'l', 375, 1200, 5, 60, 60, 1.105, 1, 0),
(7, '7132', 'Seven Kass', 3, 'm', 99, 312, 3, 75, 75, 1.135, 1, 1),
(8, '8001', 'Eight Allen', 2, 'ml', 89.9, 149.99, 5, 80, 80, 1.485, 1, 0),
(9, '9982', 'Nine Mozelle', 5, 'PÇ', 576.78, 700, 10, 95, 95, 1.896, 1, 1),
(10, '1989', 'Ten Brown', 4, 'UN', 0.99, 1.98, 5, 45, 45, 2.224, 1, 1);

truncate table purchases;
insert into purchases (id, provider, employee, day, form_of_payment, discount, total) values
(1, 2, 4, date(now() - interval 20 day), 'Dinheiro', 0.1, 143.37),
(2, 1, 1, date(now() - interval 19 day), 'A prazo', 0, 45051.25),
(3, 5, 5, date(now() - interval 18 day), 'DOC', 0.075, 64559.54),
(4, 4, 4, date(now() - interval 17 day), 'PIX', 0.12, 87.74),
(5, 3, 3, date(now() - interval 16 day), 'TED', 0.03, 532.04),
(6, 3, 1, date(now() - interval 15 day), 'Cartão de crédito', 0, 4950),
(7, 2, 5, date(now() - interval 14 day), 'Cartão de débito', 0.05, 5978.35);

truncate table product_purchase;
insert into product_purchase (id, product, purchase, quantity, unit_price) values
(1, 3, 1, 30, 5.31),
(2, 1, 2, 5, 10.25),
(3, 5, 2, 30, 1500),
(4, 6, 3, 40, 375),
(5, 9, 3, 95, 576.78),
(6, 4, 4, 20, 3.5),
(7, 10, 4, 30, 0.99),
(8, 2, 5, 10, 54.85),
(9, 7, 6, 50, 99),
(10, 8, 7, 70, 89.9);

truncate table clients;
insert into clients (id, name, surname, rg, cpf, birthday, postal_code, district, city, state, address, number, complement, email, phone, cell_phone, sex, note) values
(1, 'Ricardo Leandro Paulo', 'da Luz', '306073043', '31622176600', '1976-02-25', '79042654', 'Jardim Jerusalem', 'Campo Grande', 'MS', 'Rua Morganita', 774, null, 'ricardo@samsaraimoveis.com.br', '6735467322', '67993324187', 'M', null),
(2, 'Tereza Sabrina', 'Brito', null, '12860912339', '1944-05-14', '45028536', 'Candeias', 'Vitória da Conquista', 'BA', 'Avenida Jorge Teixeira', 485, null, null, null, null, 'F', null),
(3, 'Sérgio Isaac', 'Barros', '469899517', '12149977249', '1945-06-04', null, null, 'Itabuna', 'BA', 'Rua Luiz Oliveira', 104, null, 'sergio_isaac@barros.com.br', '7339780681', '73985148844', 'M', 'Dados gerados pelo website https://www.4devs.com.br/gerador_de_pessoas'),
(4, 'Antonieta', 'Queiroz Billé', '278785591', '28333455182', '1993-03-05', '72917471', 'Cidade do Entorno', 'Águas Lindas de Goiás', 'GO', 'Quadra 69', '100', 'Ao lado da quadra 70', 'antonieta.bille@geradornv.com.br', null, '62981676573', 'F', 'Dados gerados com o Gerador NV (https://geradornv.com.br/gerador-pessoas)'),
(5, 'Lorenzo Manoel Renato', 'dos Santos', null, '55655949288', '1953-09-01', null, null, 'Belo Horizonte', 'MG', 'Rua Fernando Marcos de Araújo', 387, null, null, null, null, 'M', null),
(6, 'Rebeca Aline', 'Aparício', null, '10211579610', '1959-07-02', null, null, 'Rio Branco', 'AC', 'Rua Bom Jesus', '965', null, null, null, null, 'F', null),
(7, 'Anthony Gael', 'Costa', null, '71207446823', '1952-08-09', null, null, 'Várzea Grande', 'MT', 'Rua Equador', '700', null, null, null, null, 'M', null);

truncate table budgets;
insert into budgets (id, employee, day, form_of_payment, discount, total) values
(1, 1, date(now() - interval 13 day), 'Dinheiro', 0.1, 450.89),
(2, 2, date(now() - interval 12 day), 'A prazo', 0, 70.1),
(3, 4, date(now() - interval 11 day), 'DOC', 0.08, 50.6),
(4, 5, date(now() - interval 10 day), 'PIX', 0.12, 6573.6),
(5, 2, date(now() - interval 9 day), 'TED', 0.09, 1226.68),
(6, 5, date(now() - interval 8 day), 'Cartão de crédito', 0, 1144.98),
(7, 1, date(now() - interval 7 day), 'Cartão de débito', 0, 64.9);

truncate table product_budget;
insert into product_budget (id, product, budget, quantity, unit_price) values
(1, 1, 1, 1, 25.99),
(2, 2, 1, 2, 130),
(3, 3, 2, 3, 6.7),
(4, 4, 3, 2, 5),
(5, 5, 4, 1, 5000),
(6, 6, 4, 2, 1200),
(7, 7, 5, 4, 312),
(8, 8, 6, 2, 149.99),
(9, 9, 6, 1, 700),
(10, 10, 7, 5, 1.98);

truncate table service_budget;
insert into service_budget (id, service, budget, quantity, unit_price) values
(1, 1, 1, 1, 15),
(2, 2, 2, 2, 100),
(3, 3, 2, 1, 50),
(4, 4, 2, 1, 45),
(5, 5, 3, 1, 55),
(6, 1, 3, 1, 15),
(7, 2, 5, 1, 100),
(8, 3, 7, 2, 50),
(9, 4, 7, 1, 45),
(10, 5, 7, 1, 55);

truncate table sales;
insert into sales (id, client, employee, day, form_of_payment, discount, total) values
(1, 1, 1, date(now() - interval 6 day), 'Dinheiro', 0.07, 368.88),
(2, 2, 2, date(now() - interval 5 day), 'A prazo', 0, 225),
(3, 3, 4, date(now() - interval 4 day), 'DOC', 0, 20215),
(4, 4, 4, date(now() - interval 3 day), 'PIX', 0.05, 1230.25),
(5, 5, 5, date(now() - interval 2 day), 'TED', 0, 1036),
(6, 6, 1, date(now() - interval 1 day), 'Cartão de crédito', 0, 1159.93),
(7, 7, 5, date(now()), 'Cartão de débito', 0, 2109.9);

truncate table product_sale;
insert into product_sale (id, product, sale, quantity, unit_price) values
(1, 1, 1, 5, 25.99),
(2, 2, 1, 2, 130),
(3, 3, 1, 1, 6.7),
(4, 4, 2, 2, 5),
(5, 5, 3, 4, 5000),
(6, 6, 4, 1, 1200),
(7, 7, 5, 3, 312),
(8, 8, 6, 7, 149.99),
(9, 9, 7, 3, 700),
(10, 10, 7, 5, 1.98);

truncate table service_sale;
insert into service_sale (id, service, sale, quantity, unit_price) values
(1, 1, 2, 1, 15),
(2, 2, 2, 2, 100),
(3, 1, 3, 1, 15),
(4, 2, 3, 2, 50),
(5, 3, 3, 1, 100),
(6, 4, 4, 1, 45),
(7, 3, 4, 1, 50),
(8, 5, 5, 1, 55),
(9, 4, 5, 1, 45),
(10, 5, 6, 2, 55);

truncate table records;
insert into records (id, reference, action, entity, employee, description, day) values
(1, 1, 'insert', 'settings', 3, 'O:8:"stdClass":13:{s:2:"id";i:1;s:12:"company_name";s:29:"Ciência da Computação Ltda";s:12:"fantasy_name";s:6:"CC UEM";s:4:"cnpj";s:14:"27149569000170";s:11:"postal_code";s:8:"87020900";s:8:"district";s:6:"Zona 7";s:4:"city";s:8:"Maringá";s:5:"state";s:2:"PR";s:7:"address";s:15:"Avenida Colombo";s:6:"number";s:4:"5790";s:5:"email";s:16:"financeiro@cc.br";s:5:"phone";s:10:"6138637861";s:7:"website";s:17:"https://www.cc.br";}', date(now() - interval 10 day)),
(2, 4, 'insert', 'employees', 2, 'O:8:"stdClass":19:{s:2:"id";s:1:"4";s:4:"name";s:12:"Heloisa Sara";s:7:"surname";s:17:"Sebastiana Santos";s:5:"alias";s:7:"heloisa";s:2:"rg";N;s:3:"cpf";s:11:"69331060203";s:8:"birthday";s:10:"1989-04-06";s:11:"postal_code";N;s:8:"district";N;s:4:"city";s:12:"João Pessoa";s:5:"state";s:2:"PB";s:7:"address";s:38:"Rua Antônio Sávio Cavalcanti de Lima";s:6:"number";s:3:"187";s:10:"complement";N;s:5:"email";s:29:"heloisa.sara@cartovale.com.br";s:5:"phone";N;s:10:"cell_phone";N;s:3:"sex";s:1:"F";s:4:"note";N;}', date(now() - interval 9 day)),
(3, 1, 'insert', 'services', 5, 'O:8:"stdClass":6:{s:2:"id";s:1:"1";s:4:"code";s:4:"1001";s:4:"name";s:31:"Montagem de extensão elétrica";s:4:"type";s:18:"Elétrica em geral";s:5:"price";s:5:"15.00";s:8:"workload";s:5:"00:10";}', date(now() - interval 8 day)),
(4, 1, 'insert', 'providers', 1, 'O:8:"stdClass":17:{s:2:"id";s:1:"1";s:12:"company_name";s:26:"Caio e Raimunda Telas Ltda";s:12:"fantasy_name";s:10:"Cara Telas";s:18:"state_registration";s:12:"966605984773";s:4:"cnpj";s:14:"31009752000147";s:15:"foundation_date";s:10:"2018-01-10";s:11:"postal_code";s:8:"14808622";s:8:"district";s:14:"Jardim Athenas";s:4:"city";s:10:"Araraquara";s:5:"state";s:2:"SP";s:7:"address";s:29:"Avenida Professora Maria Kina";s:6:"number";s:3:"261";s:10:"complement";N;s:5:"email";s:30:"qualidade@caioeraimunda.com.br";s:5:"phone";s:10:"1628899565";s:10:"cell_phone";s:11:"16988568600";s:4:"note";s:33:"www.caioeraimundatelasLtda.com.br";}', date(now() - interval 7 day)),
(5, 1, 'insert', 'products', 5, 'O:8:"stdClass":13:{s:2:"id";s:1:"1";s:4:"code";s:4:"1102";s:4:"name";s:11:"One Jeffrey";s:8:"provider";s:1:"1";s:5:"unity";s:2:"cm";s:11:"gross_price";s:5:"10.25";s:9:"net_price";s:5:"25.99";s:13:"minimum_stock";s:1:"5";s:13:"maximum_stock";s:2:"10";s:6:"amount";s:2:"10";s:6:"weigth";s:5:"1.255";s:9:"situation";s:1:"1";s:6:"source";s:1:"0";}', date(now() - interval 6 day)),
(6, 5, 'insert', 'purchases', 4, 'O:8:"stdClass":11:{s:8:"provider";s:1:"3";s:8:"employee";s:1:"1";s:8:"products";a:1:{i:0;i:2;}s:10:"quantities";a:1:{i:0;i:10;}s:6:"prices";a:1:{i:0;i:54;}s:3:"day";s:19:"2025-04-07 21:59:58";s:15:"form_of_payment";s:3:"TED";s:8:"discount";s:4:"0.03";s:5:"total";s:6:"532.04";s:4:"cart";a:1:{i:0;a:3:{s:7:"product";i:2;s:8:"quantity";i:10;s:5:"price";d:54;}}s:2:"id";s:1:"9";}', date(now() - interval 16 day)),
(7, 4, 'insert', 'clients', 3, 'O:8:"stdClass":18:{s:4:"name";s:9:"Antonieta";s:7:"surname";s:14:"Queiroz Billé";s:2:"rg";s:9:"278785591";s:3:"cpf";s:11:"28333455182";s:8:"birthday";s:10:"1993-03-05";s:11:"postal_code";s:8:"72917471";s:8:"district";s:17:"Cidade do Entorno";s:4:"city";s:23:"Águas Lindas de Goiás";s:5:"state";s:2:"GO";s:7:"address";s:9:"Quadra 69";s:6:"number";s:3:"100";s:10:"complement";s:20:"Ao lado da quadra 70";s:5:"email";s:32:"antonieta.bille@geradornv.com.br";s:5:"phone";N;s:10:"cell_phone";s:11:"62981676573";s:3:"sex";s:1:"F";s:4:"note";s:73:"Dados gerados com o Gerador NV (https://geradornv.com.br/gerador-pessoas)";s:2:"id";s:1:"8";}', date(now() - interval 4 day)),
(8, 5, 'insert', 'budgets', 2, 'O:8:"stdClass":15:{s:8:"employee";s:1:"2";s:8:"products";a:1:{i:0;i:7;}s:8:"services";a:1:{i:0;i:2;}s:19:"products_quantities";a:1:{i:0;s:1:"4";}s:19:"services_quantities";a:1:{i:0;s:1:"1";}s:15:"products_prices";a:1:{i:0;s:3:"312";}s:15:"services_prices";a:1:{i:0;s:3:"100";}s:3:"day";s:19:"2025-04-07 21:19:32";s:15:"form_of_payment";s:3:"TED";s:8:"discount";s:4:"0.09";s:5:"total";s:7:"1226.68";s:10:"quantities";a:1:{i:0;i:1;}s:6:"prices";a:1:{i:0;i:100;}s:4:"cart";a:2:{i:0;a:3:{s:7:"product";i:7;s:8:"quantity";i:4;s:5:"price";d:312;}i:1;a:3:{s:7:"service";i:2;s:8:"quantity";i:1;s:5:"price";d:100;}}s:2:"id";s:1:"8";}', date(now() - interval 10 day)),
(9, 8, 'insert', 'sales', 2, 'O:8:"stdClass":16:{s:6:"client";s:1:"2";s:8:"employee";s:1:"1";s:8:"products";a:1:{i:0;i:4;}s:8:"services";a:2:{i:0;i:2;i:1;i:1;}s:19:"products_quantities";a:1:{i:0;s:1:"2";}s:19:"services_quantities";a:2:{i:0;s:1:"2";i:1;s:1:"1";}s:15:"products_prices";a:1:{i:0;s:1:"5";}s:15:"services_prices";a:2:{i:0;s:3:"100";i:1;s:2:"15";}s:3:"day";s:19:"2025-04-07 21:45:23";s:15:"form_of_payment";s:7:"A prazo";s:8:"discount";s:1:"0";s:5:"total";s:6:"225.00";s:10:"quantities";a:2:{i:0;i:2;i:1;i:1;}s:6:"prices";a:2:{i:0;i:100;i:1;i:15;}s:4:"cart";a:3:{i:0;a:3:{s:7:"product";i:4;s:8:"quantity";i:2;s:5:"price";d:5;}i:1;a:3:{s:7:"service";i:2;s:8:"quantity";i:2;s:5:"price";d:100;}i:2;a:3:{s:7:"service";i:1;s:8:"quantity";i:1;s:5:"price";d:15;}}s:2:"id";s:1:"8";}', date(now() - interval 4 day));
