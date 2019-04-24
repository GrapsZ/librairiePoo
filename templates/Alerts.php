<?php foreach (Kernel::getInstance()->getAlerts() as $key => $value): ?>
    <div class="alert alert-<?= $value['type'] ?>">
        <?= $value['message'] ?>
    </div>
<?php endforeach; ?>
