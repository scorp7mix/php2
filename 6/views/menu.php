<? foreach ($menu_items as $item): ?>
    <li role="presentation" class=<?= $item['class'] ?>>
        <a href="index.php?c=Article&a=<?= $item['action'] ?>"><?= $item['title'] ?></a>
    </li>
<? endforeach; ?>