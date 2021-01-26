<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordController extends Controller
{
    public function passwordreset_request(){
       return view('passwordreset.index');
    }

    public function passwordreset_update(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $email = $request->get('email');
        $password = Hash::make($request['password']);
        $result = User::where('email', $email)->get();
        if(sizeof($result) > 0) {
            $options = (User::where('email', $email)->get())[0];
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

            return redirect()->route('home');   
        }else{
           return redirect()->back()->withErrors(['old_password' => 'Old password is not matched.']);
        }
       }
       else {
        return redirect()->back()->withErrors(['email' => 'Your Email is not matched.']);
       }
        
    }
}
