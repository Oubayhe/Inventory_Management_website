<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style_employee/waste_departement.css">
    <title>DOD</title>
</head>
<body>
    <div class="big-container">
        <h2 class="page-title">Produits à disposer</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Date d'expiration</th>
                    <th>Date de disposition</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('connexion.php');
                $today = date('Y-m-d');
                $get_all_waste = "SELECT wd.id_product, wd.id_responsable, pr.product_name, c.category_name, p.experation_date, res.stock_name, wd.disposition_date
                FROM waste_departement wd JOIN products p ON p.id_product = wd.id_product
                JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
                JOIN categorys c ON c.id_category = p.id_category
                JOIN responsables res ON res.id_responsable = wd.id_responsable
                WHERE wd.disposition_date >= '$today' OR wd.disposition_date IS NULL
                ORDER BY wd.id_product DESC";
                $link_all_waste = mysqli_query($link, $get_all_waste);
                while($data_all_waste = mysqli_fetch_assoc($link_all_waste)){
                    $isDispoDate = ($data_all_waste ==  NULL) ? 'nullDate' : 'notNullDate';
                    // if the date hasn't been set yet, then there is not need to show the form, but insteade we need to show the disposition date
                    ?>
                    <tr>
                        <td><?php echo $data_all_waste['product_name']?></td>
                        <td><?php echo $data_all_waste['category_name']?></td>
                        <td><?php echo $data_all_waste['stock_name']?></td>
                        <td><?php echo $data_all_waste['experation_date']?></td>
                        <td>
                        <?php
                            if($data_all_waste['disposition_date'] ==  NULL){
                                ?>
                            <form method="post" action="update_disposition_date.php">
                                <input type="hidden" name="id_responsable" value="<?php echo $data_all_waste['id_responsable']?>">
                                <input type="hidden" name="id_product" value="<?php echo $data_all_waste['id_product']?>">
                                <input class="disposition_date" name="disposition_date" type="date" placeholder="Régler la date de disposition" required>
                                <input class="setDispoDate" name="setDispoDate" type="submit" value="Entrer">
                            </form> 
                            <?php
                            } else {echo $data_all_waste['disposition_date'];}
                            ?>
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