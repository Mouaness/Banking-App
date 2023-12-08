<!-- signup.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 100px auto; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 10px; margin: 10px 0; }
        input[type="submit"] { width: 100%; padding: 10px; background-color: #4CAF50; color: white; }
        .login-link { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
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
        <form action="/register" method="post">
            @csrf
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <input type="text" name="phone_number" placeholder="Phone" value="{{ old('phone_number') }}">
            <input type="text" name="address" placeholder="Address" value="{{ old('address') }}">
            <input type="submit" value="Sign Up">
        </form>
        <div class="login-link">
            <a href="/login">Already have an account? Log in</a>
        </div>
    </div>
</body>
</html>
