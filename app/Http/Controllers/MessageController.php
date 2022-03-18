<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Events\PrivateMessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Models\Friend;
use Dotenv\Validator;
use Exception;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function privateMessages(User $user)
    {
        $privateCommunication= Message::with('user')
        ->where(['user_id'=> auth()->id(), 'receiver_id'=> $user->id])
        ->orWhere(function($query) use($user){
            $query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
        })
        ->get();

        return $privateCommunication;
    }


     // send message to user
    public function sendPrivateMessage(SendMessageRequest $request,User $user)
    {
            $input=$request->all();
            $input['receiver_id']=$user->id;
            $check_way = $user->way;
            if($check_way == 1){
            $message=auth()->user()->messages()->create($input);
            broadcast(new PrivateMessageSent($message->load('user')))->toOthers();
            return response(['status'=>'Message private sent successfully','message'=>$message]);
            }
            else{
            return response(['status'=>'404','message'=>'You Can\'t send message']);
            }
    }
    // // function to block user
    // public function BlockUser(User $user)
    //  {
    //      try{
    //         // $block = auth()->user()->friends()->where(['friend_id'=>$user->id])->update(['way'=>1]);

    //         $block= Friend::where(['user_id',auth()->user()->id,'friend_id'=> $user->id])
    //         ->whereHas('user',function($q){
    //           $q->update('users.way','0');
    //         });
    //         return response(['status'=>'You Blocked This User','message'=>$block]);
    //      }
    //      catch(Exception $e){
    //         return response(['status'=>'somthing error please try agian','message'=>$e->getMessage()]);
    //      }
    // }


     // add user to frien zoom
    public function addUser(User $user){
        $friend = Friend::where(
            ['user_id'=>auth()->user()->id,'friend_id'=>$user->id])
            ->get();
        if(count($friend) > 0){
          return response(['status'=>'your alredy added this user','message'=>null]);
        }
        try{
          $newuser = auth()->user()->friends()->create(
             ['user_id'=>auth()->user()->id,'friend_id'=>$user->id]
          );
          $friend = User::FindOrFail($newuser->friend_id);
            return response(['status'=>'You Add This User To friend zoom','message'=>$friend]);

        }
        catch(Exception $e){
            return response(['status'=>'some error, please try again','message'=>$e->getMessage()]);
        }
    }

    // reomve user
       // add user to frien zoom
       public function removeUser(User $user){
        try{
          $user = Friend::where(
             ['user_id'=>auth()->user()->id,'friend_id'=>$user->id]
            )->delete();
            return response(['status'=>'done you removed this user','message'=>$user]);
        }
        catch(Exception $e){
            return response(['status'=>'some error, please try again','message'=>null]);
        }
    }



}
