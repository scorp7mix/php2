<?php

class C_Article extends C_Base
{
    private $comments;
    private $users;
    //
    // Конструктор
    //
    public function __construct()
    {
        parent::__construct();

        $this->model = M_Article::GetInstance();
        $this->users = new C_User();
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
    public function Index($params)
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
    public function Editor($params)
    {
        if(!$this->users->CheckPriv('Article::Edit', $params['user']['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

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

        $comments = $this->comments->Request('Index', $params);
        $new_comment = $this->comments->Request('Create', $params);

        $this->view = $this->Template('./views/article/show.php',
            ['article' => $article,
            'comments' => $comments,
            'new_comment_form' => $new_comment]);
    }

    //
    // Создание новой статьи
    //
    public function Create($params)
    {
        if(!$this->users->CheckPriv('Article::Create', $params['user']['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= '::Create';

        if($this->IsPost())
            $edit_result = $this->editElement(null, 'Create');

        $this->view = $this->Template('./views/article/form.php',
            ['form_title' => 'Новая статья',
                'error' => isset($edit_result) ? $edit_result['error'] : [],
                'article' => isset($edit_result) ? $edit_result['object'] : [],
                'button_value' => 'Добавить']);
    }

    //
    // Редактирование статьи
    //
    public function Edit($params)
    {
        if(!$this->users->CheckPriv('Article::Edit', $params['user']['id_user'])) {
            $this->view = 'Отказано в доступе';
            return;
        }

        $this->title .= "::Edit";

        $article = $this->getElement($params, 'Location: index.php?c=Article&a=Editor');

        if($this->IsPost())
            $edit_result = $this->editElement($article['id_article'], 'Edit');

        $this->view = $this->Template('./views/article/form.php',
            ['form_title' => 'Редактирование статьи',
                'error' => isset($edit_result) ? $edit_result['error'] : [],
                'article' => isset($edit_result) ? $article = $edit_result['object'] : $article,
                'button_value' => 'Изменить']);
    }

    //
    // Дополнительный метод, используется при создании/изменении элемента
    //
    protected function editElement($id, $action)
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

        if ($fields_not_empty
            && $this->model->$action($id, $object))
        {
            header('Location: index.php?c=Article&a=Editor');
            die();
        }

        return ['object' => $object, 'error' => $error];
    }
}