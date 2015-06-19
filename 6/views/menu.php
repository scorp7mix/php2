<? foreach ($menu_items as $item): ?>
    <li role="presentation" class=<?= $item['class'] ?>>
        <a href=<?= $item['src'] ?>><?= $item['title'] ?></a>
    </li>
<? endforeach; ?>