<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
	
	public static function select_all_admin()
	{  
		try
		{
			$sql = User::select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), DB::raw('CONCAT(first_name, " ", last_name) AS user_names'), DB::raw('users.id AS user_id'),  'users.id',  'users.first_name', 'users.last_name', 'users.email', 'users.mobile_number',  'users.created_on', 'usergroups.name AS group_name') 
			->join('usergroups', 'users.group_id', '=', 'usergroups.id') 
			->groupby() 
			->get();   
			return	$sql; 
		}
		catch (Exception $e) 
		{ 
			 $result = array(
				'type'=>'Error',
				'code'=>0,
				'text'=>$e->getMessage()); 
				return	$result; 
		} 	 
	} 
	
	public static function select_vendors($groupname) 
	{ 
		$sql = User::select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), DB::raw('CONCAT(first_name, " ", last_name) AS user_names'), DB::raw('users.id AS user_id'),  'users.id',  'users.first_name', 'users.last_name', 'users.email', 'users.mobile_number',  'users.created_on', 'usergroups.name AS group_name') 
		->join('usergroups', 'users.group_id', '=', 'usergroups.id')   
		->where(['users.delete_flag'=>0,'usergroups.name'=>$groupname])
		->get();  
		return	$sql; 
	} 
	
	public static function select_single($id)
	{
		try
		{
			$sql = User::select(DB::raw('CONCAT(first_name, " ", last_name) AS full_name'), DB::raw('CONCAT(first_name, " ", last_name) AS user_names'), DB::raw('users.id AS user_id'),  'users.id',  'users.first_name', 'users.last_name', 'users.email', 'users.mobile_number', 'users.group_id', 'users.created_on', 'usergroups.name AS group_name') 
			->join('usergroups', 'users.group_id', '=', 'usergroups.id')    
			->where(['users.delete_flag'=>0,'users.id'=>$id])
			->groupby() 
			->get();   
			return	$sql; 
		}
		catch (Exception $e) 
		{ 
			 $result = array(
				'type'=>'Error',
				'code'=>0,
				'text'=>$e->getMessage()); 
				return	$result; 
		} 
	}
	
	 
	public static function select_by_email($email)
	{
		  
		
		$sql = User::select(DB::raw('CONCAT(first_name, ", ", last_name) AS full_name'), 'users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile_number')
		->where(['users.delete_flag'=>0,'users.email'=>$email])
		->groupby() 
		->get();   
		return	$sql;  
	} 

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	
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
    protected $table = 'users';
}
