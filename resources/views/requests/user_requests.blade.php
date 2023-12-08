<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('style/style.css') }}" rel="stylesheet">
    <title>Account Creation Requests</title>
</head>
<body>
    <a href="/dashboard">Dashboard</a>
    <br></br>
    <a href="/logout">Logout</a>
    <br></br>
    <a href="/create_bank_account">Create new bank account</a>
    <br></br>
    <a href="/accounts">View bank accounts</a>
    <br></br>
    <a href="/deposit">Deposit money</a>
    <br></br>
    <a href="/withdraw">Withdraw money</a>
    <br></br>
    <a href="/transfer">Transfer money</a>
    <br></br>
    <a href="/transaction_history">View transaction history</a>
    <br></br>
    
    <h1>Account Creation Requests</h1>
    @if(!isset($requests) || count($requests) == 0)
        <p>You have made no requests.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Account Name</th>
                    <th>Currency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->username }}</td>
                    <td>{{ $request->account_name }}</td>
                    <td>{{ $request->currency }}</td>
                    <td>{{ $request->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>