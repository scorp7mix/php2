<?php

$menu_items = [
    1 => ['title' => 'Главная',
        'src' => 'index.php'],
    2 => ['title' => 'Консоль редактора',
        'src' => 'editor.php']
];

?>

<? foreach ($menu_items as $key => $item): ?>
    <? if ($key != $pageId): ?>
        <a href=<?= $item['src'] ?>><?= $item['title'] ?></a>
    <? else: ?>
        <b><?= $item['title'] ?></b>
    <? endif; ?>
    |
<? endforeach; ?>
<hr/>