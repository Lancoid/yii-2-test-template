<?php

declare(strict_types=1);

/**
 * @var array<string> $messages
 * @var ?string $alertClass
 * @var ?string $icon
 * @var ?string $header
 */
// @phpstan-ignore-next-line
$alertClass = $alertClass ? htmlspecialchars($alertClass, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
$icon = $icon ? htmlspecialchars($icon, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : null;
$header = $header ? htmlspecialchars($header, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : null;
?>

<div class="alert <?= $alertClass ?>">
    <?php if (!empty($header)) { ?>
        <h4 class="alert-heading">
            <?php if (!empty($icon)) { ?>
                <i class="fa fa-<?= $icon ?>"></i>
            <?php } ?>
            <?= $header ?>
        </h4>
    <?php } ?>
    <?php /** @var string $message */ ?>
    <?php foreach ($messages as $message) { ?>
        <?php $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
        <p><?= $message ?></p>
    <?php } ?>
</div>
