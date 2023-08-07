<?php
include('connexion.php');
session_start();
$id_user = $_SESSION['id_user'];
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
    <link rel="stylesheet" href="./style/acheter_produit.css">
    <link rel="stylesheet" href="./style/responsable.css">
    <title>Acheter Produit</title>
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
                Acheter un produit
            </div>
            <form action="#" method="post" id="acheter_produit">
                <div class="input-division">
                    <label for="productName">Choisir le produit </label>
                    <select onchange="handleOption(event)" name="productName" id="select-product-name">
                        <?php
                        $get_reference = "SELECT
                        pr.id_product_ref,
                        pr.product_name,
                        c.category_name
                        FROM
                        products_reference pr
                        JOIN
                        categorys c ON pr.id_category = c.id_category";
                        $link_reference = mysqli_query($link, $get_reference);
                        while($data_reference = mysqli_fetch_assoc($link_reference)){
                        ?>
                        <option value="<?php echo $data_reference['id_product_ref'] ?>">
                            <?php echo $data_reference['product_name'].", categorie: ".$data_reference['category_name'] ?>
                        </option>
                        <?php
                        }
                        ?>
                        <option value="newProduct" class="newProduct">Nouveau Produit</option>
                    </select>
                </div>
                <!-- If the value of the select is New Product -->
                <div id="new_product_container" style="display: none;">
                    <div class="hidden-division">
                        <input placeholder="Nom du nouveau produit..." type="text" name="nouveau_product" id="nouveau_product">
                        <div>
                            <label for="category_name">Choisir la catégorie </label>
                            <select onchange="handleNewCategory(event)" name="category_name" id="category_name">
                                <!-- Get all the categories -->
                                <?php
                                $get_categorys = "SELECT * FROM categorys";
                                $link_categorys = mysqli_query($link, $get_categorys);
                                while($data_categorys = mysqli_fetch_assoc($link_categorys)){
                                ?>
                                <option value="<?php echo $data_categorys['id_category']?>"><?php echo $data_categorys['category_name']?></option>
                                <?php
                                }
                                ?>
                                <option value="newCategory">Nouvelle catégorie</option>
                                <!-- ---------------------- -->
                            </select>
                        </div>
                    </div>
                    <div id="new_category_container" style="display: none;">
                        <div class="trying-div">
                            <label for="nouvelle_categorie">Nom de la catégorie </label>
                            <input placeholder="La nouvelle catégorie..." type="text" name="nouvelle_categorie" id="nouvelle_categorie">
                        </div>
                    </div>
                </div>
                <!-- ----------------------------------------- -->

                <!-- The rest of the form -->
                <div class="input-division">
                    <label for="price_per_unit">Prix par unité</label>
                    <input type="number" step="0.01" name="pice_per_unit" id="pice_per_unit">
                </div>
                <div class="input-division">
                    <label for="quantity">Quantité </label>
                    <input type="number" name="quantity" id="quantity">
                </div>
                <div class="input-division">
                    <label for="experation_date">Date d'expiration </label>
                    <input type="date" name="experation_date" id="experation_date">
                </div>
                <input id="acheter_porduit" type="submit" value="Ajouter" name="ajouter_produit">
            </form>

    <?php
    if(isset($_POST['ajouter_produit'])) {
        $id_product_ref = $_POST['productName'];

        if($id_product_ref == 'newProduct'){
            $id_product_ref = $_POST['nouveau_product'];
            $id_category = $_POST['category_name'];

            if($id_category == "newCategory"){
                $id_category = $_POST['nouvelle_categorie'];
                // Ajouter la nouvelle catégorie au categorys:
                $sql_add_category = "INSERT INTO categorys (category_name) VALUES ('".$id_category."')";
                $add_category = mysqli_query($link, $sql_add_category);
                // Take th real id of the new category:
                $get_new_category = "SELECT * FROM categorys WHERE category_name = '".$id_category."'";
                $link_new_category = mysqli_query($link, $get_new_category);
                $data_new_category = mysqli_fetch_assoc($link_new_category);
                $id_category = $data_new_category['id_category'];
            }
            
            // Ajouter le nouveau produit au products_reference:
            $sql_add_product_ref = "INSERT INTO products_reference (product_name, id_category) VALUES ('$id_product_ref', $id_category)";
            $add_product_ref = mysqli_query($link, $sql_add_product_ref);
            // Get the id_product_ref:
            $get_id_product_ref = "SELECT * FROM products_reference WHERE product_name = '$id_product_ref'";
            $link_id_product_ref = mysqli_query($link, $get_id_product_ref);
            $data_id_product_ref = mysqli_fetch_assoc($link_id_product_ref);
            $id_product_ref = $data_id_product_ref['id_product_ref'];
            
        } else {
            // Get the category's name for this product
            $get_category_id = "SELECT * FROM products_reference WHERE id_product_ref = $id_product_ref";
            $link_category_id = mysqli_query($link, $get_category_id);
            $data_id_category = mysqli_fetch_assoc($link_category_id);
            $id_category = $data_id_category['id_category'];
        }

        $price_per_unit = $_POST['pice_per_unit'];
        $quantity = $_POST['quantity'];
        $experation_date = !empty($_POST['experation_date']) ? $_POST['experation_date'] : "NULL";
        
        if($experation_date != "NULL") {
            $sql_add_product = "INSERT INTO products (id_product_ref, id_category, id_responsable, price_per_unit, quantity, experation_date) VALUES ($id_product_ref, $id_category, $id_responsable, $price_per_unit, $quantity, '$experation_date')";
        } else {
            $sql_add_product = "INSERT INTO products (id_product_ref, id_category, id_responsable, price_per_unit, quantity, experation_date) VALUES ($id_product_ref, $id_category, $id_responsable, $price_per_unit, $quantity, NULL)";
        }
        
        // Inserting the new product into the Products table:
        if($add_product = mysqli_query($link, $sql_add_product) && !empty($_POST['pice_per_unit']) && !empty($_POST['quantity'])) {
            // if the product was inserted perfectly, we need to add it to Journal Produit:
            // Adding to Journal Produits:
            // prepare everything we need which is: id_product_ref is here, id_responsable is here, des_src is "Marché", operation_type, operation_date
            // Getting the id, which is the last one in the database
            $get_id_jp = "SELECT MAX(id_product) AS id_product FROM products";
            $link_id_jp = mysqli_query($link, $get_id_jp);
            $data_id_jp = mysqli_fetch_assoc($link_id_jp);
            $id_product = $data_id_jp['id_product'];
            $operation_date = date('Y-m-d');
            $operation_quantity = $quantity;
            $sql_add_journal_product = "INSERT INTO `journal_products`(`id_product`, `id_product_ref`, `operation_date`, `operation_type`, `id_responsable`, `operation_quantity`, `des_src`) 
            VALUES ($id_product, $id_product_ref, '$operation_date', 1, $id_responsable, $operation_quantity, 'Marché')";
            $link_add_journal_product = mysqli_query($link, $sql_add_journal_product);
            ?>
            <div class="inserted-perfectly-container inserted-message">
                <i class="fa-sharp fa-solid fa-circle-check"></i> 
                <div class="text-inserted-perfectly">
                    Le produit a été insérer parfaitement!
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="inserted-wrongly-container inserted-message">
                <i class="fa-sharp fa-solid fa-circle-exclamation"></i>
                <div class="text-inserted-perfectly">
                    Vérifiez les données remplis.
                </div>
            </div>
            <?php
        }
    }
    ?>
            
        </div>
   </div>


    <script>
        function handleOption(e) {
            if(e.target.value == 'newProduct') {
                const newProduct = document.getElementById('new_product_container');
                newProduct.style.display = 'block';
            }
        }

        function handleNewCategory(e) {
            if(e.target.value == 'newCategory') {
                const newProduct = document.getElementById('new_category_container');
                newProduct.style.display = 'block';
            }
        }

        setTimeout(function() {
            var messages = document.getElementsByClassName('inserted-message');
            for (var i = 0; i < messages.length; i++) {
                messages[i].style.display = 'none';
            }
        }, 2500);

    </script>
</body>
</html>