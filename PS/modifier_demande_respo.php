<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];
$id_request_respo = $_SESSION['id_request_respo'];

$get_products_for_respo = "SELECT 
    p.id_product, p.id_product_ref, p.id_responsable, p.experation_date, p.price_per_unit,
    pr.product_name,
    res.stock_name,
    u.first_name,
    u.last_name,
    rbr.message,
    p.quantity,
    rbr.quantity_requested
    FROM requested_by_respo rbr
    JOIN responsables res ON res.id_responsable = rbr.id_responsable_asking
    JOIN products p ON p.id_product = rbr.id_product
    JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
    JOIN users u ON u.id_user = res.id_user
    WHERE rbr.id_request_respo  = $id_request_respo";

$link_products_for_respo = mysqli_query($link, $get_products_for_respo);
$data = mysqli_fetch_assoc($link_products_for_respo);

$id_product_ref = $data['id_product_ref'];
$id_responsable = $data['id_responsable'];
$experation_date = $data['experation_date'];
$price_per_unit = $data['price_per_unit'];
$get_products_sum = ($experation_date == NULL) ? 
                    "SELECT SUM(quantity) AS total_quantity
                    FROM products
                    WHERE id_product_ref = $id_product_ref
                       AND id_responsable = $id_responsable
                       AND experation_date IS NULL" : 
                    "SELECT SUM(quantity) AS total_quantity
                    FROM products
                    WHERE id_product_ref = $id_product_ref
                        AND id_responsable = $id_responsable
                        AND experation_date = '$experation_date'
" ;

$link_products_sum = mysqli_query($link, $get_products_sum);
$data_products_sum = mysqli_fetch_assoc($link_products_sum);
$quantity = $data_products_sum['total_quantity'];

$get_number_request_respo = "SELECT COUNT(*) AS row_count FROM requested_by_respo r WHERE r.id_responsable_receving = $id_responsable AND r.processing_status = 0";
$link_number_request_respo = mysqli_query($link, $get_number_request_respo);
$data_number_request_respo = mysqli_fetch_assoc($link_number_request_respo);

// Get the number of rows for requested_by_employee
$get_number_request_emp = "SELECT COUNT(*) AS row_count FROM requested_by_employee r WHERE r.id_responsable = $id_responsable AND r.processing_status = 0";
$link_number_request_emp = mysqli_query($link, $get_number_request_emp);
$data_number_request_emp = mysqli_fetch_assoc($link_number_request_emp);
// -----------------------------------------------

// Get the acquisition value of expired products:
$current_date = date('Y-m-d');
$get_expired_value = "SELECT ROUND(SUM(p.quantity * p.price_per_unit), 2) AS expired_value
                FROM Products p
                WHERE p.id_responsable = $id_responsable AND ( experation_date <= '$current_date' AND experation_date IS NOT NULL )";

$link_expired_value = mysqli_query($link, $get_expired_value);
$data_expired_value = mysqli_fetch_assoc($link_expired_value);
$expired_value = $data_expired_value['expired_value'];
// ----------------------------------------------
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style/envoyer_demande_respo.css">
    <title>Modifier Demande</title>
</head>
<body>
   <div class="big-container">
        <!-- Here is for the side bar -->
        <div class="side_bar">
            <!-- Here is the top of the side bar, where we're going to put the logo -->
            <img src="./Inventory_Images/download-removebg-preview.png" alt="Logo">
            <!-- Right here is the list of the side bar -->
            <ul class="side-bar-elements">
                <a href="responsable.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Les produits du stock</span>
                    </li>
                </a>
                <a href="journalProduit.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Mouvements des produits</span>
                    </li>
                </a>
                <a href="journalAgent.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Les livraisons aux employés</span>
                    </li>
                </a>
                <a href="acheter_produit.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Acheter un produit</span>
                    </li>
                </a>
                <a href="requested_by_responsable.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Demander un produit</span>
                    </li>
                </a>
                <a href="sented_requests.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Demandes envoyées</span>
                    </li>
                </a>
                <a href="demandes_des_employees.php">
                    <li class="element-side-bar number_request_respo">
                        <span class="text-side-bar">Demandes des employés</span>
                        <div>
                            <?php echo $data_number_request_emp['row_count'] ?>
                        </div>
                    </li>
                </a>
                <a href="recieved_requests.php">
                    <li class="element-side-bar number_request_respo">
                        <span class="text-side-bar">Demandes des Responsables</span>
                        <div>
                            <?php echo $data_number_request_respo['row_count'] ?>
                        </div>
                    </li>
                </a>
                <a href="expired_products.php">
                    <li class="element-side-bar number_request_respo">
                        <span class="text-side-bar">Produits périmes</span>
                        <div class="expired_value">
                            <?php echo $expired_value."DH"; ?>
                        </div>
                    </li>
                </a>
                <a class="logout-container" href="logout.php">
                    <li class="element-side-bar">
                        <span class="text-side-bar">Déconnecter</span>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </li>
                </a>
            </ul>
        </div>

        <div class="main-page">
            <div class="titre-acheter-produit">
                Modifier la demande
            </div>
            <div class="request-box">
                <form action="traiter_modifier_respo.php" method="post">
                    <table>
                        <tbody>
                            <tr>
                                <th>Produit:</th>
                                <td><?php echo $data['product_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Stock:</th>
                                <td><?php echo $data['stock_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Date d'expiration:</th>
                                <td><?php echo $data['last_name']." ".$data['first_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Stock:</th>
                                <td><?php echo $data['message'] ?></td>
                            </tr>
                            <tr>
                                <th>Quantité demandée <span class="required-star">*</span>:</th>
                                <td>
                                    <input value="<?php echo $data['quantity_requested']?>" class="input-form" min="1" max="<?php echo $quantity?>" type="number" name="quantity_requested" id="quantity_requested" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" value="Envoyer" name="envoyer_demande_respo" id="envoyer_demande_respo">
                </form>
            </div>
        </div>
   </div>

   
    
</body>
</html>