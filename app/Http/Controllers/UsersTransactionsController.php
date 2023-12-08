<?php

namespace App\http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersTransactions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\http\Controllers\AccountController;

class UsersTransactionsController extends Controller
{
    //create new bank account
    public function create(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:deposit,withdraw,transfer',
            'user_id' => 'required|numeric',
            'username' => 'required|string',
            'account_id' => 'required|numeric',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'receiver_id' => 'numeric|nullable',
            'receiver_username' => 'string|nullable',
            'receiver_account_id' => 'numeric|nullable',
            'receiver_account_name' => 'string|nullable',
            'receiver_account_number' => 'string|nullable',
            'amount' => 'required|numeric',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        
        //if transaction is a withdrawal/deposit, receiver details are null. 
        //if transaction is a transfer, receiver details are not null.
        if($request->type == 'transfer') {
            echo "Transfer" . "\n\n";
            if(!$request->receiver_id ||
             !$request->receiver_username ||
              !$request->receiver_account_id ||
               !$request->receiver_account_name ||
                !$request->receiver_account_number) 
                return back()->withInput()->withErrors(['receiver' => 'Receiver details are required']);
        } else {
            if($request->receiver_id ||
             $request->receiver_username ||
              $request->receiver_account_id ||
               $request->receiver_account_name ||
                $request->receiver_account_number) 
                return back()->withInput()->withErrors(['receiver' => 'There should be no receiver details for this transaction type']);
        }

        //create transaction
        $transaction = UsersTransactions::create([
            'type' => $request->type,
            'user_id' => $request->user_id,
            'username' => $request->username,
            'account_id' => $request->account_id,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'receiver_id' => $request->receiver_id,
            'receiver_username' => $request->receiver_username,
            'receiver_account_id' => $request->receiver_account_id,
            'receiver_account_name' => $request->receiver_account_name,
            'receiver_account_number' => $request->receiver_account_number,
            'amount' => $request->amount,
        ]);

        //return account details
        return redirect('/');
    }

    //get all transactions for a user
    public function getTransactions(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        //get all user accounts
        $accountsController = new AccountController();
        $accounts = $accountsController->accounts();

        //get all transactions for each account
        $accountsTransactions = [];
        foreach($accounts as $account) {
            $accountTransactions = UsersTransactions::where(function ($query) use ($account) {
                $query->where('account_id', $account->id)
                      ->orWhere('receiver_account_id', $account->id);
            })->get();            
            $accountsTransactions[] = $accountTransactions;
        }   

        //return transactions
        return $accountsTransactions;
    }

    //filter transactions
    public function getFilteredTransactions(Request $request)
    {
        // Validate filter parameters
        $validator = Validator::make($request->all(), [
            'type' => 'string|in:deposit,withdraw,transfer|nullable',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'min_amount' => 'numeric|nullable',
            'max_amount' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        // Get authenticated user's ID
        $userId = Auth::id();

        // Initialize the query
        $query = UsersTransactions::where('user_id', $userId);

        // Apply filters
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }
        if ($request->has('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Execute the query
        $transactions = $query->get();

        return $transactions;
    }
}
?>