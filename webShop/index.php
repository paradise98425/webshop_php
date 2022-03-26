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

    $email = $password = NULL;

    $email_error = $password_error = NULL;

    $error_message = NULL;

    $flag = true;       // flag raised true or false inorder to send request to database. 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // email field validation 
      if (empty($_POST["email"])) {
        $email_error = "Email is required";
        $flag = false;
		  } 
      else {
			  $email = test_input($_POST["email"]);
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
        if( !preg_match ($pattern, $email) ){
            $email_error = "Email pattern is not valid";
            $flag = false;
        } 
		  }

      // password field validation
      if (empty($_POST["password"])) {
        $password_error = "Password is required";
        $flag = false;
		  } 
      else {
			  $password = test_input($_POST["password"]);
		  }

      //submitting to check with the database now
      if ($flag) {
        // logic for login now
        $login = $funcObj->UserLogin($email, $password);
        if ($login){
          $_SESSION['shopping_cart'] = array();      // Initializing the empty shopping cart
          header("Location: home.php");
        }  
        else {
          $error_message = "Username and password did not match. Please try again.";
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
            <div class="px-8 py-6 mx-4 mt-2 text-left bg-white shadow-lg md:w-1/4 lg:w-1/4 sm:w-1/4">
                <h3 class="text-2xl font-bold text-center text-gray-700 mb-2">Login </h3>
                <!-- any validation message -->
                <?php
                    // any validation messages 
                    if (!empty($error_message)){
                        echo "<span class='text-xs text-red-600'>".$error_message."</span>";
                    }
                ?>
                <!-- any validation message -->

                <!-- form begins -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="mt-2">
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

                        <!-- Submit button begins -->
                        <div class="flex mt-4 mb-4">
                            <button 
                                class="w-full px-6 py-2 mt-2 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                                type="submit"
                            >
                                Login 
                            </button>
                        </div>
                        <!-- Submit button ends -->

                        <!-- Already have account div begins -->
                        <div class="my-6 text-gray-700">
                            Don't have account?
                            <a class="text-blue-600 hover:underline" href="register.php">
                                Register
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
