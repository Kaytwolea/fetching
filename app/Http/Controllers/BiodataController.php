<?php

namespace App\Http\Controllers;

use App\Mail\registration;
use App\Mail\Twofactor;
use App\Models\biodata;
use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class BiodataController extends Controller
{
    //
    public function biodataSignup(Request $request)
    {
        try {
            $input = $request->validate([
                'title' => 'required | string',
                'firstname' => 'required | string',
                'lastname' => 'required | string',
                'date_of_birth' => 'required | date',
                'nationality' => 'required | string',
                'email' => 'required | email',
                'phone' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null,
                'error' => true,
            ], 400);
        }
        // $newBiodata = biodata::create($input);
        $newBiodata = new Biodata();
        $newBiodata->title = $request->title;
        $newBiodata->firstname = $request->firstname;
        $newBiodata->lastname = $request->lastname;
        $newBiodata->date_of_birth = $request->date_of_birth;
        $newBiodata->nationality = $request->nationality;
        $newBiodata->email = $request->email;
        $newBiodata->phone = $request->phone;
        $newBiodata->save();

        if ($newBiodata) {
            return response()->json([
                'message' => 'User Biodata created successfully',
                'data' => $newBiodata,
                'error' => false,
            ], 201);
        } else {
            return response()->json([
                'message' => 'An error has occurred, try again',
                'data' => null,
                'error' => true,
            ], 400);
        }
    }

    public function Signup(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'phone_number' => 'required',

            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        $data['password'] = Hash::make($request->password);
        $data['confirmation_code'] = rand(10000, 90000);
        $newUser = User::create($data);
        $token = $newUser->createToken('access-token')->accessToken;
        Mail::send(new registration($newUser));

        return response()->json([
            'message' => 'User Signup Success',
            'token' => $token,
            'data' => $newUser,
            'error' => false,
        ], 200);
    }

    public function getUser(Request $request)
    {
        $authUser = auth()->user();

        return response()->json([
            'message' => 'User Returned Successfully',
            'data' => $authUser,
        ]);
    }

    public function Logout(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        if ($user->two_factor_status) {
            $user->two_factor_auth = false;
            $user->save();
            auth()->user()->token()->revoke();

            return response()->json([
                'message' => 'User Logout Success',
            ]);
        } else {
            auth()->user()->token()->revoke();

            return response()->json([
                'message' => 'User Logout Success',
            ]);
        }
    }

    public function Login(Request $request)
    {
        $loginInfo = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $check_user = Auth::attempt($loginInfo);
        if ($check_user) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('access-token')->accessToken;

            return response()->json([
                'message' => 'User Login Success',
                'token' => $token,
                'data' => $user,
                'error' => false,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid login details',
                'data' => null,
                'token' => null,
                'error' => true,
            ], 401);
        }
    }

    public function notLoggedin()
    {
        return response()->json([
            'message' => 'Login to proceed',
            'data' => null,
            'error' => true,
        ], 401);
    }

    public function resendCode(Request $request)
    {
        try {
            $input = $request->validate([
                'email' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }

    if ($request->user()->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Your email have already been verified, proceed to login',
            'error' => true,
        ], 403);
    }

    $user_id = auth()->id();
        $user = User::where('id', $user_id)->first();
        $user->confirmation_code = rand(10000, 99999);
        $user->email = $input['email'];
        $user->save();
        Mail::send(new registration($user));

        return $user;
    }

public function verifyCode(Request $request)
{
    try {
        $code = $request->validate([
            'confirmation_code' => 'required',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ]);
    }

    $user_verification_code = auth()->user()->confirmation_code;

    if ($request->user()->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Your email have already been verified, proceed to login',
            'error' => true,
        ], 403);
    }

    if ($code['confirmation_code'] == $user_verification_code) {
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'Your email have been successfully verified',
        ], 200);
    } else {
        return response()->json([
            'message' => 'kindly enter a correct verification code',
        ], 401);
    }
}

public function Getuserlist()
{
    return User::orderBy('id', 'desc')->get();
}

public function ToggleTwoFactor()
{
    $user = User::where('id', auth()->id())->first();
    $user->two_factor_status = ! $user->two_factor_status;
    $user->save();
    $msg = $user->two_factor_status ? 'Two Factor Authentication Enabled Successfully'
     : 'Two Factor Authentication disabled Successfully';

    return response()->json([
        'message' => $msg,
        'error' => false,
        'data' => null,
    ], 200);
}

