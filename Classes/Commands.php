<?php

class Commands {

    private $id;
    private $date;
    private $user_id;

    private static $commands = [];

    public function __construct($id, $user_id, $date) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * @return array
     */
    public static function getCommands() {
        return self::$commands;
    }

    /**
     * @param array $commands
     */
    public static function setCommands($commands) {
        self::$commands = $commands;
    }

    /**
     * @param $id
     * @return array|null
     */
    public static function getCommandById($id) {
        if (isset($id)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM commandes INNER JOIN contenu_commande ON commandes.id = contenu_commande.id_commande INNER JOIN livres ON contenu_commande.id_livres = livres.id WHERE commandes.id = ? ORDER BY commandes.id");
            $stmt->execute([$id]);

            if ($stmt->rowCount() >= 1) {
                $result = $stmt->fetchAll();

                if ($result[0]->id_user == $_SESSION['user_id']) {
                    $books = [];
                    foreach ($result as $key => $value) {
                        $infosBook = [
                            'book_id' => $value->id_livres,
                            'count' => $value->quantite
                        ];
                        $books[] = $infosBook;
                    }
                    return $books;
                }
                return null;
            }
        }
        return null;
    }

    /**
     * @param Users $user
     * @return array|null
     */
    public static function getCommandByUser(Users $user) {
        if (isset($user)) {
            $userId = $user->getId();

            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM commandes INNER JOIN contenu_commande ON commandes.id = contenu_commande.id_commande WHERE commandes.id_user = ? GROUP BY commandes.id");
            $stmt->execute([$userId]);

            if ($stmt->rowCount() >= 1) {
                $result = $stmt->fetchAll();

                foreach ($result as $key => $value) {
                    $command = new Commands($value->id, $value->id_user, $value->date);
                    self::$commands[] = $command;
                }
                return self::$commands;
            }
        }
        return null;
    }

    /**
     * @param $user
     * @param $payment
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function saveNewCommand($user, $payment) {
        if (isset($user) && isset($payment)) {

            $userCart = Cart::getInstance()->getUserCart($user);
            if ($userCart != null && $userCart > 0) {
                $pmt = Kernel::getInstance()->clean($payment);
                $idUser = $user->getId();

                $stmt = Kernel::getInstance()->getConnection()->prepare("INSERT INTO commandes (id_user, type_paiement) VALUES (?, ?)");
                $stmt->execute([$idUser, $pmt]);

                if ($stmt->rowCount() == 1 ) {
                    $lastIdCommand = Kernel::getInstance()->getConnection()->lastInsertId();

                    foreach ($userCart as $key => $value) {
                        $stmt3 = Kernel::getInstance()->getConnection()->prepare("INSERT INTO contenu_commande (id_commande, id_livres, quantite) VALUES (?, ?, ?)");
                        $stmt3->execute([$lastIdCommand, $value['book_id'], $value['count']]);
                    }
                    if ($stmt3->rowCount() >= 1) {

                        $username = $user->getUsername();
                        $email = $user->getEmail();
                        $subject = "Librairie - Commande numéro $lastIdCommand validée";

                        $body = "Bonjour, $username.
                               <br>Votre commande a bien été validée. Retrouvez les détails 
                               de vos achats.<br>
                               Pour se faire, <a href='http://localhost/Librairie/command.php?id=".$lastIdCommand."'>cliquez ici </a>
                               <br>Cordialement, L'équipe Librairie.";
                        Kernel::getInstance()->sendEmail($email, $subject, $body);
                        return true;
                    }
                }
            }
        }
        return false;
    }
}