<!-- login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 100px auto; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; }
        input[type="submit"] { width: 100%; padding: 10px; background-color: #4CAF50; color: white; }
        .signup-link { text-align: center; margin-top: 20px; }
    </style>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</head>
<body>
    <div class="container">
        <form action="/login" method="post">
        @csrf
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <div class="signup-link">
            <a href="/register">Don't have an account? Sign up</a>
        </div>
    </div>
</body>
</html>
