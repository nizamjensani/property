<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; padding: 24px; max-width: 720px; margin: 0 auto; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
        .btn { padding: 10px 14px; border-radius: 10px; border: 1px solid #111827; background: #111827; color: #fff; cursor: pointer; }
        .btn-outline { background: transparent; color: #111827; }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .muted { color: #6b7280; }
        .success { background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px 12px; border-radius: 10px; color: #065f46; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Please verify your email</h1>

        @if (session('status') === 'verification-link-sent')
            <div class="success">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <p class="muted">
            Before continuing, please check your email for a verification link.
            If you didn’t receive the email, you can request another.
        </p>

        <div class="row">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn">Resend verification email</button>
            </form>
{{--
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline">Logout</button>
            </form>
            --}}
        </div>
    </div>
</body>
</html>
