<?php
require_once 'classes.php';

// Здесь разместить код, использующий классы из classes.php

$news_article_1 = new NewsArticle(1, "news article 1", "new article 1 text");
$news_article_2 = new NewsArticle(2, "news article 2", "new article 2 text");
$news_article_3 = new NewsArticle(3, "news article 3", "new article 3 text");

$cross_article_1 = new CrossArticle(4, "cross article 1", "cross article 1 text", "http://source1.com");
$cross_article_2 = new CrossArticle(5, "cross article 2", "cross article 2 text", "http://source2.com");
$cross_article_3 = new CrossArticle(6, "cross article 3", "cross article 3 text", "http://source3.com");

$article_list = new ArticleList();

$article_list->view();

$article_list->add($news_article_1);
$article_list->add($news_article_2);
$article_list->add($news_article_3);

$article_list->add($cross_article_1);
$article_list->add($cross_article_2);
$article_list->add($cross_article_3);

$article_list->view();

$article_with_img_1 = new ArticleWithImage(7, "article with image 1", "article with image 1 text", "http://source.com/image1.jpg");
$article_with_img_2 = new ArticleWithImage(8, "article with image 2", "article with image 2 text", "http://source.com/image2.jpg");
$article_with_img_3 = new ArticleWithImage(9, "article with image 3", "article with image 3 text", "http://source.com/image3.jpg");

$article_list->add($article_with_img_1);
$article_list->add($article_with_img_2);
$article_list->add($article_with_img_3);

$article_list->del(2);
$article_list->del(5);
$article_list->view();
