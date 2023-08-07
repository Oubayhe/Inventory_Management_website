<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];
if(isset($_POST['submit_demande'])) {
    $_SESSION['id_product'] = $_POST['idProduct'];
    $id_product = $_SESSION['id_product'];

    // 
    $get_product = "SELECT 
    p.id_product, p.id_product_ref, p.id_responsable, p.experation_date, p.price_per_unit,
	pr.product_name,
    c.category_name,
    p.experation_date,
    res.stock_name,
    p.quantity,
    u.last_name,
    u.first_name
FROM products p
JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
JOIN categorys c ON c.id_category = p.id_category
JOIN responsables res ON res.id_responsable = p.id_responsable
JOIN users u ON u.id_user = res.id_user
WHERE id_product = $id_product";
    $link_product = mysqli_query($link, $get_product);
    $data_product = mysqli_fetch_assoc($link_product);    

    $id_product_ref = $data_product['id_product_ref'];
    $id_responsable = $data_product['id_responsable'];
    $experation_date = $data_product['experation_date'];
    $price_per_unit = $data_product['price_per_unit'];
    $product_quantity = $data_product['quantity'];
    $product_name = $data_product['product_name'];
    $category_name = $data_product['category_name'];
    $experation_date = $data_product['experation_date'];
    $stock_name = $data_product['stock_name'];
    $full_name = "Mr/Mme ".$data_product['last_name']." ".$data_product['first_name']."";

    $get_products_sum = ($experation_date == null) ? 
    "SELECT SUM(quantity) AS total_quantity
                     FROM products
                     WHERE id_product_ref = $id_product_ref
                       AND id_responsable = $id_responsable
                       AND price_per_unit = $price_per_unit
                       AND experation_date IS NULL" : 
    "SELECT SUM(quantity) AS total_quantity
                     FROM products
                     WHERE id_product_ref = $id_product_ref
                       AND id_responsable = $id_responsable
                       AND price_per_unit = $price_per_unit
                       AND experation_date = '$experation_date'";

    $link_products_sum = mysqli_query($link, $get_products_sum);
    $data_products_sum = mysqli_fetch_assoc($link_products_sum);
    $quantity = $data_products_sum['total_quantity'];

    $get_number_request_respo = "SELECT COUNT(*) AS row_count FROM requested_by_respo r WHERE r.id_responsable_receving = $id_responsable AND r.processing_status = 0";
    $link_number_request_respo = mysqli_query($link, $get_number_request_respo);
    $data_number_request_respo = mysqli_fetch_assoc($link_number_request_respo);

// Get the acquisition value of expired products:
$current_date = date('Y-m-d');
$get_expired_value = "SELECT ROUND(SUM(p.quantity * p.price_per_unit), 2) AS expired_value
                FROM Products p
                WHERE p.id_responsable = $id_responsable AND ( experation_date <= '$current_date' AND experation_date IS NOT NULL )";

$link_expired_value = mysqli_query($link, $get_expired_value);
$data_expired_value = mysqli_fetch_assoc($link_expired_value);
$expired_value = $data_expired_value['expired_value'];
// ----------------------------------------------

// Get the number of rows for requested_by_employee
$get_number_request_emp = "SELECT COUNT(*) AS row_count FROM requested_by_employee r WHERE r.id_responsable = $id_responsable AND r.processing_status = 0";
$link_number_request_emp = mysqli_query($link, $get_number_request_emp);
$data_number_request_emp = mysqli_fetch_assoc($link_number_request_emp);
// -----------------------------------------------

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style/envoyer_demande_respo.css">
    <title>Demander Produit</title>
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
                        <span class="text-side-bar">Les acquisitions des employés</span>
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
                La carte de la demande
            </div>
            <div class="request-box">
                <form action="traitement_envoyer_par_respo.php" method="post">
                    <table>
                        <tbody>
                            <tr>
                                <th>Produit:</th>
                                <td><?php echo $product_name ?></td>
                            </tr>
                            <tr>
                                <th>Catégorie:</th>
                                <td><?php echo $category_name ?></td>
                            </tr>
                            <tr>
                                <th>Date d'expiration:</th>
                                <td><?php echo $experation_date ?></td>
                            </tr>
                            <tr>
                                <th>Stock:</th>
                                <td><?php echo $stock_name ?></td>
                            </tr>
                            <tr>
                                <th>Responsable du stock:</th>
                                <td><?php echo $full_name ?></td>
                            </tr>
                            <tr>
                                <th>Quantité demandée <span class="required-star">*</span>:</th>
                                <td>
                                    <input class="input-form" type="number" max="<?php echo $quantity?>" min="1" name="quantity_requested" id="quantity_requested" required>
                                </td>
                            </tr>
                            <tr>
                                <th id="th-message">Message:</th>
                                <td>
                                    <textarea class="input-form" placeholder="Entrez un message pour expliquer votre situation..." name="message" id="message" cols="30" rows="3"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" value="Envoyer" name="send_request_fromEmp" id="envoyer_demande_respo">
                </form>
            </div>
        </div>
   </div>

   

    
</body>
</html>