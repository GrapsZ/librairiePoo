<?php
include "./templates/header.php";
include "./templates/menu.php";

if (!Users::isConnected()) {
    header('Location: connect.php');
    exit();
}
$user = null;
$total = 0;
if (isset($_SESSION['user_id'])) {
    $user = Users::getUserById($_SESSION['user_id']);
    $userCart = Cart::getInstance()->getUserCart($user);
}
?>
    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row mb-5">
                    <h2 class="m-auto text-center">Votre panier</h2>

                    <?php include './templates/Alerts.php'?>

                </div>
                <div class="row cart_table">
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

                        <?php if ($user): ?>
                            <?php if ($userCart != null && count($userCart) > 0): ?>
                                <?php foreach($userCart as $key => $value){
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
                                                <a href="panier_actions.php?action=update_remove&book_id=<?= $book->getId()?>&user_id=<?= $user->getId()?>"><i class="fas fa-minus count_btn_red"></i></a>
                                                <p><?= $value['count'] ?></p>
                                                <a href="panier_actions.php?action=update_add&book_id=<?= $book->getId()?>&user_id=<?= $user->getId()?>"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </td>
                                        <td data-th="Total" class="total_price"><?= $priceBooks.' ' ?><i class="fas fa-euro-sign"></i></td>
                                        <td class="actions" data-th="">
                                            <a href="panier_actions.php?action=remove&book_id=<?= $book->getId()?>&user_id=<?= $user->getId()?>" class="btn btn-danger btn_remove_all_books"><i class="fas fa-trash-alt"></i></a>
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
                <?php if ($userCart != null && count($userCart) > 0): ?>
                <div class="cart_button">
                    <a href="set_command.php?action=cart_valid&user_id=<?= $user->getId(); ?>" class="btn btn-success cart_button_buy">Commander <i class="fa fa-angle-right"></i></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>