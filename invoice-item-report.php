<?php
require('fpdf/fpdf.php');
include('config/dbcon.php'); 


if(isset($_POST['generateReport'])) {
    // Retrieve selected date range from form submission
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date']; 

    // Fetch data from the database and add it to the PDF
    $query = "SELECT 
            i.invoice_no AS invoiceNumber,
            i.date AS invoicedDate,
            CONCAT(c.first_name, ' ', COALESCE(c.middle_name, ''), ' ', c.last_name) AS customerName,
            it.item_name AS itemName,
            it.item_code AS itemCode,
            ic.category AS itemCategory,
            it.unit_price AS itemUnitPrice
          FROM 
            invoice AS i
          JOIN 
            customer AS c ON i.customer = c.id
          JOIN 
            invoice_master AS im ON i.invoice_no = im.invoice_no
          JOIN 
            item AS it ON im.item_id = it.id
          JOIN 
            item_category AS ic ON it.item_category = ic.id";


    $result = mysqli_query($conn, $query);

    
// Create a new PDF instance with landscape orientation
$pdf = new FPDF('L');
$pdf->AddPage();

// Set font for the report
$pdf->SetFont('Arial', 'B', 12);

// Add a title to the report
$pdf->Cell(0, 10, 'Invoice Item Report', 0, 1, 'C');

// Set font for the table headers
$pdf->SetFont('Arial', 'B', 10);

// Add headers to the table
$pdf->Cell(40, 10, 'Invoice Number', 1);
$pdf->Cell(40, 10, 'Invoiced Date', 1);
$pdf->Cell(50, 10, 'Customer Name', 1);
$pdf->Cell(50, 10, 'Item Name', 1);
$pdf->Cell(30, 10, 'Item Code', 1);
$pdf->Cell(40, 10, 'Item Category', 1);
$pdf->Cell(30, 10, 'Item Unit Price', 1);
$pdf->Ln(); // Move to the next line

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(40, 10, $row['invoiceNumber'], 1);
    $pdf->Cell(40, 10, $row['invoicedDate'], 1);
    $pdf->Cell(50, 10, $row['customerName'], 1);
    $pdf->Cell(50, 10, $row['itemName'], 1);
    $pdf->Cell(30, 10, $row['itemCode'], 1);
    $pdf->Cell(40, 10, $row['itemCategory'], 1);
    $pdf->Cell(30, 10, $row['itemUnitPrice'], 1);
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
                        <h3 class="text-center font-weight-light my-4">Invoice Item Report</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="mt-4">
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