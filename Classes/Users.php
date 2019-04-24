<?php

class Users {

    private $id;
    private $username;
    private $email;
    private $password;
    private $sex;

    /**
     * Users constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $password
     */
    public function __construct($id, $username, $sex, $email, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->sex = $sex;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param $id
     * @return |null
     */
    public function getRegisterDateById($id) {
        if (isset($id)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT id, date_inscription FROM utilisateurs WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();

                return $result->date_inscription;
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public static function isConnected(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['isConnected']);
    }

    /**
     * @param $password
     * @return bool
     */
    public static function checkPass($password) {
        if (preg_match('/[A-Za-z0-9_]/', $password)) {
            return true;
        } else {
        return false;
        }
    }

    /**
     * @param $str
     * @return bool
     */
    public static function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    /**
     * @param $username
     * @param $sex
     * @param $email
     * @param $password
     * @return bool
     */
    public static function registerNewUser($username, $sex, $email, $password) {
        if (isset($username) && isset($email) && isset($sex) && isset($password)) {
            $checkUser = Kernel::getInstance()->getConnection()->prepare("SELECT nom, email FROM utilisateurs WHERE nom = ? OR email = ?");
            $checkUser->execute([$username, $email]);
            if ($checkUser->fetch() == false) {
                try {
                    Kernel::getInstance()->generateToken(20);

                    $stmt = Kernel::getInstance()->getConnection()->prepare("INSERT INTO utilisateurs (nom, sexe, email, motdepasse, token) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $sex, $email, sha1($password), $token]);
                    $subject = "Librairie - Validation de votre inscription";
                    $body = "Bonjour, $username.
                            <br>Vous venez de vous inscrire sur notre site. Voici vos identifiants :<br>
                            <br><b>Nom de compte :</b> $username<br>
                            <br><b>Adresse email :</b> $email<br><br>
                            Pour valider votre compte, <a href='http://localhost/Librairie/validate.php?nom=".$username."&token=".$token."&action=inscription'>cliquez ici </a>
                            <br>Cordialement, L'équipe Librairie.";
                    Kernel::getInstance()->sendEmail($email, $subject, $body);
                    return true;
                } catch (Exception $e) {
                    $message = "Erreur d'inscription". $e->getMessage();
                    Kernel::getInstance()->addLogEvent($message);
                    die("<h3>$message</h3>");
                }
            }
        }
        return false;
    }

    /**
     * @param $login
     * @param $password
     * @param $remember
     * @return bool
     */
    public static function connectUser($login, $password, $remember) {
        if (isset($login) && isset($password)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE nom = ? OR email = ?");
            $stmt->execute([$login, $login]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();

                $newI = $result->id;
                $newN = $result->nom;
                //$newE = $result->email;
                $newM = $result->motdepasse;
                $Active = $result->isActive;
                if ($newM == sha1($password)) {
                    if ($Active == 1) {
                        $_SESSION['isConnected'] = 1;
                        $_SESSION['user_id'] = $newI;
                        $_SESSION['pseudo'] = $newN;
                        if ($remember) {
                            setcookie('myLibrary', sha1($newN), time() + 86400);
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param $cookie
     */
    public static function connectUserByCookie($cookie) {
        if (isset($cookie)) {

            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE SHA1(nom) = ?");
            $stmt->execute([$cookie]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();

                $newI = $result->id;
                $newN = $result->nom;

                $_SESSION['isConnected'] = 1;
                $_SESSION['user_id'] = $newI;
                $_SESSION['pseudo'] = $newN;
            }
        }
    }

    /**
     * @param Users $user
     * @return array|null
     */
    public static function getAdressByUser(Users $user) {
        if (isset($user)) {
            $idUser = $user->getId();
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM adresses INNER JOIN adresses_infos ON adresses.id = adresses_infos.id_adresse WHERE adresses_infos.id_user = ? ORDER BY adresses.nom");
            $stmt->execute([$idUser]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetchAll();

                $adresses = [];
                foreach ($result as $key => $value) {
                    $address = [
                        'nom' => $value->nom,
                        'num' => $value->num,
                        'rue' => $value->rue,
                        'code_postal' => $value->code_postal,
                        'ville' => $value->ville
                    ];
                    $adresses[] = $address;
                }
                return $adresses;
            }
        }
        return null;
    }

    /**
     * @param $id
     * @return Users|null
     */
    public static function getUserById($id) {
        if (isset($id)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();
                $myUser = new Users($result->id, $result->nom, $result->sexe, $result->email, $result->motdepasse);
                return $myUser;
            }
        }
        return null;
    }

    /**
     * @param $login
     * @param $email
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function recoveryPassword($login, $email) {
        if (isset($login) && isset($email)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND email = ?");
            $stmt->execute([$login, $email]);
            if ($stmt->rowCount() == 1) {
                $token = Kernel::getInstance()->generateToken(20);

                $stmtUp = Kernel::getInstance()->getConnection()->prepare("UPDATE utilisateurs SET token = ? WHERE nom = ? AND email = ?");
                $stmtUp->execute([$token, $login, $email]);

                $subject = "Librairie - Réinitialisation du mot de passe";
                $body = "Bonjour, $login.
                       <br>Vous souhaitez réinitialiser le mot de passe de votre
                       compte. Pour se faire, <a href='http://localhost/Librairie/validate.php?nom=".$login."&token=".$token."&action=recovery'>cliquez ici </a>
                       <br>Cordialement, L'équipe Librairie.";
                Kernel::getInstance()->sendEmail($email, $subject, $body);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $userId
     * @param $newpass
     * @param $oldpass
     * @return string|null
     */
    public function changePassword($userId, $newpass, $oldpass) {
        if (isset($userId) && isset($newpass) && isset($oldpass)) {

            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT motdepasse FROM utilisateurs WHERE id = ?");
            $stmt->execute([$userId]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();

                if ($result->motdepasse == sha1($oldpass)) {
                    $newShaPass = sha1($newpass);

                    $stmt2 = Kernel::getInstance()->getConnection()->prepare("UPDATE utilisateurs SET motdepasse = ? WHERE id = ?");
                    $stmt2->execute([$newShaPass, $userId]);

                    if ($stmt2->rowCount() == 1) {
                        $send = "update";
                        return $send;
                    }
                } else {
                    $send = "nomatch";
                    return $send;
                }
            }
        }
        return null;
    }

    /**
     * @param $userId
     * @param $newmail
     * @param $oldmail
     * @return string|null
     */
    public function changeEmail($userId, $newmail, $oldmail) {
        if (isset($userId) && isset($newmail) && isset($oldmail)) {

            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT email FROM utilisateurs WHERE id = ?");
            $stmt->execute([$userId]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();

                if ($result->email == $oldmail) {

                    $stmt2 = Kernel::getInstance()->getConnection()->prepare("UPDATE utilisateurs SET email = ? WHERE id = ?");
                    $stmt2->execute([$newmail, $userId]);

                    if ($stmt2->rowCount() == 1) {
                        $send = "update";
                        return $send;
                    }
                } else {
                    $send = "nomatch";
                    return $send;
                }
            }
        }
        return null;
    }

    /**
     * @param $userId
     * @param $nom_add
     * @param $num
     * @param $rue
     * @param $code_postal
     * @param $ville
     * @return bool
     */
    public function changeAdress($userId, $nom_add, $num, $rue, $code_postal, $ville) {
        if (isset($userId) && isset($nom_add) && isset($num) && isset($rue) && isset($code_postal) && isset($ville)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM adresses INNER JOIN adresses_infos ON adresses.id = adresses_infos.id_adresse WHERE adresses_infos.id_user = ? ORDER BY adresses.id");
            $stmt->execute([$userId]);

            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();
                $idAdress = $result->id;

                try {
                    $stmt2 = Kernel::getInstance()->getConnection()->prepare("UPDATE adresses SET adresses.nom = ? WHERE adresses.id = ?");
                    $stmt2->execute([$nom_add, $idAdress]);
                    //todo return d'un update ?????????????
                    $stmt3 = Kernel::getInstance()->getConnection()->prepare("UPDATE adresses_infos SET num = ?, rue = ?, code_postal = ?, ville = ? WHERE id_user = ? AND id_adresse = ?");
                    $stmt3->execute([$num, $rue, $code_postal, $ville, $userId, $idAdress]);

                    return true;

                } catch (PDOException $e) {
                    echo "Erreur sql ".$e->getMessage();
                }
            } else {
                $stmtInsert = Kernel::getInstance()->getConnection()->prepare("INSERT INTO adresses (nom) VALUES (?)");
                $stmtInsert->execute([$nom_add]);

                if ($stmtInsert->rowCount() == 1) {
                    $lastIdAdress = Kernel::getInstance()->getConnection()->lastInsertId();

                    $stmt2Insert = Kernel::getInstance()->getConnection()->prepare("INSERT INTO adresses_infos (id_user, id_adresse, num, rue, code_postal, ville) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt2Insert->execute([$userId, $lastIdAdress, $num, $rue, $code_postal, $ville]);

                    if ($stmt2Insert->rowCount() == 1) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}