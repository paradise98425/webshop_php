<?php
    /****************************** NOTES ****************************** */
        // The only difference between the two is that require and its sister 
        // require_once throw a fatal error if the file is not found, 
        // whereas include and include_once only show a warning and 
        // continue to load the rest of the page.

        // this class includes all the functions related to database
        // operation in this project. We are doing it this way so that
        // whenever we have to do the same operation in different 
        // situations, we can simply call the function and use it instead 
        // of rewriting it on every places.
        // CONCEPT OF OOP
    /******************************************************************** */

    require_once "dbConnect.php";

    // class begins here 
    class dbFunction {

        private $conn;
        
        // Constructor 
        function __construct() {
            $dbc = new dbConnect();
            $this->conn = $dbc->getConnection();
        }

        /****************************************************************************************************** */
                // Functions related to User registration, user login 
        /****************************************************************************************************** */

        // function to register user in the databse
        public function UserRegister($name, $address, $phone_number, $email, $password) { 
            // inserting the data into databse table called users, if failed
            // it prints the error message mysql gives and then terminated 
            // the running PHP script
            $qr = mysqli_query($this->conn, "INSERT INTO users(name, address, phone_number, email, password) 
                                values('".$name."','".$address."','".$phone_number."','".$email."','".$password."')") 
                                or die(mysqli_error());  
            return $qr;      
        } 

        // function to login user 
        public function UserLogin($email, $password) {
            // trying to fetch record from the database with the email 
            // and password that matches the details coming from the form
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'"
                            ) 
                            or die(mysqli_error()); 
            
            $user_data = mysqli_fetch_array($result);       // Storing the details of the fetched result in the variable
            $no_rows = mysqli_num_rows($result);            // Storing the number of rows fetched
            
            if ($no_rows == 1){
                // if login is true, we store some information about the user
                $_SESSION['login'] = true;
                $_SESSION['type'] = $user_data['type'];
                $_SESSION['user_name'] = $user_data['name'];
                $_SESSION['user_email'] = $user_data['email'];
                return TRUE;  
            }  
            else  
            {  
                return FALSE;  
            }  
        }

        /****************************************************************************************************** */
                // Functions related to fetching all the categories, sub-categories and products 
        /****************************************************************************************************** */

        // function to fetch the categories 
        public function FetchCategories() {
            // fetching all the categories from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM categories"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        // function to fetch the sub-categories 
        public function FetchSubCategories() {
            // fetching all the categories from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM sub_categories"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        // function to fetch the sub-categories based on the categories
        public function FetchSubCategoriesFromCategory($categoryId) {
            // fetching all the sub categories of the categoryId from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM sub_categories WHERE category_id = '".$categoryId."'"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        // function to fetch all the products 
        public function FetchProducts() {
            // fetching all the categories from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM products"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        /****************************************************************************************************** */
                // Functions related to fetching the products based on subcategories products 
        /****************************************************************************************************** */

        // function to fetch all the products of specific subcategory
        public function FetchProductsFromSubCategory($subCategoryId) {
            // fetching all the categories from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM products WHERE product_sub_category_id = '".$subCategoryId."'"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        // function to fetch all the products of specific product id
        public function FetchProductsFromProductId($productId) {
            // fetching details of one specific product from the table
            $result = mysqli_query($this->conn, 
                                "SELECT * FROM products WHERE id = '".$productId."'"
                            ) 
                            or die(mysqli_error());
            
            return $result;         // returning the result
        }

        /****************************************************************************************************** */
                // Functions related to adding and editing the products 
        /****************************************************************************************************** */
        public function UpdateProduct($productId, $name, $price) { 
            // Inserting the data into database table called products, if failed
            // it prints the error message mysql gives and then terminated 
            // the running PHP script
            $qr = mysqli_query($this->conn, 
                                "UPDATE products SET product_name = '".$name."', product_price = '".$price."' WHERE id = '".$productId."'" 
                            ) 
                            or die(mysqli_error()); 
            return $qr; 
        } 
        
        
        public function InsertProduct($product_name, $product_image, $product_description, $product_price, $product_sub_category_id, $product_category_id) { 
            // updating the data into databse table called products, if failed
            // it prints the error message mysql gives and then terminated 
            // the running PHP script
            $qr = mysqli_query($this->conn, 
                                "INSERT INTO products(product_name, product_image, product_description, product_price, product_sub_category_id, product_category_id) 
                                values('".$product_name."','".$product_image."','".$product_description."','".$product_price."','".$product_sub_category_id."','".$product_category_id."')"
                            ) 
                            or die(mysqli_error()); 
            return $qr; 
        } 
    }

?>