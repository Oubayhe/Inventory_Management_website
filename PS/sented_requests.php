<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];

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
    <link rel="stylesheet" href="./style/sented_respo.css">
    <title>Responsable</title>
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
        <!-- Here is where we putting the main page, where you can find the products table -->
        <div class="main-page">
            <!-- Down here we have the table -->
            <h2 class="titre-page">Les demandes envoyées</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Date d'expiration</th>
                        <th>Quantité demandée</th>
                        <th>Du Stock</th>
                        <th>Etat du traitement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_products = "SELECT 
                    pr.product_name,
                    c.category_name,
                    p.experation_date,
                    rbr.quantity_requested,
                    res.stock_name,
                    rbr.processing_status
                FROM requested_by_respo rbr
                JOIN responsables res ON res.id_responsable = rbr.id_responsable_receving
                JOIN products p ON p.id_product = rbr.id_product
                JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
                JOIN categorys c ON c.id_category = p.id_category
                WHERE rbr.id_responsable_asking = $id_responsable
                ORDER BY rbr.id_request_respo DESC";

                    $link_products = mysqli_query($link, $get_products);
                    while($data_products = mysqli_fetch_assoc($link_products)){
                        ?>
                        <tr>
                            <td><?php echo $data_products['product_name'] ?></td>
                            <td><?php echo $data_products['category_name'] ?></td>
                            <td><?php echo $data_products['experation_date'] ?></td>
                            <td><?php echo $data_products['quantity_requested'] ?></td>
                            <td><?php echo $data_products['stock_name'] ?></td>
                            <td><?php
                            if(($data_products['processing_status']) == 0) {
                                echo '<span class="cours"><i class="fa-solid fa-spinner"></i> En cours du traitement</span>';
                            } else if(($data_products['processing_status']) == 1) {
                                echo '<span class="rejected"><i class="fa-solid fa-circle-xmark"></i> Refusée</span>';
                            }else if(($data_products['processing_status']) == 2){
                                echo '<span class="modifier"><i class="fa-solid fa-gear"></i> Acceptée après modification</span>';
                            } else {
                                echo '<span class="accepted"><i class="fa-solid fa-circle-check"></i> Acceptée</span>';
                            }
                            ?></td>
                        </tr>
                        <?php
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        var acceptedSpans = document.querySelectorAll('span.accepted');
        acceptedSpans.forEach(function(span) {
            var parentRow = span.closest('tr');
            parentRow.classList.add('accepted-row');
        });

        var modifierSpans = document.querySelectorAll('span.modifier');
        modifierSpans.forEach(function(span) {
            var parentRow = span.closest('tr');
            parentRow.classList.add('modifier-row');
        });

        var rejectedSpans = document.querySelectorAll('span.rejected');
        rejectedSpans.forEach(function(span) {
            var parentRow = span.closest('tr');
            parentRow.classList.add('rejected-row');
        });
    </script>
</body>
</html>