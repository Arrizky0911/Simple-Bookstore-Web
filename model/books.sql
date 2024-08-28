CREATE TABLE authors (
    author_id INT AUTO_INCREMENT,
    author_name VARCHAR(400) NOT NULL,
    CONSTRAINT pk_author PRIMARY KEY (author_id)
);

CREATE TABLE publisher (
    publisher_id INT,
    publisher_name VARCHAR(400),
    CONSTRAINT pk_publisher PRIMARY KEY (publisher_id)
);

CREATE TABLE book_language (
    language_id INT,
    language_code VARCHAR(8),
    language_name VARCHAR(50),
    CONSTRAINT pk_language PRIMARY KEY (language_id)
);

CREATE TABLE books (
    book_id INT AUTO_INCREMENT,
    title VARCHAR(400) NOT NULL,
    picture VARCHAR(400) NOT NULL,
    isbn13 VARCHAR(13) NOT NULL,
    language_id INT NOT NULL,
    num_pages INT NOT NULL,
    publication_date DATE NOT NULL,
    publisher_id INT NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    cover VARCHAR(13) NOT NULL,
    CONSTRAINT pk_book PRIMARY KEY (book_id),
    CONSTRAINT fk_book_lang FOREIGN KEY (language_id) REFERENCES book_language (language_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_book_pub FOREIGN KEY (publisher_id) REFERENCES publisher (publisher_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE book_author (
    book_id INT,
    author_id INT,
    CONSTRAINT pk_bookauthor PRIMARY KEY (book_id, author_id),
    CONSTRAINT fk_ba_book FOREIGN KEY (book_id) REFERENCES books (book_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ba_author FOREIGN KEY (author_id) REFERENCES authors (author_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE country (
    country_id INT NOT NULL,
    country_name VARCHAR(200) NOT NULL,
    CONSTRAINT pk_country PRIMARY KEY (country_id)
);

CREATE TABLE address (
    address_id INT AUTO_INCREMENT,
    street_name VARCHAR(200) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country_id INT NOT NULL,
    CONSTRAINT pk_address PRIMARY KEY (address_id),
    CONSTRAINT fk_addr_ctry FOREIGN KEY (country_id) REFERENCES country (country_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE users (
    user_id CHAR(15) UNIQUE NOT NULL,
    first_name VARCHAR(200) NOT NULL,
    last_name VARCHAR(200) NOT NULL,
    email VARCHAR(350) NOT NULL,
    password VARCHAR(350) NOT NULL,
    CONSTRAINT pk_customer PRIMARY KEY (user_id)
);

CREATE TABLE user_address (
    user_id CHAR(15),
    address_id INT,
    CONSTRAINT pk_custaddr PRIMARY KEY (user_id, address_id),
    CONSTRAINT fk_ca_cust FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ca_addr FOREIGN KEY (address_id) REFERENCES address (address_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE shipping_method (
    method_id INT,
    method_name VARCHAR(100),
    cost INT,
    CONSTRAINT pk_shipmethod PRIMARY KEY (method_id)
);
CREATE TABLE payment_method (
    method_id INT,
    method_name VARCHAR(100),
    CONSTRAINT pk_paymethod PRIMARY KEY (method_id)
);

CREATE TABLE order_status (
    status_id INT,
    status_value VARCHAR(20),
    CONSTRAINT pk_orderstatus PRIMARY KEY (status_id)
);

CREATE TABLE cust_order (
    order_id INT AUTO_INCREMENT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id CHAR(15) NOT NULL,
    shipping_method_id INT NOT NULL,
    payment_method_id INT NOT NULL,
    dest_address_id INT NOT NULL,
    total_order INT NOT NULL,
    status_order INT NOT NULL,
    CONSTRAINT pk_custorder PRIMARY KEY (order_id),
    CONSTRAINT fk_order_cust FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_ship FOREIGN KEY (shipping_method_id) REFERENCES shipping_method (method_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_pay FOREIGN KEY (payment_method_id) REFERENCES payment_method (method_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_addr FOREIGN KEY (dest_address_id) REFERENCES address (address_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_order_stat FOREIGN KEY (status_order) REFERENCES order_status (status_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE order_line (
    line_id INT AUTO_INCREMENT,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    CONSTRAINT pk_orderline PRIMARY KEY (line_id),
    CONSTRAINT fk_ol_book FOREIGN KEY (book_id) REFERENCES books (book_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE order_product (
    line_id INT NOT NULL,
    order_id INT NOT NULL,
    CONSTRAINT pk_custaddr PRIMARY KEY (line_id, order_id),
    CONSTRAINT fk_ca_prod FOREIGN KEY (line_id) REFERENCES order_line (line_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ca_order FOREIGN KEY (order_id) REFERENCES cust_order (order_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE carts (
    line_id INT NOT NULL,
    user_id CHAR(15) NOT NULL,
    CONSTRAINT pk_custaddr PRIMARY KEY (line_id, user_id),
    CONSTRAINT fk_ca_line FOREIGN KEY (line_id) REFERENCES order_line (line_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ca_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE admin (
    username VARCHAR(200) NOT NULL,
    password VARCHAR(200) NOT NULL,
    CONSTRAINT pk_admins PRIMARY KEY (username)
);

CREATE TABLE vouchers (
    code VARCHAR(200) NOT NULL,
    disc DECIMAL(4,3) NOT NULL,
    CONSTRAINT pk_vouchers PRIMARY KEY (code)
);


