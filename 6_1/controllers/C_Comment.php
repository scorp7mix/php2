<?php

class C_Comment extends C_Base
{
    private $id_article;

    //
    // Конструктор
    //
    public function __construct($id_article)
    {
        parent::__construct();

        $this->id_article = $id_article;
        $this->model = M_Comment::GetInstance();
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
    public function Index()
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
    public function Create()
    {
        $this->title .= '::Create';

        if($this->IsPost())
        {
            $edit_result = $this->editElement(
                $this->id_article,
                ['user', 'text'],
                'Create',
                'Location: index.php?c=Article&a=Show&id=' . $this->id_article);

            $comment = $edit_result['object'];
            $error = $edit_result['error'];
        }

        $this->view = $this->Template('./views/comment/form.php',
            ['form_title' => 'Новый комментарий',
                'error' => isset($error) ? $error : ['user' => '', 'text' => ''],
                'comment' => isset($comment) ? $comment : ['user' => '', 'text' => ''],
                'button_value' => 'Добавить']);
    }

    //
    // Редактирование комментария
    //
    public function Edit($params)
    {
        $this->title .= "::Edit";

        $comment = $this->getElement($params, 'Location: index.php?c=Article&a=Show&id=' . $this->id_article);

        if($this->IsPost())
        {
            $edit_result = $this->editElement(
                $comment['id_comment'],
                ['user', 'text'],
                'Edit',
                'Location: index.php?c=Article&a=Show&id=' . $comment['id_article']);

            $comment = $edit_result['object'];
            $error = $edit_result['error'];
        }

        $this->view = $this->Template('./views/comment/form.php',
            ['form_title' => 'Редактирование комментария',
                'error' => isset($error) ? $error : ['user' => '', 'text' => ''],
                'comment' => isset($comment) ? $comment : ['user' => '', 'text' => ''],
                'button_value' => 'Изменить']);
    }
}