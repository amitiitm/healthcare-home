<?php namespace App\Http\Controllers\Rest;

use App\Contracts\Domain\IArticleDomainContract;
use App\Contracts\Rest\IArticleRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\Controller;

use App\Templates\SCResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleRestController extends Controller
{
    protected $articleRestService, $userRestService;

    public function __construct(IUserRestContract $userRestService, IArticleRestContract $articleRestService)
    {
        $this->articleRestService = $articleRestService;
        $this->userRestService = $userRestService;
    }

    public function getArticle($id)
    {
        $article = $this->articleRestService->getArticle($id);
        if (is_null($article)) {
            return response()->json(SCResponse::getErrorResponse('Article does not exist', []));
        } else {
            return response()->json(SCResponse::getSuccessResponse('Article Found', $article));
        }
    }

    public function getAllArticles()
    {
        $articles = $this->articleRestService->getAllArticles();
        if (is_null($articles)) {
            return response()->json(SCResponse::getErrorResponse('Article does not exist', []));
        } else {
            return response()->json(SCResponse::getSuccessResponse('Article Found', $articles));
        }
    }

    public function createArticle(Request $request)
    {
        if ($request->has('title') and $request->has('content') and $request->has('contributors')) {
//            return 's';
            $article = $this->articleRestService->createArticle($request->input('title'), $request->input('content'), $request->input('contributors'));

            if (is_null($article)) {
                return response()->json(SCResponse::getErrorResponse('Article cannot be created', []));
            } else {
                return response()->json(SCResponse::getSuccessResponse('Article created', $article));
            }
        }
        return response()->json(SCResponse::getErrorResponse('Incomplete parameters', []));
    }

    public function deleteArticle($id)
    {
        $delete = $this->articleRestService->deleteArticle($id);
        if (is_null($delete)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised/Invalid id', []));
        }
        return response()->json(SCResponse::getErrorResponse('Successfully Deleted', $delete));
    }

    public function editArticle(Request $request)
    {
        if ($request->has('title') and $request->has('content') and $request->has('id')) {
            $article = $this->articleRestService->editArticle($request->input('id'), $request->input('title'), $request->input('content'));
            if (is_null($article)) {
                return response()->json(SCResponse::getErrorResponse('Unauthorised/Invalid id', []));
            }
            return response()->json(SCResponse::getSuccessResponse('Article Edited', $article));
        } else {
            return response()->json(SCResponse::getErrorResponse('Incomplete parameters', []));
        }
    }
}
