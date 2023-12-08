<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('style/style.css') }}" rel="stylesheet">
    <title>Transfer Money</title>
</head>
<body>
    <h2>Bank Accounts</h2>
    <a href="/dashboard">Dashboard</a>
    <br></br>
    <a href="/create_bank_account">Create Bank Account</a>
    <br></br>
    <a href="/logout">Logout</a>
    <br></br>
    <a href="/deposit">Deposit</a>
    <br></br>
    <a href="/transfer">Transfer</a>
    <br></br>

    <!-- display errors if any -->
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!isset($accounts) || count($accounts) == 0)
        <p>You have no bank accounts.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>Currency</th>
                <th>Balance</th>
                <th>Amount to transfer</th>
                <th>Transfer To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            <tr>
                <td>{{ $account->account_name }}</td>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->currency }}</td>
                <td>{{ $account->balance }}</td>
                <td>
                    <form action="/transfer" method="POST">
                        @csrf
                        <input type="hidden" name="account_name" value="{{ $account->account_name }}">
                        <input type="number" name="amount" placeholder="amount">
                        <input type='text' name='receiver_account_number' placeholder='receiver_account_number'>
                        <input type="submit" value="Transfer">
                    </form>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</body>
</html>