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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style/responsable.css">
    <link rel="stylesheet" href="./style/requested_by_responsable.css">
    <link rel="stylesheet" href="./style_employee/All_Agents.css">
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
            <!-- Here we have the little message -->
            <h3 class="welcomeMessage">
                
            </h3>
            <!-- Down here we have the table -->
            <h2 class="titre-page">Les Employées</h2>
            <div class="cards_container">
            <?php
                    $get_all_agents = "SELECT u.first_name, u.last_name, u.id_user 
                    FROM users u 
                    WHERE u.id_responsable = $id_responsable AND u.id_user NOT IN (SELECT id_user FROM responsables WHERE id_responsable = $id_responsable)";
                    $link_all_agents = mysqli_query($link, $get_all_agents);
                    while($data_all_agents = mysqli_fetch_assoc($link_all_agents)){
                        ?>
                        <div class="agent_card">
                            <div class="agent_image">
                                <img src="./Inventory_Images/OIP.jpg" >
                            </div>
                            <div class="agent_name">
                                <?php echo $data_all_agents['last_name']." ".$data_all_agents['first_name'];?>
                            </div>
                            <form method="post" action="getTheAgent.php">
                                <input type="hidden" name="agent_id" value="<?php echo $data_all_agents['id_user']?>">
                                <button type="submit">Voir les Acquisitions</button>
                            </form>
                        </div>
                        <?php
                    }
            ?>
            </div>
        </div>
    </div>
</body>
</html>