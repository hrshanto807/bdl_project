<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;



class UserController extends Controller
{
    //create user
    public function Useregister(Request $request){

      try {
        $request->validate([
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users,email',
            'mobile' => 'required|string|max:50',
            'password' => 'required|string|min:3|max:50',
            ]);

    User::create([

        "firstName"=> $request->input('firstName'),
        "lastName"=>$request->input('lastName'),
        "email"=>$request->input('email'),
        "mobile"=>$request->input('mobile'),
        "password"=>Hash::make($request->input('password'))                    
    ]);

       //  Redirect to login page with success message
      return redirect()->route('login')->with('success', 'User Created Successfully! Please log in.');

           } catch (Exception $e) {
           return back()->withErrors(['error' => $e->getMessage()]);
   }
     }

  // user Login 
  public function UserLogin(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:3',
        ]);

        // Find the user by email
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with('status', 'failed')->with('message', 'Invalid credentials');
        }

        // Generate the token
        $token = $user->createToken('authToken')->plainTextToken;

        // Log the user in
        Auth::login($user);

        // If the request expects an API response (e.g., from a mobile app or SPA), return the token
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
        }

        // If the request is a regular web request, redirect to the profile page
        return redirect()->route('userProfilePage')->with('success', 'User logged in successfully');
        
    } catch (Exception $e) {
        return back()->withErrors(['message' => $e->getMessage()]);
    }
}


public function UserProfile(Request $request)
{
   return Auth::user();
}

  

  // send OTP
  public function SendOTP(Request $request)
  {
      try {
          $request->validate([
              'email' => 'required|string|email|max:50',
          ]);
  
          $email = $request->input('email');
          $otp = rand(100000, 999999);
          $count = User::where('email', $email)->count();
  
          if ($count == 1) {
              // Send OTP email
              Mail::to($email)->send(new OTPMail($otp));
              
              // Store OTP in the user record
              User::where('email', '=', $email)->update(['otp' => $otp]);
              
              // Flash success message
              return redirect()->route('verifyotp')->with('status', 'success')->with('message', '6 digit OTP Sent Successfully');
          } else {
              // Flash failure message
              return redirect()->back()->with('status', 'fail')->with('message', 'User Not Found');
          }
      } catch (Exception $e) {
          // Flash error message
          return redirect()->back()->with('status', 'fail')->with('message', $e->getMessage());
      }
  }
  

  public function VerifyOTP(Request $request)
  {
      try {
          $request->validate([
              'email' => 'required|string|email|max:50',
              'otp' => 'required|string|min:1'
          ]);
  
          $email = $request->input('email');
          $otp = $request->input('otp');
  
          $user = User::where('email', $email)->where('otp', $otp)->first();
  
          if (!$user) {
              return back()->with('status', 'fail')->with('message', 'Invalid OTP');
          }
  
          // Clear OTP
          $user->update(['otp' => '0']);
  
          // Log in the user
          Auth::login($user);
  
          // Generate Sanctum token (if needed for API calls)
          $token = $user->createToken('authToken')->plainTextToken;
  
          return redirect()->route('resetPass')->with('status', 'success')->with('message', 'OTP Verified Successfully');
      } catch (Exception $e) {
          return back()->with('status', 'fail')->with('message', $e->getMessage());
      }
  }
  
  
  public function ResetPassword(Request $request)
  {
      try {
          $request->validate([
              'password' => 'required|string|min:3'
          ]);
  
          $userId = Auth::id();
  
          if (!$userId) {
              return redirect()->route('login')->with('status', 'fail')->with('message', 'Unauthorized access.');
          }
  
          User::where('id', $userId)->update([
              'password' => Hash::make($request->input('password'))
          ]);
  
          return redirect()->route('login')->with('status', 'success')->with('message', 'Password Reset Successfully');
      } catch (Exception $e) {
          return back()->with('status', 'fail')->with('message', $e->getMessage());
      }
  }
  

public function UserLogout(Request $request){
  $request->user()->tokens()->delete();

    // Invalidate the session (for web-based session)
    session()->invalidate();
    session()->regenerateToken();

  
  return redirect('/login');
}

public function UpdateProfile(Request $request)
{
    try {
        $request->validate([
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'mobile' => 'required|string|max:15',
        ]);

        User::where('id', Auth::id())->update([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'mobile' => $request->input('mobile'),
        ]);

        return redirect()->route('userProfilePage')->with('status', 'success')->with('message', 'Profile updated successfully!');
    } catch (Exception $e) {
        return redirect()->back()->with('status', 'fail')->with('message', $e->getMessage());
    }
}


// Auth view
  public function UserRegister(){
    return view('componands.registration_form'); 
    
}
    public function profile()
    {
        return view('componands.profile');
    }
public function SendOtpPage(){
  return view('componands.send_otp');
}
public function VerifyOtpPage(){
  return view('componands.verify_otp');
}

public function ResetPasswordPage(){
  return view('componands.reset_pass');
}

}
