<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 4/10/16
 * Time: 4:56 PM
 */

namespace app\Http\Controllers\Link;

use App\Contracts\Rest\IArticleRestContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ArticleLinkController extends Controller
{
    public function __construct(IArticleRestContract $articleRestService)
    {
        $this->articleRestService = $articleRestService;
    }

    public function index()
    {
        return view('article.article');
    }
    public function create()
    {
        if (Auth::user()->level()->first()->can_publish)
            return view('article.create');
        else
            return redirect('home');
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user) {
            $article = $this->articleRestService->getArticle($id);

            if ($article and ($article->author_id == $user->id or $user->level()->first()->can_edit)) {
                return view('article.edit')->with('articleId', $id);
            }
            foreach ($article->contributors as $contributor) {
                if ($contributor->id == $user->id) {
                    return view('article.edit')->with('articleId', $id);
                }

            }
        }
        return Redirect::to(URL::previous());

    }
    public function deleteArticle($id)
    {
        $user = Auth::user();
        if ($user) {
            $article = $this->articleRestService->getArticle($id);

            if ($article and ($article->author_id == $user->id or $user->level()->first()->can_edit)) {
                $this->articleRestService->deleteArticle($id);
            }
        }
        return Redirect::to(URL::previous());

    }
    public function article($id)
    {
        return view('article.article')->with('articleId', $id);
    }
}