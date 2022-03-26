<!-- starting session inorder to get the variables from the registration-action.php file -->
<?php
session_start();

// if the login is true, they can directly get inside the system
if(isset($_SESSION['login'])){  
    header("Location:home.php");  
  }
?>

<!-- php code begins -->

<?php

    /**************************************** */
    //Debbuging mode code

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    /*************************************** */

    /****************************** NOTES ****************************** */
            // The only difference between the two is that require and its sister 
            // require_once throw a fatal error if the file is not found, 
            // whereas include and include_once only show a warning and 
            // continue to load the rest of the page.
    /******************************************************************** */
    include_once('dbFunction.php'); 

    $funcObj = new dbFunction();     // Instantiate - Creating an instance or object of a class

    $name = $address = $phone_number = $email = $password = $confirmPassword = NULL;

    $name_error = $address_error = $phone_number_error = $email_error = $password_error = $confirmPassword_error = NULL;

    $success_message = $error_message = NULL;

    $flag = true;       // flag raised true or false inorder to send request to database. 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["name"])) {
			$name_error = "Name is required";
			$flag = false;
		} else {
			$name = test_input($_POST["name"]);
            //checking if the name if alphabetic  
            if (!preg_match ("/^([a-zA-Z' ]+)$/",$name)){
                $name_error = "Name should only have alphabets";
			    $flag = false;
            }  
		}
        
        if (empty($_POST["address"])) {
			$address_error = "Address is required";
			$flag = false;
		} else {
			$address = test_input($_POST["address"]);
            if (!preg_match ("/^[\.a-zA-Z0-9,!? ]*$/",$address)){
                $address_error = "Address should be only alplhnumeric, commas and whitespaces";
			    $flag = false;
            }  
		}

        if (empty($_POST["phone_number"])) {
			$phone_number_error = "Phone number is required";
			$flag = false;
		} else {
			$phone_number = test_input($_POST["phone_number"]);
            if(strlen($phone_number) !== 8){
                $phone_number_error = "Phone number should exactly 8 digits";
			    $flag = false;
            }
		}

        if (empty($_POST["email"])) {
			$email_error = "Email is required";
			$flag = false;
		} else {
			$email = test_input($_POST["email"]);
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
            if( !preg_match ($pattern, $email) ){
                $email_error = "Email pattern is not valid";
                $flag = false;
            } 
		}

        if (empty($_POST["password"])) {
			$password_error = "Password is required";
			$flag = false;
		} else {
			$password = test_input($_POST["password"]);
		}

        if (empty($_POST["confirm_password"])) {
			$confirmPassword_error = "Confirm password is required";
			$flag = false;
		} else {
			$confirmPassword = test_input($_POST["confirm_password"]);
		}

        //submitting to the database now
        if ($flag) {
            if($password == $confirmPassword) {  
                // funcObj is the object of the class dbFunction
                $register = $funcObj->UserRegister($name, $address, $phone_number, $email, $password);  
                if($register){
                    $success_message = "Registration has been successfully done";  
                }
                else {  
                    $error_message = "Registration failed. Please try again";  
                }  
            } 
            else {  
                $error_message = "Two password does not match each other. Please try again";  
                
            }   
        }
    }

    // trims the data for all the field
    function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}    

?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
    
    <body>
        <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="px-8 py-6 mx-4 mt-2 text-left bg-white shadow-lg md:w-1/3 lg:w-1/3 sm:w-1/3">
                <h3 class="text-2xl font-bold text-center text-gray-700 mb-2">Join us</h3>
                <!-- any validation message -->
                <?php
                    // any validation messages 
                    if(!empty($success_message)){
                        echo "<span class='text-xs text-green-600'>".$success_message."</span>";
                    }
                    if (!empty($error_message)){
                        echo "<span class='text-xs text-red-600'>".$error_message."</span>";
                    }
                ?>
                <!-- any validation message -->

                <!-- form begins -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="mt-2">
                        <!-- name field begins -->
                        <div>
                            <label class="block" for="Name">Name<label>
                            <input 
                                type="text" 
                                placeholder="Your name"
                                name="name"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            />
                        </div>
                        <?php
                            if(!empty($name_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$name_error."</span>";
                            } 
                        ?>
                        <!-- name field ends -->

                        <!-- address field begins -->
                        <div class="mt-2">
                            <label class="block" for="Address">Address<label>
                            <input 
                                type="text" 
                                placeholder="Your address"
                                name="address"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            />
                        </div>
                        <?php
                            if(!empty($address_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$address_error."</span>";
                            } 
                        ?>
                        <!-- address field ends -->

                        <!-- phone number field begins -->
                        <div class="mt-2">
                            <label class="block" for="Address">Phone number<label>
                            <input 
                                type="number" 
                                placeholder="Your Phone number"
                                name="phone_number"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            />
                        </div>
                        <?php
                            if(!empty($phone_number_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$phone_number_error."</span>";
                            } 
                        ?>
                        <!-- phone number field ends -->

                        <!-- email field begins -->
                        <div class="mt-2">
                            <label class="block" for="Email">Email<label>
                            <input 
                                type="text" 
                                placeholder="email"
                                name="email"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                            />
                        </div>
                        <?php
                            if(!empty($email_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$email_error."</span>";
                            } 
                        ?>
                        <!-- email field ends -->

                        <!-- password field begins -->
                        <div class="mt-2">
                            <label class="block">Password<label>
                            <input 
                                type="password" 
                                placeholder="Password"
                                name="password"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" 
                            />
                        </div>
                        <?php
                            if(!empty($password_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$password_error."</span>";
                            } 
                        ?>
                        <!-- password field ends -->

                        <!-- confirm password field begins -->
                        <div class="mt-2">
                            <label class="block">Confirm Password<label>
                            <input 
                                type="password" 
                                placeholder="Password"
                                name="confirm_password"
                                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" 
                            />
                        </div>
                        <?php
                            if(!empty($confirmPassword_error)){
                                echo "<span class='text-xs text-red-600 ml-2'>".$confirmPassword_error."</span>";
                            } 
                        ?>
                        <!-- confirm password field ends -->

                        <!-- Submit button begins -->
                        <div class="flex">
                            <button 
                                class="w-full px-6 py-2 mt-2 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                                type="submit"
                            >
                                Create Account 
                            </button>
                        </div>
                        <!-- Submit button ends -->

                        <!-- Already have account div begins -->
                        <div class="mt-6 text-gray-700">
                            Already have an account?
                            <a class="text-blue-600 hover:underline" href="index.php">
                                Log in
                            </a>
                        </div>
                        <!-- Already have account div ends -->
                    </div>
                </form>
                <!-- form ends -->
            </div>
        </div>
    </body>
    
</html>
