<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;

class UserController extends Controller
{

    public function test(){
        $user = User::first();
        return $user;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'string|required|min:2',
            'email' => 'string|email|required|max:100|unique:users',
            'password' =>'string|required|confirmed|min:6',
            'user_name' =>'unique:users'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $user = new User;
        $user->name = $request->name;
        $user->user_name = $this->setUsernameAttribute($user->name);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([$user]);
    }

    public function sendOtp(Request $request)
    {
//        $otp = rand(100000,999999);
//        $time = time();
//
//        EmailVerification::updateOrCreate(
//            ['email' => $request->email],
//            [
//                'email' => $request->email,
//                'otp' => $otp,
//                'created_at' => $time
//            ]
//        );
//
//        $data['email'] = $request->email;
//        $data['title'] = 'Mail Verification';
//
//        $data['body'] = 'Your OTP is:- '.$otp;
//
//        Mail::send('mailVerification',['data'=>$data],function($message) use ($data){
//            $message->to($data['email'])->subject($data['title']);
//        });

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json(['error' => 'Email not found'], 401);
        }else{
            $otp =$this->generateCode();
            Mail::to($request->email)->send(new SendMail($otp));
            $user->otp = $otp;
            $user->is_verified = 0;
            $user->update();
            return response()->json(["message"=>"Please check your email for get the OTP"]);
        }
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if (!$token = auth()->attempt($validator->validated()))
        {
            return response() ->json(['error'=>'Unauthorized']);
        }
        return $this->responseWithToken($token);

    }
    protected function responseWithToken($token){
        return response()->json([
            'success'=>true,
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()-> getTTL() * 60
        ]);

    }



    public function verification($id)
    {
        $user = User::where('id',$id)->first();
        if(!$user || $user->is_verified == 1){
            return response()->json(['message'=>'Please register First']);
        }
 else{
        $email = $user->email;

        $this->sendOtp($user);

        return response()->json(['message'=>'check your mail to collect your otp pin']);
    }
}

    public function verifiedOtp(Request $request)
    {
        $user = User::first();
        $otpData = EmailVerification::where('otp',$request->otp)->first();
        if(!$otpData){
            return response()->json(['success' => false,'msg'=> 'You entered wrong OTP']);
        }
        else{

            $currentTime = time();
            $time = $otpData->created_at;

            if($currentTime >= $time && $time >= $currentTime - (180+5)){
                User::where('id',$user->id)->update([
                    'is_verified' => 1
                ]);
                return response()->json([
                    'success' => true,'msg'=> 'Mail has been verified',
                    'user id is' => $user->user_name,
                ]);
            }
            else{
                return response()->json(['success' => false,'msg'=> 'Your OTP has been Expired']);
            }

        }
    }

    public function resendOtp(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        $otpData = EmailVerification::where('email',$request->email)->first();

        $currentTime = time();
        $time = $otpData->created_at;

        if($currentTime >= $time && $time >= $currentTime - (180+5)){
            return response()->json(['success' => false,'msg'=> 'Please try after some time']);
        }
        else{

            $this->sendOtp($user);
            return response()->json(['success' => true,'msg'=> 'OTP has been sent']);
        }

    }
    public function resetPassword(request $request){
        $request->validate([
        'password'=>'required|string|min:6|confirmed'
        ]);
        $user= User::find($request->id);
        $user->password=Hash::make($request->password);
        $user->save();

        PasswordReset::where('email',$user->email)->delete();

        return "<h1> Your password has been reset successfully</h1>";

    }
    public function logout()
    {
        try{
        auth()->logout();
        return response()->json(['status'=>true,'message'=>'User Successfully Logged Out']);
    }
    catch(\Exception $e){
        return response()->json(['status'=>false,'message'=>$e->getMessage()]);
    }
}
public function profile()
{
    try{

        return response()->json(auth()->user());
    }
    catch(\Exception $e){
        return response()->json(['status'=>false,'message'=>$e->getMessage()]);
    }


}
public function profileUpdate(Request $request){
    if(auth()->user()){
$validator= Validator::make($request->all(),[
    'id'=>'required',
    'name'=>'required|string',
    'email'=>'required|email|string'
]);
if ($validator->fails()){
    return response()->json($validator->errors(),400);
}
$user=User::find($request->id);

$user->name=$request->name ;
$user->email=$request->email ;
$user->save();
return response()->json(['status'=>true,'message'=>'user is updated','Data'=>$user]);
    }
else{
    return response()->json(['status'=>false,'message'=>'User is not Authenticated']);
}
}
public function refreshToken()
    {
        if(auth()->user()){
            return $this->responseWithToken(auth()->refresh());
      }
      else{
        return response()->json(['success'=>false,'message'=>'User is not authenticated.']);
      }
    }

    private function setUsernameAttribute($name)
    {
        $str_lower = strtolower($name);
        $username = str_replace(' ', '', $str_lower);
        $i = 0;
        while(User::whereuser_name($username)->exists())
        {
            $i++;
            $username = $username . $i;
        }
        return $username;
    }

    public function generateCode(){
        $this->timestamps = false;
        $this->otp =rand(100000,999999);
        $this->expire_at = now()->addMinute(3);
        return $this->otp;
    }
}
