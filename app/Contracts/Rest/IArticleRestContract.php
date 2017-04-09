<?php

namespace App\Contracts\Rest;

interface IArticleRestContract
{
    public function getArticle($id);

    public function getAllArticles();
    
    public function deleteArticle($id);

    public function createArticle($title, $content, $contributors);

    public function editArticle($id, $title, $content);

    public function editContributors($id, $contributors);

    
    
}