public function TwoFactorMail()
{
    $user = User::where('id', auth()->id())->first();
    if (! $user->two_factor_status) {
        return response()->json([
            'message' => 'Two Factor Authentication not enabled',
            'data' => null,
            'error' => true,
        ], 400);
    }
    $user->two_factor_code = rand(11111, 99999);
    $user->save();
    Mail::send(new Twofactor($user));

    return response()->json([
        'message' => 'Two Factor Authentication code sent',
        'data' => null,
        'error' => false,
    ], 200);
}

public function ConfirmTwoFactor(Request $request)
{
    $input = $request->validate([
        'code' => 'required',
    ]);

    $user = User::where('id', auth()->id())->first();

    if ($input['code'] == $user->two_factor_code) {
        $user->two_factor_auth = true;
        $user->save();

        return response()->json([
            'message' => 'Two Factor Authentication code verified',
            'data' => null,
            'error' => false,
        ], 200);
    } else {
        return response()->json([
            'message' => 'incorrect code',
            'data' => null,
            'error' => true,
        ], 400);
    }
}

public function Verifynumber(Request $request)
{
    try {
        $phone = $request->validate([
            'phone_number' => 'required|unique:users',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'data' => null,
            'error' => true,
        ], 400);
    }

    $prefixes = ['234'];
    $check_phone = substr($request->phone_number, 0, 3);
    $phonelength = strlen($request->phone_number);
    $api_key = 'TLs2Bl5jWipLkeH6OatRBn6ib3kl3nTuH7dN9xu46v5SuKrcqKCQKwlvkoQAUq';
    $user = User::where('id', auth()->id())->first();
    $user->phone_number = $request->phone_number;
    $user->phone_number_code = rand(11111, 88888);
    $user->save();

    if ($user->phone_number_status == 1) {
        return response()->json([
            'message' => 'Phone number is already verified',
            'data' => null,
            'error' => true,
        ], 400);
    } elseif ($phonelength !== 13) {
        return response()->json([
            'message' => 'Phone number is not valid',
            'data' => null,
            'error' => true,
        ], 400);
    } else {
        $curl = curl_init();
        $data = ['api_key' => $api_key, 'to' => $request->phone_number,  'from' => 'Kaytwo',
            'sms' => "Hi there, this is kaytwolea enter $user->phone_number_code as your verification code",  'type' => 'plain',  'channel' => 'generic'];

        $post_data = json_encode($data);

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.ng.termii.com/api/sms/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}

public function Verifyphonecode(Request $request)
{
    try {
        $input = $request->validate([
            'code' => 'required',
        ]);
    } catch(Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'error' => true,
            'data' => null,
        ], 400);
    }

    $user = User::where('id', auth()->id())->first();

    if ($request->code == $user->phone_number_code) {
        $user->phone_number_status = ! $user->phone_number_status;
        $user->save();

        return response()->json([
            'message' => 'Your phone number is verified',
            'data' => $user,
            'error' => false,
        ], 200);
    } else {
        return response()->json([
            'message' => 'Wrong code',
            'error' => true,
            'data' => null,
        ], 400);
    }
}

public function Createpost(Request $request, Post $post)
{
    try {
        $data = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'data' => null,
            'error' => true,
        ], 400);
    }
    $data['user_id'] = auth()->id();
    $newpost = Post::create($data);

    return response()->json([
        'message' => 'Post created',
        'data' => $newpost,
        'error' => false,
    ], 201);
}

public function getUserPosts(Request $request)
{
    $user = User::whereId(auth()->id())->first();
    $userPost = $user->thepost;

    return response()->json([
        'message' => 'Posts returned successfully',
        'data' => $userPost,
        'error' => false,
    ], 200);
}

public function Comment(Request $request, comment $comment)
{
    try {
        $comment = $request->validate([
            'comment' => 'required',
            'post_id' => 'required',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'error' => true,
            'data' => null,
        ], 400);
    }

    $createcomment = comment::create($comment);
    return response()->json([
        'message' => 'Comment created',
        'error' => false,
        'data' => $createcomment,
    ], 201);
}
public function getPostcomment (Request $request)
{
    $input = $request->validate([
        'id' => 'require'
    ]);
//    return Post::where('id', id())->first();
//    $postcomment = $post->comment;
//    return response()->json([
//        'message' => 'Comment returned successfully',
//        'error' => false,
//        'data' => $postcomment
//    ], 200);
}
}
