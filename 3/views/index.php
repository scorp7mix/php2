<h3>Перечень статей:</h3>
<ul>
    <? if ($type == 'edit'): ?>
        <li>
            <p>
                <b><a href="new.php">Новая статья</a></b>
            </p>
        </li>
    <? endif; ?>
    <? if (empty($articles)): ?>
        <p>Статей в базе не найдено..</p>
    <? else: ?>
        <? foreach ($articles as $article): ?>
            <li>
                <p>
                    <a href="<?= $type ?>.php?id=<?= $article['id_article'] ?>">
                        <?= $article['title'] ?>
                    </a>
                </p>

                <p><?= $article['content'] ?></p>
            </li>
        <? endforeach; ?>
    <? endif; ?>
</ul>