<?php
require('fpdf/fpdf.php');
include('config/dbcon.php'); 
// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font for the report
$pdf->SetFont('Arial', 'B', 12);

// Add a title to the report
$pdf->Cell(0, 10, 'Item Report', 0, 1, 'C');

// Set font for the table headers
$pdf->SetFont('Arial', 'B', 10);

// Add headers to the table
$pdf->Cell(50, 10, 'Item Name', 1);
$pdf->Cell(50, 10, 'Item Category', 1);
$pdf->Cell(50, 10, 'Item Subcategory', 1);
$pdf->Cell(40, 10, 'Item Quantity', 1);
$pdf->Ln(); // Move to the next line

// Fetch data from the database and add it to the PDF
$query = "SELECT i.item_name AS itemName, 
                 ic.category AS itemCategory, 
                 isc.sub_category AS itemSubcategory, 
                 SUM(i.quantity) AS itemQuantity
          FROM item i 
          JOIN item_category ic ON i.item_category = ic.id 
          JOIN item_subcategory isc ON i.item_subcategory = isc.id 
          GROUP BY i.item_name, ic.category, isc.sub_category";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(50, 10, $row['itemName'], 1);
    $pdf->Cell(50, 10, $row['itemCategory'], 1);
    $pdf->Cell(50, 10, $row['itemSubcategory'], 1);
    $pdf->Cell(40, 10, $row['itemQuantity'], 1);
    $pdf->Ln(); // Move to the next line
}

// Output the PDF
$pdf->Output();
?>