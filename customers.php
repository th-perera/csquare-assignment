<?php 
include('config/dbcon.php'); 
include('includes/header.php'); 
?>

<!-- main content start -->
<!-- main content start -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Customers</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Customers > All Customers</li>
    </ol>
    <?php 
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    
    ?>
    <!-- ? -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>
            All Customers
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Contact No</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM customer c JOIN district d ON c.district = d.id";
                    $result = mysqli_query($conn, $query);
                    if(mysqli_num_rows($result) > 0) {
                        
                        foreach($result as $customer) {

                        ?>
                    <tr>
                        <td>
                            <?= $customer['title'] ?>
                        </td>
                        <td><?= $customer['first_name'] ?></td>
                        <td><?= $customer['middle_name'] ?></td>
                        <td><?= $customer['last_name'] ?></td>
                        <td><?= $customer['contact_no'] ?></td>
                        <td><?= $customer['district'] ?></td>
                        <td>
                            <a href="edit-customer.php"><i class="fa-solid fa-pen-to-square fs-4 me-3"></i></a>
                            <a href="" class="link-danger"><i class="fa-solid fa-trash fs-4"></i></a>
                        </td>

                    </tr>
                    <?php
                    }

                    } else {
                    echo "<h3>No Records Found !</h3>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- end of main -->
<?php 
include('includes/footer.php'); 
include('includes/scripts.php'); 
?>