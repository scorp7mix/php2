<?php

class Article
{
	protected $id;
    protected $title;
    protected $content;

    public $preview;
	
	function Article($id, $title, $content)
	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;

        $this->preview = substr($content, 0, 15);
	}
	
	//  Функция для вывода статьи
	function view()
	{
		echo "<h1>$this->title</h1><p>$this->content</p>";
	}

    function getId() {
        return $this->id;
    }
}

class NewsArticle extends Article
{
	private $datetime;

	function NewsArticle($id, $title, $content)
	{
		parent::Article($id, $title, $content);
		$this->datetime = time();
	}
	
	//  Функция для вывода статьи
	function view()
	{
		echo "<h1>$this->title</h1><span style='color: red'>".
				strftime('%d.%m.%y', $this->datetime).
				" <b>Новость</b></span><p>$this->content</p>";
	}
}

class CrossArticle extends Article
{
    private $source;
	
	function CrossArticle($id, $title, $content, $source)
	{
		parent::Article($id, $title, $content);
		$this->source = $source;
	}

	function view()
	{
		parent::view();
		echo '<small>'.$this->source.'</small>';
	}
}

class ArticleWithImage extends Article
{
    private $image_src;

    function ArticleWithImage($id, $title, $content, $image_src)
    {
        parent::Article($id, $title, $content);
        $this->image_src = $image_src;
    }

    function view()
    {
        parent::view();
        echo '<img src=' . $this->image_src . '>';
    }
}

class ArticleList
{
    protected $alist;
	
	function add(Article $article)
	{
		$this->alist[] = $article;
	}
	
	//  Вывод статей
	function view()
	{
        if (!empty($this->alist)) {
            foreach ($this->alist as $article) {
                $article->view();
                echo '<hr />';
            }
        }
	}

    // Удаление статьи
    function del($id) {
        foreach($this->alist as $article)
        {
            if($article->getId() == $id) {
                unset($this->alist[key($this->alist) - 1]);
            }
        }
    }
}


class ArticleReverseList extends ArticleList
{
    //  Вывод статей
    function view()
    {
        if (!empty($this->alist)) {
            $rlist = array_reverse($this->alist);

            foreach ($rlist as $article) {
                $article->view();
                echo '<hr />';
            }
        }
    }
}
