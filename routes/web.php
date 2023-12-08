<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;    
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UsersTransactionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//only authenticated users can access these routes
Route::middleware(['auth'])->group(function() {

    //only bank agents can access these routes
    Route::middleware(['userType:agent'])->group(function() {
        Route::get('/admin_dashboard',function() {
            $requestController = new RequestController();
            return view('dashboard.admin_dashboard', ['requests' => $requestController->getPendingRequests()]);
        });
        Route::post('/handle_request', [RequestController::class, 'handleRequest']);
    });

    //only clients can access these routes
    Route::middleware(['userType:client'])->group(function() {
        Route::get('/dashboard',function() {
            return view('dashboard.dashboard');
        });
        Route::get('/create_bank_account', function () {
            $user = Auth::user();
            return view('requests.create_bank_account', ['username' => $user->username]);
        }); 
        Route::post('/create_bank_account', [RequestController::class, 'create']); 
        Route::get('/accounts', function() {
            $accountController = new AccountController();
            return view('requests.accounts', ['accounts' => $accountController->accounts()]);
        });
        Route::get('/deposit', function() {
            $accountController = new AccountController();
            $user = Auth::user();
            return view('requests.deposit', ['accounts' => $accountController->accounts()]);
        });
        Route::post('/deposit', [AccountController::class, 'deposit']);
        Route::get('/withdraw', function() {
            $accountController = new AccountController();
            $user = Auth::user();
            return view('requests.withdraw', ['accounts' => $accountController->accounts()]);
        });
        Route::post('/withdraw', [AccountController::class, 'withdraw']);
        Route::get('/transfer', function() {
            $accountController = new AccountController();
            $user = Auth::user();
            return view('requests.transfer', ['accounts' => $accountController->accounts()]);
        });
        Route::post('/transfer', [AccountController::class, 'transfer']);
        Route::get('/user_requests', function() {
            $requestController = new RequestController();
            $user = Auth::user();
            return view('requests.user_requests', ['requests' => $requestController->getRequests($user->id)]);
        });
        Route::get('/transaction_history', function() {
            $transactionController = new UsersTransactionsController();
            $user = Auth::user();
            $request = new Request(["user_id" => $user->id]);
            return view('requests.user_transactions', ['accountsTransactions' => $transactionController->getTransactions($request)]);
        });
    });

    Route::get("/logout", function() {
        Auth::logout();
        return redirect("/login");
    });
});

Route::get("/", function() {
    if (Auth::check()) {
        if (Auth::user()->type == "agent") {
            return redirect("/admin_dashboard");
        } else {
            return redirect("/dashboard");
        }
    } else {
        return redirect("/login");
    }
});

Route::get("/register", function() {
    return view("authentication.register");
});

Route::post("/register", [UserController::class, "register"]);

Route::get("/login", function() {
    if (Auth::check()) 
        return redirect("/");
    else 
        return view("authentication.login");
})->name("login");

Route::post("/login", [UserController::class, "login"]);


