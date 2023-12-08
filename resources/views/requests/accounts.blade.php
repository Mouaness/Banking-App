<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('style/style.css') }}" rel="stylesheet">
    <title>Bank Accounts</title>
    <a href="/logout">Logout</a>
    <br></br>
    <a href="/dashboard">Dashboard</a>
    <br></br>
    <a href="/deposit">Deposit</a>
    <br></br>
    <a href="/withdraw">Withdraw</a>
    <br></br>
    <a href="/transfer">Transfer</a>
    <br></br>
</head>
<body>
    <h2>Bank Accounts</h2>
    @if (!(isset($accounts)) || count($accounts) == 0)
        <p>You have no bank accounts.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>Currency</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            <tr>
                <td>{{ $account->account_name }}</td>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->currency }}</td>
                <td>{{ $account->balance }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</body>
</html>