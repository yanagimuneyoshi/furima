<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  public function index()
  {
    return view('auth.login');
  }

  public function authenticate(Request $request)
  {
  
      $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
      ]);

      // \Log::info('Login attempt with credentials:', $credentials);

      if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        // \Log::info('Authenticated user:', [Auth::user()]);
        return redirect()->intended('/');
      }

      throw ValidationException::withMessages([
        'email' => __('auth.failed'),
      ]);
    // } catch (ValidationException $e) {
    //   \Log::error('Validation error:', $e->errors());
    //   return response()->json(['error' => 'Validation failed'], 422);
    // } catch (\Exception $e) {
    //   \Log::error('Authentication error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    //   return response()->json(['error' => 'Authentication failed'], 500);
    // }
    }
  




public function logout(Request $request)
  {
    try {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        \Log::info('User logged out, session cleared:', [session()->all()]);
        return redirect('/')->with('status', 'Logged out successfully!');
    } catch (\Exception $e) {
        \Log::error('Logout error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        return response()->json(['error' => 'Logout failed'], 500);
    }
  }
}
