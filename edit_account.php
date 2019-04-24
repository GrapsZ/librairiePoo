<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if ($_GET['user_id'] == $_SESSION['user_id']) {
        $user = Users::getUserById($_SESSION['user_id']);
        $step = null;

        if ($_GET['action'] == 'changePassword') {
            if (isset($_POST['submit'])) {
                //echo "changeMDP";
                if (!empty($_POST['oldpassword']) && !empty($_POST['password']) && !empty($_POST['confirmpassword'])) {

                    if ($_POST['oldpassword'] == $_POST['password']) {
                        Kernel::getInstance()->addAlert("danger",
                            "Vous ne pouvez pas utiliser le même mot de passe que l'ancien.");
                    } elseif ($_POST['password'] != $_POST['confirmpassword']) {
                        Kernel::getInstance()->addAlert("danger",
                            "Votre nouveau mot de passe et sa confirmation ne sont pas identiques.");
                    } else {
                        $oldpass = Kernel::getInstance()->clean($_POST['oldpassword']);
                        $newpass = Kernel::getInstance()->clean($_POST['password']);
                        $userId = $user->getId();

                        if ($user->checkPass($newpass)) {
                            $updatePass = $user->changePassword($userId, $newpass, $oldpass);
                            if ($updatePass == "update") {
                                Kernel::getInstance()->addAlert("success",
                                    "Votre mot de passe a bien été modifié.");
                                header("Refresh: 2;url=account.php");
                            } elseif($updatePass == "nomatch") {
                                Kernel::getInstance()->addAlert("danger",
                                    "Votre ancien mot de passe n'est pas valide.");
                            } else {
                                Kernel::getInstance()->addAlert("danger",
                                    "Une erreur s'est produite lors de la mise à jour de votre mot de passe.");
                            }
                        } else {
                            Kernel::getInstance()->addAlert("danger",
                                "Votre mot de passe doit faire entre 6 et 12 caractères, et ne doit pas contenir de caractères spéciaux.");
                        }
                    }
                } else {
                    Kernel::getInstance()->addAlert("danger",
                        "Veuillez remplir tous les champs.");
                }
            }
            $step = 'changePassword';
        } elseif ($_GET['action'] == 'changeEmail') {
            if (isset($_POST['submit'])) {

                if (!empty($_POST['oldemail']) && !empty($_POST['email']) && !empty($_POST['confirmemail'])) {

                    if ($_POST['oldemail'] == $_POST['email']) {
                        Kernel::getInstance()->addAlert("danger",
                            "Vous ne pouvez pas utiliser le même email que l'ancien.");
                    } elseif ($_POST['email'] != $_POST['confirmemail']) {
                        Kernel::getInstance()->addAlert("danger",
                            "Votre nouvel email et sa confirmation ne sont pas identiques.");
                    } else {
                        $oldmail = Kernel::getInstance()->clean($_POST['oldemail']);
                        $newmail = Kernel::getInstance()->clean($_POST['email']);
                        $userId = $user->getId();

                        if ($user->checkPass($newmail)) {
                            $updateEmail = $user->changeEmail($userId, $newmail, $oldmail);
                            if ($updateEmail == "update") {
                                Kernel::getInstance()->addAlert("success",
                                    "Votre email a bien été modifié.");
                                header("Refresh: 2;url=account.php");
                            } elseif($updateEmail == "nomatch") {
                                Kernel::getInstance()->addAlert("danger",
                                    "Votre ancien email n'est pas valide.");
                            } else {
                                Kernel::getInstance()->addAlert("danger",
                                    "Une erreur s'est produite lors de la mise à jour de votre email.");
                            }
                        } else {
                            Kernel::getInstance()->addAlert("danger",
                                "Votre email n'est pas un email valide.");
                        }
                    }
                } else {
                    Kernel::getInstance()->addAlert("danger",
                        "Veuillez remplir tous les champs.");
                }
            }
            $step = 'changeEmail';
        } elseif ($_GET['action'] == 'changeAddress') {
            if (isset($_POST['submit'])) {

                if (!empty($_POST['adresse']) && !empty($_POST['numero']) && !empty($_POST['rue']) && !empty($_POST['code_postal']) && !empty($_POST['ville'])) {

                    $nom_add = Kernel::getInstance()->clean($_POST['adresse']);
                    $num = Kernel::getInstance()->clean($_POST['numero']);
                    $rue = Kernel::getInstance()->clean($_POST['rue']);
                    $code_postal = Kernel::getInstance()->clean($_POST['code_postal']);
                    $ville = Kernel::getInstance()->clean($_POST['ville']);
                    $userId = $user->getId();

                    $updateAdress = $user->changeAdress($userId, $nom_add, $num, $rue, $code_postal, $ville);

                    if ($updateAdress) {
                        Kernel::getInstance()->addAlert("success",
                            "Votre adresse a bien été éditée / ajoutée.");
                        header("Refresh: 2;url=account.php");
                    } else {
                        Kernel::getInstance()->addAlert("danger",
                            "Une erreur s'est produite lors de la mise à jour de votre adresse.");
                    }
                } else {
                    Kernel::getInstance()->addAlert("danger",
                        "Veuillez remplir tous les champs.");
                }
            }
            $step = 'changeAddress';
        } else {
            $mess = "Bad action in edit_account.php";
            Kernel::getInstance()->addLogEvent($mess);
            header('Location: index.php');
        }
    } else {
        $mess = "Session user_id isn't same GET user_id in edit_account.php";
        Kernel::getInstance()->addLogEvent($mess);
        header('Location: index.php');
    }
}
?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <?php include './templates/Alerts.php'?>
                        <?php if ($step == 'changePassword'): ?>
                        <h2 class="mb-5 text-center">Editer mon mot de passe</h2>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="username">Ancien mot de passe</label>
                                    <input type="password" name="oldpassword" placeholder="Saisissez votre ancien mot de passe" value="" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="password">Mot de passe</label>
                                    <input type="password" name="password" placeholder="Saisissez votre mot de passe" value="" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="password">Confirmation du mot de passe</label>
                                    <input type="password" name="confirmpassword" placeholder="Confirmez votre mot de passe" value="" class="form-control"/>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Valider les changements</button>
                            </form>

                        <?php elseif ($step == 'changeEmail'): ?>
                            <h2 class="mb-5 text-center">Editer mon email</h2>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <label for="oldemail">Ancien email</label>
                                    <input type="email" name="oldemail" placeholder="Saisissez votre ancien email" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="email">Nouveau email</label>
                                    <input type="email" name="email" placeholder="Saisissez votre nouvel email" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="confirmemail">Confirmation du nouveau email</label>
                                    <input type="email" name="confirmemail" placeholder="Confirmez votre nouvel email" value="" class="form-control"/>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Valider les changements</button>
                            </form>

                        <?php elseif ($step == 'changeAddress'): ?>
                            <h2 class="mb-5 text-center">Editer mon adresse</h2>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <label for="adresse">Nom de votre adresse</label>
                                    <input type="text" name="adresse" placeholder="Saisissez le nom de votre adresse" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <label for="numero">Numéro</label>
                                    <input type="number" name="numero" placeholder="Saisissez votre numéro" min="1" max="1000" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <label for="rue">Rue</label>
                                    <input type="text" name="rue" placeholder="Saisissez votre rue" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <label for="code_postal">Code postal</label>
                                    <input type="number" name="code_postal" placeholder="Saisissez votre code postal" min="1" max="99999" value="" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                    <label for="ville">Ville</label>
                                    <input type="text" name="ville" placeholder="Saisissez votre ville" value="" class="form-control"/>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Valider les changements</button>
                            </form>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>