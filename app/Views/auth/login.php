<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundryku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #13121f;
            color: #e4e0f3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; }

        .login-card {
            background: rgba(31, 30, 43, 0.6);
            border: 1px solid rgba(73, 68, 85, 0.4);
            border-radius: 1rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(8px);
            position: relative;
        }
        .login-card::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 1rem;
            padding: 1px;
            background: linear-gradient(135deg, rgba(134, 93, 255, 0.3), rgba(255, 163, 253, 0.1), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #cdbdff;
            letter-spacing: -0.02em;
        }
        .login-header p {
            font-size: 14px;
            color: #7E7B9A;
            margin-top: 0.5rem;
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 13px;
            font-weight: 500;
        }
        .alert-error {
            background: rgba(255, 180, 171, 0.1);
            border: 1px solid rgba(255, 180, 171, 0.25);
            color: #ffb4ab;
        }
        .alert-success {
            background: rgba(255, 95, 158, 0.1);
            border: 1px solid rgba(255, 95, 158, 0.25);
            color: #FF5F9E;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #cac3d7;
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(6, 0, 71, 0.5);
            border: 1px solid rgba(197, 195, 223, 0.15);
            border-radius: 0.5rem;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-group input::placeholder { color: #7E7B9A; }
        .form-group input:focus {
            outline: none;
            border-color: #865DFF;
            box-shadow: 0 0 0 3px rgba(134, 93, 255, 0.25);
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            background: #865DFF;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            background: #E384FF;
            box-shadow: 0 0 20px rgba(227, 132, 255, 0.4);
        }
        .btn-submit:focus-visible {
            outline: 2px solid #E384FF;
            outline-offset: 2px;
        }

        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
        }
        .login-footer p {
            font-size: 13px;
            color: #7E7B9A;
        }
        .login-footer a {
            color: #cdbdff;
            font-weight: 600;
        }
        .login-footer a:hover { color: #E384FF; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 13px;
            font-weight: 500;
            color: #7E7B9A;
            padding: 0.75rem;
            border-radius: 9999px;
            border: 1px solid rgba(73, 68, 85, 0.3);
            transition: all 0.2s ease;
        }
        .back-link:hover {
            border-color: rgba(134, 93, 255, 0.3);
            color: #cac3d7;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>Laundryku</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/attempt') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" placeholder="Masukkan email Anda" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="login-footer">
            <p>Belum punya akun? <a href="/auth/register">Daftar sekarang</a></p>
        </div>
        <a href="/" class="back-link">Kembali ke beranda</a>
    </div>
</body>
</html>
