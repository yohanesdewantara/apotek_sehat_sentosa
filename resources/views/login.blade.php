<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Apotek Sehat Sentosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background-color: #e9f5ec;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .card {
            height: auto;
            width: 320px;
            padding: 2rem 1.5rem;
            text-align: center;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 128, 0, 0.1);
        }

        .title {
            margin-bottom: 1rem;
            font-size: 1.5em;
            font-weight: 600;
            color: #2d6a4f;
        }

        .field {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5em;
            background-color: #f0fdf4;
            border-radius: 5px;
            padding: 0.6em 1em;
            border: 1px solid #95d5b2;
        }

        .input-icon {
            height: 1em;
            width: 1em;
            fill: #2d6a4f;
        }

        .input-field {
            background: none;
            border: none;
            outline: none;
            width: 100%;
            color: #2d6a4f;
            padding-left: 0.5rem;
        }

        .btn {
            cursor: pointer;
            margin-top: 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.9em;
            text-transform: uppercase;
            padding: 0.6em 1.2em;
            background-color: #2d6a4f;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 128, 0, 0.15);
            transition: background 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #1b4332;
        }

        .btn-link {
            color: #40916c;
            display: block;
            font-size: 0.75em;
            text-decoration: none;
            margin-top: 0.8rem;
        }

        .btn-link:hover {
            color: #1b4332;
        }

        .field input:focus::placeholder {
            opacity: 0;
            transition: opacity 0.3s;
        }

        .footer {
            font-size: 0.75rem;
            color: #7f8c8d;
            margin-top: 1rem;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Login Admin</div>

        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/></svg>
                <input type="email" name="email" placeholder="Email" class="input-field" required>
            </div>

            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm6-10V6a6 6 0 0 0-12 0v1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-8-1a4 4 0 0 1 8 0v1H10V6z"/></svg>
                <input type="password" name="password" placeholder="Password" class="input-field" required>
            </div>

            <button type="submit" class="btn">Masuk</button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Apotek Sehat Sentosa
        </div>
    </div>
</body>
</html>
