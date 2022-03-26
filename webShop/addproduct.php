<!-- starting session inorder to get the variables from the registration-action.php file -->
<?php
    session_start();

    // if the login is true, they can directly get inside the system
    if(!$_SESSION['login']){  
        header("Location:index.php");  
    }
    else if($_SESSION['type'] == "user" ){
        header("Location:403.php");
    } 

    /**************************************** */
    //Debbuging mode code

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    /*************************************** */

    // including the dbFunction file since it contains all the required function to communicate with the databse
    include_once('dbFunction.php');     

    $funcObj = new dbFunction();     // Instantiate - Creating an instance or object of a class

    $categories = $funcObj->FetchCategories();      // getting all the categories

    $subCategories = $funcObj->FetchSubCategories();    // getting all the sub categories

    $success_message = $error_message = NULL;

    //$subCategoriesOfCategory = $funcObj->FetchSubCategoriesFromCategory("3");

    // logout
    if(isset($_POST['logout_button'])){
        session_destroy();
        header("Location:index.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["name"]) || !empty($_POST["price"])) {
            // funcObj is the object of the class dbFunction
            $addProduct = $funcObj->InsertProduct($_POST["name"], "image" , $_POST["description"], $_POST["price"], $_POST["subCategoryId"], $_POST["categoryId"]); 
            if($addProduct){
                $success_message = "Product successfully added"; 
            } 
            else {
                $error_message = "Product failed adding";
            }
        }
        else {
            // print that name and price field cannot be empty
        }
    }


?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./styles.css";>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
            <!-- navbar begins-->
            <ul id="nav">
                <span class="text-white mt-1 text-2xl mx-3 font-medium"><a href="index.php">Roshan Webshop</a></span>
                <!-- displaying all the categories from the database inloop begins -->
                <li class="mx-6"><a href="#">All items</a>
                    <ul>
                        <?php 
                             foreach($categories as $category) {
                        ?>
                        <li><a href="#"><?php echo $category['category_name'] ?> »</a>
                            <ul>
                                <?php
                                    foreach($subCategories as $subCategory){
                                        if($category['id'] == $subCategory['category_id']){

                                        
                                ?>
                                    <li><a href="products.php?subCategoryId=<?php echo $subCategory['id']; ?>"><?php echo $subCategory['sub_category_name'] ?></a>
                                <?php
                                    }   // closing the braces for the if condition  of the category id and subcategory id 
                                }       // closing the braces for subcategories foreach loop
                                ?>
                            </ul>
                        </li>
                        <?php  
                        };  //  closing the braces for categories foreach loop
                        ?>
                    </ul>
                </li>
                <!-- displaying all the categories from the database inloop ends -->
                <div class="w-7/12"></div>
                <!-- Basket starts here-->
                <li class="font-sans block basket lg:inline-block lg:mt-0 lg:ml-6 align-middle text-black hover:text-gray-700">
                        <a href="home.php" role="button" class="relative flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                                <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <?php
                                if(!empty($_SESSION['shopping_cart'])){
                            ?>
                                <span 
                                class="absolute right-0 top-0 rounded-full bg-red-600 w-4 h-4 top right p-0 m-0 text-white font-mono text-sm  leading-tight text-center"
                                >
                                <?php echo count($_SESSION['shopping_cart']); ?>
                                </span>
                            <?php
                                }       // closing the braces for the if condition of shopping cart 
                            ?>
                        </a>
                    </li>
                    <!--Basket ends-->
                <span class="flex mt-1 mx-6">
                    <p class="text-white">
                        <!-- printing only the first name -->
                        Welcome <?php echo strtok($_SESSION['user_name'], " "); ?>
                    </p>
                    <span class="ml-6 mt-1">
                        <form action="" method="post">
                            <button
                                type="submit"
                                name="logout_button"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                                </svg>
                            <button>
                        </form>
                    </span>
                </span>
            </ul>
            <!-- navbar ends -->
            <div class="flex flex-col p-5">
                <div class="flex flex-col items-center justify-center p-10">  
                    <!-- content of the page begins -->
                    <form action="" method="post">
                        <div class="mt-2">
                            <!-- name field begins -->
                            <div class="mt-2">
                                <label class="block" for="name">Name<label>
                                <input 
                                    type="text" 
                                    placeholder="name"
                                    name="name"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                                />
                            </div>
                            <!-- name field ends -->

                            <!-- description field begins -->
                            <div class="mt-2">
                                <label class="block">Description<label>
                                <input 
                                    type="text" 
                                    placeholder="description of the product"
                                    name="description"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" 
                                />
                            </div>
                            <!-- description field ends -->

                            <!-- dropdown menu for categories begins -->
                            <div class="mt-2">
                                <label class="block">Categories<label>
                                <select 
                                    name="categoryId"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                                >
                                    <?php 
                                        foreach($categories as $category) {
                                    ?>
                                        <option value="<?php echo $category['id'] ?>"><?php echo $category['category_name'] ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                
                            </div>
                            <!-- dropdown menu for categories begins -->

                            <!-- dropdown menu for categories begins -->
                            <div class="mt-2">
                                <label class="block">Sub categories<label>
                                <select 
                                    name="subCategoryId"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                                >
                                    <?php 
                                        foreach($subCategories as $subCategory) {
                                    ?>
                                        <option value="<?php echo $subCategory['id'] ?>"><?php echo $subCategory['sub_category_name'] ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                
                            </div>
                            <!-- dropdown menu for categories begins -->

                            <!-- price field begins -->
                            <div class="mt-2">
                                <label class="block">Price<label>
                                <input 
                                    type="price" 
                                    placeholder="price"
                                    name="price"
                                    class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600" 
                                />
                            </div>
                            <!-- price field ends -->

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

                            <!-- Submit button begins -->
                            <div class="flex mt-4 mb-4">
                                <button 
                                    class="w-full px-6 py-2 mt-2 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
                                    type="submit"
                                >
                                    Add product 
                                </button>
                            </div>
                            <!-- Submit button ends -->
                        </div>
                    </form>
                    <!-- content of the page ends -->
                </div>
            </div>
    </body>
</html>