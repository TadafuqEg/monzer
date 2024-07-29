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
    public function home(){
        $img1=url(Setting::where('key','introduction_image')->where('category','website')->where('type','file')->first()->value);
        $img2=url(Setting::where('key','intro_image')->where('category','application')->where('type','file')->first()->value);
        $img3=url(Setting::where('key','summary-of-the-edition-image')->where('category','website')->where('type','file')->first()->value);
        $img4=url(Setting::where('key','work_image1')->where('category','website')->where('type','file')->first()->value);
        $img5=url(Setting::where('key','work-image2')->where('category','website')->where('type','file')->first()->value);
        $img6=url(Setting::where('key','work-image3')->where('category','website')->where('type','file')->first()->value);
        $img7=url(Setting::where('key','article-image1')->where('category','website')->where('type','file')->first()->value);
        $img8=url(Setting::where('key','article-image2')->where('category','website')->where('type','file')->first()->value);
        return view('website2.home2',compact('img1','img2','img3','img4','img5','img6','img7','img8'));
    }

    public function contact_us(Request $request){
        ContactUs::create(['name'=>$request->name,'state'=>$request->state,'city'=>$request->city,'area'=>$request->area,'phone'=>$request->phone,'models_num'=>$request->models_num]);
        return $this->sendResponse(null,'تم تسجيل النموزج بنجاح');
    }
}