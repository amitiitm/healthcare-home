<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\IUserRestContract;
use App\Http\Controllers\Controller;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class UserRestController extends Controller
{
    protected $userRestService;

    public function __construct(IUserRestContract $userRestService)
    {
        $this->userRestService = $userRestService;
    }


    public function getUserGridData(){
        $userListToReturn = $this->userRestService->getUserGridList();

        return Response::json(PRResponse::getSuccessResponse("",$userListToReturn));
    }

    public function getVendorGridData(){
        $userListToReturn = $this->userRestService->getVendorGridList();

        return Response::json(PRResponse::getSuccessResponse("",$userListToReturn));
    }

    public function addUser(){

        $userResponse = $this->userRestService->addUser(Input::all());
        return Response::json($userResponse);
    }







    public function getUser($id)
    {
        $user = $this->userRestService->getUser($id);
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Details', $user));
    }

    public function getCurrentUser()
    {
        $user = $this->userRestService->getCurrentUser();
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Details', $user));
    }

    public function getCurrentUserContributions()
    {
        $contributions = $this->userRestService->getCurrentUserContributions();
        if (is_null($contributions)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Contributions', $contributions));
    }

    public function makeAdmin($id)
    {
        $user = $this->userRestService->makeAdmin($id);
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function removeAdmin($id)
    {
        $user = $this->userRestService->removeAdmin($id);
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function makeModerator($id)
    {
        $user = $this->userRestService->makeModerator($id);
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function removeModerator($id)
    {
        $user = $this->userRestService->removeModerator($id);
        if (is_null($user)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function makeEditor($id)
    {
        $user = $this->userRestService->makeEditor($id);
        if (is_null($user )) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function removeEditor($id)
    {
        $user = $this->userRestService->removeEditor($id);
        if (is_null($user )) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function makeAuthor($id)
    {
        $user = $this->userRestService->makeAuthor($id);
        if (is_null($user )) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function removeAuthor($id)
    {
        $user = $this->userRestService->removeAuthor($id);
        if (is_null($user )) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Level Changed', $user));
    }

    public function getCurrentUserArticles()
    {
        $articles = $this->userRestService->getCurrentUserArticles();
        if (is_null($articles)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Articles', $articles));
    }
    public function getUserArticles($id)
    {
        $articles = $this->userRestService->getUserArticles($id);
        if (is_null($articles)) {
            return response()->json(SCResponse::getErrorResponse('Unauthorised', []));
        }
        return response()->json(SCResponse::getSuccessResponse('User Articles', $articles));
    }


}