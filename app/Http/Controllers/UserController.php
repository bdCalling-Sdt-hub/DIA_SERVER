<?php

namespace App\Http\Controllers;

use App\Events\SendNotification;
use App\Models\EmailVerification;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:2',
            'email' => 'string|email|required|max:100|unique:users',
            'password' => 'string|required|confirmed|min:6'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([$user]);
    }

    public function sendOtp(Request $request)
    {
        $otp = rand(100000, 999999);
        $time = time();

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'otp' => $otp,
                'created_at' => $time
            ]
        );

        $data['email'] = $request->email;
        $data['title'] = 'Mail Verification';

        $data['body'] = 'Your OTP is:- ' . $otp;

        Mail::send('mailVerification', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Sending otp plese check your mail'
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized']);
        }
        return $this->responseWithToken($token);
    }

    protected function responseWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function verification($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user || $user->is_verified == 1) {
            return response()->json(['message' => 'Please register First']);
        } else {
            $email = $user->email;

            $this->sendOtp($user);

            return response()->json(['message' => 'check your mail to collect your otp pin']);
        }
    }

    public function verifiedOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $otpData = EmailVerification::where('otp', $request->otp)->first();
        if (!$otpData) {
            return response()->json(['success' => false, 'msg' => 'You entered wrong OTP']);
        } else {
            $currentTime = time();
            $time = $otpData->created_at;

            if ($currentTime >= $time && $time >= $currentTime - (180 + 5)) {
                User::where('id', $user->id)->update([
                    'is_verified' => 1
                ]);
                return response()->json(['success' => true, 'msg' => 'Mail has been verified']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Your OTP has been Expired']);
            }
        }
    }

    // public function resendOtp(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();
    //     $otpData = EmailVerification::where('email', $request->email)->first();

    //     $currentTime = time();
    //     $time = $otpData->created_at;

    //     if ($currentTime >= $time && $time >= $currentTime - (180 + 5)) {
    //         return response()->json(['success' => false, 'msg' => 'Please try after some time']);
    //     } else {
    //         $this->sendOtp($user);
    //         return response()->json(['success' => true, 'msg' => 'OTP has been sent']);
    //     }
    // }

    public function resetPassword(request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'success your password change'
        ]);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['status' => true, 'message' => 'User Successfully Logged Out']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function profile()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function profileUpdate(Request $request)
    {
        if (auth()->user()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required|string',
                'email' => 'required|email|string',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $user = User::find($request->id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->image = $request->image;
            $user->save();
            return response()->json(['status' => true, 'message' => 'user is updated', 'Data' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => 'User is not Authenticated']);
        }
    }

    public function refreshToken()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function profileEdit($id)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $editeUser = User::where('id', $id)->first();
            return response()->json([
                'status' => 'success',
                'data' => $editeUser
            ]);
        }
    }

    // public function profileUpdate(Request $request)
    // {
    //     if (auth()->user()) {
    //         $validator = Validator::make($request->all(), [
    //             'id' => 'required',
    //             'name' => 'required|string',
    //             'email' => 'required|email|string'
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json($validator->errors(), 400);
    //         }
    //         $user = User::find($request->id);

    //         $user->name = $request->name;
    //         $user->email = $request->email;
    //         $user->save();
    //         return response()->json(['status' => true, 'message' => 'user is updated', 'Data' => $user]);
    //     } else {
    //         return response()->json(['status' => false, 'message' => 'User is not Authenticated']);
    //     }
    // }

    // public function refreshToken()
    // {
    //     if (auth()->user()) {
    //         return $this->responseWithToken(auth()->refresh());
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'User is not authenticated.']);
    //     }
    // }
    public function sendNotification(Request $request)
    {
        try {
            event(new SendNotification($request->message, auth()->user()->id));

            return response()->json(['success' => true, 'msg' => 'Notification Added']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
