<?php

namespace blog\controllers;

use blog\models\M_Article;

class C_Article extends C_Base
{
    private $comments;
    private $users;
    private $user;

    public function __construct()
    {
        $this->model = M_Article::getInstance();
    }

    public function before()
    {
        $this->title = 'Article';
        $this->users = $this->params['users'];
        $this->user = $this->params['user'];
    }

    public function render()
    {
        $menu = $this->template(
            './views/article/menu.php',
            ['menu_items' => $this->model->get_menu($this->action)]
        );

        $page = $this->template(
            './views/article/layout.php',
            [
                'title' => $this->title,
                'menu'  => $menu,
                'view'  => $this->view
            ]
        );

        return $page;
    }

    public function index()
    {
        $this->title .= '::Index';
        $this->action = 'index';

        $articles = $this->model->index();

        $this->view = $this->template(
            './views/article/index.php',
            ['articles' => $articles]
        );
    }

    public function editor()
    {
        if(!$this->users->checkPriv('Article::Edit', $this->user['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= '::Editor';
        $this->action = 'editor';

        $articles = $this->model->index();

        $this->view = $this->template(
            './views/article/editor.php',
            ['articles' => $articles]
        );
    }

    public function show()
    {
        $this->title .= '::Show';

        $article = $this->getArticle('Location: /');

        $this->comments = new C_Comment($article['id_article']);

        $comments = $this->comments->request('index', $this->params);
        $new_comment = $this->comments->request('create', $this->params);

        $this->view = $this->template(
            './views/article/show.php',
            [
                'article' => $article,
                'comments' => $comments,
                'new_comment_form' => $new_comment
            ]
        );
    }

    public function create()
    {
        if(!$this->users->checkPriv('Article::Create', $this->user['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= '::Create';

        if($this->isPost())
            $edit_result = $this->editElement('create', null);

        $this->view = $this->template(
            './views/article/form.php',
            [
                'form_title'    => 'Новая статья',
                'error'         => isset($edit_result) ? $edit_result['error'] : [],
                'article'       => isset($edit_result) ? $edit_result['object'] : [],
                'button_value'  => 'Добавить'
            ]
        );
    }

    public function edit()
    {
        if(!$this->users->checkPriv('Article::Edit', $this->user['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= "::Edit";

        $article = $this->getArticle('Location: /Article/Editor');

        if($this->isPost())
            $edit_result = $this->editElement('edit', $article['id_article']);

        $this->view = $this->template(
            './views/article/form.php',
            [
                'form_title'    => 'Редактирование статьи',
                'error'         => isset($edit_result) ? $edit_result['error'] : [],
                'article'       => isset($edit_result) ? $article = $edit_result['object'] : $article,
                'button_value'  => 'Изменить'
            ]
        );
    }

    protected function getArticle($headerOnError)
    {
        $id = isset($this->params[2]) ? intval($this->params[2]) : false;

        if (!$id)
            header($headerOnError);

        $article = $this->model->show($id);

        if (!$article)
            header($headerOnError);

        return $article;
    }

    protected function editElement($action, $id)
    {
        $object = [];
        $error = [];

        $fields = ['title', 'content'];

        $fields_not_empty = true;

        foreach ($fields as $field) {
            if (!empty($_POST[$field])) {
                $object[$field] = $_POST[$field];
                $error[$field] = '';
            } else {
                $fields_not_empty = false;
                $object[$field] = '';
                $error[$field] = 'has-error';
            }
        }

        if($fields_not_empty && $this->model->$action($object, $id)) {
            header('Location: /article/editor');
            die();
        }

        return ['object' => $object, 'error' => $error];
    }
}
