<?php
include "./templates/header.php";
include "./templates/menu.php";

if (Users::isConnected()) {
    header('Location: account.php');
    exit();
}

if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        Kernel::getInstance()->addAlert("danger",
            "Veuillez saisir un Nom d'utilisateur.");
    } elseif (empty($_POST['mail'])) {
        Kernel::getInstance()->addAlert("danger",
            "Veuillez saisir une adresse email.");
    } elseif (empty($_POST['sexH']) && empty($_POST['sexF'])) {
        Kernel::getInstance()->addAlert("danger",
            "Veuillez définir votre sexe.");
    } elseif (empty($_POST['password'])) {
        Kernel::getInstance()->addAlert("danger",
            "Veuillez saisir un mot de passe.");
    } else {
        if (($_POST['password']) != $_POST['password-confirm']) {
            Kernel::getInstance()->addAlert("danger",
                "Les deux mots de passe ne sont pas identiques.");
        } elseif (!Users::checkPass($_POST['password'])) {
            Kernel::getInstance()->addAlert("danger",
                "Votre mot de passe doit faire entre 6 et 12 caractères, et ne doit pas contenir de caractères spéciaux.");
        } elseif (!Users::valid_email($_POST['mail'])) {
            Kernel::getInstance()->addAlert("danger",
                "Votre adresse email est invalide.");
        } else {
            if ($_POST['sexF'] == "on") {
                $sex = "f";
            } elseif($_POST['sexM'] == "on") {
                $sex = "m";
            }
            if (Users::registerNewUser(Kernel::getInstance()->clean($_POST['username']), Kernel::getInstance()->clean($sex), Kernel::getInstance()->clean($_POST['mail']), Kernel::getInstance()->clean($_POST['password']))) {
                Kernel::getInstance()->addAlert("success",
                    "Votre inscription est validée. Un email de confirmation vous a été envoyé.");
            } else {
                Kernel::getInstance()->addAlert("danger",
                    "Inscription refusée. Nom d'utilisateur ou adresse email déjà utilisé.");
            }
        }
    }
}

?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center">Inscription</h2>

                        <?php include './templates/Alerts.php'?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                <label for="username">Nom d'utilisateur  <span class="text-danger">*</span></label>
                                <input type="text" name="username" placeholder="Choissiez un nom de compte" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="homme" name="sexH" class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="homme">Homme</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="femme" name="sexF" class="custom-control-input">
                                    <label class="custom-control-label" for="femme">Femme</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-envelope-open-text"></i></span>
                                <label for="mail">Adresse email  <span class="text-danger">*</span></label>
                                <input type="email" name="mail" placeholder="Saisissez votre email" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                <label for="">Mot de passe  <span class="text-danger">*</span></label>
                                <input type="password" name="password" placeholder="Choissiez un mot de passe (6-12 caractères)" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                <label for="password-confirm">Confirmation du mot de passe  <span class="text-danger">*</span></label>
                                <input type="password" name="password-confirm" placeholder="Confirmez votre mot de passe" value="" class="form-control"/>
                            </div>
                            <span class="badge badge-danger">Note :</span><p>En vous inscrivant vous acceptez les termes d'utilisation</p>
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-share"></i> S'inscrire</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>