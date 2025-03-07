<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();
        //  dd($user);
        // Ensure the user exists and has the 'admin' role
        if ($user==null || $user->user_role === 'client') {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Invalid credentials or unauthorized access.',
            ]);
        }
    
        // Authenticate only if the user is an admin
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('app'); // Redirect to admin dashboard
        }
    
        return back()->withErrors(['email' => 'Invalid admin credentials']);
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
