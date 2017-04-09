<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintResolutionGroupsMembers extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaint_resolution_groups_members';

    use SoftDeletes;
}