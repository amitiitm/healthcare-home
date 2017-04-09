<?php

namespace App\Services\Domain;

use App\Contracts\Domain\IArticleDomainContract;
use App\Models\Article;
use App\Models\Enums\SCConstants;

class ArticleDomainService implements IArticleDomainContract
{
    public function getArticle($id)
    {
        return Article::where('id',$id)->with('author')->with('contributors')->first();
    }

    public function getAllArticles()
    {
        return Article::orderBy('created_at', 'desc')->paginate(SCConstants::PAGINATION_NUMBER)->all();
    }

    public function createArticle($title, $content, $authorId, $contributors)
    {
        $article = new Article();
        $article->title = $title;
        $article->content = $content;
        $article->author_id = $authorId;
        $article->save();
        $article->contributors()->sync($contributors);
        $article->save();
        return $article;
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);
        if ($article != null)
        {
            $article->delete();
            return [];
        }
        return null;
    }

    public function editArticle($id, $title, $content)
    {
        $article = Article::find($id);
        if ($article != null)
        {
            $article->title = $title;
            $article->content = $content;
            $article->save();
            return $article;
        }
        return null;
    }
    public function editContributors($id, $contributors)
    {
        $article = Article::find($id);
        if ($article != null)
        {
            $article->contributors()->sync($contributors);
            $article->save();
            return $article;
        }
        return null;
    }

    public function isContributor($id, $userId)
    {
        return Article::find($id)->contributors()->where('id', $userId);
    }
}