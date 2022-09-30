<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Cities extends Model
{
 
	 
	public static function select_all()
	{
			$sql = Cities::select('cities.id',  'cities.city',   'cities.created_by', 'cities.created_on', 'cities.modified_by', 'cities.modified_on', 'cities.delete_flag')
			->where(['cities.delete_flag'=>0 ]) 
			->groupby()
			->orderby('cities.city','asc')
			->get();    
			 
				
			
		return	$sql;  
		 
		
	}
	  
	
	public static function select_single($id)
	{
		$sql = Cities::select('cities.id',   'cities.city',  'cities.created_by', 'cities.created_on', 'cities.modified_by', 'cities.modified_on', 'cities.delete_flag') 
		->where(['cities.delete_flag'=>0, 'cities.id'=>$id])
		->groupby()
		->orderby('cities.city', 'desc') 
		->get();   
		return	$sql;  
	}
	  
	
	public static function soft_delete($deleteid, $modified_by, $modified_on)
	{			 
		
		if( is_array($deleteid) )
		{ 
			DB::table('cities')
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
 
    protected $table = 'cities';
}
 