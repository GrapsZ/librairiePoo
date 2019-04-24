<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
} else {
    $username = Kernel::getInstance()->clean($_SESSION['pseudo']);
    $idUser = Kernel::getInstance()->clean($_SESSION['user_id']);

    if (Users::getUserById($idUser) != null) {
        $user = Users::getUserById($idUser);

        $adress = Users::getAdressByUser($user);
    }
}

?>

    <main role="main">
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div id="user-profile-2" class="user-profile">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-18">
                                    <li class="active">
                                        <a data-toggle="tab" href="#home">
                                            <i class="green ace-icon fa fa-user bigger-120"></i>
                                            Mon profil
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#commands">
                                            <i class="orange ace-icon fas fa-shopping-basket bigger-120"></i>
                                            Mes commandes
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content no-border padding-24">
                                    <div id="home" class="tab-pane in active">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-3 center">
                                                <span class="profile-picture">
                                                    <?php if ($user->getSex() == "h"): ?>
                                                        <img class="editable img-responsive" alt="Homme" id="avatar2" src="./Assets/images/garcon.jpg">
                                                    <?php else: ?>
                                                        <img class="editable img-responsive" alt="Femme" id="avatar2" src="./Assets/images/fille.jpg">
                                                    <?php endif; ?>
                                                </span>

                                                <div class="space space-4"></div>

                                                <a href="edit_account.php?action=changePassword&user_id=<?= $user->getId(); ?>" class="btn btn-sm btn-block btn-success">
                                                    <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                                    <span class="bigger-110">Editer mon mot de passe</span>
                                                </a>

                                                <a href="edit_account.php?action=changeEmail&user_id=<?= $user->getId(); ?>" class="btn btn-sm btn-block btn-primary">
                                                    <i class="ace-icon far fa-envelope bigger-110"></i>
                                                    <span class="bigger-110">Editer mon email</span>
                                                </a>

                                                <a href="edit_account.php?action=changeAddress&user_id=<?= $user->getId(); ?>" class="btn btn-sm btn-block btn-info">
                                                    <i class="ace-icon fa fa-map-marker bigger-110"></i>
                                                    <span class="bigger-110">Editer mon adresse</span>
                                                </a>
                                            </div>

                                            <div class="col-xs-12 col-sm-9">
                                                <h4 class="blue">
                                                    <span class="middle">Bonjour, <?= $user->getUsername(); ?></span>
                                                </h4>

                                                <div class="profile-user-info">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Nom de compte : </div>

                                                        <div class="profile-info-value">
                                                            <span><?= $user->getUsername(); ?></span>
                                                        </div>
                                                    </div>

                                                    <?php if ($adress != null && $adress > 0) {
                                                        foreach ($adress as $key => $value) {

                                                        ?>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Adresse :  </div>

                                                            <div class="profile-info-value">
                                                                <i class="fa fa-map-marker light-orange bigger-110"></i>
                                                                <span><?= $value['nom']; ?></span>
                                                                <span> <?= $value['num']. " ".$value['rue']; ?></span>
                                                                <span> <?= $value['code_postal']. " ".$value['ville']; ?></span>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                        } else { ?>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Adresse : </div>

                                                            <div class="profile-info-value">
                                                                <i class="fa fa-map-marker light-orange bigger-110"></i>
                                                                <span>Veuillez définir votre adresse</span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Date d'inscription : </div>

                                                        <div class="profile-info-value">
                                                            <span><?= Kernel::getInstance()->toHTML($user->getRegisterDateById($user->getId())); ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="hr hr-8 dotted"></div>

                                                <div class="profile-user-info">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Mon email : </div>

                                                        <div class="profile-info-value">
                                                            <div class="profile-info-value">
                                                                <span><?= $user->getEmail(); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <i class="middle ace-icon fab fa-facebook-square bigger-150 blue"></i>
                                                        </div>

                                                        <div class="profile-info-value">
                                                            <a href="#">Lier Facebook</a>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <i class="middle ace-icon fab fa-twitter-square bigger-150 light-blue"></i>
                                                        </div>

                                                        <div class="profile-info-value">
                                                            <a href="#">Lier Twitter</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="commands" class="tab-pane">
                                        <div class="profile-feed row">
                                            <div class="col-sm-12">
                                                <?php
                                                $tabCommands = Commands::getCommandByUser($user);
                                                    if ($tabCommands != null) {
                                                        foreach ($tabCommands as $key => $value) {
                                                            echo "<p>Commande numéro <b><a href='command.php?id=".$value->getId()."'>".$value->getId()."</a></b> passée le : <b>" .Kernel::getInstance()->toHTML($value->getDate())."</b><br></p>";
                                                        }
                                                    } else {
                                                        echo "<p>Vous n'avez pas encore passé de commande.</p>";
                                                    }
                                                 ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
include "./templates/footer.php";
?>