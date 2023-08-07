<?php
    include('connexion.php');
    session_start();
    $id_responsable = $_SESSION['id_responsable'];
   if(isset($_POST['send_request_fromEmp'])){
        $id_product = $_SESSION['id_product'];
        $id_responsable_asking = $id_responsable;
        $processing_status = 0;
        $get_responsable_receving = "SELECT * FROM products WHERE id_product = $id_product";
        $link_responsable_receving = mysqli_query($link, $get_responsable_receving);
        $data_responsable_receving = mysqli_fetch_assoc($link_responsable_receving);
        $id_responsable_receving = $data_responsable_receving['id_responsable'];
        $message = !empty($_POST['message']) ? $_POST['message'] : "NULL";
        $quantity_requested = $_POST['quantity_requested'];

        //Inserting the data into the requested_by_respo table 
        $insert_data = ($message == "NULL") ? "INSERT INTO requested_by_respo (id_product, id_responsable_asking, id_responsable_receving, message, quantity_requested, processing_status) VALUES ($id_product, $id_responsable_asking, $id_responsable_receving, NULL, $quantity_requested, $processing_status)" : 
        "INSERT INTO requested_by_respo (id_product, id_responsable_asking, id_responsable_receving, message, quantity_requested, processing_status) 
        VALUES ($id_product, $id_responsable_asking, $id_responsable_receving, '$message', $quantity_requested, $processing_status)";
        $link_insert_data = mysqli_query($link, $insert_data);
        if($link_insert_data){
            header('location:sented_requests.php');
        } else {
            echo "Vérifiez les données inserées";
        }
   } 
   ?>