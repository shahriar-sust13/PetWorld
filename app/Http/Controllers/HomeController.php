<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\profileEditReq ;
use App\userProfile;
use App\petImage;
use App\User;
use App\pet;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\InfoCheck;
use File ;

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
        $this->middleware('checkInfo');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pets = NULL ;

        try
        {
            $uid = Auth::user()->id ;

            $pets = pet::where('userId',$uid)->orderBy('id','desc')->paginate(8) ;

            foreach ($pets as $pet) {
                $pet['name'] = $pet->petName ;
                $pet['id'] = $pet->id ;
                $pet['ctg'] = $pet->category ;
                $tp = petImage::where('petId',$pet->id)->firstOrFail() ;
                $pet['img'] = $tp->id ;
                $pet['dam'] = $pet->price ;
            }
        }

        catch(\Exception $ex)
        {
            return "Problems Occured ! Sorry :( " ;
        }

        $ctg = -1 ;
        $type = -1 ;

        return view('home',compact('pets','ctg','type'));
    }

    public function Rhome(Request $req)
    {
        $pets = NULL ;

        try
        {
            $uid = Auth::user()->id ;

            $pets = pet::where('userId',$uid)->orderBy('id','desc')->paginate(8) ;

            foreach ($pets as $pet) {
                $pet['name'] = $pet->petName ;
                $pet['id'] = $pet->id ;
                $pet['ctg'] = $pet->category ;
                $tp = petImage::where('petId',$pet->id)->firstOrFail() ;
                $pet['img'] = $tp->id ;
                $pet['dam'] = $pet->price ;
            }
        }

        catch(\Exception $ex)
        {
            return "Problems Occured ! Sorry :( " ;
        }

        $ctg = -1 ;
        $type = -1 ;

        if($req->ctg >= 2 && $req->ctg <= 4)
            $ctg = $req->ctg ;

        if($req->type >= 2 && $req->type <= 3)
            $type = $req->type ;

        return view('home',compact('pets','ctg','type'));
    }

    public function profile($profileId)
    {
        $data = NULL ;

        try
        {
            //$temp = User::findOrFail($profileId) ;
            // $data['name'] = $temp->name ;
            // return $temp->name ;
            $data['name'] = User::findOrFail($profileId)->name ;
        }

        catch(\Exception $ex)
        {
            return "Invlid Request ".$profileId ;
        }

        try
        {
            $temp = userProfile::where('userId',$profileId)->get();
            $data['fb'] = $temp[0]['facebookLink'] ;
            $data['twt'] = $temp[0]['TwitterLink'] ;
            $data['descr'] = $temp[0]['intro'] ;
            $data['imgId'] = $profileId ;
        }

        catch(\Exception $ex)
        {
            return "Something Wrong" ;
        }

        $pets = NULL ; 


        try
        {
            $pets = pet::where('userId',$profileId)->orderBy('id','desc')->paginate(8) ;

            foreach ($pets as $pet) {
                $pet['name'] = $pet->petName ;
                $pet['id'] = $pet->id ;
                $tp = petImage::where('petId',$pet->id)->firstOrFail() ;
                $pet['img'] = $tp->id ;
            }
        }

        catch(\Exception $ex)
        {
            return "Oh shit !!";
        }
//return $data ;
        return view('profile',compact('data','pets'));
    }
}
