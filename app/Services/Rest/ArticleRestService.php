<?php

namespace App\Services\Rest;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Domain\IUserDomainContract;
use App\Contracts\Rest\IArticleRestContract;
use Illuminate\Support\Facades\Auth;

class ArticleRestService implements IArticleRestContract
{
    protected $articleDomainService, $userDomainService;

    public function __construct(IArticleDomainContract $articleDomainService, IUserDomainContract $userDomainService)
    {
        $this->articleDomainService = $articleDomainService;
        $this->userDomainService = $userDomainService;
    }

    public function getArticle($id)
    {
        return $this->articleDomainService->getArticle($id);
    }
    
    public function getAllArticles()
    {
        return $this->articleDomainService->getAllArticles();
    }
    public function deleteArticle($id)
    {
        $user = Auth::user();
        if ($user) {
            $article = $this->articleDomainService->getArticle($id);
            if ($article) {
                if ($article->author_id == $user->id or $user->level()->first()->can_unpublish) {
                    return $this->articleDomainService->deleteArticle($id);
                }
            }
        }
        return null;
    }

    public function createArticle($title, $content, $contributors)
    {

        $user = Auth::user();
        if ($user) {
            if ($user->level()->first()->can_publish) {

                $contributors = $this->userDomainService->getIdsFromEmails($contributors);
                return $this->articleDomainService->createArticle($title, $content, $user->id, $contributors);
            }
        }
        return null;
    }

    public function editArticle($id, $title, $content)
    {
        $user = Auth::user();
        if ($user) {
            $article = $this->articleDomainService->getArticle($id);

            if ($article->author_id == $user->id or $user->level()->first()->can_edit) {
                return $this->articleDomainService->editArticle($id, $title, $content);
            }
            foreach ($article->contributors as $contributor) {
                if ($contributor->id == $user->id) {
                    return view('article.edit')->with('articleId', $id);
                }

            }
        }
        return null;
    }

    public function editContributors($id, $contributors)
    {
        $user = Auth::user();
        if ($user) 
        {
            $article = $this->articleDomainService->getArticle($id);
            if ($article->author_id == $user->id or $user->level()->first()->can_edit) 
            {
                return $this->articleDomainService->editArticle($id, $title, $content);
            }
        }
    }
}