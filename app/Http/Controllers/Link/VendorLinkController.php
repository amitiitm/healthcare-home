<?php

namespace App\Http\Controllers\Link;

use App\Contracts\Domain\IVendorDomainContract;
use App\Http\Controllers\Controller;
use App\Services\Domain\OperationDomainService;
use App\Services\Rest\OperationRestService;
use App\Templates\PRResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class VendorLinkController extends Controller
{

    protected $vendorDomainService;

    public function __construct(IVendorDomainContract $IVendorDomainContract)
    {
        $this->vendorDomainService = $IVendorDomainContract;
    }

    public function getVendorProfile($userId) {
        $model = array('vendorId'=>$userId);
        return view('admin.vendor.vendor_profile')->with('model',$model);
    }
    public function editVendorProfile($vendorId) {
        $model = array('vendorId'=>$vendorId);
        return view('admin.vendor.vendor_profile_edit')->with('model',$model);
    }
    public function newVendor() {
        return view('admin.vendor.newVendor');
    }

    public function editVendor($vendorId)
    {
        $model = array('vendorId'=>$vendorId);
        return view('admin.vendor.editVendor')->with('model',$model);
    }

    public function getVendorDocumentPreview($documentId){
        $documentOrm = $this->vendorDomainService->getVendorDocumentById($documentId);

        //d($documentOrm);
        $folder = "other";
        if($documentOrm && $documentOrm->documentType && $documentOrm->documentType->directory && $documentOrm->documentType->directory!=""){
            $folder = $documentOrm->documentType->directory;
        }
        $fileStoragePath = storage_path("app".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$documentId.".png");
        $userCoverImage = null;
        $maxSize = 200;
        if(file_exists($fileStoragePath)){
            $userCoverImage = Image::make($fileStoragePath);
        }else{
            $fileStoragePath = storage_path("app".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."default.png");
            $userCoverImage = Image::make($fileStoragePath);
        }
        if($userCoverImage!=null){
            if(Input::has('size') && false){
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