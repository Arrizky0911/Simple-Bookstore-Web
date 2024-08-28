<?php
class DBConnection {
    private $conn;

    public function __construct() {
        $this->conn = new PDO(
            "mysql:host=localhost;dbname=books",
            "root", "");
    }

    public function __destruct() {
        $this->conn = null;
    }

    //Get user information for account setting
    public function getUserById($id) {
        $sql = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }

    //login to get user_id data for the session
    public function login($email, $password) {
        $sql = "SELECT user_id FROM users WHERE email = ? AND password = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$email, $password]);
        return $result;
    }
    public function loginAdmin($username, $password) {
        $sql = "SELECT username FROM admin WHERE username = ? AND password = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$username, $password]);
        return $result;
    }

    //CRUD for user account
    public function createAccount($id, $fname, $lname, $email, $password) {
        $sql = "INSERT INTO users (user_id, first_name, last_name, email, password)
        VALUES (?, ?, ?, ?, ?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$id, $fname, $lname, $email, $password]);
    }
    
    // used in create account in order to prevent duplicate email address
    public function validateEmail($email) {
        $sql = "SELECT COUNT(*) as 'n' FROM users WHERE email = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$email]);
        $results = $result->fetch();
        $n = (int) $results['n'];
        if ($n > 0) {
            return FALSE;
        }
        return TRUE;
    }
    
    // only change first name and last name on the account setting
    public function updateUser($id, $fname, $lname) {
        $sql = "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$fname, $lname, $id]);
    }
    
    //Update password after some validation
    public function resetPassword($id, $password) {
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$password, $id]);
    }

    //Showing all countries in a datalist for adding an address
    public function getCountries() {
        $sql = "SELECT country_name FROM country";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }

    //Adding new address in order to set the delivery destination on the transaction. It only can be set at the account setting
    public function addAddress($id, $street, $city, $country) {
        $sql_1 = "INSERT INTO address (street_name, city, country_id)
        VALUES (?, ?, (
            SELECT country_id FROM country WHERE country_name = ?
        ))";
        $sql_2 = "INSERT INTO user_address (user_id, address_id)
        VALUES (?, (SELECT DISTINCT address_id FROM address WHERE street_name = ? AND city = ?))";
        $address = $this->conn->prepare($sql_1);
        $address->execute([$street, $city, $country]);
        $user_address = $this->conn->prepare($sql_2);
        $user_address->execute([$id, $street, $city]);

    }

    //Showing all addresses in account setting and in review order to checkout to set the delivery destination
    public function getAllAddressesByID($id) {
        $sql = "SELECT a.address_id as 'address_id', a.street_name as 'street', a.city as 'city', b.country_name as 'country' FROM address a
        INNER JOIN country b ON a.country_id = b.country_id
        WHERE a.address_id IN (SELECT address_id FROM user_address WHERE user_id = ?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }
    public function getAllDetailAddressByID($id) {
        $sql = "SELECT a.address_id as 'adresss_id', a.street_name as 'street', a.city as 'city', b.country_name as 'country' FROM address a
        INNER JOIN country b ON a.country_id = b.country_id
        WHERE a.address_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }
    
    //Delete address. If user want to update, it needs to be deleted first then create the new one.
    public function deleteAddress($id) {
        $sql = "DELETE FROM address WHERE address_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
    }

    //Showing a details information from a book in product page
    public function getBooksById($id) {
        $sql = "SELECT b.book_id as 'book_id', b.title as 'title', b.picture as 'picture', a.author_name as 'author', b.price as 'price', b.isbn13 as 'isbn', p.publisher_name as 'publisher', b.publication_date as 'publication_date', l.language_name as 'language', b.num_pages as 'pages', b.cover as 'cover', b.stock as 'stock' FROM books b 
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN authors a ON ba.author_id = a.author_id
        INNER JOIN publisher p ON p.publisher_id = b.publisher_id
        INNER JOIN book_language l ON l.language_id = b.language_id
        WHERE b.book_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }

    //default search for books in search page
    public function getAllBooks() {
        $sql = "SELECT b.book_id as 'book_id', b.title as 'title', b.picture as 'picture', b.price as 'price', a.author_name as 'author', b.isbn13 as 'isbn', b.publication_date as 'publication_date', p.publisher_name as 'publisher', b.cover as 'cover', b.num_pages as 'pages', b.stock as 'stock', l.language_name as 'language' FROM books b 
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN book_language l ON l.language_id = b.language_id
        INNER JOIN publisher p ON p.publisher_id = b.publisher_id
        INNER JOIN authors a ON ba.author_id = a.author_id 
        ORDER BY b.title";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }
    public function nGetAllBooks() {
        $sql = "SELECT count(*) as 'n' FROM books";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }

    //Use to display a book in carousel. It is the same as the previous method but has limitation
    public function getCarouselBooks() {
        $sql = "SELECT b.book_id as 'book_id', b.title as 'title', b.picture as 'picture', b.price as 'price', a.author_name as 'author' FROM books b 
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN authors a ON ba.author_id = a.author_id
        LIMIT 15 ";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }

    //Use to search books with keyword of the book's title in the search page
    public function findBooks($k) {
        $key = "%" . $k . "%";
        $sql = "SELECT b.book_id as 'book_id', b.title as 'title', b.picture as 'picture', b.price as 'price',a.author_name as 'author', b.isbn13 as 'isbn', b.publication_date as 'publication_date', p.publisher_name as 'publisher', b.cover as 'cover', b.num_pages as 'pages', b.stock as 'stock', l.language_name as 'language' FROM books b 
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN book_language l ON l.language_id = b.language_id
        INNER JOIN publisher p ON p.publisher_id = b.publisher_id
        INNER JOIN authors a ON ba.author_id = a.author_id 
        WHERE b.title LIKE ? OR a.author_name LIKE ?
        ORDER BY b.title";
        $result = $this->conn->prepare($sql);
        $result->execute([$key, $key]);
        return $result;
    }
    

    //CRUD for book data management in admin panel
    public function addBook($title, $author, $isbn, $price, $pages, $language, $stock, $cover, $publication, $publisher, $picture) {
        $sql = "INSERT INTO books (title, picture, isbn13, cover, stock, price, num_pages, publication_date, publisher_id, language_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?,
        (SELECT publisher_id FROM publisher WHERE publisher_name = ?),
        (SELECT language_id FROM book_language WHERE language_name = ?))";
        $book = $this->conn->prepare($sql);
        $book->execute([$title, $picture, $isbn, $cover, $stock, $price, $pages, $publication, $publisher, $language]);
        $sql_2 = "INSERT INTO book_author (book_id, author_id)
        VALUES ((SELECT book_id FROM books WHERE isbn13 = ? AND picture?),
        (SELECT author_id FROM authors WHERE author_name = ?))";
        $authorized = $this->conn->prepare($sql_2);
        $authorized->execute([$isbn, $picture, $author]);
    }
    
    public function updateBook($id, $title, $author, $isbn, $price, $pages, $language, $stock, $cover, $publication, $publisher, $picture) {
        $sql = "UPDATE books SET title = ?, picture = ?, isbn13 = ?, cover = ?, stock = ?, price = ?, num_pages = ?, publication_date = ?, 
        publisher_id = (SELECT publisher_id FROM publisher WHERE publisher_name = ?),
        language_id = (SELECT language_id FROM book_language WHERE language_name = ?)
        WHERE book_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$title, $picture, $isbn, $cover, $stock, $price, $pages, $publication, $publisher, $language, $id]);
    }
    
    public function deleteBook($id) {
        $sql = "DELETE FROM books WHERE book_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
    }

    // CRUD for authors data management. Since the data only consist of name and id, so it is no need to be update
    public function getAllAuthors() {
        $sql = "SELECT author_id, author_name FROM authors ORDER BY author_name";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }
    public function getAuthorByKey($k) {
        $key = "%" . $k . "%";
        $sql = "SELECT author_id, author_name FROM authors WHERE author_name LIKE ? ORDER BY author_name";
        $result = $this->conn->prepare($sql);
        $result->execute([$key]);
        return $result;
    }

    public function addAuthors($name) {
        $sql = "INSERT INTO authors (author_name)
        VALUES (?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$name]);
    }
    
    public function deleteAuthor($id) {
        $sql = "DELETE FROM authors WHERE author_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
    }
    
    //CRUD for publishers data management. Almost the same as authors data management
    public function getAllPublishers() {
        $sql = "SELECT publisher_id, publisher_name FROM publisher ORDER BY publisher_name";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }
    public function getPublisherByKey($k) {
        $key = "%" . $k . "%";
        $sql = "SELECT publisher_id, publisher_name FROM publisher WHERE publisher_name LIKE ? ORDER BY publisher_name ";
        $result = $this->conn->prepare($sql);
        $result->execute([$key]);
        return $result;
    }
    
    public function addPublisher($name) {
        $sql = "INSERT INTO publisher (publisher_name)
        VALUES (?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$name]);
    }

    public function deletePublisher($id) {
        $sql = "DELETE FROM publisher WHERE publisher_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
    }
    public function getAllLanguages() {
        $sql = "SELECT language_id, language_code, language_name FROM book_language ORDER BY language_code";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }

    public function getLanguagesByKey($k) {
        $key = "%" . $k . "%";
        $sql = "SELECT language_id, language_code, language_name FROM book_language WHERE language_name LIKE ? OR language_code LIKE ? ORDER BY language_code";
        $result = $this->conn->prepare($sql);
        $result->execute([$key, $key]);
        return $result;
    }

    // CRUD for language
    public function addLanguage($name, $code) {
        $sql = "INSERT INTO book_language (language_name, language_code)
        VALUES (?,?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$name, $code]);
    }

    public function deleteLanguage($id) {
        $sql = "DELETE FROM book_language WHERE language_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
    }

    //Use to add an item to a cart
    public function addCart($id, $number, $user) {
        $date = date('Y-m-d h:i:s');
        $sql_1 = "INSERT INTO order_line (book_id, quantity, createdAt)
        VALUES (?, ?, ?)";
        $set_line = $this->conn->prepare($sql_1);
        $set_line->execute([$id, $number, $date]);
        $sql_2 = "INSERT INTO carts (user_id, line_id)
        VALUES (?, (SELECT line_id FROM order_line WHERE createdAt = ?))";
        $cart = $this->conn->prepare($sql_2);
        $cart->execute([$user, $date]);
    }

    //Use to adding item to cart to check is the product available
    public function validateStock($id, $n) {
        $sql = "SELECT book_id as 'n' FROM books WHERE book_id = ? AND stock >= ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id, $n]);
        return $result;
    }

    //Showing the cart in cart page and review order page
    public function getCarts($id) {
        $sql = "SELECT b.title as 'title', b.picture as 'picture', a.author_name as 'author', b.price as 'price', ol.quantity as 'quantity', ol.line_id as 'line_id' FROM books b 
        INNER JOIN order_line ol ON ol.book_id = b.book_id
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN authors a ON ba.author_id = a.author_id
        WHERE line_id IN (SELECT line_id FROM carts WHERE user_id = ?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }

    //Use for update cart item one by one
    public function getCartsItem($id) {
        $sql = "SELECT quantity, book_id FROM order_line
        WHERE line_id = 
        (SELECT line_id FROM carts WHERE user_id = ?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }

    //CRUD for cart items data management used by user
    public function updateCart($id, $number) {
        $sql = "UPDATE order_line SET quantity = ? WHERE line_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$number, $id]);
    }
    
    public function deleteCart($id) {
        $sql = "DELETE FROM order_line WHERE line_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        
    }

    //Showing all shipment method into a datalist in order page 
    public function getAllShipMethod() {
        $sql = "SELECT method_id, method_name, cost FROM shipping_method";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }
    public function getShipMethod($id) {
        $sql = "SELECT method_name, cost FROM shipping_method WHERE method_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }


    //Showing all payment method into a datalist in order page
    public function getAllPayMethod() {
        $sql = "SELECT method_id, method_name FROM payment_method";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }
    public function getPayMethod($id) {
        $sql = "SELECT method_name FROM payment_method WHERE method_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }
    public function checkVoucher($code) {
        $sql = "SELECT code, disc FROM vouchers WHERE code = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$code]);
        return $result;
    }

    //Use to manage the transactions in admin panel
    public function getAllStatus() {
        $sql = "SELECT status_id, status_value FROM order_status";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result;
    }

    public function checkVouchers($code) {
        $sql = "SELECT code, disc FROM vouchers WHERE code = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$code]);
        return $result;
    }

    //The three functions below are used to make an order
    public function checkout($id, $total, $address, $shipment, $payment) {
        $i = 1;
        $sql = "INSERT INTO cust_order (user_id, total_order, status_order, dest_address_id, shipping_method_id, payment_method_id)
        VALUES (?, ?, ?, ?, ?, ?)"; //Make order ID
        $result = $this->conn->prepare($sql);
        $result->execute([$id, $total, $i, $address, $shipment, $payment]);
        $sql_2 = "INSERT INTO order_product (order_id, line_id)
        SELECT co.order_id as 'order_id', c.line_id as 'line_id' FROM cust_order co
        LEFT JOIN carts c ON co.user_id = c.user_id
        WHERE c.user_id = ?"; //connect to the items without cart
        $result_2 = $this->conn->prepare($sql_2);
        $result_2->execute([$id]);
        $sql_3 = "DELETE FROM carts WHERE user_id = ?"; //then remove all order line on a cart
        $removeCarts = $this->conn->prepare($sql_3);
        $removeCarts->execute([$id]);
    }

    //To get orders that does not finish or being cancelled
    public function getCurrentOrder($id) {
        $sql = "SELECT order_id, order_date FROM cust_order
        WHERE user_id = ? AND status_order < 5";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    } 

    //To get orders that does finish or being cancelled
    public function getHistoryOrder($id) {
        $sql = "SELECT order_id, order_date FROM cust_order
        WHERE user_id = ? AND status_order >= 5";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    } 

    public function getAllOrder() {
        $sql = "SELECT co.user_id as 'user_id', u.first_name as 'fname', u.last_name as 'lname', co.order_id as 'order_id', co.order_date as 'order_date' FROM cust_order co
        INNER JOIN users u ON u.user_id = co.user_id
        ORDER BY co.order_date DESC";
        $result = $this->conn->prepare($sql);
        $result->execute([]);
        return $result;
    } 
    public function findOrder($k) {
        $key = "%" . $k . "%";
        $sql = "SELECT co.user_id as 'user_id', u.first_name as 'fname', u.last_name as 'lname', co.order_id as 'order_id', co.order_date as 'order_date' FROM cust_order co
        INNER JOIN users u ON u.user_id = co.user_id
        WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR co.user_id LIKE ? OR co.order_id LIKE ?
        ORDER BY co.order_date DESC";
        $result = $this->conn->prepare($sql);
        $result->execute([$key, $key, $key, $key]);
        return $result;
    } 

    //Showing the details of the user order specified by order ID
    public function getDetailsOrder($id) {
        $sql = "SELECT co.user_id as 'user_id', co.total_order as 'total', os.status_value as 'status', co.status_order as 'status_id', sm.method_name as 'shipment', pm.method_name as 'payment', 
        a.street_name as 'address', a.city as 'city', c.country_name as 'country'
        FROM cust_order co
        INNER JOIN shipping_method sm ON sm.method_id = co.shipping_method_id
        INNER JOIN payment_method pm ON pm.method_id = co.payment_method_id
        INNER JOIN address a ON a.address_id = co.dest_address_id
        INNER JOIN order_status os ON os.status_id = co.status_order
        INNER JOIN country c ON a.country_id = c.country_id
        WHERE co.order_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    } 

    //Use to list all the items in an order/transactions
    public function getCartsOrders($id) {
        $sql = "SELECT b.title as 'title', b.picture as 'picture', a.author_name as 'author', b.price as 'price', ol.quantity as 'quantity', ol.line_id as 'line_id' FROM books b 
        INNER JOIN order_line ol ON ol.book_id = b.book_id
        INNER JOIN book_author ba ON ba.book_id = b.book_id
        INNER JOIN authors a ON ba.author_id = a.author_id
        WHERE ol.line_id IN (SELECT line_id FROM order_product WHERE order_id = ?)";
        $result = $this->conn->prepare($sql);
        $result->execute([$id]);
        return $result;
    }

    //Use to update the order status by admin
    public function updateTransaction($id, $new) {
        $sql = "UPDATE cust_order SET status_order = (
        SELECT status_id FROM order_status WHERE status_value = ?)
        WHERE order_id = ?";
        $result = $this->conn->prepare($sql);
        $result->execute([$new, $id]);
    }
}
?>