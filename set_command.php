<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['user_id'])) {

    if ($_GET['user_id'] == $_SESSION['user_id']) {

        if ($_GET['action'] == "cart_valid") {
            $user = Users::getUserById($_SESSION['user_id']);

            $userCart = Cart::getInstance()->getUserCart($user);
            if ($userCart != null && $userCart > 0) {
                Kernel::getInstance()->addAlert("info",
                    "Veuillez confirmer votre adresse");
            } else {
                $mess = "Empty or Null cart in set_command.php ";
                Kernel::getInstance()->addLogEvent($mess);
                header('Location: mycart.php');
                exit();
            }
        } else {
            $mess = "action isn't cart_valid ".Kernel::getInstance()->clean($_GET['action']);
            Kernel::getInstance()->addLogEvent($mess);
            header('Location: mycart.php');
            exit();
        }
    } else {
        $mess = "user_id isn't same Session id but ".Kernel::getInstance()->clean($_GET['user_id']);
        Kernel::getInstance()->addLogEvent($mess);
        header('Location: mycart.php');
        exit();
    }
} else {
    $mess = "no action or user_id GET in set_command.php";
    Kernel::getInstance()->addLogEvent($mess);
    header('Location: mycart.php');
    exit();
}
?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center">Choisissez une adresse de livraison</h2>

                        <?php include './templates/Alerts.php'?>

                        <?php
                            $addressesList = Users::getAdressByUser($user);
                            if ($addressesList != null) {
                                foreach ($addressesList as $key => $value) {
                                    echo "Adresse - ".$value['nom']."<br>";
                                    echo $value['num']." ".$value['rue']."<br>";
                                    echo $value['code_postal']." ".$value['ville']."<br>";
                                    echo "<br>";
                                    echo "<a href='set_command_payment.php?user_id=".$user->getId()."&action=paiement'><button type=\"submit\" name=\"submit\" class=\"btn btn-primary\"><i class=\"fas fa-sign-in-alt\"></i> Choisir cette adresse</button></a>";
                                }
                            } else {
                                echo "<p>Vous n'avez pas encore défini d'adresse</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>