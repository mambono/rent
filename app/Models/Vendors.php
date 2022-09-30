<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Vendors extends Model
{
   
	public static function select_all()
	{
			$sql = Vendors::select('vendors.id', 'vendors.city_id', 'vendors.user_id',  'vendors.vendor_name', 'vendors.address', 'vendors.email', 'vendors.telephone_number', 'cities.city', DB::raw("CONCAT(users.first_name,' ',users.last_name) as vendor"), 'vendors.created_by', 'vendors.created_on', 'vendors.modified_by', 'vendors.modified_on', 'vendors.delete_flag')
			->join('cities', 'vendors.city_id', '=', 'cities.id') 
			->leftJoin('users', 'users.id', '=', 'vendors.user_id')  
			->where(['vendors.delete_flag'=>0 ]) 
			->groupby()
			->orderby('vendors.vendor_name','asc')
			->get();    
			 
				
			
		return	$sql;  
		
	}
	  
	
	public static function select_single($id)
	{
		$sql = vendors::select('vendors.id',   'vendors.city_id', 'vendors.user_id', 'vendors.vendor_name', 'vendors.address', 'vendors.email', 'vendors.telephone_number',DB::raw("CONCAT(users.first_name,' ',users.last_name) as vendor"),  'cities.city',  'vendors.created_by', 'vendors.created_on', 'vendors.modified_by', 'vendors.modified_on', 'vendors.delete_flag')
		->join('cities', 'vendors.city_id', '=', 'cities.id') 
		->leftJoin('users', 'users.id', '=', 'vendors.user_id')->where(['vendors.delete_flag'=>0, 'vendors.id'=>$id])
		->groupby()
		->orderby('vendors.vendor_name', 'desc') 
		->get();   
		return	$sql;  
	}
	  
	
	public static function soft_delete($deleteid, $modified_by, $modified_on)
	{			 
		
		if( is_array($deleteid) )
		{ 
			DB::table('vendors')
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
 
    protected $table = 'vendors';
}
 