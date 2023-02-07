<?php //variables
$className = 'c-modal-loading';
?>

<div class="<?= $className ?> <?= $className ?>--<?= $args['type'] ?>">
    <div class="<?= $className ?>__inner">
        <div class="<?= $className ?>__text text--center">
            <div class="<?= $className ?>__animation">
            </div>
            <p class="h2 text--white"><?= $args['message'] ?></p>
        </div>
    </div>
    <div class="<?= $className ?>__background"></div>
</div>