<?php

class C_Comment extends C_Base
{
    private $id_article;
    private $users;
    //
    // Конструктор
    //
    public function __construct($id_article)
    {
        parent::__construct();

        $this->id_article = $id_article;
        $this->model = M_Comment::GetInstance();
        $this->users = new C_User();
    }

    //
    // Предварительная обработка перед обработчиком действия
    //
    public function Before()
    {
        $this->title = 'Comment';
    }

    //
    // Компоновка страницы и ее вывод
    //
    public function Render()
    {
        $page = $this->Template('./views/comment/layout.php',
            ['title' => $this->title,
            'view' => $this->view]);

        return $page;
    }

    //
    // Перечень всех комментариев
    //
    public function Index($params)
    {
        $this->title .= '::Index';
        $this->action = 'Index';

        $comments = $this->model->All($this->id_article);

        $this->view = $this->Template('./views/comment/index.php',
            ['comments' => $comments]);
    }

    //
    // Показ одного комментария
    //
    public function Show($params)
    {
        $this->title .= '::Show';

        $comment = $this->getElement($params, 'Location: index.php?c=Article&a=Show&id=' . $this->id_article);

        $this->view = $this->Template('./views/comment/show.php',
            ['comment' => $comment]);
    }

    //
    // Создание нового комментария
    //
    public function Create($params)
    {
        if(!$this->users->CheckPriv('Comment::Create', $params['user']['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= '::Create';

        if($this->IsPost())
            $edit_result = $this->editElement($this->id_article, 'Create', $params['user']['id_user'], null);

        $this->view = $this->Template('./views/comment/form.php',
            ['form_title' => 'Новый комментарий',
                'error' => isset($edit_result) ? $edit_result['error'] : [],
                'comment' => isset($edit_result) ? $edit_result['object'] : [],
                'button_value' => 'Добавить']);
    }

    //
    // Редактирование комментария
    //
    public function Edit($params)
    {
        if(!$this->users->CheckPriv('Comment::Edit', $params['user']['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= "::Edit";

        $comment = $this->getElement($params, 'Location: index.php?c=Article&a=Show&id=' . $this->id_article);

        if($comment['id_user'] != $params['user']['id_user']) {
            $this->view = 'Вы не можете редактировать чужой комментарий!!';
            return;
        }

        if($this->IsPost())
            $edit_result = $this->editElement($comment['id_comment'], 'Edit', $params['user']['id_user'], $comment['id_article']);

        $this->view = $this->Template('./views/comment/form.php',
            ['form_title' => 'Редактирование комментария',
                'error' => isset($edit_result) ? $edit_result['error'] : [],
                'comment' => isset($edit_result) ? $edit_result['object'] : $comment,
                'button_value' => 'Изменить']);
    }

    //
    // Дополнительный метод, используется при создании/изменении элемента
    //
    protected function editElement($id, $action, $id_user, $id_article)
    {
        $object = [];
        $error = [];

        $text = $_POST['text'];
        $object['text'] = !empty($text) ? $text : '';
        $object['id_user'] = $id_user;
        $error['text'] = !empty($text) ? '' : 'has-error';

        if(!empty($text)
            && $this->model->$action($id, $object))
        {
            header('Location: index.php?c=Article&a=Show&id=' . ($id_article ?  $id_article : $id));
            die();
        }

        return ['object' => $object, 'error' => $error];
    }
}