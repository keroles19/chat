<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;

use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function private()
    {
        return view('private');
    }

    public function users()
    {
        return User::all();
    }

    public function friends()
    {
       return DB::table('friends')
       ->select('users.*')
       ->join('users','friends.friend_id','=','users.id')
            ->where('friends.user_id', '=', auth()->user()->id)
            ->orWhere('friends.user_id', '=', auth()->user()->id)
            ->get();
    }
}

