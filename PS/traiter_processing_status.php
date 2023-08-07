<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];
// This for the source, there still need to add one for the destination
$get_src = "SELECT res.stock_name
FROM responsables res JOIN users u ON u.id_user = res.id_user
WHERE res.id_responsable = $id_responsable";
$link_src = mysqli_query($link, $get_src);
$get_src = mysqli_fetch_assoc($link_src);
$id_src = "Stock ".$get_src['stock_name'];
if(isset($_POST['changer_etat'])){
    $_SESSION['id_request_respo'] = $_POST['id_demande'];
    $id_request_respo = $_POST['id_demande'];
    $processing_status = intval($_POST['action']);
    // The conditions :
    if($processing_status != 2){
        $sql_update_status = "UPDATE requested_by_respo SET processing_status = $processing_status WHERE id_request_respo=".$id_request_respo."";
        $link_update_status = mysqli_query($link, $sql_update_status);
        if($link_update_status){
            $get_quantity_requested = "SELECT
            rbr.id_product,
            p.id_product_ref,
            p.id_category,
            rbr.id_responsable_asking,
            p.price_per_unit,
            rbr.quantity_requested,
            p.quantity,
            p.experation_date
        FROM requested_by_respo rbr
        JOIN products p ON rbr.id_product = p.id_product
        WHERE id_request_respo = $id_request_respo";
            $link_quantity_requested = mysqli_query($link, $get_quantity_requested);
            $data_quantity_requested = mysqli_fetch_assoc($link_quantity_requested);
            $quantity_requested = $data_quantity_requested['quantity_requested'];
            $id_product = $data_quantity_requested['id_product'];

            // Update the quantity if the request was accepted
            if($processing_status == 3){
                $id_product_ref = $data_quantity_requested['id_product_ref'];
                $experation_date = $data_quantity_requested['experation_date'];
                $price_per_unit = $data_quantity_requested['price_per_unit'];

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
                $link_products = mysqli_query($link, $get_products);
                $deduct_quantity = $quantity_requested;
                while($data_product = mysqli_fetch_assoc($link_products)){
                    $other_product_id = $data_product['id_product'];
                    $other_product_quantity = $data_product['quantity'];

                    if ($deduct_quantity >= $other_product_quantity) {
                        // Deduct the full other product quantity
                        $deduct_quantity -= $other_product_quantity;
                        $other_product_quantity = 0;
                    } else {
                        // Deduct the remaining quantity
                        $other_product_quantity -= $deduct_quantity;
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

                    // Add the product to the requesting stock in the products table
                    $id_product_ref = $data_quantity_requested['id_product_ref'];
                    $id_category = $data_quantity_requested['id_category'];
                    $price_per_unit = $data_quantity_requested['price_per_unit'];
                    $experation_date = $data_quantity_requested['experation_date'];
                    $id_responsable_asking = $data_quantity_requested['id_responsable_asking'];
                    // Get the destination based on R.Asking:
                    $get_des = "SELECT res.stock_name
                    FROM responsables res JOIN users u ON u.id_user = res.id_user
                    WHERE res.id_responsable = $id_responsable_asking";
                    $link_des = mysqli_query($link, $get_des);
                    $get_des = mysqli_fetch_assoc($link_des);
                    $id_des = "Stock ".$get_des['stock_name'];
                    // ADD to journal products:
                    // If it was accepted this means that in history one is transferd the products while the other one recevied it.
                    // So for Aquisition après acceptation (operation_type is 2) here he is the asking:
                    $operation_date = date('Y-m-d');
                    $operation_quantity = $quantity_requested;
                    $sql_add_journal = "INSERT INTO `journal_products`(`id_product`, `id_product_ref`, `id_responsable`, `operation_date`, `operation_type`, `operation_quantity`, `des_src`) 
                    VALUES ( $id_product, $id_product_ref, $id_responsable_asking,'$operation_date',2, $operation_quantity, '$id_src')";
                    $link_add_journal = mysqli_query($link, $sql_add_journal);
                    // For Transfère le produit (operation_type is 3) here is the receving:
                    $sql_add_journal = "INSERT INTO `journal_products`(`id_product`, `id_product_ref`, `id_responsable`, `operation_date`, `operation_type`, `operation_quantity`, `des_src`) 
                    VALUES ( $id_product, $id_product_ref, $id_responsable,'$operation_date',3, $operation_quantity, '$id_des')";
                    $link_add_journal = mysqli_query($link, $sql_add_journal);
                    // Adding the product part:
                    
                    $sql_adding_product = ($experation_date == NULL) ? 
                    "INSERT INTO products(id_product_ref, id_category, id_responsable, price_per_unit, quantity, experation_date) 
                    VALUES($id_product_ref, $id_category, $id_responsable_asking, $price_per_unit, $quantity_requested, NULL)" : 
                    "INSERT INTO products(id_product_ref, id_category, id_responsable, price_per_unit, quantity, experation_date) 
                    VALUES($id_product_ref, $id_category, $id_responsable_asking, $price_per_unit, $quantity_requested, '$experation_date')";
                    if($link_adding_product = mysqli_query($link, $sql_adding_product)){
                        header('location:recieved_requests.php');
                    }
            } else {
                header('location:recieved_requests.php');
            }
        }
    } else {
        header('location:modifier_demande_respo.php');
    }
}
?>
