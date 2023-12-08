<?php
//this controller create new bank accounts from user requests
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;   
use Illuminate\Support\Facades\Auth;
use App\http\Controllers\UsersTransactionsController;
use App\http\Controllers\UserController;

class AccountController extends Controller
{
    //create new bank account
    public function create(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'account_name' => 'required|string',
            'currency' => 'required|string|in:LBP,EUR,USD',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //make sure account name is unique
        $account = Account::where("user_id", $request->user_id)->where('account_name', $request->account_name)->first();
        if($account) {
            return back()->withInput()->withErrors(['account_name' => 'Account name already exists']);
        }

        //create new account
        $account = Account::create([
            'user_id' => $request->user_id,
            'account_name' => $request->account_name,
            'account_number' => uniqid(),
            'currency' => $request->currency,
            'balance' => 0,
            'status' => 'active',
        ]);

        //return account details
        return redirect('/');    
    }

    //deposit money into account
    public function deposit(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //get user 
        $user = Auth::user();

        //find account
        $account = Account::where('user_id', $user->id)->where('account_name', $request->account_name)->first();

        //update account balance
        $account->balance += $request->amount;
        $account->save();

        //create transaction
        $transaction = new UsersTransactionsController();
        $data = [
            'type' => 'deposit',
            'user_id' => $user->id,
            'username' => $user->username,
            'account_id' => $account->id,
            'account_name' => $account->account_name,
            'account_number' => $account->account_number,
            'amount' => $request->amount,
        ];
        $request = new Request($data);
        $transaction->create($request);

        //check for errors coming from transaction controller
        if (isset($errors) && $errors->any()){
            return back()->withInput()->withErrors($errors);
        }

        //return account details
        return redirect('/deposit');
    }

    //withdraw money from account
    public function withdraw(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //get user 
        $user = Auth::user();

        //find account
        $account = Account::where('user_id', $user->id)->where('account_name', $request->account_name)->first();

        //make sure account has enough balance
        if($account->balance < $request->amount) {
            return back()->withInput()->withErrors(['amount' => 'Insufficient balance']);
        }

        //update account balance
        $account->balance -= $request->amount;
        $account->save();

        //create transaction
        $transaction = new UsersTransactionsController();
        $data = [
            'type' => 'withdraw',
            'user_id' => $user->id,
            'username' => $user->username,
            'account_id' => $account->id,
            'account_name' => $account->account_name,
            'account_number' => $account->account_number,
            'amount' => $request->amount,
        ];
        $request = new Request($data);
        $transaction->create($request);

        //check for errors coming from transaction controller
        if (isset($errors) && $errors->any()){
            return back()->withInput()->withErrors($errors);
        }

        //return account details
        return redirect('/withdraw');
    }

    //transfer money from one account to another
    public function transfer(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string',
            'receiver_account_number' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //get user and receiver user
        $user = Auth::user();

        //find accounts
        $account = Account::where('user_id', $user->id)->where('account_name', $request->account_name)->first();
        $receiver_account = Account::where('account_number', $request->receiver_account_number)->first();

        //make sure user account exists
        if(!$account) {
            return back()->withInput()->withErrors(['account_name' => 'Account does not exist']);
        }

        //make sure receiver account exists
        if(!$receiver_account) {
            return back()->withInput()->withErrors(['receiver_account_number' => 'Account does not exist']);
        }

        $receiverRequest = new Request(['user_id' => $receiver_account->user_id]);
        $userController = new UserController();
        $receiver = $userController->find($receiverRequest);

        //make sure receiver user exists
        if(!$receiver) {
            return back()->withInput()->withErrors(['receiver_id' => 'Receiver does not exist']);
        }

        //make sure account has enough balance
        if($account->balance < $request->amount) {
            return back()->withInput()->withErrors(['amount' => 'Insufficient balance']);
        }

        //make sure both accounts are of the same currency
        if($account->currency != $receiver_account->currency) {
            return back()->withInput()->withErrors(['receiver_account_number' => 'Accounts are not of the same currency']);
        }

        //update accounts balance
        $account->balance -= $request->amount;
        $receiver_account->balance += $request->amount;
        $account->save();
        $receiver_account->save();

        //create transaction
        $transaction = new UsersTransactionsController();
        $data = [
            'type' => 'transfer',
            'user_id' => $user->id,
            'username' => $user->username,
            'account_id' => $account->id,
            'account_name' => $account->account_name,
            'account_number' => $account->account_number,
            'receiver_id' => $receiver->id,
            'receiver_username' => $receiver->username,
            'receiver_account_id' => $receiver_account->id,
            'receiver_account_name' => $receiver_account->account_name,
            'receiver_account_number' => $receiver_account->account_number,
            'amount' => $request->amount,
        ];
        $request = new Request($data);
        $transaction->create($request);

        //check for errors coming from transaction controller
        if (isset($errors) && $errors->any()){
            return back()->withInput()->withErrors($errors);
        }

        //return account details
        return redirect('/transfer');
    }

    //return all user accounts
    public function accounts() {
        $user = Auth::user();
        if($user && $user->type == 'client') {
            $accounts = Account::where('user_id', $user->id)->get();
            return $accounts;
        } else {
            return null;
        }
    }

    //check if user has an account with the given name
    public function hasAccount($account_name, $user_id) {
        $account = Account::where('user_id', $user_id)->where('account_name', $account_name)->first();
        if($account) 
            return true;
        else 
            return false;
    }

    //check if account belongs to user
    public function isOwner($account_id, $user_id) {
        $account = Account::find($account_id);
        if($account && $account->user_id == $user->id) 
            return true;
        else
            return false;
    }
}
?>