<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Bookings extends Model
{
  
	
	
	public static function select_all()
	{
			$sql = Bikes::select('bikes.id',     'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city', 'bike_bookings.start_date', 'bike_bookings.end_date', 'vendors.vendor_name', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name') )
			->join('cities', 'bikes.city_id', '=', 'cities.id') 
			->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')  
			->join('vendors', 'vendors.id', '=', 'bikes.vendor_id')  
			->join('users', 'bike_bookings.booked_by', '=', 'users.id')    
			->where([ 'bike_bookings.delete_flag'=>0, 'bikes.delete_flag'=>0 ]) 
			->groupby()
			->orderby('bikes.short_name','asc')
			->get();     
		return	$sql;   
	}
	
	public static function select_filtered($bike_id, $vendor_id, $user_id, $start_date, $end_date)
	{
		$where1 = null;
		$where2 = null;
		$where3 = null;
		$where4 = null;
		
		
		if(($start_date != '')	&& ($end_date != ''))
		{  
			$where1 = DB::raw('date(bike_bookings.end_date) <= "'.$end_date.'" AND date(bike_bookings.start_date) >= "'.$start_date.'" AND null');
		}
		if($bike_id != null) 
		{
			$where2 = ['bikes.id'=>$bike_id]; 
		} 
		if(isset($vendor_id))
		{ 
			$where3 = ['bikes.vendor_id'=>$vendor_id]; 
		}
		if($user_id != null) 
		{
			$where4 = ['bike_bookings.booked_by'=>$user_id]; 
		} 
		
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city', 'bike_bookings.start_date', 'bike_bookings.end_date', 'vendors.vendor_name', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),   'bikes.delete_flag')
			->join('cities', 'bikes.city_id', '=', 'cities.id') 
			->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')  
			->join('vendors', 'vendors.id', '=', 'bikes.vendor_id')  
			->join('users', 'bike_bookings.booked_by', '=', 'users.id')    
			->where([ 'bike_bookings.delete_flag'=>0, 'bikes.delete_flag'=>0 ]) 
			->where($where1)
			->where($where2)
			->where($where3)
			->where($where4)
			->groupby()
			->orderby('bikes.short_name','asc')
			->get();     
		return	$sql;   
	}
	 
	public static function select_my_bikes($id)
	{
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size', 'cities.city', 'bike_bookings.start_date', 'bike_bookings.end_date', 'vendors.vendor_name', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))
		->join('cities', 'bikes.city_id', '=', 'cities.id') 
		->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')  
		->join('vendors', 'vendors.id', '=', 'bikes.vendor_id')  
		->join('users', 'bike_bookings.booked_by', '=', 'users.id')    
		->where(['bikes.delete_flag'=>0, 'bike_bookings.delete_flag'=>0, 'vendors.user_id'=>$id])
		->groupby()
		->orderby('bikes.short_name', 'desc') 
		->get();   
		return	$sql;  
	} 
	
	public static function select_vendor_bikes($id)
	{
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size', 'cities.city', 'bike_bookings.start_date', 'bike_bookings.end_date', 'vendors.vendor_name', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))
		->join('cities', 'bikes.city_id', '=', 'cities.id') 
		->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')  
		->join('vendors', 'vendors.id', '=', 'bikes.vendor_id')  
		->join('users', 'bike_bookings.booked_by', '=', 'users.id')    
		->where(['bikes.delete_flag'=>0, 'bike_bookings.delete_flag'=>0, 'vendors.vendor_name'=>$id])
		->groupby()
		->orderby('bikes.short_name', 'desc') 
		->get();   
		return	$sql;  
	}  
	
	public static function select_single($id)
	{
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size','bikes.electric', 'bikes.gear_speed', 'cities.city',  'bikes.created_by', 'bikes.created_on', 'bikes.modified_by', 'bikes.modified_on', 'bikes.delete_flag')
		->join('cities', 'bikes.city_id', '=', 'cities.id') 
		->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')   
		->where(['bikes.delete_flag'=>0, 'bikes.id'=>$id])
		->groupby()
		->orderby('bikes.short_name', 'desc') 
		->get();   
		return	$sql;  
	}
	 
	public static function select_my($id)
	{
		$sql = Bikes::select('bikes.id',   'bikes.city_id', 'bikes.vendor_id', 'bikes.short_name', 'bikes.color', 'bikes.hourly_cost', 'bikes.size', 'cities.city', 'bike_bookings.start_date', 'bike_bookings.end_date')
		->join('cities', 'bikes.city_id', '=', 'cities.id') 
		->join('bike_bookings', 'bike_bookings.bike_id', '=', 'bikes.id')  
		->where(['bikes.delete_flag'=>0, 'bike_bookings.delete_flag'=>0, 'bike_bookings.booked_by'=>$id])
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
 