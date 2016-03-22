<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'm_user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('user_pass', 'remember_token');
	protected $primaryKey = "id_user";
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
	   return $this->user_pass;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function getUserData($user_name)
	{
		return DB::table($this->table)
						->select(array('id_user','user_name','id_group'))
						->where('user_name', $user_name)
						->first();
	}
	
	public function getUserPrivi($id_group)
	{
		$privi_data = array();
		$data = DB::table('m_user_privilege')->where('id_group', $id_group)->get();
		foreach($data as $row)
		{
			$privi_data[$row->privi_code]['view'] =  $row->view;
			$privi_data[$row->privi_code]['new'] =  $row->new;
			$privi_data[$row->privi_code]['edit'] =  $row->edit;
			$privi_data[$row->privi_code]['delete'] =  $row->delete;
		}
		return $privi_data;
	}

}
