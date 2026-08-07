<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | AMSTROOM COMPUTERS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>AMSTROOM COMPUTERS</h2>
        <p>Forgot Administrative Password</p>

        @if($errors->any())
            <div class="error-box" style="margin-bottom: 20px; background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 12px 15px; border-radius: 6px; color: #dc3545; font-size: 13.5px;">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="error-box" style="margin-bottom: 20px; background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 12px 15px; border-radius: 6px; color: #dc3545; font-size: 13.5px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 25px;">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your registered email" value="{{ old('email') }}" required autofocus>
                <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block; line-height: 1.4;">
                    We will send a 6-digit verification code to this address to verify your identity.
                </small>
            </div>

            <button type="submit" class="btn-submit">Send Reset Code <i class="fas fa-paper-plane"></i></button>
        </form>
        
        <div style="text-align: center; margin-top: 25px; border-top: 1px dashed #e2e8f0; padding-top: 15px;">
            <a href="{{ route('login') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='#64748b'">
                <i class="fas fa-arrow-left"></i> Back to Sign In
            </a>
        </div>
    </div>

</body>
</html>
