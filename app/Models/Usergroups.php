<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Usergroups extends Model
{
	public static function select_all()
	{
		$sql = DB::table('usergroups')  
            ->select('usergroups.id', 'usergroups.name',  'usergroups.created_by', 'usergroups.created_on', 'usergroups.modified_by', 'usergroups.modified_on', 'usergroups.delete_flag') 
			->where('usergroups.delete_flag', '=', 0) 
			->groupby()
            ->orderby('usergroups.name', 'asc')
			->get()->map(function ($item, $key) {
					return (array) $item;
				})
			->all();   
		return	$sql;  
		 
		
	}
	  
	
	public static function select_single($id)
	{
		$sql = DB::table('usergroups')
		 ->select('usergroups.id', 'usergroups.name',  'usergroups.created_by', 'usergroups.created_on', 'usergroups.modified_by', 'usergroups.modified_on', 'usergroups.delete_flag') 
		->where(['usergroups.delete_flag'=>0,'usergroups.delete_flag'=>0,'usergroups.id'=>$id])
		->groupby()
		->orderby('usergroups.name', 'desc') 
		->get()->map(function ($item, $key) {
				return (array) $item;
			})
		->all();   
		return	$sql;  
	}
		
	public static function select_by_id($id)
	{
		$sql = DB::table('usergroups')
		 ->select('usergroups.id', 'usergroups.name',  'usergroups.created_by','usergroups.created_on','usergroups.modified_by', 'usergroups.modified_on') 
		->where(['usergroups.delete_flag'=>0, 'usergroups.id'=>$id]) 
		->groupby()
		->orderby('usergroups.id', 'desc') 
		->get()->map(function ($item, $key) {
				return (array) $item;
			})
		->all();   
		
		return	$sql;  
		 
	}
	
	public static function select_by_name($name)
	{
		$sql = DB::table('usergroups')
		 ->select('usergroups.id', 'usergroups.name',  'usergroups.created_by','usergroups.created_on','usergroups.modified_by', 'usergroups.modified_on') 
		->where(['usergroups.delete_flag'=>0, 'usergroups.name'=>$name]) 
		->groupby()
		->orderby('usergroups.id', 'desc') 
		->get()->map(function ($item, $key) {
				return (array) $item;
			})
		->all();   
		
		return	$sql;  
		 
	}
	
	public static function soft_delete($deleteid, $modified_by, $modified_on)
	{			 
		
		if( is_array($deleteid) )
		{ 
			DB::table('usergroups')
			->whereIn('id', $deleteid)
			->update(['delete_flag' => '1', 'modified_by' => $modified_by, 'modified_on' => $modified_on]); 
		}
	}
	
    protected $primaryKey = 'id';
	/**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
	
    public $timestamps = false;
	 const CREATED_AT = 'created_on';
    const UPDATED_AT = 'modified_on';
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usergroups';
}
 