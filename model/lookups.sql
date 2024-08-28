INSERT INTO book_language (language_code, language_id, language_name) VALUES
('eng', 1, 'English'),
('en-US', 2, 'United States English'),
('fre', 3, 'French'),
('spa', 4, 'Spanish'),
('en-GB', 5, 'British English'),
('mul', 6, 'Multiple Languages'),
('grc', 7, 'Greek'),
('enm', 8, 'Middle English'),
('en-CA', 9, 'Canadian English'),
('ger', 10, 'German'),
('jpn', 11, 'Japanese'),
('ara', 12, 'Arabic'),
('nl', 13, 'Dutch'),
('zho', 14, 'Chinese'),
('lat', 15, 'Latin'),
('por', 16, 'Portuguese'),
('srp', 17, 'Serbian'),
('ita', 18, 'Italian'),
('rus', 19, 'Russian'),
('msa', 20, 'Malaysian'),
('glg', 21, 'Galician'),
('wel', 22, 'Welsh'),
('swe', 23, 'Swedish'),
('nor', 24, 'Norwegian'),
('tur', 25, 'Turkish'),
('gla', 26, 'Gaelic'),
('ale', 27, 'Aleut');

INSERT INTO shipping_method (method_id, method_name, cost) VALUES
(1, 'Standard', 6),
(2, 'Priority', 9),
(3, 'Express', 12),
(4, 'International', 25);

INSERT INTO payment_method (method_id, method_name) VALUES
(1, 'bank transfer'),
(2, 'GOPAY'),
(3, 'OVO'),
(4, 'PAYPAL');

INSERT INTO order_status (status_id, status_value) VALUES
(1, 'Pending Payment'),
(2, 'Pending Delivery'),
(3, 'Delivery In Progress'),
(4, 'Delivered'),
(5, 'Cancelled'),
(6, 'Finished');

INSERT INTO vouchers (code, disc) VALUES
('bnbwt', 0.200);

INSERT INTO admin (username, password) VALUES
('admin', 'bnbwt');
