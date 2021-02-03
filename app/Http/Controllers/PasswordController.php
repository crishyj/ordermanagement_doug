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

    public function passwordreset_link(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255']
        ]);
        $email = $request->get('email');
        $result = User::where('email', $email)->get();
        if(sizeof($result) > 0) {
            $options = $result[0];
            $receiver_email = $options->email;

            $sender = User::find(1);
            $sender_email = $sender->email;     
            
            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $resetpassword_link = url()->current().'/'.$options->id;          
            $subject = "Password Change Link";

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
                            <td style="font-size:14px; color:#323232"><a href = '.$resetpassword_link.'>Reset Password Link </a></td>
                        </tr>
                                                                                
                    </table>
                </div>
            </body>
            ';
            
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            
            mail($to,$subject,$message,$headers);
            return redirect()->route('home'); 
        }
        else 
        {
            return redirect()->back()->withErrors(['email' => 'Your Email is not matched.']);
        }
    }

    public function passwordreset_updatepage($id){
        return view('passwordreset.reset', ['id' => $id]);
    }
    public function passwordreset_update(Request $request){
        $request->validate([            
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $id = $request->get('user_id');
        $password = Hash::make($request['password']);
        $options = User::findOrFail($id);
            
        $options->password = Hash::make($request['password']);
        $options->save();

        $receiver_email = $options->email;

        $sender = User::find(1);
        $sender_email = $sender->email;     
        
        $emailFrom = $sender_email;
        $reply = $sender_email;
        $to = $receiver_email;
        $subject = "Your Password Changed";
        
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
    }
}
