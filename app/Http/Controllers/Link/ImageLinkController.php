<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 5/30/2016
 * Time: 5:59 PM
 */
class ImageLinkController extends controller
{
    public function generateUserImage($userId) {

        $fileStoragePath = storage_path("app".DIRECTORY_SEPARATOR."user".DIRECTORY_SEPARATOR."avatar".DIRECTORY_SEPARATOR.$userId.".png");
        $userCoverImage = null;
        $maxSize = 200;
        if(file_exists($fileStoragePath)){
            $userCoverImage = Image::make($fileStoragePath);
        }else{
            $fileStoragePath = storage_path("app".DIRECTORY_SEPARATOR."user".DIRECTORY_SEPARATOR."avatar".DIRECTORY_SEPARATOR."default.png");
            $userCoverImage = Image::make($fileStoragePath);
        }
        if($userCoverImage!=null){
            if(Input::has('size')){
                $imageSize = Input::get('size');
                switch ($imageSize) {
                    case 'icon': return $userCoverImage->resize('100','100')->response();
                    case 'small': return $userCoverImage->resize('200','200')->response();
                    case 'medium': return $userCoverImage->resize('400','400')->response();
                    default: return $userCoverImage->response();
                }
            }
            return $userCoverImage->response();
        }
        $img = Image::canvas(1, 1,'#DEDEDE');
        $img->opacity(0);
        return $img->response();
    }
}