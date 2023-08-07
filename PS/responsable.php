<?php
include('connexion.php');
session_start();
$id_responsable = $_SESSION['id_responsable'];

// Go to delete any expired product real quick
include('connexion.php');
// Get all the waste
$get_all_waste = "SELECT id_product, id_responsable, disposition_date
FROM waste_departement
WHERE id_product IN (
SELECT id_product
FROM products WHERE quantity != 0)";
$link_all_waste = mysqli_query($link, $get_all_waste);
// -----------------
while($data_all_waste = mysqli_fetch_assoc($link_all_waste)){
    $id_product = $data_all_waste['id_product'];
    $id_responsable_exp = $data_all_waste['id_responsable'];
    $today = date('Y-m-d');
    if($data_all_waste['disposition_date'] == $today){
      $get_products_toZero = "SELECT 
    p.id_product, p.id_product_ref, p.experation_date, p.price_per_unit
    FROM products p WHERE id_product = $id_product";
    $link_products_toZero = mysqli_query($link, $get_products_toZero);
    $data_products_toZero = mysqli_fetch_assoc($link_products_toZero);

    // Get other prodcuts that share the same id_product_ref, experation_date, id_responsable, price_per_unit
    $id_product_ref = $data_products_toZero['id_product_ref'];
    $experation_date = $data_products_toZero['experation_date'];
    $price_per_unit = $data_products_toZero['price_per_unit'];
    // Adding to Journal Products:
    $operation_date = date('Y-m-d');
    $get_operation_quantity = "SELECT sum(quantity) as total_quantity
    FROM products WHERE id_product_ref = $id_product_ref
                    AND price_per_unit = $price_per_unit
                    AND experation_date = '$experation_date'
                    AND id_responsable = $id_responsable_exp";
    $link_operation_quantity = mysqli_query($link, $get_operation_quantity);
    $data_operation_quantity = mysqli_fetch_assoc($link_operation_quantity);
    $operation_quantity = $data_operation_quantity['total_quantity'];
    $sql_add_journal = "INSERT INTO `journal_products`(id_product, `id_product_ref`, `id_responsable`, `operation_date`, `operation_type`, `operation_quantity`, `des_src`) 
    VALUES ($id_product, $id_product_ref,$id_responsable_exp,'$operation_date',5, $operation_quantity, 'Département des Opérations de Disposition')";
    $link_add_journal = mysqli_query($link, $sql_add_journal);
    // Updating the values
    $get_products = "SELECT id_product, quantity
    FROM products
    WHERE id_product_ref = $id_product_ref
      AND id_responsable = $id_responsable_exp
      AND price_per_unit = $price_per_unit
      AND experation_date = '$experation_date'";
    $link_products = mysqli_query($link, $get_products);
    while($data_product = mysqli_fetch_assoc($link_products)){
        $other_product_id = $data_product['id_product'];

        $update_quantity = "UPDATE products
                        SET quantity = 0
                        WHERE id_product = $other_product_id";
        $link_update_quantity = mysqli_query($link, $update_quantity);
    }

    }
    
}
// ------------------------------------------

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
                <?php echo $welcomeMessage ?>
            </h3>
            <!-- Down here we have the table -->
            <h2 class="titre-page">Tableau des produits</h2>
            <div class="acquisition-container">
                <?php
                $get_acquisition_value = "SELECT ROUND(SUM(p.quantity * p.price_per_unit), 2) AS acquisition_value
                FROM Products p
                WHERE p.id_responsable = $id_responsable";

                $link_acquisition_value = mysqli_query($link, $get_acquisition_value);
                if($data_acquisition_value = mysqli_fetch_assoc($link_acquisition_value)){
                    ?>
                    Valeur du stock: <span class="acquisition-value">
                        <?php echo $data_acquisition_value['acquisition_value'] ?> DH </span>
                    <?php
                }
                ?>
                <div class="stock-name-container">
                <?php
                $get_stock_name = "SELECT * FROM responsables WHERE id_responsable = $id_responsable";
                $link_stock_name = mysqli_query($link, $get_stock_name);
                $data_stock_name = mysqli_fetch_assoc($link_stock_name);
                $stock_name = "Les produits du stock ".$data_stock_name['stock_name'];
                echo $stock_name;
                ?>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix moyenne</th>
                        <th>Quantité</th>
                        <th>La plus proche date d'expiration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $current_date = date('Y-m-d');

                    $get_products = "SELECT
                    MAX(p.id_product) AS id_product,
                    pr.product_name,
                    c.category_name,
                    p.id_product_ref,
                    p.id_category,
                    p.id_responsable,
                    SUM(p.quantity) AS quantity,
                    ROUND(SUM(p.quantity * p.price_per_unit) / SUM(p.quantity), 2) AS price_per_unit,
                    MIN(p.experation_date) AS experation_date
                FROM
                    products p
                JOIN
                    products_reference pr ON p.id_product_ref = pr.id_product_ref
                JOIN
                    categorys c ON p.id_category = c.id_category
                WHERE
                    p.id_responsable = $id_responsable AND (p.experation_date > '$current_date' OR p.experation_date IS NULL) 
                GROUP BY
                    p.id_product_ref
                ORDER BY
                    id_product DESC";

                    $link_products = mysqli_query($link, $get_products);
                    while($data_products = mysqli_fetch_assoc($link_products)){
                        $zeroQuantity = ($data_products['quantity'] == 0) ? 'zero-quantity' : '';
                        ?>
                        <tr class="<?php echo $zeroQuantity ?>">
                            <td><?php echo $data_products['product_name'] ?></td>
                            <td><?php echo $data_products['category_name'] ?></td>
                            <td><?php echo $data_products['price_per_unit'] ?> DH</td>
                            <td><?php echo $data_products['quantity'] ?></td>
                            <td style="text-align: center;"><?php echo $data_products['experation_date'] ?></td>
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