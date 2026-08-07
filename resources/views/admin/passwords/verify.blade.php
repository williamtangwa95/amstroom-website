<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Reset Code | AMSTROOM COMPUTERS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>AMSTROOM COMPUTERS</h2>
        <p>Verify Reset Code</p>

        @if(session('success'))
            <div style="background: rgba(40, 167, 69, 0.1); border-left: 4px solid #28a745; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; color: #28a745; display: flex; align-items: center; gap: 8px; font-size: 13.5px;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="error-box" style="margin-bottom: 20px; background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; padding: 12px 15px; border-radius: 6px; color: #dc3545; font-size: 13.5px;">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.password.verify') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 25px;">
                <label for="code">6-Digit Verification Code</label>
                <input type="text" name="code" id="code" class="form-control" placeholder="123456" pattern="[0-9]{6}" maxlength="6" style="text-align: center; font-size: 24px; letter-spacing: 8px; font-weight: 700; height: 55px;" required autofocus>
                <small style="color: #64748b; font-size: 12px; margin-top: 5px; display: block; line-height: 1.4; text-align: center;">
                    Please enter the code sent to: <strong>{{ session('reset_email') }}</strong>.
                </small>
            </div>

            <button type="submit" class="btn-submit">Verify Code <i class="fas fa-check"></i></button>
        </form>
        
        <div style="text-align: center; margin-top: 25px; border-top: 1px dashed #e2e8f0; padding-top: 15px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('admin.password.request') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500;">
                <i class="fas fa-redo"></i> Resend Code
            </a>
            <a href="{{ route('login') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500;">
                Cancel
            </a>
        </div>
    </div>

</body>
</html>
