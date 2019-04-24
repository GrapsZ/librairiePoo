<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: account.php');
    exit();
}
$total = 0;
$found = false;
if (isset($_GET['id'])) {
    $cmdNb = Kernel::getInstance()->clean($_GET['id']);
    $idCommand = Commands::getCommandById($_GET['id']);

    if ($idCommand != null && $idCommand > 0) {
        $found = true;
    } else {
        $found = false;
        header('Location: command.php');
    }
} else {
    Kernel::getInstance()->addAlert("danger",
        "Numéro de commande invalide.");
}
?>

    <main role="main">
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row shop-tracking-status">

                    <div class="col-md-12">
                        <div class="well">

                            <div class="order-status">

                                <div class="order-status-timeline">
                                    <div class="order-status-timeline-completion c4"></div>
                                </div>

                                <div class="image-order-status image-order-status-new active img-circle">
                                    <span class="status">Accepté</span>
                                    <div class="icon"></div>
                                </div>
                                <div class="image-order-status image-order-status-active active img-circle">
                                    <span class="status">En préparation</span>
                                    <div class="icon"></div>
                                </div>
                                <div class="image-order-status image-order-status-intransit active img-circle">
                                    <span class="status">Expediée</span>
                                    <div class="icon"></div>
                                </div>
                                <div class="image-order-status image-order-status-delivered active img-circle">
                                    <span class="status">Délivrée</span>
                                    <div class="icon"></div>
                                </div>
                                <div class="image-order-status image-order-status-completed active img-circle">
                                    <span class="status">Completée</span>
                                    <div class="icon"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="mb-5 text-center">Détails de votre commande</h2>
                <div class="row command_table">
                    <table id="cart" class="table table-hover table-condensed mt-5">
                        <thead>
                        <tr>
                            <th style="width:48%">Livres</th>
                            <th style="width:10%">Prix</th>
                            <th style="width:10%">Quantité</th>
                            <th style="width:20%" class="text-center">Total</th>
                            <th style="width:12%"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if ($idCommand != null && count($idCommand) > 0): ?>
                                <?php foreach($idCommand as $key => $value){
                                    $book = Books::getBookById($value['book_id']);
                                    $priceBooks = $book->getPrice() * $value['count'];
                                    $total = $total + $priceBooks;
                                    ?>
                                    <tr>
                                        <td data-th="Livres">
                                            <div class="row">
                                                <div class="col-2 hidden-xs p-1">
                                                    <img src="<?= $book->getJaquette(); ?>" height="100px" width="100px" alt="<?= $book->getTitle(); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-10 cart_book_title">
                                                    <h4><?= $book->getTitle(); ?></h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-th="Prix"><?= $book->getPrice().' '?><i class="fas fa-euro-sign"></i></td>
                                        <td data-th="Quantité">
                                            <div class="count_element">
                                                <p><?= $value['count'] ?></p>
                                            </div>
                                        </td>
                                        <td data-th="Total" class="total_price"><?= $priceBooks.' ' ?><i class="fas fa-euro-sign"></i></td>
                                        <td class="actions" data-th="">
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td data-th="Livres">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h4 class="nomargin"><?= "Le panier est vide !" ?></h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="Prix"></td>
                                    <td data-th="Quantité">
                                    </td>
                                    <td data-th="Total" class=""></td>
                                    <td class="actions" data-th="">
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td colspan="2" class="hidden-xs"></td>
                            <td class="hidden-xs text-center"><strong>Total: <?= $total.' ' ?><i class="fas fa-euro-sign"></i></strong></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>