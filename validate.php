<?php
include "./templates/header.php";
include "./templates/menu.php";

if (isset($_GET['nom']) && isset($_GET['token']) && isset($_GET['action'])) {
    $username = Kernel::getInstance()->clean($_GET['nom']);
    $token = Kernel::getInstance()->clean($_GET['token']);
    $action = Kernel::getInstance()->clean($_GET['action']);

    if ($action == 'inscription') {
        $description = "Validation de votre compte";
        $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND token = ?");
        $stmt->execute([$username, $token]);
        if ($stmt->fetch()) {
            $stmtValide = Kernel::getInstance()->getConnection()->prepare("UPDATE utilisateurs SET isActive = 1, token = '' WHERE nom = ? AND token = ?");
            $stmtValide->execute([$username, $token]);
            Kernel::getInstance()->addAlert("success",
                "Félicitations, votre compte a bien été activé. Vous pouvez vous connecter.");
            header("Refresh: 2;url=connect.php");
        } else {
            Kernel::getInstance()->addAlert("danger",
                "Cette clef de validation n'est plus valide. Votre compte n'est pas activé");
        }
    } elseif ($action == 'recovery') {
        $description = "Réinitialisation de votre mot de passe";
        $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND token = ?");
        $stmt->execute([$username, $token]);
        if ($stmt->rowCount() == 1) {
            $email = $stmt->fetch()->email;
            $passToken = Kernel::getInstance()->generateToken(6);
            $newPass = sha1($passToken);
            $stmtValide = Kernel::getInstance()->getConnection()->prepare("UPDATE utilisateurs SET motdepasse = ?, token = '' WHERE nom = ? AND token = ?");
            $stmtValide->execute([$newPass, $username, $token]);

            $subject = "Librairie - Réinitialisation du mot de passe";
            $body = "Bonjour, $username.
                       <br>Votre mot de passe a bien été changé par un mot de passe provisoire. <br>
                       <br>Mot de passe provisoire : <b>$passToken</b><br>
                       <br>Pensez à changer votre mot de passe.<br>
                       <br>Cordialement, L'équipe Librairie.";
            Kernel::getInstance()->sendEmail($email, $subject, $body);

            Kernel::getInstance()->addAlert("success",
                "Félicitations, votre mot de passe a bien été réinitialisé. Il vous a été envoyé par mail.");
            header("Refresh: 2;url=connect.php");
        } else {
            Kernel::getInstance()->addAlert("danger",
                "Cette clef de validation n'est plus valide. Votre mot de passe n'a pas été modifié.");
        }
    }
} else {
    Kernel::getInstance()->addAlert("danger",
        "Le lien entré est invalide. Veuillez réessayer.");
}
?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center"><?= $description; ?></h2>

                        <?php include './templates/Alerts.php'?>

                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
include "./templates/footer.php";
?>