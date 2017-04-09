<?php
namespace App\Models\DTO;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class VendorDocumentDTO{

    public $id;

    public $mime;

    public $vendorId;

    public $documentType;

    public $documentUrl;

    public $caption;

    public $filename;




    public function convertToDTO($vendorDocumentOrm){
        $vendorDocumentDto = new VendorDocumentDTO();
        $vendorDocumentDto->setId($vendorDocumentOrm->id);
        $vendorDocumentDto->setCaption($vendorDocumentOrm->caption);
        $vendorDocumentDto->setFilename($vendorDocumentOrm->filename);
        $vendorDocumentDto->setDocumentUrl(url("/vendor/document/view/".$vendorDocumentOrm->id));
        if($vendorDocumentOrm->documentType){
            $vendorDocumentDto->setDocumentType($vendorDocumentOrm->documentType);
        }
        return $vendorDocumentDto;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return mixed
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * @param mixed $documentType
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     * @return mixed
     */
    public function getDocumentUrl()
    {
        return $this->documentUrl;
    }

    /**
     * @param mixed $documentUrl
     */
    public function setDocumentUrl($documentUrl)
    {
        $this->documentUrl = $documentUrl;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param mixed $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return mixed
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * @param mixed $vendorId
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
    }






}