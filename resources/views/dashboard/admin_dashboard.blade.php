<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('style/style.css') }}" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="/logout">Logout</a>
    <br></br>

    <!-- display errors -->
    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br></br>
    @endif

    <!-- display all requests and link to handle each one -->
    <h2>Pending Requests</h2>
    @if ($requests->isEmpty())
        <p>There are no pending requests.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Account Name</th>
                <th>Currency</th>
                <th>created_at</th>
                <th>updated_at</th>
                <th>Handle Request</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->username }}</td>
                    <td>{{ $request->account_name }}</td>
                    <td>{{ $request->currency }}</td>
                    <td>{{ $request->created_at }}</td>
                    <td>{{ $request->updated_at }}</td>
                    <td> 
                        <form action="/handle_request" method="POST">
                            @csrf
                            <input type="hidden" name="username" value="{{ $request->username }}">
                            <input type="hidden" name="account_name" value="{{ $request->account_name }}">
                            <input type="hidden" name="created_at" value="{{ $request->created_at }}">
                            <select name="decision">
                                <option value="approve">approve</option>
                                <option value="reject">reject</option>
                            </select>
                            <input type="submit" value="Handle Request">
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif 
</body>
</html>