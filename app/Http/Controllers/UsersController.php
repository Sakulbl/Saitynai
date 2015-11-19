<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Hash;
use App\User;
use App\Character;
use App\Http\Controllers\Controller;

class UsersController extends Controller {	

	// /create
	public function index(){
		return view('create');
	}
	
	public function store(){
		$rules = array(
			'username' => 'required|unique:users',
			'password' => 'required|min:6',
			'password-repeat' => 'required|same:password',
			'character' => 'required'
		);
		
		$validator = Validator::make(Input::all(), $rules);
		
		if($validator->fails())
			return Redirect::to('create')
			->withInput()
			->withErrors($validator->messages());
			
		User::create(array(
			'username' => Input::get('username'),
			'password' => Hash::make(Input::get('password'))
		));
		
		Character::create(array(
			'name' => Input::get('character'),
			'username' => Input::get('username'),
			'level' => '0.00',
			'energy' => '1.00',
			'hunger' => '0.00',
			'intellect' => '0.00',
			'strength' => '0.00',
			'action_name' => NULL,
			'last_updated' => date('Y-m-d H:i:s')
		));
		
		return Redirect::to('login');
	}
}



