
<?php
require_once "db.php";
session_start();

$pdo = new PDO(
    "mysql:host=localhost;port=8889;dbname=blogart25;charset=utf8mb4",
    "root",
    "root",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pseudo = trim($_POST["pseudo"] ?? ""); //récupères ce que l'utilisateur a tapé + supp les espaces inutiles
    $password = $_POST["password"] ?? ""; //pareil pour le mdp mais garde les espaces tapés

    if ($pseudo === "" || $password === "") { //empêche d'envoyer un formulaire vide
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Utilisateur introuvable.";
        } elseif ($password !== $user["password"]) { //vérifie que l'utilisateur existe et que le mdp correspond A REMPLACER
            $error = "Mot de passe incorrect.";
        } else {
            $_SESSION["user_id"] = $user["id"]; //se souviens que l'utilisateur est co
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
                <input type="checkbox"> Afficher le mot de passe
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

<!--<div class="login-container">
    <h1>Connexion</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required>

        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Se connecter</button>
    </form>

    <p>
        Pas de compte ?
        <a href="register.php">Inscription</a>
    </p>
</div> -->

</html>
