<? foreach ($menu_items as $item): ?>
    <li role="presentation" class=<?= $item['class'] ?>>
        <a href="/article/<?= $item['action'] ?>"><?= $item['title'] ?></a>
    </li>
<? endforeach; ?>