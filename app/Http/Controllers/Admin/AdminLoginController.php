<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminLoginController extends Controller
{
    public function index(){
        return view ('admin.login');
    }

    public function authenticate(Request $request){
        $validator = validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'

        ]);

        if ($validator->passes()){

            if(Auth::guard('admin')->attempt(['email'=> $request->email,'password'=> $request->password],
            $request->get('remember'))){

                $admin = Auth::guard('admin')->user();

                if($admin->role==2){
                    return redirect()->route('admin.dashboard');
                }else{
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You\'re not uauthorized to access Admin Panel.');    
                }

                
            }else{
                return redirect()->route('admin.login')->with('error','Either Email/Password
                is Incorrect');
            }

        }
        
        else
        {
            return redirect()->route('admin.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    
}
