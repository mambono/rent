<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Bikes extends Model
{
  
	
	
	public static function select_all()
	{
			$sql = Bikes::select('bikes.id', 'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city', 'bikes.created_by', 'bikes.created_on', 'bikes.modified_by', 'bikes.modified_on', 'bikes.delete_flag')
			->join('cities', 'bikes.city_id', '=', 'cities.id') 
			->where(['bikes.delete_flag'=>0 ]) 
			->groupby()
			->orderby('bikes.short_name','asc')
			->get();    
			 
				
			
		return	$sql;   
	} 
	
	public static function select_my_bikes($id)
	{
			$sql = Bikes::select('bikes.id', 'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city', 'bikes.created_by', 'bikes.created_on', 'bikes.modified_by', 'bikes.modified_on', 'bikes.delete_flag')
			->join('cities', 'bikes.city_id', '=', 'cities.id') 
			->join('vendors', 'vendors.id', '=', 'bikes.vendor_id')  
			->where(['bikes.delete_flag'=>0, 'vendors.user_id'=>$id ]) 
			->groupby()
			->orderby('bikes.short_name','asc')
			->get();    
			 
				
			
		return	$sql;   
	}
	  
	
	public static function select_single($id)
	{
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city', 'vendors.vendor_name', 'bikes.created_by', 'bikes.created_on', 'bikes.modified_by', 'bikes.modified_on', 'bikes.delete_flag')
		->join('cities', 'bikes.city_id', '=', 'cities.id')  
		->join('vendors', 'bikes.vendor_id', '=', 'vendors.id')  
		->where(['bikes.delete_flag'=>0, 'bikes.id'=>$id])
		->groupby()
		->orderby('bikes.short_name', 'desc') 
		->get();   
		return	$sql;  
	}
	  
	
	public static function soft_delete($deleteid, $modified_by, $modified_on)
	{			 
		
		if( is_array($deleteid) )
		{ 
			DB::table('bikes')
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
 
    protected $table = 'bikes';
}
 