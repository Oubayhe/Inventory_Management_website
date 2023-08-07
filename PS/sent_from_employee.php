<?php
include('connexion.php');
session_start();
$id_user = $_SESSION['id_user'];

$get_rbe = "SELECT pr.product_name,
c.category_name,
rbe.quantity_requested,
rbe.processing_status,
p.experation_date
FROM requested_by_employee rbe
JOIN products p ON p.id_product = rbe.id_product
JOIN products_reference pr ON pr.id_product_ref = p.id_product_ref
JOIN categorys c ON c.id_category = p.id_category
WHERE rbe.id_user = $id_user
ORDER BY rbe.id_request_employee DESC";
$link_rbe = mysqli_query($link, $get_rbe);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./style_employee/sent_from_employee.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des produits</title>
</head>
<body>
    <div class="big_container">
        <div class="top-bar">
            <div class="welcomeMessage">
            </div>
            <div class="configurations display-flex">
                <div class="voirDemandeEnvoye">
                    <!-- A table of the resquests sent by the employee -->
                    <a href="employee.php" class="display-flex" id="demandeEnv">
                        <div class="text">Le stock</div>
                        <div class="icon"><i class="fa-solid fa-warehouse"></i></div>
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
                    <th>Etat du traitement</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($data_rbe = mysqli_fetch_assoc($link_rbe)){
                    ?>
                    <tr>
                        <td><?php echo $data_rbe['product_name']  ?></td>
                        <td><?php echo $data_rbe['category_name']  ?></td>
                        <td><?php echo $data_rbe['quantity_requested']  ?></td>
                        <td><?php echo $data_rbe['experation_date']  ?></td>
                        <td><?php
                            if(($data_rbe['processing_status']) == 0) {
                                echo '<span class="cours"><i class="fa-solid fa-spinner"></i> En cours du traitement</span>';
                            } else if(($data_rbe['processing_status']) == 1) {
                                echo '<span class="rejected"><i class="fa-solid fa-circle-xmark"></i> Refusée</span>';
                            }else if(($data_rbe['processing_status']) == 2){
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