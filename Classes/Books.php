<?php

class Books {

    private $title;
    private $date;
    private $description;
    private $category;
    private $format;
    private $price;
    private $id;
    private $jaquette;

    public static $allBooks = [];

    /**
     * Books constructor.
     * @param $id
     * @param $title
     * @param $description
     * @param $date
     * @param $category
     * @param $format
     * @param $price
     */

    public function __construct($id, $title, $description, $jaquette, $date, $category, $format, $price) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->jaquette = $jaquette;
        $this->date = $date;
        $this->category = $category;
        $this->format = $format;
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param $id
     * @return string
     */
    public function getAuthorById($id) {
        if (isset($id)) {
            $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT nom as authName FROM auteurs INNER JOIN auteurs_livres ON auteurs.id = auteurs_livres.id_auteur WHERE auteurs_livres.id_livre = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() >= 1) {
                $result = $stmt->fetch();

                return $result->authName;
            }
        }
        return "Auteur inconnu";
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
    public function getJaquette() {
        return $this->jaquette;
    }

    /**
     * @param mixed $jaquette
     */
    public function setJaquette($jaquette) {
        $this->jaquette = $jaquette;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @return array
     */
    public static function getAllBooks() {
        return self::$allBooks;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public static function getBookById($id) {
        foreach (self::$allBooks as $book) {
            if ($id == $book->getId()) {
                return $book;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getBestSellers() {
        $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT id_livres, SUM(quantite) AS bookQ FROM contenu_commande GROUP BY id_livres ORDER BY bookQ DESC LIMIT 3");
        $stmt->execute();

        $result = $stmt->fetchAll();
        $tab = [];

        if (count($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {
                $tab[] = Books::getBookById($result[$i]->id_livres);
            }
            return $tab;
        } else {
            return Books::getThreeLastBooks();
        }
    }

    /**
     * @return array
     */
    public static function getThreeLastBooks() {
        $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM livres ORDER BY id DESC LIMIT 3");
        $stmt->execute();

        $result = $stmt->fetchAll();
        $tab = [];

        for ($i = 0; $i < count($result); $i++) {
            $tab[] = Books::getBookById($result[$i]->id);
        }
        return $tab;
    }

    /**
     * @param $type
     * @param $choice
     */
    //todo Non utilisée pour le moment. Passage via Datatables
    public static function filterByType($type, $choice) {
        if (isset($type) && isset($choice)) {
            if ($type == 'genre') {
                $stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM livres WHERE $type = ?");
                $stmt->execute([$choice]);

                if ($stmt->rowCount() >= 1) {
                    //todo return info
                } else {
                    //todo return info nulle
                }
            } elseif ($type == 'format') {
                //todo
            } elseif ($type == 'author') {
                //todo
            }
        }
    }
}