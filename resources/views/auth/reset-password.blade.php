<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>

    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --ring: rgba(59, 130, 246, 0.25);
            --primary: #000000;
            --primary-dark: #636363;
            /* --primary: #2563eb;
            --primary-dark: #1d4ed8; */
            --danger: #dc2626;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji","Segoe UI Emoji";
            background: var(--bg);
            color: var(--text);
        }
        .wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 460px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 10px 25px rgba(2, 6, 23, 0.06);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .logo {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }
        h1 { font-size: 18px; margin: 0; letter-spacing: .2px; }
        p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.5; }

        form { margin-top: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 10px 0 6px; }
        .input {
            width: 100%;
            padding: 11px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            outline: none;
            font-size: 14px;
            background: #fff;
        }
        .input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px var(--ring);
        }
        .error { margin-top: 6px; color: var(--danger); font-size: 13px; }

        .btn {
            margin-top: 14px;
            width: 100%;
            appearance: none;
            border: 0;
            border-radius: 12px;
            padding: 11px 14px;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
            background: var(--primary);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
        }
        .btn:hover { background: var(--primary-dark); }
        .hint { margin-top: 10px; font-size: 13px; color: var(--muted); }
        .link { color: var(--muted); text-decoration: none; }
        .link:hover { color: var(--text); text-decoration: underline; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="brand">
            <div class="logo" aria-hidden="true"></div>
            <div>
                <h1>Reset password</h1>
                <p>Choose a new password to continue.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email ?? request('email')) }}">

            <label for="password">New password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="input"
                required
                autocomplete="new-password"
                placeholder=""
            />
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="input"
                required
                autocomplete="new-password"
                placeholder=""
            />

            <button class="btn" type="submit">Update password</button>

            <div class="hint">
                After updating your password, you can return to
                <a class="link" href="{{ route('filament.admin.auth.login') }}">admin login</a>.
            </div>
        </form>
    </div>
</div>
</body>
</html>
