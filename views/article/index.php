<h3>Перечень статей:</h3>

<br>

<div class="list-group">
    <? if (empty($articles)): ?>
        <p>Статей в базе не найдено..</p>
    <? else: ?>
        <? foreach ($articles as $article): ?>
                    <a href="/article/show/<?= $article['id_article'] ?>"
                       class="list-group-item">
                        <h4 class="list-group-item-heading"><?= $article['title'] ?></h4>
                        <p class="list-group-item-text"><?= $article['intro'] ?></p>
                    </a>
        <? endforeach; ?>
    <? endif; ?>
</div>