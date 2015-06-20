<?php

class C_Article extends C_Base
{
    private $model;
    private $comments;
    //
    // Конструктор
    //
    public function __construct()
    {
        parent::__construct();

        $this->model = M_Article::GetInstance();
        $this->comments = M_Comment::GetInstance();
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
        $menu = $this->Template('./views/menu.php',
            ['menu_items' => $this->model->get_menu($this->action)]);


        $page = $this->Template('./views/layout.php',
            ['title' => $this->title,
                'menu' => $menu,
                'view' => $this->view]);

        echo $page;
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
    public function Show()
    {
        $this->title .= '::Show';

        if (!isset($_GET['id']))
            header('Location: index.php');

        $id = intval($_GET['id']);
        $article = $id ? $this->model->Show($id) : false;

        if (!$article)
            header('Location: index.php');

        $comments_data = $this->comments->All($id);

        $this->view = $this->Template('./views/article/show.php',
            ['article' => $article,
            'comments' => $comments_data]);
    }

    //
    // Создание новой статьи
    //
    public function Create()
    {
        $this->title .= '::Create';

        if ($this->IsPost()) {
            if (!empty($_POST['title']) && !empty($_POST['content'])
                && $this->model->Create($_POST['title'], $_POST['content']))
            {
                header('Location: index.php?c=Article&a=Editor');
                die();
            }
            $article['title'] = $_POST['title'];
            $article['content'] = $_POST['content'];
            $error['title'] = empty($_POST['title']) ? 'has-error' : '';
            $error['content'] = empty($_POST['content']) ? 'has-error' : '';
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
    public function Edit()
    {
        $this->title .= "::Edit";

        if (!isset($_GET['id']))
            header('Location: index.php?c=Article&a=Editor');

        $id = intval($_GET['id']);
        $article = $id ? $this->model->Show($id) : false;

        if (!$article)
            header('Location: index.php?c=Article&a=Editor');

        if ($this->IsPost()) {
            if (!empty($_POST['title']) && !empty($_POST['content'])
                && $this->model->Edit($id, $_POST['title'], $_POST['content'])
            ) {
                header('Location: index.php?c=Article&a=Editor');
                die();
            }
            $article['title'] = $_POST['title'];
            $article['content'] = $_POST['content'];
            $error['title'] = empty($_POST['title']) ? 'has-error' : '';
            $error['content'] = empty($_POST['content']) ? 'has-error' : '';
        }

        $this->view = $this->Template('./views/article/form.php',
            ['form_title' => 'Редактирование статьи',
            'error' => isset($error) ? $error : ['title' => '', 'content' => ''],
            'article' => isset($article) ? $article : ['title' => '', 'content' => ''],
            'button_value' => 'Изменить']);
    }
}