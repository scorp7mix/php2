<h3>Комментарии:</h3>

<br>

<div class="list-group">
    <? if (empty($comments)): ?>
        <p>Комментариев пока нет..</p>
    <? else: ?>
        <? foreach ($comments as $comment): ?>
                    <a href="#"
                       class="list-group-item">
                        <h4 class="list-group-item-heading"><?= $comment['user'] ?></h4>
                        <p class="list-group-item-text"><?= $comment['text'] ?></p>
                    </a>
        <? endforeach; ?>
    <? endif; ?>
</div>