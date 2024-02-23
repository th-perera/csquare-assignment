<?php 
include('config/dbcon.php'); 
$id = $_GET['id'];
$error='';
$success='';

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];
    $contact = $_POST['contact_no'];
    $district = $_POST['district'];

    // Validation for Title
    if (empty($title)) {
        $error .= "Title is required<br>";
    }

    // Validation for Name Fields (First Name, Middle Name, Last Name)
    $name_pattern = "/^[a-zA-Z'-]+$/"; // Allows letters, hyphens, and apostrophes
    
    if (empty($fname)){
        $error .= "First name is required<br>";
    }elseif(!preg_match($name_pattern, $fname)) {
        $error .= "Invalid first name<br>";
    }
        
    if (empty($mname)) {
        $error .= "Middle name is required<br>";
    } elseif (!preg_match($name_pattern, $mname)) {
        $error .= "Invalid middle name<br>";
    }


    if (empty($lname)) {
        $error .= "Last name is required<br>";
    } elseif (!preg_match($name_pattern, $lname)) {
        $error .= "Invalid last name<br>";
    }

    // Validation for Contact Number
    if (empty($contact)) {
        $error .= "Contact number is required<br>";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error .= "Invalid contact number format<br>";
    }

    // Validation for District
    if (empty($district)) {
        $error .= "District is required<br>";
    }

    // If no validation errors, proceed with database update
    if (empty($error)) {
        // Prepare SQL statement to update data in the database
        $query = "UPDATE customer SET title='$title', first_name='$fname', middle_name='$mname', last_name='$lname', contact_no='$contact', district='$district' WHERE id='$id'";

        if(mysqli_query($conn, $query)) {
            // $success = "Record updated successfully";
            header("Location: customers.php?msg=Customer updated successfully!");
        } else {
            $error =  "Error: " . $query . "<br>" . $conn->error;
        }
    }
}

include('includes/header.php'); 
?>

<!-- main content start -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Customers</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Customers > Edit Customer</li>
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


    <!--  -->
    <?php 
    $id = $_GET['id'];
    $query = "SELECT * FROM customer WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);
    ?>

    <!-- add customer container -->
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Edit Customer</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <select class="form-select" id="inputTitle" name="title"
                                                        aria-label="Title">
                                                        <option value="">Select Title</option>
                                                        <option
                                                            <?php echo ($customer['title'] == 'Mr') ? 'selected':''; ?>
                                                            value="Mr">Mr.</option>
                                                        <option
                                                            <?php echo ($customer['title'] == 'Mrs') ? 'selected':''; ?>
                                                            value="Mrs">Mrs.</option>
                                                        <option
                                                            <?php echo ($customer['title'] == 'Miss') ? 'selected':''; ?>
                                                            value="Miss">Miss.</option>
                                                        <option
                                                            <?php echo ($customer['title'] == 'Dr') ? 'selected':''; ?>
                                                            value="Dr">Dr.</option>
                                                    </select>
                                                    <label for="inputTitle">Title</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputFirstName" type="text"
                                                        name="first_name" placeholder="Enter your first name"
                                                        value="<?= $customer['first_name'] ?>" />
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputMiddleName" type="text"
                                                        name="middle_name" placeholder="Middle name"
                                                        value="<?= $customer['middle_name'] ?>" />
                                                    <label for="inputMiddleName">Middle name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="inputLastName" type="text"
                                                        name="last_name" placeholder="Last name"
                                                        value="<?= $customer['last_name'] ?>" />
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputContactNo" type="text"
                                                name="contact_no" placeholder="Contact number"
                                                value="<?= $customer['contact_no'] ?>" />
                                            <label for="inputContactNo">Contact number</label>
                                        </div>

                                        <div class="form-floating mb-3 mb-md-0">
                                            <select class="form-select" id="inputDistrict" name="district">
                                                <option
                                                    <?php if(isset($customer['district']) && $customer['district'] == '') echo 'selected'; ?>
                                                    value="">Select District
                                                </option>
                                                <?php
                                                $query = "SELECT * FROM district";
                                                $result = mysqli_query($conn, $query);
                                                // Loop through each district and generate options dynamically
                                                foreach ($result as $district) {
                                                    echo "<option value='" . $district['id'] . "'";
                                                    if(isset($customer['district']) && $customer['district'] == $district['id']) echo 'selected';
                                                    echo ">" . $district['district'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="inputDistrict">District</label>
                                        </div>

                                        <!-- button -->
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <input type="submit" class="btn btn-primary btn-block" value="Save"
                                                    name="update">
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