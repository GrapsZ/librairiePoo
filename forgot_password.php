<?php
include "./templates/header.php";
include "./templates/menu.php";

if (Users::isConnected()) {
    header('Location: account.php');
    exit();
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['email'])) {
        $name = Kernel::getInstance()->clean($_POST['username']);
        $email = Kernel::getInstance()->clean($_POST['email']);

        if (Users::recoveryPassword($name, $email)) {
            Kernel::getInstance()->addAlert("success",
                "Félicitations, la procèdure est en cours. Redirection dans quelques secondes...");
            header("Refresh: 2;url=connect.php");
        } else {
            Kernel::getInstance()->addAlert("danger",
                "Les identifiants saisis sont incorrects.");
        }
    } else {
        Kernel::getInstance()->addAlert("danger",
            "Veuillez remplir tous les champs.");
    }
}

?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center">Réinitialisation du mot de passe</h2>

                        <?php include './templates/Alerts.php'?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                <label for="username">Nom de compte  <span class="text-danger">*</span></label>
                                <input type="text" name="username" placeholder="Saisissez votre nom de compte" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-envelope-open-text"></i></span>
                                <label for="email">Adresse email  <span class="text-danger">*</span></label>
                                <input type="email" name="email" placeholder="Saisissez votre adresse email" value="" class="form-control"/>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-random"></i> Réinitialiser</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>