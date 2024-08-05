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
use App\Models\JoinUs;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Image;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Mail;
use File;

class WebsiteHomeController extends ApiController
{
    public function home2(){
        
        return view('website2.home2');
    }
    public function home1(){
        
        return view('website2.home1');
    }
    public function home3(){
       // Mail::to('01154857080fa@gmail.com')->send(new UserCredentialsMail('fflieuro@gmail.com', '124596782', 'username'));
        return view('website2.home3');
    }
    public function join_us(Request $request){
        JoinUs::create(['email'=>$request->email]);
        $password = Str::random(10);
        $username='';
        
        do {
            $username='username' . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'),0,3);
        } while (User::where('username', $username)->exists());

        $email='';
        
        do {
            $email='username' . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'),0,3) .'@gmail.com';
        } while (User::where('email', $email)->exists());
        $user= User::create([

            'email' => $email,
            'password' => Hash::make($password),
            'username' => $username // or generate a username as you wish
        ]);
        $role = Role::where('name','Client')->first();
            
        $user->assignRole([$role->id]);
        Mail::to($request->email)->send(new UserCredentialsMail($email, $password, $username));

        return $this->sendResponse(null,'شكرا لإنضمامكم معنا');
    }
    public function contact_us(Request $request){
        ContactUs::create(['name'=>$request->name,'state'=>$request->state,'city'=>$request->city,'area'=>$request->area,'phone'=>$request->phone]);
        return $this->sendResponse(null,'تم تسجيل النموزج بنجاح');
    }
}