<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Hash;
use Auth;
use App\User;
use App\Character;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class MainController extends Controller {	

	// /
	public function index(){
		return view('login');
	}
	
	// /login
	public function getLogin(){
		return view('login');
	}
	
	public function postLogin(){
		$creds = array(
			'username' => Input::get('username'),
			'password' => Input::get('password')
		);
		
		if(Auth::attempt($creds)){
			return Redirect::intended('overview');
		}else{
			return Redirect::to('login')
			->withInput();
		}
	}
	
	public function store(){
		
	}
}





