<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | AMSTROOM COMPUTERS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>AMSTROOM COMPUTERS</h2>
        <p>Set New Password</p>

        @if($errors->any())
            <div class="error-box" style="margin-bottom: 20px; background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 12px 15px; border-radius: 6px; color: #dc3545; font-size: 13.5px;">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.password.reset') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password">New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" style="padding-right: 45px;" required autofocus>
                    <button type="button" class="toggle-password" data-target="password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 16px; outline: none; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password_confirmation">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-type new password" style="padding-right: 45px;" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 16px; outline: none; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Reset Password <i class="fas fa-key"></i></button>
        </form>
        
        <div style="text-align: center; margin-top: 25px; border-top: 1px dashed #e2e8f0; padding-top: 15px;">
            <a href="{{ route('login') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500;">
                Cancel and Exit
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>

</body>
</html>
