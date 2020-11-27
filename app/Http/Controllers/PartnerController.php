<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use App\Models\User;

class PartnerController extends Controller
{
    public function index(){
        $options = User::where('permission', '!=', '1')->get();     
        return view('partner.index', compact('options'));
    }
    
    public function create(){
        return view('partner.create');
    }  
 
  
    protected function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'min:8'],
            'company' => ['required', 'string', 'min:8'],
        ]);

        $options = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
            'company' => $request['company'],
            'permission' => '2',
        ]);
        
        $options->save();

        return redirect('/partner')->with('success', 'Successfully added');   
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required'],
            'phone' => ['required'],
            'company' => ['required'],
        ]);

        $options = User::find($request->get('id'));
            
        $options->name = $request->get('name');
        $options->email = $request->get('email');
        $options->phone = $request->get('phone');
        $options->company = $request->get('company');
        $options->save();
        return response()->json('success');
    }

    public function delete($id)
    { 
        $options = User::find($id);
        if (!$options) {
            return back()->withErrors(['delete' => 'Something went wrong.']);
        }     
        $options->delete();        
        return back()->with('success', 'Deleted Successfully');
    }

    public function changepassword(){
        $userId = Auth::id();    
        $users = User::find($userId);
        return view('partner.changepassowrd', compact( 'users'));
    }

    public function resetpassword(Request $request){
        $userId = Auth::id();

        $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $options = User::find($userId);
        $password = Hash::make($request['password']);
        
        if(Hash::check($request['old_password'], $options->password)){      
            $options->password = Hash::make($request['password']);
            $options->save();
            return redirect()->back()->with('success', 'Successfully Changed');    
        }else{
           return redirect()->back()->withErrors(['old_password' => 'Old password is not matched.']);
        }
    }
}
