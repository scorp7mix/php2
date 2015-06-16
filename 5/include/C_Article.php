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

    public function Action_Index()
    {
        $this->title .= '::Index';
        $this->action = 'Index';

        $articles = articles_intro();

        $this->view = $this->Template('./views/index.php',
            ['action' => 'Show', 'articles' => $articles]);
    }

    public function Action_Editor()
    {
        $this->title .= '::Editor';
        $this->action = 'Editor';

        $articles = articles_all();

        $this->view = $this->Template('./views/index.php',
            ['action' => 'Edit', 'articles' => $articles]);
    }

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
            $error = true;
        }
        else
        {
            $article['title'] = '';
            $article['content'] = '';
            $error = false;
        }

        $this->view = $this->Template('./views/form.php',
            ['form_title' => 'Новая статья',
            'error' => $error,
            'article' => $article,
            'button_value' => 'Добавить']);
    }

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
            $error = true;
        }

        $this->view = $this->Template('./views/form.php', [
            'form_title' => 'Редактирование статьи',
            'error' => isset($error) ? $error : false,
            'article' => $article,
            'button_value' => 'Изменить'
        ]);
    }
}