<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Recommendation;
use App\Models\Volunteer;
use App\Models\Setting;
use App\Models\ContactUs;
class AuthController extends ApiController
{
///////////////////////////////////////////  Register  ///////////////////////////////////////////

   

    public function register(Request $request){

        $validator  =   Validator::make($request->all(), [
            'first_name' => ['nullable', 'string', 'max:191'],
            'last_name' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable','unique:users,phone'],
            'password' => ['required', 'string','confirmed'],
            'email' =>['required','email', 'unique:users,email'],
            
            
        ]);
        // dd($request->all());
        if ($validator->fails()) {

            return $this->sendError(null,$validator->errors());
        }
        
        $username='';
        
        do {
            $username=$request->first_name . $request->last_name . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'),0,3);
        } while (User::where('username', $username)->exists());
            //return $this->sendError($username,'اسم المستخدم موجود بالفعل');
        
        
        
        
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'device_token' => $request->fcm_token,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'username'=> $username,
            'password'=>  Hash::make($request->password),
            
        ]);

        $role = Role::where('name','Client')->first();
            
        $user->assignRole([$role->id]);
        $user->token=$user->createToken('api')->plainTextToken;
        $user->picture=getFirstMediaUrl($user,$user->avatarCollection);
        return $this->sendResponse($user,'تم إنشاء الحساب بنجاح');

    }

    public function login(Request $request){
       
        $validator = Validator::make($request->all(), [
            'password' => ['required'],
            'email' =>['required'],
           
        ]);
      
        if ($validator->fails()) {
            return $this->sendError(null,$validator->errors());
        }
        $success_login = false;
        
        $user = User::where(function($query) use ($request) {
                    $query->where('phone', $request->email)
                        ->orWhere('email', $request->email);
                })
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Client');
                })
                ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $success_login = true;
            if($request->device_token){
                $user->device_token=$request->device_token;
                $user->save();
            }
        }
        

        if($success_login){
            $user->token=$user->createToken('api')->plainTextToken;
            $user->picture=getFirstMediaUrl($user,$user->avatarCollection);
           

        }else{
            return $this->sendError(null,"كلمة المرور غير صحيحة",400);
           
        }
        return $this->sendResponse($user);
         
        
    }
///////////////////////////////////////////  Logout  ///////////////////////////////////////////

    public function logout(Request $request){
        $user = $request->user();
        $currentToken = $user->currentAccessToken();
        // Revoke the token of the current device
        $currentToken->delete();
        $response['message']='تم تسجيل الخروج';
        return $this->sendResponse(null,$response['message']);
        
    }

    public function regist_volunteer(Request $request){
        $validator  =   Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            
            'phone' => ['required'],
            'email' =>['required','email'],
            
            
        ]);
        // dd($request->all());
        if ($validator->fails()) {

            return $this->sendError(null,$validator->errors());
        }
        $map=[];
        if(($request->lat != null && $request->lng != null)||$request->link != null){
          $map['lat']=$request->lat;
          $map['lng']=$request->lng;
          $map['link']=$request->link;
        }else{
            $map=null;
        }
        Volunteer::create(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'phone'=>$request->phone,'email'=>$request->email,'location'=>json_encode($map),'street_id'=>$request->street_id]);
        return $this->sendResponse(null,'Volunteer Account created successfuly');
    }


    public function recommend(Request $request){
        $validator  =   Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            
            'phone' => ['required'],
            'email' =>['required','email'],
            
            
        ]);
        // dd($request->all());
        if ($validator->fails()) {

            return $this->sendError(null,$validator->errors());
        }
        Recommendation::create(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'phone'=>$request->phone,'email'=>$request->email,'user_id'=>auth()->user()->id]);
        $message=Setting::where('key','recommendation_message')->where('category','general')->where('type','textarea')->first()->value;
        //$message= 'Respected mr ' .'('. $request->first_name . ' ' . $request->last_name .') '. $message;
        return $this->sendResponse(null,$message);
    }

    public function contact_us(Request $request){
        $validator  =   Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'state' => ['required', 'string', 'max:191'],
            
            'phone' => ['required'],
            'city' =>['required', 'string', 'max:191'],
            
            'area'=>['required', 'string', 'max:191'],
            'models_num'['required'],
        ]);
        // dd($request->all());
        if ($validator->fails()) {

            return $this->sendError(null,$validator->errors());
        }
        ContactUs::create(['name'=>$request->name,'state'=>$request->state,'city'=>$request->city,'area'=>$request->area,'phone'=>$request->phone,'models_num'=>$request->models_num]);
        return $this->sendResponse(null,'شكرا لمشاركتكم');
    }
}