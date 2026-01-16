<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email</title>
</head>
<body>
    <h1>Please verify your email</h1>

    @if (session('status') === 'verification-link-sent')
        <p>A new verification link has been sent to your email address.</p>
    @endif

    <p>Before continuing, please check your email for a verification link.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend verification email</button>
    </form>

    
</body>
</html>
