<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Hash;
use Auth;
use DB;
use App\User;
use App\Character;
use App\Http\Controllers\Controller;

class JsonController extends Controller {	
	public function getCharData()
    {
		try{
			$statusCode = 200;
		
			$data = array(
					'character' => DB::table('characters')->where('username', Auth::user()->username)->pluck('name'),
					'level' => floor(DB::table('characters')->where('username', Auth::user()->username)->pluck('level')),
					'energy' => (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy'))*100,
					'hunger' => (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger'))*100,
					'intellect' => floor(DB::table('characters')->where('username', Auth::user()->username)->pluck('intellect')),
					'strength' => floor(DB::table('characters')->where('username', Auth::user()->username)->pluck('strength'))
				);
		} catch (Exception $e){
			$statusCode = 404;
		} finally{
			return response()->json($data, $statusCode);
		}
    }
	
	public function updateCharData()
    {
		try{
			$statusCode = 200;
		
			// Update time when user was last seen on site and energy and hunger values			
			$d1 = DB::table('characters')->where('username', Auth::user()->username)->pluck('last_updated');
			$d2 = date('Y-m-d H:i:s');
			$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
			$reduce_energy = ($diff/20)/100;
			$increase_hunger = ($diff/4)/100;;
			DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', $reduce_energy);
			if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
				DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
			DB::table('characters')->where('username', Auth::user()->username)->increment('hunger', $increase_hunger);
			if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') > 1)
				DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 1]);
			DB::table('characters')->where('username', Auth::user()->username)->update(['last_updated' => date('Y-m-d H:i:s')]);
			
		} catch (Exception $e){
			$statusCode = 404;
		} finally{
			return response()->json("{}", $statusCode);
		}
    }
}