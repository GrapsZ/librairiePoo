<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['user_id'])) {

    if ($_GET['user_id'] == $_SESSION['user_id']) {

        if ($_GET['action'] == "paiement") {
            $user = Users::getUserById($_SESSION['user_id']);

            $userCart = Cart::getInstance()->getUserCart($user);
            if ($userCart != null && $userCart > 0) {
                Kernel::getInstance()->addAlert("info",
                    "Veuillez sélectionner un mode de paiement");
            } else {
                $mess = "Empty Cart";
                Kernel::getInstance()->addLogEvent($mess);
                header('Location: mycart.php');
                exit();
            }
        } else {
            $mess = "Bad action set ".Kernel::getInstance()->clean($_GET['action']);
            Kernel::getInstance()->addLogEvent($mess);
            header('Location: mycart.php');
            exit();
        }
    } else {
        $mess = "User id != session userId ".Kernel::getInstance()->clean($_GET['user_id'])." ".$_SESSION['user_id'];
        Kernel::getInstance()->addLogEvent($mess);
        header('Location: mycart.php');
        exit();
    }
} else {
    $mess = "No GET action or GET paiement in set_command_payment.php";
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
                        <h2 class="mb-5 text-center">Choisissez un mode de paiement</h2>

                        <?php include './templates/Alerts.php'?>

                            <?php
                            $paymentList = Kernel::getInstance()->getPaymentMode();
                            if ($paymentList != null) {
                                foreach ($paymentList as $key => $value) {
                                    echo "<a href='set_command_finalize.php?user_id=".$user->getId()."&paiement=".$value['nom']."&action=finalize'><button type=\"submit\" name=\"submit\" class=\"btn btn-primary\"><i class=\"fas fa-sign-in-alt\"></i> Choisir ".$value['nom']."</button></a><br>";
                                    echo "<br>";
                                }
                            } else {
                                echo "<p>Aucun mode de paiement n'est défini sur notre site</p>";
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