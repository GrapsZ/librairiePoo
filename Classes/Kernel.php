<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Kernel extends KernelSingleton {

    CONST CONFIG_FILE = './Includes/config.json';

    public $itemsTab = [];
    private $settings = [];
    private $connection = null;
    private $title = "Librairie des beaux arts";

    private $alerts = [];

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @param $key
     * @param $service
     */
    public function addItem($key, $service) {
        $this->itemsTab[$key] = $service;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isItemKeyExist($key) {
        return isset($this->itemsTab[$key]);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getItemByKey($key) {
        if ($this->isItemKeyExist($key)) {
            return $this->itemsTab[$key];
        }
        return null;
    }

    /**
     * @param $key
     */
    public function removeItemByKey($key) {
        if ($this->getItemByKey($key)) {
            unset($this->itemsTab[$key]);
        }
    }

    /**
     * @return mixed
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function initConnection() {
        try {
            $pdoInstance = new PDO('mysql:host='.$this->getSettingsByKey('host').';dbname='.$this->getSettingsByKey('db'),
                $this->getSettingsByKey('username'), $this->getSettingsByKey('password'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);

            $pdoInstance->exec("SET CHARACTER SET utf8");
            $pdoInstance->exec("SET NAMES utf8");
            $this->connection = $pdoInstance;

        }
        catch(PDOException $e) {
            $message = "Erreur de base de données.";
            $this->addLogEvent($message);
            die("<h3>".$message."</h3>");
        }
    }

    /**
     * disconnect from database.
     */
    public function destroyConnection() {
        unset($this->connection);
    }

    /**
     * @param $key
     * @return |null
     */
    public function getSettingsByKey($key) {
        if (!isset($this->settings->$key)) return null;
        return $this->settings->$key;
    }

    /**
     * load and read setting files
     */
    public function generateSettings() {
        if (!file_exists(Kernel::CONFIG_FILE)) {
            $message = "Fichier de config inexistant";
            $this->addLogEvent($message);
            return;
        }
        $configContent = file_get_contents(Kernel::CONFIG_FILE);
        $this->settings = json_decode($configContent);
    }

    /**
     * @param $long
     * @return string|null
     * @throws \Exception
     */
    public function generateToken($long){
        if (isset($long)) {
            $token = bin2hex(random_bytes($long));
            return $token;
        }
        return null;
    }

    /**
     * @param $targetEmail
     * @param $subject
     * @param $message
     * @throws Exception
     */
    public function sendEmail($targetEmail, $subject, $message) {
        $mail = new PHPMailer();

        if ($this->getSettingsByKey('mailer_useSMTP')) $mail->isSMTP();
        if (!$this->getSettingsByKey('mailer_useSMTP')) $mail->isMail();

        $mail->Host = $this->getSettingsByKey('mailer_host');
        $mail->SMTPAuth = $this->getSettingsByKey('mailer_smtp_auth');
        $mail->Username = $this->getSettingsByKey('mailer_username');
        $mail->Password = $this->getSettingsByKey('mailer_password');
        $mail->SMTPSecure = $this->getSettingsByKey('mailer_smtp_secure');
        $mail->Port = $this->getSettingsByKey('mailer_port');

        $mail->setFrom($this->getSettingsByKey('mailer_username'), $this->getSettingsByKey('mailer_name'));
        $mail->addAddress($targetEmail);

        $mail->isHTML($this->getSettingsByKey('mailer_ishtml'));
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';

        try {
            $mail->send();
        } catch (Exception $e) {
            $error = "Erreur envoie mail : ".$e->getMessage();
            $this->addLogEvent($error);
        }
    }

    /**
     * @param $event
     */
    public function addLogEvent($event) {
        $time = date("D, d M Y H:i:s");
        $time = "[".$time."] ";

        $event = $time.$event."\n";

        file_put_contents("./logs/library.log", $event, FILE_APPEND);
    }

    /**
     * increment static variable for listing all books
     */
    public function initLibrary() {
        $result = $this->getConnection()->query("SELECT * FROM livres ORDER BY id");

        foreach ($result as $key => $book) {
            $_book = new Books($book->id, $book->titre, $book->descriptif, $book->jaquette, $book->date, $book->genre, $book->format, $book->prix);
            Books::$allBooks[] = $_book;
        }
    }

    /**
     * @param $valeur
     * @return string
     */
    public function clean($valeur) {
        $valeur = addslashes($valeur);
        $valeur = strip_tags($valeur);
        $valeur = mb_convert_encoding($valeur, 'UTF-8');
        return $valeur;
    }

    /**
     * @return mixed
     */
    public function get_ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @param $date
     * @param $before
     * @param $after
     * @return string
     */
    public function alter($date, $before, $after) {
        return DateTime::createFromFormat($before, $date)->format($after);
    }

    /**
     * @param $date
     * @param string $before
     * @return string
     */
    public function toSQL($date, $before = 'd/m/Y') {
        return $this->alter($date, $before, 'Y-m-d H:i:s');
    }

    /**
     * @param $date
     * @param string $before
     * @return string
     */
    public function toHTML($date, $before = 'Y-m-d H:i:s') {
        return $this->alter($date, $before, 'd/m/Y');
    }

    /**
     * @param $date
     * @param string $before
     * @return string
     */
    public function toHTMLviaDate($date, $before = 'Y-m-d') {
        return $this->alter($date, $before, 'd/m/Y');
    }

    /**
     * @return array
     */
    public function getAlerts(){
        return $this->alerts;
    }

    /**
     * type =  danger or warning or success (Bootstrap CSS classes)
     * @param $type
     * @param $message
     */
    public function addAlert($type, $message){
        $alert = [
            'type' => $type,
            'message' => $message
        ];
        array_push($this->alerts, $alert);
    }

    /**
     * @return array|null
     */
    public function getPaymentMode() {
        $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM modes_paiement");
        $stmt->execute();

        if ($stmt->rowCount() >= 1) {
            $result = $stmt->fetchAll();

            $payments = [];
            foreach ($result as $key => $value) {
                $payment = [
                    'nom' => $value->nom
                ];
                $payments[] = $payment;
            }
            return $payments;
        }
        return null;
    }
}