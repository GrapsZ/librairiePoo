<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['user_id']) && isset($_GET['paiement'])) {

    if ($_GET['user_id'] == $_SESSION['user_id']) {

        if ($_GET['paiement'] == 'paypal' || $_GET['paiement'] == 'carte bancaire' || $_GET['paiement'] == 'virement' || $_GET['paiement'] == 'paysafecard') {

            if ($_GET['action'] == "finalize") {
                $user = Users::getUserById(intval($_SESSION['user_id']));
                $payment = Kernel::getInstance()->clean($_GET['paiement']);

                $userCart = Cart::getInstance()->getUserCart($user);
                if ($userCart != null && $userCart > 0) {
                    $cmdInfos = Commands::saveNewCommand($user, $payment);
                    if ($cmdInfos) {
                        Cart::getInstance()->deleteCartById($user);
                        Kernel::getInstance()->addAlert("success",
                            "Votre commande est confirmée. Nous vous remercions et vous souhaitons bonne lecture.");
                        header("Refresh: 2;url=account.php#commands");
                    } else {
                        Kernel::getInstance()->addAlert("danger",
                            "Une erreur s'est produite durant la validation de votre commande.");
                    }
                } else {
                    $mess = "Empty cart in set_command_finalize.php";
                    Kernel::getInstance()->addLogEvent($mess);
                    header('Location: mycart.php');
                    exit();
                }
            } else {
                $mess = "Action isn't finalize but : ".Kernel::getInstance()->clean($_GET['action']);
                Kernel::getInstance()->addLogEvent($mess);
                header('Location: mycart.php');
                exit();
            }
        } else {
            $mess = "payment method not good but : ".Kernel::getInstance()->clean($_GET['paiement']);
            Kernel::getInstance()->addLogEvent($mess);
            header('Location: mycart.php');
            exit();
        }
    } else {
        $mess = "Session isn't same GET user_id ".Kernel::getInstance()->clean($_GET['user_id']);
        Kernel::getInstance()->addLogEvent($mess);
        header('Location: mycart.php');
        exit();
    }
} else {
    $mess = "No action Set in set_command_finalize.php";
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
                        <h2 class="mb-5 text-center">Information de commande</h2>

                        <?php include './templates/Alerts.php'?>

                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>