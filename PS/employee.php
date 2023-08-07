<?php
include('connexion.php');
session_start();
$id_user = $_SESSION['id_user'];

// Get the data that we need to list down
// It should get all the products, that belong to the same id_responsable our user is and its quantity > 0 and not expired
$current_date = date('Y-m-d');
$get_user_products = "SELECT MAX(p.id_product) AS id_product,
pr.product_name,
c.category_name,
SUM(p.quantity) AS quantity,
p.experation_date
FROM products p
JOIN products_reference pr ON p.id_product_ref = pr.id_product_ref
JOIN categorys c ON p.id_category = c.id_category
JOIN users u ON u.id_responsable = p.id_responsable
WHERE u.id_user = $id_user AND ( experation_date > '$current_date' OR experation_date IS NULL ) AND p.quantity > 0
GROUP BY p.id_product_ref, p.experation_date, p.price_per_unit
ORDER BY id_product DESC";
$link_user_products = mysqli_query($link, $get_user_products);
// --------------------------------------------------

// Welcome Message:
$get_welcome_msg = "SELECT * FROM users WHERE id_user = $id_user";
$link_welcome_msg = mysqli_query($link, $get_welcome_msg);
$data_welcome_msg = mysqli_fetch_assoc($link_welcome_msg);
$welcomeMessage = "Bonjour Mr/Mme <span class='welcommed-name'>".$data_welcome_msg["last_name"]." ".$data_welcome_msg["first_name"]."</span>,";
// ----------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style_employee/employee.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des produits</title>
</head>
<body>
    <div class="big_container">
        <div class="top-bar">
            <div class="welcomeMessage">
                <?php echo $welcomeMessage;?>
            </div>
            <div class="configurations display-flex">
                <div class="voirDemandeEnvoye">
                    <!-- A table of the resquests sent by the employee -->
                    <a href="sent_from_employee.php" class="display-flex" id="demandeEnv">
                        <div class="text">Demandes Envoyées</div>
                        <div class="icon"><i class="fa-solid fa-paper-plane"></i></div>
                    </a>
                </div>
                <div class="logout">
                    <a href="logout.php" class="display-flex" id="logingOut">
                        <div class="text">Déconnecter</div> 
                        <div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                    </a>
                </div>
            </div>
        </div>
        <h2 class="titre-page">Tableau des produits</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Date d'expiration</th>
                    <th>Demander</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($data_user_products = mysqli_fetch_assoc($link_user_products)){
                    ?>
                    <tr>
                        <td><?php echo $data_user_products['product_name']  ?></td>
                        <td><?php echo $data_user_products['category_name']  ?></td>
                        <td><?php echo $data_user_products['quantity']  ?></td>
                        <td><?php echo $data_user_products['experation_date']  ?></td>
                        <td>
                            <form action="envoyer_demande_employee.php" method="post">
                                <input type="hidden" name="idProduct" value="<?php echo $data_user_products['id_product']?>">
                                <button class="icon-chart" name="submit_demande" type="submit"><i class="fa-solid fa-envelope"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>