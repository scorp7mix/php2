<div class="jumbotron">

    <h3 class="text-center"><?= $article['title'] ?></h3>

    <hr>

    <p><?= $article['content'] ?></p>

</div>

<h3>Комментарии:</h3>

<br>

<div class="list-group">
    <? if (empty($comments)): ?>
        <p>Комментариев пока нет..</p>
    <? else: ?>
        <? foreach ($comments as $comment): ?>
            <a href="index.php?c=Comment&a=Show&id=<?= $comment['id_comment'] ?>"
               class="list-group-item">
                <h4 class="list-group-item-heading"><?= $comment['user'] ?></h4>
                <p class="list-group-item-text"><?= $comment['text'] ?></p>
            </a>
        <? endforeach; ?>
    <? endif; ?>
</div>