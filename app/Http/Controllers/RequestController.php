<?php
//handle user requests

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Validator;
use App\http\Controllers\AccountController;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    //create new request
    public function create(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'account_name' => 'required|string',
            'currency' => 'required|string|in:LBP,EUR,USD',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //auth user id
        $user = Auth::user();
        $request->user_id = $user->id;

        //make sure user has no other request with the same account name
        $userRequest = UserRequest::where('account_name', $request->account_name)->first();
        if($userRequest && $userRequest->status == 'pending') {
            return back()->withInput()->withErrors(['account_name' => 'Same request already in progress']);
        }

        //make sure user has no other account with the same account name
        $accountController = new AccountController();
        if($accountController->hasAccount($request->account_name, $request->user_id)) {
            return back()->withInput()->withErrors(['account_name' => 'You already have an account with this name']);
        }

        //create new request
        $request = UserRequest::create([
            'user_id' => $request->user_id,
            'username' => $request->username,
            'account_name' => $request->account_name,
            'currency' => $request->currency,
            'status' => 'pending',
        ]);

        //return request details
        return redirect('/');
    }

    //accept/reject request
    public function handleRequest(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'account_name' => 'required|string',
            'decision' => 'required|string|in:approve,reject',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //get request id from username and account name
        $userRequest = UserRequest::where('username', $request->username)->where('account_name', $request->account_name)->first();
        if(!$userRequest){
            return back()->withInput()->withErrors(['request not found' => 'request not found']);
        }

        //check if request is pending
        if($userRequest->status != 'pending'){
            return back()->withInput()->withErrors(['request error' => 'request is not pending']);
        }

        //check if request is accepted or rejected
        if($request->decision == "approve"){

            //create request data
            $data = [
                'user_id' => $userRequest->user_id,
                'account_name' => $userRequest->account_name,
                'currency' => $userRequest->currency,
            ];
            $request = new Request($data);

            //create account controller to call its create account functions
            $this->accountController = new AccountController($userRequest);
            $this->accountController->create($request);

            //check if there were any errors while creating account
            if (isset($errors) && $errors->any()){
                return back()->withInput()->withErrors($errors);
            }

            //accept request
            $userRequest->status = "accepted";
        } else {
            //reject request
            $userRequest->status = "rejected";
        }

        //save request
        $userRequest->save();
        
        //return request details
        return redirect('/');
    }

    //return pending requests
    public function getPendingRequests()
    {
        //get pending requests
        $requests = UserRequest::where('status', 'pending')->get();

        //return requests
        return $requests;
    }

    //return user requests
    public function getRequests($user_id)
    {
        //get user requests
        $requests = UserRequest::where('user_id', $user_id)->get();

        //return requests
        return $requests;
    }
}
?>