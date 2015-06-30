<h3>Перечень статей:</h3>

<br>

<a href="/article/create" class="btn btn-info btn-sm">Добавить новую статью</a>

<br><br>

<div class="list-group">
    <? if (empty($articles)): ?>
        <p>Статей в базе не найдено..</p>
    <? else: ?>
        <? foreach ($articles as $article): ?>
            <a href="/article/edit/<?= $article['id_article'] ?>"
               class="list-group-item">
                <?= $article['title'] ?>
            </a>
        <? endforeach; ?>
    <? endif; ?>
</div>