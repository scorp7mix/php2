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

        header('Content-type: text/html; charset=utf-8');

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

        if ($this->IsPost()) {
            if (!empty($_POST['user']) && !empty($_POST['text'])
                && $this->comments->Create($id, $_POST['user'], $_POST['text']))
            {
                header('Location: index.php?c=Article&a=Show&id=' . $id);
                die();
            }
            $new_comment['user'] = $_POST['user'];
            $new_comment['text'] = $_POST['text'];
            $error['user'] = empty($_POST['user']) ? 'has-error' : '';
            $error['text'] = empty($_POST['text']) ? 'has-error' : '';
        }

        $comments_view = $this->Template('./views/comment/index.php',
            ['comments' => $comments_data]);

        $new_comment_view = $this->Template('./views/comment/form.php',
            ['form_title' => 'Новый комментарий',
                'error' => isset($error) ? $error : ['user' => '', 'text' => ''],
                'new_comment' => isset($new_comment) ? $new_comment : ['user' => '', 'text' => ''],
                'button_value' => 'Добавить']);

        $this->view = $this->Template('./views/article/show.php',
            ['article' => $article,
            'comments' => $comments_view,
            'new_comment_form' => $new_comment_view]);
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