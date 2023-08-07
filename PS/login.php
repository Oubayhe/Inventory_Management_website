<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style_folder/style.css">
    <link rel="stylesheet" href="./style/login.css">
    <title>Connexion</title>
</head>
<body>
<body>
  <div class="container">
    <div class="image-container">
      <img src="./Inventory_Images/download-removebg-preview.png" alt="Logo">
    </div>
    <div class="form-container">
      <form id="loginForm" action="#" method="post">
        <h3>Connexion</h3>
        <input class="info_input input_loginPage" placeholder="Address Email" type="email" name="email" required> 
        <input class="info_input input_loginPage" placeholder="Mot de Passe" type="password" name="password" required> 
        <input name="submit" class="submit_login input_loginPage" type="submit" value="Se connecter">
      </form>
    </div>
  </div>

    <?php
    if(isset($_POST['submit'])) {
        if(!empty($_POST['email']) && !empty($_POST['password'])) {
            include("connexion.php");
            $email=$_POST["email"];
            $password = $_POST['password'];
            $req = "SELECT * FROM users WHERE email='".$email."' AND password='".$password."'";
            $result=mysqli_query($link, $req);
            if($data=mysqli_fetch_assoc($result)) {
                session_start();
                $_SESSION['id_user'] = $data['id_user'];
                header('location:traitement.php');
            } else {
                echo "<p style='color:red' >L\'email ou le mot de passe est incorrecte</p>";
            }
        } else {
            ?>
            <script>
                alert("Vous n\'avez pas entrez les données demandées");
            </script>
            <?php
        }
    }
    ?>
</body>
</html>