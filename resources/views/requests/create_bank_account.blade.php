<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bank Account</title>

</head>
<body>
    <h1>Create Bank Account</h1>
    <a href="/dashboard">Dashboard</a>
    <br></br>
    <a href="/logout">Logout</a>
    <br></br>
    <a href="/deposit">Deposit</a>
    <br></br>
    <a href="/withdraw">Withdraw</a>
    <br></br>
    <a href="/transfer">Transfer</a>
    <br></br>
    <!-- display errors -->
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/create_bank_account" method="post">
        @csrf
        <input type="hidden" name="username" value="{{ $username }}">
        <input type="text" name="account_name" placeholder="Account Name">
        <select name="currency" >
            <option value="LBP">LBP</option>
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
        </select>
        <input type="submit" value="Create">
    </form>
</body>
</html>