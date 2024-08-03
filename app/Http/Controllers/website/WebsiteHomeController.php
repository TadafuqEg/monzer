<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\ContactUs;
use App\Models\Accident;
use Illuminate\Validation\Rule;
use Image;
use Str;
use File;

class WebsiteHomeController extends ApiController
{
    public function home2(){
        
        return view('website2.home2');
    }
    public function home1(){
        
        return view('website2.home1');
    }

    public function contact_us(Request $request){
        ContactUs::create(['name'=>$request->name,'state'=>$request->state,'city'=>$request->city,'area'=>$request->area,'phone'=>$request->phone,'models_num'=>$request->models_num]);
        return $this->sendResponse(null,'تم تسجيل النموزج بنجاح');
    }
}