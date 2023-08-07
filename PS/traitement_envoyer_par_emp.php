<?php
    include('connexion.php');
    session_start();
    $id_user = $_SESSION['id_user'];
    // Get the responsable of our user:
    $get_id_responsable = "SELECT * FROM users WHERE id_user = $id_user";
    $link_id_responsable = mysqli_query($link, $get_id_responsable);
    $data_id_responsable = mysqli_fetch_assoc($link_id_responsable);
    $id_responsable = $data_id_responsable['id_responsable'];
    // --------------------------------
   if(isset($_POST['envoyer_demande_emp'])){
        $id_product = $_SESSION['id_product'];
        $processing_status = 0;
        $message = !empty($_POST['message']) ? $_POST['message'] : "NULL";
        $quantity_requested = $_POST['quantity_requested'];
        $operation_date = date('Y-m-d');

        //Inserting the data into the requested_by_employee table 
        $insert_data = ($message == "NULL") ? 
        "INSERT INTO `requested_by_employee`(`id_product`, `id_responsable`, `quantity_requested`, `processing_status`, `message`, `id_user`, `operation_date`) 
        VALUES ($id_product, $id_responsable, $quantity_requested, $processing_status, NULL, $id_user, '$operation_date')" : 
        "INSERT INTO `requested_by_employee`(`id_product`, `id_responsable`, `quantity_requested`, `processing_status`, `message`, `id_user`, `operation_date`) 
        VALUES ($id_product, $id_responsable, $quantity_requested, $processing_status, '$message', $id_user, '$operation_date')";
        $link_insert_data = mysqli_query($link, $insert_data);
        if($link_insert_data){
            header('location:sent_from_employee.php');
            exit();
        } else {
            echo "Vérifiez les données inserées". mysqli_error($link);
            echo "($id_product, $id_responsable, $quantity_requested, $processing_status, $message, $id_user, $operation_date)";
        }
   } 
   ?>