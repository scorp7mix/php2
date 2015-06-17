<?php

include_once("./include/model.php");
include_once("./include/startup.php");

class C_Article extends C_Base
{
    public function __construct()
    {
        parent::__construct();

        startup();
    }

    //
    // Перечень всех статей
    //
    public function Action_Index()
    {
        $this->title .= '::Index';
        $this->action = 'Index';

        $articles = articles_intro();

        $this->view = $this->Template('./views/index.php',
            ['articles' => $articles]);
    }

    //
    // Перечень статей для редактирования
    //
    public function Action_Editor()
    {
        $this->title .= '::Editor';
        $this->action = 'Editor';

        $articles = articles_all();

        $this->view = $this->Template('./views/editor.php',
            ['articles' => $articles]);
    }

    //
    // Показ одной статьи
    //
    public function Action_Show()
    {
        $this->title .= '::Show';

        if (!isset($_GET['id']))
            header('Location: index.php');

        $id = intval($_GET['id']);
        $article = $id ? articles_get($id) : false;

        if (!$article)
            header('Location: index.php');

        $this->view = $this->Template('./views/show.php',
            ['article' => $article]);
    }

    //
    // Создание новой статьи
    //
    public function Action_New()
    {
        $this->title .= '::New';

        if ($this->IsPost()) {
            if (!empty($_POST['title']) && !empty($_POST['content'])
                && articles_new($_POST['title'], $_POST['content']))
            {
                header('Location: index.php?c=Article&a=Editor');
                die();
            }
            $article['title'] = $_POST['title'];
            $article['content'] = $_POST['content'];
            $error['title'] = empty($_POST['title']) ? 'has-error' : '';
            $error['content'] = empty($_POST['content']) ? 'has-error' : '';
        }

        $this->view = $this->Template('./views/form.php',
            ['form_title' => 'Новая статья',
            'error' => isset($error) ? $error : ['title' => '', 'content' => ''],
            'article' => isset($article) ? $article : ['title' => '', 'content' => ''],
            'button_value' => 'Добавить']);
    }

    //
    // Редактирование статьи
    //
    public function Action_Edit()
    {
        $this->title .= "::Edit";

        if (!isset($_GET['id']))
            header('Location: index.php?c=Article&a=Editor');

        $id = intval($_GET['id']);
        $article = $id ? articles_get($id) : false;

        if (!$article)
            header('Location: index.php?c=Article&a=Editor');

        if ($this->IsPost()) {
            if (!empty($_POST['title']) && !empty($_POST['content'])
                && articles_edit($id, $_POST['title'], $_POST['content'])
            ) {
                header('Location: index.php?c=Article&a=Editor');
                die();
            }
            $article['title'] = $_POST['title'];
            $article['content'] = $_POST['content'];
            $error['title'] = empty($_POST['title']) ? 'has-error' : '';
            $error['content'] = empty($_POST['content']) ? 'has-error' : '';
        }

        $this->view = $this->Template('./views/form.php', [
            'form_title' => 'Редактирование статьи',
            'error' => isset($error) ? $error : ['title' => '', 'content' => ''],
            'article' => isset($article) ? $article : ['title' => '', 'content' => ''],
            'button_value' => 'Изменить'
        ]);
    }
}