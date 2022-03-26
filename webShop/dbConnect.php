<?php  
    /******************************************************************** */
        // this is a class which requires the configuration file 
        // for the database connection.
    /******************************************************************** */

    class dbConnect {   

        private $conn;

        // constructor
        function __construct() {  
            $this->conn = mysqli_connect('localhost', 'root', 'password', 'web_shop');  

            // testing the connection  
            if(!$this->conn)
            {  
                // die is inbuilt PHP function that is used to print message
                // and exit from the current PHP script. 
                die ("Cannot connect to the database");  
            }   
            return $this->conn; 
        } 
        
        // this function returns the connection to the mysql database
        public function getConnection() {
            return $this->conn;
        }
    }  
?>  