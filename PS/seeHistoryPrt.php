<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];
$id_product_ref = $_POST['idProduct'];
    // Now I have the id journal and the id_responsable
    // Let's select them
    $sql_history_prt = "SELECT 
	p.experation_date,
    p.price_per_unit,
    jp.operation_date, 
    jp.operation_type, 
    jp.operation_quantity, 
    jp.des_src
    FROM journal_products jp JOIN products p ON p.id_product = jp.id_product
    WHERE jp.id_product_ref = $id_product_ref AND jp.id_responsable = $id_responsable";
    $link_history_prt = mysqli_query($link, $sql_history_prt);

    // product Info, its name, price per unit and expiration date
    $get_info = "SELECT 
    pr.product_name,
    MIN(p.experation_date) AS experation_date
FROM products p
JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
WHERE p.id_product_ref = $id_product_ref";
    $link_info = mysqli_query($link, $get_info);
    $data_info = mysqli_fetch_assoc($link_info);
    $product_name = $data_info['product_name'];
    $experation_date = $data_info['experation_date'];

    // Get the quantity of the product that share the same id_product_ref, price_per_unit and experation_date
    $get_total_quantity = "SELECT SUM(quantity) AS total_quantity
    FROM products
    WHERE id_product_ref = $id_product_ref 
    AND id_responsable = $id_responsable ";
    $link_total_quantity = mysqli_query($link, $get_total_quantity);
    $data_total_quantity = mysqli_fetch_assoc($link_total_quantity);
    $total_quantity = $data_total_quantity['total_quantity'];

// ------------------------------------------
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
    <link rel="stylesheet" href="./style/seeHistory.css">
    <title>Historique</title>
</head>
<body>
    <!-- Here we are putting the big container -->
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
        <!-- Here is where we putting the main page, where you can find the products table -->
        <div class="main-page">
            <!-- Down here we have the table -->
            <div class="topInfo">
                <div class="iconGoBack"><a href="journalProduit.php"><i class="fa-solid fa-arrow-left"></i></a></div>
                <h2 class="titre-page">Les Acquisitions et Les Mouvements</h2>
            </div>
            <div class="middleInfo">
                <table class="tableInfo">
                    <tbody>
                        <tr>
                            <td>Produit: </td>
                            <th class="infoBox"><?php echo $product_name?></th>
                        </tr>
                        <tr>
                            <td>La plus proche date d'expiration: </td>
                            <th class="infoBox"><?php echo $experation_date?></th>
                        </tr>
                            
                    </tbody>
                </table>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date d'expiration</th>
                        <th>Prix par unité</th>
                        <th>Date d'opération</th>
                        <th>Type d'opération</th>
                        <th>Source ou Destination</th>
                        <th>Quantité d'opération</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($data_history_prt = mysqli_fetch_assoc($link_history_prt)){
                        $experation_date = $data_history_prt['experation_date'];
                        $price_per_unit = $data_history_prt['price_per_unit'];
                        $operation_date = $data_history_prt['operation_date'];
                        $operation_type = $data_history_prt['operation_type'];
                        $operation_quantity = $data_history_prt['operation_quantity'];
                        $des_src = $data_history_prt['des_src'];
                        ?>
                        <!-- class=" >?php echo "color_".$operation_type;?>" -->
                        <!--         ^  Red lbal hila kenti ghadi tzidha-->
                        <tr class="<?php echo "color_".$operation_type;?>">
                            <td style="text-align: center;"><?php echo $experation_date?></td>
                            <td style="text-align: center;"><?php echo $price_per_unit?></td>
                            <td style="text-align: center;"><?php echo $operation_date ?></td>
                            <td style="text-align: center;"><?php 
                            switch ($operation_type) {
                                case 1:
                                    echo "Acquisition d'après un achat";
                                    break;
                                case 2:
                                    echo "Acquisition d'après une demande";
                                    break;
                                case 3:
                                    echo "Transfère à un stock";
                                    break;
                                case 4:
                                    echo "Sortie pour un agent";
                                    break;
                                case 5:
                                    echo "Élimination du produit en raison de sa péremption";
                                    break;}?></td>
                            <td style="text-align: center;"><?php echo $des_src ?></td>
                            <td style="text-align: center;"><?php echo $operation_quantity ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="etatStock">Etat du stock: <span><?php echo $total_quantity?></span></div>
        </div>
    </div>
</body>
</html>