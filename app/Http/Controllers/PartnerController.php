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
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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

        $receiver_email = $request['email'];

        $sender = User::find(1);
        $sender_email = $sender->email;     
        
        $emailFrom = $sender_email;
        $reply = $sender_email;
        $to = $receiver_email;
        $subject = "Stoneworks Partner Portal Login Information";
        
        $message = '<body >
            <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                    <tr>
                        <td style="font-size:14px; color:#323232">Name</td>
                    </tr>
                    <tr>
                        <td style="font-size:16px; font-weight:bold"><strong>' .  $request['name'] .'</strong></td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#323232">Email :</td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;  font-weight:bold"><strong>'.$request['email'] .'</strong></td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#323232">Password :</td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;  font-weight:bold"><strong>'.$request['password'] .'</strong></td>
                    </tr>                                                        
                </table>
            </div>
        </body>
        ';
        
        $headers = "From:" . $emailFrom . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        
        mail($to,$subject,$message,$headers);
        
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

            $receiver_email = $options->email;

            $sender = User::find(1);
            $sender_email = $sender->email;     
            
            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $subject = "You Password Changed";
            
            $message = '<body >
                <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                    <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                        <tr>
                            <td style="font-size:14px; color:#323232">Name</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; font-weight:bold"><strong>' . $options->name .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232">Email :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'. $options->email  .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232">New Password :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'.$request['password'] .'</strong></td>
                        </tr>                                                        
                    </table>
                </div>
            </body>
            ';
            
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            
            mail($to,$subject,$message,$headers);

            return redirect()->back()->with('success', 'Successfully Changed');    
        }else{
           return redirect()->back()->withErrors(['old_password' => 'Old password is not matched.']);
        }
    }
}
