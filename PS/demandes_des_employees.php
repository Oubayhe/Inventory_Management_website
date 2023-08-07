<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];


// Getting the number of requestes from requested_by respo, where our responsable is the responsable receiving and the processing_status is 0 (en cours du traitement)
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

// Get the products for the Responsables to use, except the current user
$get_products_for_respo = "SELECT 
rbe.id_request_employee,
p.id_product,
pr.product_name, 
u.first_name, 
u.last_name,
rbe.quantity_requested, 
rbe.message, 
c.category_name
FROM requested_by_employee rbe
JOIN products p ON p.id_product = rbe.id_product
JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
JOIN users u ON rbe.id_user = u.id_user
JOIN categorys c ON c.id_category = p.id_category
WHERE rbe.id_responsable = $id_responsable AND rbe.processing_status = 0
ORDER BY rbe.id_product DESC;";

$link_products_for_respo = mysqli_query($link, $get_products_for_respo);
// ---------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style/received_by_respo.css">
    <title>Envoyer Demande</title>
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
            <h2 class="titre-page">Les demandes envoyée par des employés</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Employee</th>
                        <th>Quantité demandée</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($data_PFR = mysqli_fetch_assoc($link_products_for_respo)){
                        ?>
                        <tr>
                            <td><?php echo $data_PFR['product_name'] ?></td>
                            <td><?php echo $data_PFR['category_name'] ?></td>
                            <td><?php echo "Mr/Mme ".$data_PFR['last_name']." ".$data_PFR['first_name'] ?></td>
                            <td><?php echo $data_PFR['quantity_requested'] ?></td>
                            <td><?php echo $data_PFR['message'] ?></td>
                            <td>
                                <!-- Make sure to update the code to only show those that have processing_status in 0 -->
                                <form id="form_rbr" action="traiter_PS_emp.php" method="post">
                                    <input type="radio" name="action" id="accept" value="3">
                                    <label>Accepter la demande</label> <br>
                                    <input type="radio" name="action" id="reject" value="1">
                                    <label>Refuser la demande</label> <br>
                                    <input type="radio" name="action" id="reject" value="2">
                                    <label>Modifier la demande</label> <br>
                                    <input type="hidden" name="id_demande" value="<?php echo $data_PFR['id_request_employee']; ?>">
                                    <input type="submit" id="changer_etat" name="changer_etat" value="Confirmer">
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>