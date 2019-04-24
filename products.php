<?php
include "./templates/header.php";
include "./templates/menu.php";

$allBooks = Books::getAllBooks();

?>
    <main role="main">

        <div class="container-fluid mt-5 product_page">
            <div class="w-75 m-auto">
                <table id="dtBasicExample" class="table table-striped table-bordered " cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="">
                            Titre
                        </th>
                        <th class="">
                            Auteur
                        </th>
                        <th class="">
                            Catégorie
                        </th>
                        <th class="">
                            Format
                        </th>
                        <th class="">
                            Prix
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach ($allBooks as $key => $value) {
                        ?>
                        <tr>
                            <td>
                                <div class="row"><div class="col-2">
                                        <img src="<?= $value->getJaquette(); ?>" title="<?= $value->getTitle(); ?>" height="100px" width="100px">
                                    </div>
                                    <div class="col-10 cart_book_title">
                                        <a href="product.php?id=<?= $value->getId() ?>" target="_blank" title="Consulter la page de <?= $value->getTitle(); ?>"><?= $value->getTitle(); ?></a>
                                    </div>
                                </div>
                            </td>
                            <td><?= $value->getAuthorById($value->getId()); ?></td>
                            <td><?= $value->getCategory() ?></td>
                            <td><?= $value->getFormat() ?></td>
                            <td colspan="2"><?= $value->getPrice(). ' €' ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<?php

include "./templates/footer.php";
?>