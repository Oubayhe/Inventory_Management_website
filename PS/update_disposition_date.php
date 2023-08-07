<?php
include('connexion.php');

if (isset($_POST['setDispoDate'])) { // Check if the form was submitted using POST method
    $id_responsable = $_POST['id_responsable'];
    $id_product = $_POST['id_product'];
    $disposition_date = $_POST['disposition_date'];

    // Note: It's a good practice to use prepared statements to prevent SQL injection.
    $sql_disposition_date = "UPDATE `waste_departement` SET `disposition_date`='$disposition_date' 
    WHERE id_responsable =  $id_responsable AND id_product =  $id_product";

    if ($link_disposition_date = mysqli_query($link, $sql_disposition_date)) {
        header('location:waste_departement.php'); // Corrected the file name in the redirection
        exit; // It's good to add an exit here to prevent further code execution after redirection.
    }
}
?>
