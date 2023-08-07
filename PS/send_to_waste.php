<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];

if(isset($_POST['submit_demande'])){
    $id_product = $_POST['idProduct'];
    // Get connected with Waste Departement (send information)
    $sql_new_waste = "INSERT INTO `waste_departement`(`id_product`, `id_responsable`, `disposition_date`) 
    VALUES ($id_product, $id_responsable, NULL)";
    $link_new_waste = mysqli_query($link, $sql_new_waste);
    if($link_new_waste){
        header('location:expired_products.php');
    }
}
?>