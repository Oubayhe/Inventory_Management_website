<?php
include('connexion.php');
session_start();
$id_user = $_SESSION['id_user'];
if(isset($_POST['submit_demande'])) {
    $_SESSION['id_product'] = $_POST['idProduct'];
    $id_product = $_SESSION['id_product'];

    // Get id_responsable
    $get_id_responsable = "SELECT * FROM users WHERE id_user = $id_user";
    $link_id_responsable = mysqli_query($link, $get_id_responsable);
    $data_id_responsable = mysqli_fetch_assoc($link_id_responsable);
    $id_responsable = $data_id_responsable['id_responsable'];
    // ------------------

    // WE have to select the product id ref, and name its experation_date, price_per_unit, and category name
    $get_product_infos = "SELECT p.id_product_ref, pr.product_name, c.category_name, p.price_per_unit, p.experation_date
    FROM products p
    JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
    JOIN categorys c ON c.id_category = p.id_category
    WHERE id_product = $id_product";
    $link_prodct_info = mysqli_query($link, $get_product_infos);
    $data_product_info = mysqli_fetch_assoc($link_prodct_info);

    $id_product_ref = $data_product_info['id_product_ref'];
    $product_name = $data_product_info['product_name'];
    $category_name = $data_product_info['category_name'];
    $experation_date = $data_product_info['experation_date'];
    $price_per_unit = $data_product_info['price_per_unit'];
    // -----------------------------------------------------------------------------------------------------

    // Get the necessary Quantity
    $get_total_quantity = ($experation_date == null) ?
    "SELECT SUM(quantity) as total_quantity
    FROM products WHERE id_product_ref = $id_product_ref
                    AND experation_date IS NULL
                    AND price_per_unit = $price_per_unit
                    AND id_responsable = $id_responsable" :
    "SELECT SUM(quantity) as total_quantity
    FROM products WHERE id_product_ref = $id_product_ref
                    AND experation_date = '$experation_date'
                    AND id_responsable = $id_responsable
                    AND price_per_unit = $price_per_unit";
    $link_total_quantity = mysqli_query($link, $get_total_quantity);
    $data_total_quantity = mysqli_fetch_assoc($link_total_quantity);
    $quantity = $data_total_quantity['total_quantity'];
    // --------------------------

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style_employee/envoyer_demande.css">
    <title>Demander Produit</title>
</head>
<body>
   <div class="big-container">
        <div class="main-page">
            <div class="titre-acheter-produit">
                La carte de la demande
            </div>
            <div class="request-box">
                <form action="traitement_envoyer_par_emp.php" method="post">
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
                    <input type="submit" value="Envoyer" name="envoyer_demande_emp" id="envoyer_demande_emp">
                </form>
            </div>
        </div>
   </div>

   

    
</body>
</html>