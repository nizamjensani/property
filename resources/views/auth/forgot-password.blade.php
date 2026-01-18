<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>

    <style>
        :root {
            --bg: #f8fafc;         /* slate-50 */
            --card: #ffffff;
            --text: #0f172a;       /* slate-900 */
            --muted: #64748b;      /* slate-500 */
            --border: #e2e8f0;     /* slate-200 */
            --ring: rgba(59, 130, 246, 0.25); /* blue-500 w/ alpha */
            --primary: #2563eb;    /* blue-600 */
            --primary-dark: #1d4ed8; /* blue-700 */
            --danger: #dc2626;     /* red-600 */
            --success-bg: #ecfdf5; /* emerald-50 */
            --success-border: #a7f3d0; /* emerald-200 */
            --success-text: #065f46; /* emerald-800 */
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
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }
        h1 {
            font-size: 18px;
            margin: 0;
            letter-spacing: .2px;
        }
        p {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }
        .alert-success {
            margin-top: 14px;
            padding: 10px 12px;
            border-radius: 12px;
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
            font-size: 14px;
        }
        form { margin-top: 16px; }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin: 10px 0 6px;
        }
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
            border-color: #93c5fd; /* blue-300 */
            box-shadow: 0 0 0 4px var(--ring);
        }
        .error {
            margin-top: 6px;
            color: var(--danger);
            font-size: 13px;
        }
        .actions {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .btn {
            appearance: none;
            border: 0;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
            background: var(--primary);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
        }
        .btn:hover { background: var(--primary-dark); }
        .link {
            color: var(--muted);
            font-size: 13px;
            text-decoration: none;
        }
        .link:hover { color: var(--text); text-decoration: underline; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="brand">
            <div class="logo" aria-hidden="true"></div>
            <div>
                <h1>Forgot password</h1>
                <p>Enter your email and we’ll send a reset link.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                class="input"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="name@example.com"
            />

            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <div class="actions">
                <button type="submit" class="btn">Send reset link</button>

                {{-- If you use Filament admin login --}}
                <a class="link" href="{{ route('filament.admin.auth.login') }}">
                    Back to admin login
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
