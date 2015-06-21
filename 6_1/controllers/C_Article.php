<?php

class C_Article extends C_Base
{
    private $comments;
    //
    // Конструктор
    //
    public function __construct()
    {
        parent::__construct();

        $this->model = M_Article::GetInstance();
    }

    //
    // Предварительная обработка перед обработчиком действия
    //
    public function Before()
    {
        $this->title = 'Article';
    }

    //
    // Компоновка страницы и ее вывод
    //
    public function Render()
    {
        $menu = $this->Template('./views/article/menu.php',
            ['menu_items' => $this->model->get_menu($this->action)]);


        $page = $this->Template('./views/article/layout.php',
            ['title' => $this->title,
                'menu' => $menu,
                'view' => $this->view]);

        return $page;
    }

    //
    // Перечень всех статей
    //
    public function Index()
    {
        $this->title .= '::Index';
        $this->action = 'Index';

        $articles = $this->model->All();

        $this->view = $this->Template('./views/article/index.php',
            ['articles' => $articles]);
    }

    //
    // Перечень статей для редактирования
    //
    public function Editor()
    {
        $this->title .= '::Editor';
        $this->action = 'Editor';

        $articles = $this->model->All();

        $this->view = $this->Template('./views/article/editor.php',
            ['articles' => $articles]);
    }

    //
    // Показ одной статьи
    //
    public function Show($params)
    {
        $this->title .= '::Show';

        $article = $this->getElement($params, 'Location: index.php');

        $this->comments = new C_Comment($article['id_article']);

        $comments = $this->comments->Request('Index', null);
        $new_comment = $this->comments->Request('Create', null);

        $this->view = $this->Template('./views/article/show.php',
            ['article' => $article,
            'comments' => $comments,
            'new_comment_form' => $new_comment]);
    }

    //
    // Создание новой статьи
    //
    public function Create()
    {
        $this->title .= '::Create';

        if($this->IsPost())
        {
            $edit_result = $this->editElement(
                               null,
                               ['title', 'content'],
                               'Create',
                               'Location: index.php?c=Article&a=Editor');

            $article = $edit_result['object'];
            $error = $edit_result['error'];
        }

        $this->view = $this->Template('./views/article/form.php',
            ['form_title' => 'Новая статья',
                'error' => isset($error) ? $error : ['title' => '', 'content' => ''],
                'article' => isset($article) ? $article : ['title' => '', 'content' => ''],
                'button_value' => 'Добавить']);
    }

    //
    // Редактирование статьи
    //
    public function Edit($params)
    {
        $this->title .= "::Edit";

        $article = $this->getElement($params, 'Location: index.php?c=Article&a=Editor');

        if($this->IsPost())
        {
            $edit_result = $this->editElement(
                $article['id_article'],
                ['title', 'content'],
                'Edit',
                'Location: index.php?c=Article&a=Editor');

            $article = $edit_result['object'];
            $error = $edit_result['error'];
        }

        $this->view = $this->Template('./views/article/form.php',
            ['form_title' => 'Редактирование статьи',
                'error' => isset($error) ? $error : ['title' => '', 'content' => ''],
                'article' => isset($article) ? $article : ['title' => '', 'content' => ''],
                'button_value' => 'Изменить']);
    }
}