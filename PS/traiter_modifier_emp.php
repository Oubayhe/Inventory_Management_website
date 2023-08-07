<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];
$id_request_employee = $_SESSION['id_request_employee'];

if(isset($_POST['envoyer_demande_respo'])){
    $quantity_requested = $_POST['quantity_requested'];
    $operation_date = date('Y-m-d');

    // Update the new quantity_requested to the new one:
    $sql_new_quantity_requested = "UPDATE requested_by_employee SET quantity_requested = $quantity_requested, operation_date = '$operation_date' WHERE id_request_employee=".$id_request_employee."";
    if($link_new_quantity_requested = mysqli_query($link, $sql_new_quantity_requested)){
        // The rest of the updates
        $sql_update_status = "UPDATE requested_by_employee SET processing_status = 2 WHERE id_request_employee=".$id_request_employee."";
        $link_update_status = mysqli_query($link, $sql_update_status);
        if($link_update_status){
            $get_quantity_requested = "SELECT
            u.first_name,
            u.last_name,
            rbe.id_product,
            p.id_product_ref,
            p.id_category,
            rbe.id_user,
            p.price_per_unit,
            rbe.quantity_requested,
            p.quantity,
            p.experation_date
        FROM requested_by_employee rbe
        JOIN products p ON rbe.id_product = p.id_product
        JOIN users u ON u.id_user = rbe.id_user
        WHERE id_request_employee = $id_request_employee";
            if($link_quantity_requested = mysqli_query($link, $get_quantity_requested)){
                $data_quantity_requested = mysqli_fetch_assoc($link_quantity_requested);
            $quantity_requested = $data_quantity_requested['quantity_requested'];
            $id_product = $data_quantity_requested['id_product'];
            // Update the quantity if the request was accepted
            // Deduct the value from the original stock in the products table

                $id_product_ref = $data_quantity_requested['id_product_ref'];
                $experation_date = $data_quantity_requested['experation_date'];
                $price_per_unit = $data_quantity_requested['price_per_unit'];
                $id_des = "Mr/Mme ".$data_quantity_requested['last_name']." ".$data_quantity_requested['first_name'];
                // Adding to the journal products:
                $operation_date = date('Y-m-d');
                $operation_quantity = $quantity_requested;
                $sql_add_journal = "INSERT INTO `journal_products`(`id_product`, `id_product_ref`, `id_responsable`, `operation_date`, `operation_type`, `operation_quantity`, `des_src`) 
                VALUES ( $id_product, $id_product_ref, $id_responsable,'$operation_date',4, $operation_quantity, '$id_des')";
                $link_add_journal = mysqli_query($link, $sql_add_journal);

                // Updating the quantities part:

                $get_products = ($experation_date == NULL) ? 
                "SELECT id_product, quantity
                FROM products
                WHERE id_product_ref = $id_product_ref
                  AND id_responsable = $id_responsable
                  AND experation_date IS NULL
                  AND price_per_unit = $price_per_unit
                ORDER BY id_product DESC" : 
                "SELECT id_product, quantity
                 FROM products
                 WHERE id_product_ref = $id_product_ref
                   AND id_responsable = $id_responsable
                   AND experation_date = '$experation_date'
                   AND price_per_unit = $price_per_unit
                 ORDER BY id_product DESC";
                
                if($link_products = mysqli_query($link, $get_products)){
                    $deduct_quantity = $quantity_requested;
                while($data_product = mysqli_fetch_assoc($link_products)){
                    $other_product_id = $data_product['id_product'];
                    $other_product_quantity = $data_product['quantity'];

                    if ($deduct_quantity >= $other_product_quantity) {
                        // Deduct the full other product quantity
                        $deduct_quantity = $deduct_quantity - $other_product_quantity;
                        $other_product_quantity = 0;
                    } else {
                        // Deduct the remaining quantity
                        $other_product_quantity = $other_product_quantity - $deduct_quantity;
                        $deduct_quantity = 0;
                    }
                    // Update the other product quantity
                    $update_quantity = "UPDATE products
                        SET quantity = $other_product_quantity
                        WHERE id_product = $other_product_id";
                    $link_update_quantity = mysqli_query($link, $update_quantity);
                    if ($deduct_quantity == 0) {
                        break; // Stop updating if the remaining quantity is exhausted
                    }
                }
                header('location:demandes_des_employees.php');
                }
            }
            
        }
    }
}
?>