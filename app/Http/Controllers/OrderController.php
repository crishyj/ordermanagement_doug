<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Stat;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $options = Order::where('archive', '!=', '1')->where('stock_partner', '!=', '1')->get();     
        $users = User::where('permission', '!=', '1')->get();     
        $stats = Stat::all();
        return view('order.index', compact('options', 'users', 'stats'));
    }

    public function create(){
        $users = User::where('permission', '!=', '1')->get();     
        return view('order.create', compact('users'));
    }

    public function store(Request $request){
        $image = time().'.'.$request->image->getClientOriginalExtension();  
        $request->image->move(public_path('image/'), $image);
        $image_file = 'image/'.$image;
       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],           
            'info' => ['required', 'string'],
            'user' => ['required', 'string'],
        ]);       

        $receiver = User::find($request['user']);
        $receiver_email = $receiver->email;

        $sender = User::find(1);
        $sender_email = $sender->email;

        $product_name = $request['name'];
        $product_info = $request['info'];
        $product_image =url('/'). '/'. $image_file;

        $emailFrom = $sender_email;
        $reply = $sender_email;
        $to = $receiver_email;
        $subject = "New Order";
        
        $message = '<body >
            <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                    <tr>
                        <td style="font-size:14px; color:#323232">Product Name</td>
                    </tr>
                    <tr>
                        <td style="font-size:16px; font-weight:bold"><strong>' . $product_name .'</strong></td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#323232"> <a href ="'.url('/').'/orderstat"> View Order </a> </td>
                    </tr> 
                </table>
            </div>
        </body>
        ';
       
        $headers = "From:" . $emailFrom . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
      
        mail($to,$subject,$message,$headers);


        $options = new Order([
            'name' => $request['name'],
            'image' => $image_file,
            'info' => $request['info'],
            'users_id' => $request['user'],
            'stats_id' => '1',
        ]);
        
        $options->save();

        return redirect('/order')->with('success', 'Successfully added');  
    }

    public function update(Request $request){
        if($request->get('admin_update')){

            $options = Order::find($request->get('id'));
            $options->name = $request->get('name');
            $options->info = $request->get('info');
            if($request->image != 'undefined'){
                unlink($options['image']);
                $image = time().'.'.$request->image->getClientOriginalExtension();  
                $request->image->move(public_path('image/'), $image);
                $image_file = 'image/'.$image;
                $options->image = $image_file;
            }

            $receiver = User::find($options->users_id);
            $receiver_email = $receiver->email;

            $sender = User::find(1);
            $sender_email = $sender->email;

            $product_name = $options->name;
            
            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $subject = "New Order";
            
            $message = '<body >
                <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                    <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                        <tr>
                            <td style="font-size:14px; color:#323232">Product Name</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; font-weight:bold"><strong>' . $product_name .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232"> <a href ="'.url('/').'/orderstat"> View Order </a> </td>
                        </tr>                           
                    </table>
                </div>
            </body>
            ';
           
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
          
            mail($to,$subject,$message,$headers);
            
            $options->save();
            return response()->json('success');

        }       
        elseif($request->get('update_partner')){     
            
            $options = Order::find($request->get('id'));
            $options->name = $request->get('name');
            $options->info = $request->get('info');
            $options->stats_id = $request->get('stat');
            $options->track = $request->get('track');
            
            if($request->get('stat') == 2){
                $options->stock_partner = 1;
            }
            else{
                $options->stock_partner = 0;
            }

            $sender_id = $options->users_id;
            $sender = User::find($sender_id);
            $sender_email = $sender->email;

            $receiver = User::find(1);
            $receiver_email = $receiver->email;
       

            $name = $request->get('name');
            $info = $request->get('info');
            $stats = Stat::find($options->stats_id);
            $stat = $stats->name;
            $trak = $request->get('track');

            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $subject = "Order Track";
            
            $message = '<body >
                <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                    <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                        <tr>
                            <td style="font-size:14px; color:#323232">Product Name</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; font-weight:bold"><strong>' . $name .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232">Order Status :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'.$stat .'</strong></td>
                        </tr>                          
                        <tr>
                            <td style="font-size:14px; color:#323232">Order Track :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'. $trak .'</strong></td>
                        </tr>  
                    </table>
                </div>
            </body>
            ';
           
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
          
            mail($to,$subject,$message,$headers);

            $options->save();
            return response()->json('success');
        }
        elseif($request->get('update_stock')){     

            $options = Order::find($request->get('id'));
            
            $options->name = $request->get('name');
            $options->info = $request->get('info');
            $options->stats_id = $request->get('stat');

            if($request->image != 'undefined'){
                unlink($options['image']);
                $image = time().'.'.$request->image->getClientOriginalExtension();  
                $request->image->move(public_path('image/'), $image);
                $image_file = 'image/'.$image;
                $options->image = $image_file;
            }
            
            if($request->get('stat') == 2){
                $options->stock_partner = 1;
            }
            else{
                $options->stock_partner = 0;
            }

            $sender_id = $options->users_id;
            $sender = User::find($sender_id);
            $sender_email = $sender->email;

            $receiver = User::find(1);
            $receiver_email = $receiver->email;
       
            $product_image =url('/'). '/'. $options->image;

            $name = $request->get('name');
            $info = $request->get('info');
            $stats = Stat::find($options->stats_id);
            $stat = $stats->name;
            $trak = $request->get('track');

            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $subject = "Update Order";
            
            $message = '<body >
                <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                    <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                        <tr>
                            <td style="font-size:14px; color:#323232">Product Name</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; font-weight:bold"><strong>' . $name .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232">Order Status :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'.$stat .'</strong></td>
                        </tr>                          
                    </table>
                </div>
            </body>
            ';
           
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
          
            mail($to,$subject,$message,$headers);

            $options->save();
            return response()->json('success');
        }
        elseif($request->get('partner_stock')){     

            $options = Order::find($request->get('id'));
            $options->name = $request->get('name');
            $options->info = $request->get('info');
            $options->stats_id = $request->get('stat');
            
            if($request->get('stat') == 2){
                $options->stock_partner = 1;
            }
            else{
                $options->stock_partner = 0;
            }

            $sender_id = $options->users_id;
            $sender = User::find($sender_id);
            $sender_email = $sender->email;

            $receiver = User::find(1);
            $receiver_email = $receiver->email;
       
            $product_image =url('/'). '/'. $options->image;

            $name = $request->get('name');
            $info = $request->get('info');
            $stats = Stat::find($options->stats_id);
            $stat = $stats->name;
            $trak = $request->get('track');

            $emailFrom = $sender_email;
            $reply = $sender_email;
            $to = $receiver_email;
            $subject = "Update Order";
            
            $message = '<body >
                <div style="width:500px; margin:10px auto; background:#f1f1f1; border:1px solid #ccc">
                    <table  width="100%" border="0" cellspacing="5" cellpadding="10">
                        <tr>
                            <td style="font-size:14px; color:#323232">Product Name</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; font-weight:bold"><strong>' . $name .'</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size:14px; color:#323232">Order Status :</td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;  font-weight:bold"><strong>'.$stat .'</strong></td>
                        </tr>                          
                    </table>
                </div>
            </body>
            ';
           
            $headers = "From:" . $emailFrom . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
          
            mail($to,$subject,$message,$headers);

            $options->save();
            return response()->json('success');
        }
        else{
            $options = Order::find($request->get('id'));

            if($request->image !='undefined'){
                unlink($options['image']);
                $image = time().'.'.$request->image->getClientOriginalExtension();  
                $request->image->move(public_path('image/'), $image);
                $image_file = 'image/'.$image;
                $options->image = $image_file;
            }   
    
            $options->name = $request->get('name');
            $options->info = $request->get('info');
            $options->save();
            return response()->json('success');
        }
    }

    public function move_archive($id){
        $options = Order::find($id);
        if (!$options) {
            return back()->withErrors(['delete' => 'Something went wrong.']);
        }
        $options->archive = '1';
        $options->save();  
        return back()->with('success', 'Succefully Moved');
    }

    public function delete($id){
        $options = Order::find($id);
        if (!$options) {
            return back()->withErrors(['delete' => 'Something went wrong.']);
        }
        unlink($options['image']);
        $options->delete();        
        return back()->with('success', 'Deleted Successfully');
    }

    public function partner(){
        $userId = Auth::id();      
        $users = User::find($userId);
        $orders = Order::where('users_id', $userId)->where('archive', '!=', '1')->where('stock_partner', '!=', '1')->get();        
        $stats = Stat::all();
        return view('order.partner', compact('orders', 'users', 'stats'));
    }

    public function archive(){
        $options = Order::where('archive', '!=', '0')->get();     
        $users = User::where('permission', '!=', '1')->get();     
        $stats = Stat::all();
        return view('order.archive', compact('options', 'users', 'stats'));
    }

    public function adminStock(){
        $options = Order::where('stock_partner', '!=', '0')->get();
        $users = User::where('permission', '!=', '1')->get();     
        $stats = Stat::all();
        $changeStats = Stat::where('id', '!=', '2')->where('id', '!=', '3')->where('id', '!=', '4')->get();
        return view('order.adminStock', compact('options', 'users', 'stats', 'changeStats'));
    }

    public function partnerStock(){
        $userId = Auth::id();      
        $users = User::find($userId);
        $orders = Order::where('users_id', $userId)->where('stock_partner', '!=', '0')->get();        
        $stats = Stat::all();
        return view('order.partnerStock', compact('orders', 'users', 'stats'));
    }
  
}
