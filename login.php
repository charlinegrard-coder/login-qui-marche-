
<?php
require_once "db.php";
session_start();

$pdo = new PDO(
    "mysql:host=localhost;port=8889;dbname=BLOGART26;charset=utf8mb4",
    "root",
    "root",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pseudo = trim($_POST["pseudo"] ?? ""); //récupères ce que l'utilisateur a tapé + supp les espaces inutiles
    $password = $_POST["password"] ?? "";  //pareil pour le mdp mais garde les espaces tapés


    if ($pseudo === "" || $password === "") { //empêche d'envoyer un formulaire vide
        $error = "Tous les champs sont obligatoires.";
    } else {

        $sql = "SELECT numMemb, passMemb FROM MEMBRE WHERE pseudoMemb = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Pseudo incorrect."; //vérifie que l'utilisateur existe et que le mdp correspond
        } elseif ($password !== $user["passMemb"]) {
            $error = "Mot de passe incorrect.";
        } else {
            $_SESSION["user_id"] = $user["numMemb"]; //se souviens que l'utilisateur est co
            $_SESSION["pseudo"] = $pseudo;
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">  <!--pour accents et caractères spéciaux -->
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="login-wrapper">
    <h1>Connexion</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-options">
            <label>
                <input type="checkbox" id="showPassword" onclick="togglePassword()"> Afficher le mot de passe
            </label>
        </div>
    


        <button type="submit">Se connecter</button><!-- bouton pour envoyer le formulaire -->
    </form>
</div>


<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
}
</script>

</body>
<link rel="stylesheet" href="style.css">

</html>
