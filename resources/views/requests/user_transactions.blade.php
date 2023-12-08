<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('style/style.css') }}" rel="stylesheet">
    <title>User transactions</title>
</head>
<body>
    <a href="/dashboard">dashboard</a>
    <br></br>
    <a href="/logout">logout</a>
    <h1>User transactions</h1>

    <!-- display errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error) 
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- display users transactions table if any -->
    @if (count($accountsTransactions) == 0)
        <p>No transactions</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Username</th>
                    <th>Account number</th>
                    <th>Amount</th>                   
                    <th>Receiver username</th>
                    <th>Receiver account number</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accountsTransactions as $accountTransactions)
                    @foreach ($accountTransactions as $transaction)
                        <?php
                        $receiver_account_number = 'N/A';
                        $receiver_username = 'N/A';
                        if ($transaction->type == 'transfer'){
                            $receiver_account_number = $transaction->receiver_account_number;
                            $receiver_username = $transaction->receiver_username;
                        }
                        ?>
                        <tr>    
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->username }}</td>
                            <td>{{ $transaction->account_number }}</td>
                            <td>{{ $transaction->amount }}</td>
                            <td>{{ $receiver_username }}</td>
                            <td>{{ $receiver_account_number }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>{{ $transaction->updated_at }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>