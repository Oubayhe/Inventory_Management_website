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

// The welcoming message
$id_user=$_SESSION['id_user'];
$req2="SELECT * FROM users WHERE id_user=".$id_user."";
$result2=mysqli_query($link, $req2);
$data2= mysqli_fetch_assoc($result2);
$welcomeMessage = "Bonjour Mr/Mme <span class='welcommed-name'>".$data2["last_name"]." ".$data2["first_name"]."</span>,";
// ---------------------------

// Get the acquisition value of expired products:
$current_date = date('Y-m-d');
$get_expired_value = "SELECT ROUND(SUM(p.quantity * p.price_per_unit), 2) AS expired_value
                FROM Products p
                WHERE p.id_responsable = $id_responsable AND ( experation_date <= '$current_date' AND experation_date IS NOT NULL )";

$link_expired_value = mysqli_query($link, $get_expired_value);
$data_expired_value = mysqli_fetch_assoc($link_expired_value);
$expired_value = $data_expired_value['expired_value'];
// ----------------------------------------------

// The main PHP code of this Page:
// Getting the user's id and its acquisition history:
// Get the user's id:
$id_user = $_POST['agent_id'];
$get_full_user_name = "SELECT first_name, last_name FROM users WHERE id_user = $id_user";
$link_full_user_name = mysqli_query($link, $get_full_user_name);
$data_full_user_name = mysqli_fetch_assoc($link_full_user_name);
$full_user_name = "Mr/Mme ".$data_full_user_name['last_name']." ".$data_full_user_name['first_name'];

// The code for the acquisition history
$get_history_agent = "SELECT u.first_name, u.last_name, rbe.operation_date, rbe.quantity_requested,
p.id_product, pr.product_name, c.category_name
FROM requested_by_employee rbe
JOIN users u ON u.id_user = rbe.id_user
JOIN products p ON p.id_product = rbe.id_product
JOIN products_reference pr ON p.id_product_ref = pr.id_product_ref
JOIN categorys c ON c.id_category = pr.id_category
WHERE rbe.id_responsable = $id_responsable AND rbe.processing_status > 1 AND rbe.id_user = $id_user";
$link_history_agent = mysqli_query($link, $get_history_agent);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style/responsable.css">
    <link rel="stylesheet" href="./style/requested_by_responsable.css">
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
            <!-- Here we have the little message -->
            <h3 class="welcomeMessage">
                Suivre la consommation des produits, comprendre les besoins des employés.
            </h3>
            <!-- Down here we have the table -->
            <h2 class="titre-page">Les acquisitions de <?php echo $full_user_name?></h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Date d'opération</th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Quantitée demandée</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($data_history_agent = mysqli_fetch_assoc($link_history_agent)){
                        $operation_date = $data_history_agent['operation_date'];
                        $first_name = $data_history_agent['first_name'];
                        $last_name = $data_history_agent['last_name'];
                        $quantity_requested = $data_history_agent['quantity_requested'];
                        $product_name = $data_history_agent['product_name'];
                        $category_name = $data_history_agent['category_name'];
                        ?>
                        <tr>
                            <td><?php echo $operation_date?></td>
                            <td><?php echo $product_name?></td>
                            <td><?php echo $category_name?></td>
                            <td style="text-align: center;"><?php echo $quantity_requested?></td>
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