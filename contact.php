<?php
include "./templates/header.php";
include "./templates/menu.php";

# Liste des questions avec leurs différentes réponses possibles
$liste_questions = array(
    'question1' => array(
        'question' => "Quelle est la couleur du cheval blanc ?",
        'reponses' => array('blanc', 'blanche', 'neige', 'clair')
    ),
    'question2' => array(
        'question' => "Combien font deux + quatre ?",
        'reponses' => array('6', 'six')
    ),
    'question3' => array(
        'question' => "Combien font neuf - cinq ?",
        'reponses' => array('4', 'quatre')
    ),
    'question4' => array(
        'question' => "Quelle est la couleur du soleil ?",
        'reponses' => array('jaune', 'orange', 'feu')
    ),
    'question5' => array(
        'question' => "Vendons-nous des livres sur notre site ?",
        'reponses' => array('oui', 'ouais')
    )
);

# Sélection d'une question à poser au hasard
$id_question_posee = array_rand($liste_questions);

if (!isset($_SESSION['captcha'])) {
    # Mémorisation de la question posée à l'utilisateur dans la session
    $_SESSION['captcha']['id_question_posee'] = $id_question_posee;
} else {
    $id_question_posee = $_SESSION['captcha']['id_question_posee'];
}

if (isset($_POST['send'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['objet']) && !empty($_POST['message']) && !empty($_POST['captcha'])) {
        # On récupère l'identifiant (clé) de la question posée dans la session
        $id_question_posee_session = $_SESSION['captcha']['id_question_posee'];
        # On récupère la réponse de l'utlisateur
        $reponse_utilisateur = strtolower($_POST['captcha']);
        # Vérification de la réponse : si la réponse de l'utilisateur n'est pas dans la liste des réponses exactes, on affiche un message d'erreur
        if(!in_array($reponse_utilisateur, $liste_questions[$id_question_posee_session]['reponses'])) {
            Kernel::getInstance()->addAlert("danger",
                "La réponse de la question de sécurité n'est pas bonne.");
                unset($_SESSION['captcha']);
            header("Refresh: 2;url=contact.php");
        } else {
            $name = Kernel::getInstance()->clean($_POST['username']);
            $email = Kernel::getInstance()->clean($_POST['email']);
            $objet = Kernel::getInstance()->clean($_POST['objet']);
            $message = Kernel::getInstance()->clean($_POST['message']);

            $objet = "Nouveau message de contact - $objet";

            $body = "Bonjour,<br>
               Un nouveau message est arrivé depuis le formulaire de contact.<br>
               Expéditeur : <b>$name</b> <br>
               Email : <b>$email</b> <br>
               Voici le contenu du message : <br>
               <pre>$message</pre>
               <br>Cordialement, L'équipe Librairie.";
            Kernel::getInstance()->sendEmail(Kernel::getInstance()->getSettingsByKey('mailer_username'), $objet, $body);

            if (isset($_POST['copy']) && Kernel::getInstance()->clean($_POST['copy']) == "on") {
                $objet = "Copie de votre message : $objet";

                $body = "Bonjour, $name<br>
               Voici la copie de votre message envoyé via notre formulaire de contact.<br>
               <pre>$message</pre>
               <br>Cordialement, L'équipe Librairie.";
                Kernel::getInstance()->sendEmail($email, $objet, $body);
            }
            Kernel::getInstance()->addAlert("success",
                "Votre message nous a bien été envoyé. Nous vous apporterons une réponse prochainement.");

            unset($_SESSION['captcha']);
        }
    } else {
        Kernel::getInstance()->addAlert("warning",
            "Veuillez remplir tous les champs.");
    }
}
?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center">Nous contacter</h2>

                        <?php include './templates/Alerts.php'?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                <label for="username">Votre nom</label>
                                <input type="text" name="username" placeholder="Saisissez votre nom" value="" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-at"></i></span>
                                <label for="email">Votre adresse email</label>
                                <input type="text" name="email" placeholder="Saisissez votre email" value="" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <span class="input-group-addon"><i class="far fa-newspaper"></i></span>
                                <label for="objet">Objet du message</label>
                                <input type="text" name="objet" placeholder="Objet du message" value="" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-comment-alt"></i></span>
                                <label for="message">Votre message</label>
                                <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea"></textarea>
                            </div>

                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-question"></i></span>
                                <label for="captcha">Question : <b><?= $liste_questions[$id_question_posee]['question']; ?></b></label>
                                <input type="text" name="captcha" value="" class="form-control md-textarea"/>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="copy" class="custom-control-input" id="copy" checked="">
                                    <label class="custom-control-label" for="copy">M'envoyer une copie ?</label>
                                </div>
                            </div>
                            <button type="submit" name="send" class="btn btn-primary"><i class="fas fa-envelope-open"></i> Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>