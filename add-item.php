<?php 
include('config/dbcon.php'); 
$error='';
$success='';

if (isset($_POST['add'])) {
    $code = $_POST['item_code'];
    $category = $_POST['item_category'];
    $subcategory = $_POST['item_sub_category'];
    $name = $_POST['item_name'];
    $qty = $_POST['item_qty'];
    $price = $_POST['unit_price'];
    
    // Validation for Item Code
    $max_length = 15; 
    if (empty($code)) {
        $error .= "Item Code is required<br>";
    } elseif (strlen($code) > $max_length) {
        $error .= "Item Code cannot exceed $max_length characters<br>";
    } elseif (!preg_match("/^[a-zA-Z]{2}\d+$/", $code)) {
        $error .= "Invalid item code format. Item code should contain only alphanumeric characters Ex: AB123<br>";
    }

    // Maximum length for category and subcategory
    $max_length_category = 50; 
    $max_length_subcategory = 50;

    // Validation for Category
    if (empty($category)) {
        $error .= "Category is required<br>";
    } elseif (strlen($category) > $max_length_category) {
        $error .= "Category cannot exceed $max_length_category characters<br>";
    }

    // Validation for Subcategory
    if (empty($subcategory)) {
        $error .= "Sub category is required<br>";
    } elseif (strlen($subcategory) > $max_length_subcategory) {
        $error .= "Sub category cannot exceed $max_length_subcategory characters<br>";
    }
 
    $name_pattern = "/^[a-zA-Z\s\-',.()&]+$/";
    
    if (empty($name)){
        $error .= "Item name is required<br>";
    }elseif(!preg_match($name_pattern, $name)) {
        $error .= "Invalid item name<br>";
    }

    if (empty($qty)) {
        $error .= "Quantity is required<br>";
    } elseif (!preg_match("/^[1-9][0-9]*$/", $qty)) {
        $error .= "Invalid quantity format. Quantity should be a positive number<br>";
    }

    if (empty($price)) {
        $error .= "Unit price is required<br>";
    } elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $price)) {
        $error .= "Invalid unit price format. Price should be a number with up to two decimal places<br>";
    }


    // If no validation errors, proceed with database insertion
    if (empty($error)) {
        // Prepare SQL statement to insert data into the database
        $query = "INSERT INTO item (item_code, item_category, item_subcategory, item_name, quantity, unit_price)
                VALUES ('$code', '$category', '$subcategory', '$name', '$qty', '$price')";

        if(mysqli_query($conn, $query)) {
            // $success = "New record created successfully";
            header("Location: items.php?msg=Item added successfully!");
        } else {
            $error =  "Error: " . $query . "<br>" . $conn->error;
        }
    }

}

include('includes/header.php'); 
?>

<!-- main content start -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Items</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Items > Add Item</li>
    </ol>

    <!-- error alert -->
    <?php
    if(!empty($error)) { ?>
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- add customer container -->
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <div class="card shadow-lg border-0 rounded-lg mt-2">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Add Item</h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputItemCode" type="text"
                                                        name="item_code" placeholder="Enter item code"
                                                        value="<?php if(isset($_POST['item_code'])) echo htmlspecialchars($_POST['item_code']); ?>" />
                                                    <label for="inputItemCode">Item code</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <select class="form-select" id="inputItemCategory"
                                                        name="item_category">
                                                        <option
                                                            <?php if(isset($_POST['item_category']) && $_POST['item_category'] == '') echo 'selected'; ?>
                                                            value="">Select Category</option>
                                                        <?php
                                                            $query = "SELECT * FROM item_category";
                                                            $result = mysqli_query($conn, $query);
                                                            // Loop through each item category and generate options dynamically
                                                            foreach ($result as $category) {
                                                                echo "<option value='" . $category['id'] . "'";
                                                                if(isset($_POST['item_category']) && $_POST['item_category'] == $category['id']) echo 'selected';
                                                                echo ">" . $category['category'] . "</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <label for="inputItemCategory">Category</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="inputItemSubCategory"
                                                name="item_sub_category">
                                                <option
                                                    <?php if(isset($_POST['item_sub_category']) && $_POST['item_sub_category'] == '') echo 'selected'; ?>
                                                    value="">Select Sub Category</option>
                                                <?php
                                                            $query = "SELECT * FROM item_subcategory";
                                                            $result = mysqli_query($conn, $query);
                                                            // Loop through each item category and generate options dynamically
                                                            foreach ($result as $subCategory) {
                                                                echo "<option value='" . $subCategory['id'] . "'";
                                                                if(isset($_POST['item_sub_category']) && $_POST['item_sub_category'] == $subCategory['id']) echo 'selected';
                                                                echo ">" . $subCategory['sub_category'] . "</option>";
                                                            }
                                                        ?>
                                            </select>
                                            <label for="inputItemSubCategory">Sub Category</label>
                                        </div>

                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="inputItemName" type="text" name="item_name"
                                                placeholder="Item name"
                                                value="<?php if(isset($_POST['item_name'])) echo htmlspecialchars($_POST['item_name']); ?>" />
                                            <label for="inputItemName">Item name</label>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputQty" type="text"
                                                        name="item_qty" placeholder="Quantity"
                                                        value="<?php if(isset($_POST['item_qty'])) echo htmlspecialchars($_POST['item_qty']); ?>" />
                                                    <label for="inputQty">Quantity</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="inputUnitPrice" type="text"
                                                        name="unit_price" placeholder="Unit price"
                                                        value="<?php if(isset($_POST['unit_price'])) echo htmlspecialchars($_POST['unit_price']); ?>" />
                                                    <label for="inputUnitPrice">Unit price</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- button -->
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <input type="submit" class="btn btn-primary btn-block" value="Add"
                                                    name="add">
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- end of main -->
    <?php 
include('includes/footer.php'); 
include('includes/scripts.php'); 

?>