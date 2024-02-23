<?php 
include('config/dbcon.php'); 
$error='';
$success='';

if (isset($_POST['add'])) {
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

    // If no validation errors, proceed with database insertion
    if (empty($error)) {
    // Prepare SQL statement to insert data into the database
    $query = "INSERT INTO customer (title, first_name, middle_name, last_name, contact_no, district)
            VALUES ('$title', '$fname', '$mname', '$lname', '$contact', '$district')";

    if(mysqli_query($conn, $query)) {
        // $success = "New record created successfully";
        header("Location: customers.php?msg=Customer added successfully!");
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
        <li class="breadcrumb-item active">Customers > Add Customer</li>
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
                                    <h3 class="text-center font-weight-light my-4">Add Customer</h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <select class="form-select" id="inputTitle" name="title"
                                                        aria-label="Title">
                                                        <option
                                                            <?php if(isset($_POST['title']) && $_POST['title'] == '') echo 'selected'; ?>
                                                            value="">Select Title</option>
                                                        <option
                                                            <?php if(isset($_POST['title']) && $_POST['title'] == 'Mr') echo 'selected'; ?>
                                                            value="Mr">Mr.</option>
                                                        <option
                                                            <?php if(isset($_POST['title']) && $_POST['title'] == 'Mrs') echo 'selected'; ?>
                                                            value="Mrs">Mrs.</option>
                                                        <option
                                                            <?php if(isset($_POST['title']) && $_POST['title'] == 'Miss') echo 'selected'; ?>
                                                            value="Miss">Miss.</option>
                                                        <option
                                                            <?php if(isset($_POST['title']) && $_POST['title'] == 'Dr') echo 'selected'; ?>
                                                            value="Dr">Dr.</option>
                                                    </select>
                                                    <label for="inputTitle">Title</label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputFirstName" type="text"
                                                        name="first_name" placeholder="Enter your first name"
                                                        value="<?php if(isset($_POST['first_name'])) echo htmlspecialchars($_POST['first_name']); ?>" />
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="inputMiddleName" type="text"
                                                        name="middle_name" placeholder="Middle name"
                                                        value="<?php if(isset($_POST['middle_name'])) echo htmlspecialchars($_POST['middle_name']); ?>" />
                                                    <label for="inputMiddleName">Middle name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="inputLastName" type="text"
                                                        name="last_name" placeholder="Last name"
                                                        value="<?php if(isset($_POST['last_name'])) echo htmlspecialchars($_POST['last_name']); ?>" />
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputContactNo" type="text"
                                                name="contact_no" placeholder="Contact number"
                                                value="<?php if(isset($_POST['contact_no'])) echo htmlspecialchars($_POST['contact_no']); ?>" />
                                            <label for="inputContactNo">Contact number</label>
                                        </div>

                                        <div class="form-floating mb-3 mb-md-0">
                                            <select class="form-select" id="inputDistrict" name="district">
                                                <option
                                                    <?php if(isset($_POST['district']) && $_POST['district'] == '') echo 'selected'; ?>
                                                    value="">Select District</option>
                                                <?php
                                            $query = "SELECT * FROM district";
                                            $result = mysqli_query($conn, $query);
                                            // Loop through each district and generate options dynamically
                                            foreach ($result as $district) {
                                                echo "<option value='" . $district['id'] . "'";
                                                if(isset($_POST['district']) && $_POST['district'] == $district['id']) echo 'selected';
                                                echo ">" . $district['district'] . "</option>";
                                            }
                                            ?>
                                            </select>
                                            <label for="inputDistrict">District</label>
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