<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | AMSTROOM COMPUTERS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>AMSTROOM COMPUTERS</h2>
        <p>Administration Portal Login</p>

        @if($errors->any())
            <div class="error-box">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="admin@amstroom.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-submit">Sign In <i class="fas fa-sign-in-alt"></i></button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='#64748b'">
                <i class="fas fa-arrow-left"></i> Back to Homepage
            </a>
        </div>
    </div>

</body>
</html>
