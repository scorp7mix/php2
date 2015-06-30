<?php

namespace blog\controllers;

use blog\models\M_Comment;

class C_Comment extends C_Base
{
    private $id_article;
    private $users;
    private $user;

    public function __construct($id_article)
    {
        $this->id_article = $id_article;
        $this->model = M_Comment::getInstance();
    }

    public function before()
    {
        $this->title = 'Comment';
        $this->users = $this->params['users'];
        $this->user = $this->params['user'];
    }

    public function render()
    {
        $page = $this->template(
            './views/comment/layout.php',
            [
                'title' => $this->title,
                'view' => $this->view
            ]
        );

        return $page;
    }

    public function index()
    {
        $this->title .= '::Index';
        $this->action = 'index';

        $comments = $this->model->index($this->id_article);

        $this->view = $this->template(
            './views/comment/index.php',
            ['comments' => $comments]
        );
    }

    public function show()
    {
        $this->title .= '::Show';

        $comment = $this->getComment('Location: /Article/Show/' . $this->id_article);

        $this->view = $this->template(
            './views/comment/show.php',
            ['comment' => $comment]
        );
    }

    public function create()
    {
        if(!$this->users->checkPriv('Comment::Create', $this->user['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= '::Create';

        if($this->isPost())
            $edit_result = $this->editElement($this->id_article, 'create', $this->user['id_user'], null);

        $this->view = $this->template(
            './views/comment/form.php',
            [
                'form_title'    => 'Новый комментарий',
                'error'         => isset($edit_result) ? $edit_result['error'] : [],
                'comment'       => isset($edit_result) ? $edit_result['object'] : [],
                'button_value'  => 'Добавить'
            ]
        );
    }

    public function edit()
    {
        if(!$this->users->checkPriv('Comment::Edit', $this->user['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= "::Edit";

        $comment = $this->getComment('Location: /Article/Show/' . $this->id_article);

        if($comment['id_user'] != $this->user['id_user']) {
            $this->view = 'Вы не можете редактировать чужой комментарий!!';
            return;
        }

        if($this->isPost())
            $edit_result = $this->editElement($comment['id_comment'], 'edit', $this->user['id_user'], $comment['id_article']);

        $this->view = $this->Template(
            './views/comment/form.php',
            [
                'form_title' => 'Редактирование комментария',
                'error' => isset($edit_result) ? $edit_result['error'] : [],
                'comment' => isset($edit_result) ? $edit_result['object'] : $comment,
                'button_value' => 'Изменить'
            ]
        );
    }

    protected function getComment ($headerOnError)
    {
        $id = isset($this->params[2]) ? intval($this->params[2]) : false;

        if (!$id)
            header($headerOnError);

        $comment = $this->model->show($id);

        if (!$comment)
            header($headerOnError);

        return $comment;
    }

    protected function editElement($id, $action, $id_user, $id_article)
    {
        $object = [];
        $error = [];

        $text = $_POST['text'];
        $object['text'] = !empty($text) ? $text : '';
        $object['id_user'] = $id_user;
        $error['text'] = !empty($text) ? '' : 'has-error';

        if (!empty($text) && $this->model->$action($id, $object)) {
            header('Location: /Article/Show/' . ($id_article ?  $id_article : $id));
            die();
        }

        return ['object' => $object, 'error' => $error];
    }
}
