<? foreach ($menu_items as $item): ?>
    <? if (false == $item['readonly']): ?>
        <a href=<?= $item['src'] ?>><?= $item['title'] ?></a>
    <? else: ?>
        <b><?= $item['title'] ?></b>
    <? endif; ?>
<? endforeach; ?>
<hr/>