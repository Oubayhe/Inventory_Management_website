<?php
include('connexion.php');
session_start();
$id_user = $_SESSION['id_user'];
$isResponsable = "SELECT * FROM responsables WHERE id_user = $id_user";
$linkToResponsable = mysqli_query($link, $isResponsable);
if($dataResponsable = mysqli_fetch_assoc($linkToResponsable)) {
    $_SESSION['id_responsable'] = $dataResponsable['id_responsable'];
    header('location:responsable.php');
} else {
    header('location:employee.php');
}

?>