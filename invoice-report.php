<?php
require('fpdf/fpdf.php');
include('config/dbcon.php'); // Include your database connection file

if(isset($_POST['generateReport'])) {
    // Retrieve selected date range from form submission
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch records from the database based on the selected date range
    $query = "SELECT 
                i.invoice_no AS invoiceNumber,
                i.date AS date,
                CONCAT(c.first_name, ' ', COALESCE(c.middle_name, ''), ' ', c.last_name) AS customer,
                d.district AS customerDistrict,
                i.item_count AS itemCount,
                i.amount AS invoiceAmount
              FROM 
                invoice AS i
              JOIN 
                customer AS c ON i.customer = c.id
              JOIN 
                district AS d ON c.district = d.id
              WHERE 
                i.date BETWEEN '$start_date' AND '$end_date'";

    $result = mysqli_query($conn, $query);

    // Create a new PDF instance with landscape orientation
    $pdf = new FPDF('L');
    $pdf->AddPage();

    // Set font for the report
    $pdf->SetFont('Arial', 'B', 12);

    // Add a title to the report
    $pdf->Cell(0, 10, 'Invoice Report', 0, 1, 'C');

    // Set font for the table headers
    $pdf->SetFont('Arial', 'B', 10);

    // Add headers to the table
    $pdf->Cell(40, 10, 'Invoice Number', 1);
    $pdf->Cell(30, 10, 'Date', 1);
    $pdf->Cell(60, 10, 'Customer', 1);
    $pdf->Cell(40, 10, 'Customer District', 1);
    $pdf->Cell(30, 10, 'Item Count', 1);
    $pdf->Cell(40, 10, 'Invoice Amount', 1);
    $pdf->Ln(); // Move to the next line

    // Add data to the PDF
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(40, 10, $row['invoiceNumber'], 1);
        $pdf->Cell(30, 10, $row['date'], 1);
        $pdf->Cell(60, 10, $row['customer'], 1);
        $pdf->Cell(40, 10, $row['customerDistrict'], 1);
        $pdf->Cell(30, 10, $row['itemCount'], 1);
        $pdf->Cell(40, 10, $row['invoiceAmount'], 1);
        $pdf->Ln(); // Move to the next line
    }

    // Output the PDF
    $pdf->Output();
}
?>

<?php 
include('includes/header.php'); 
?>

<!-- main content start -->

<main>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light my-4">Invoice Report</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="mt-4">
                            <!-- Removed action attribute -->
                            <div class="form-group mb-4">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                            </div>
                            <div class="form-group mb-4">
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                            </div>

                            <!-- button -->
                            <div class="mt-4 mb-0">
                                <div class="d-grid">
                                    <input type="submit" class="btn btn-primary btn-block" value="Generate Report"
                                        name="generateReport">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<!-- end of main -->
<?php 
include('includes/scripts.php'); 
?>