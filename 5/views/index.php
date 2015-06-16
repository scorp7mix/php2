<h3>Перечень статей:</h3>
<ul>
    <? if ($action == 'Edit'): ?>
        <li>
            <p>
                <b><a href="./index.php?c=Article&a=New">Новая статья</a></b>
            </p>
        </li>
    <? endif; ?>
    <? if (empty($articles)): ?>
        <p>Статей в базе не найдено..</p>
    <? else: ?>
        <? foreach ($articles as $article): ?>
            <li>
                <p>
                    <a href="./index.php?c=Article&a=<?= $action ?>&id=<?= $article['id_article'] ?>">
                        <?= $article['title'] ?>
                    </a>
                </p>

                <p><?= $article['content'] ?></p>
            </li>
        <? endforeach; ?>
    <? endif; ?>
</ul>