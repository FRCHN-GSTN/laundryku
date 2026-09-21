<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Laundryku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark': '#191825',
                        'dark-secondary': '#060047',
                        'primary': '#865DFF',
                        'primary-light': '#E384FF',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #191825;
        }
        .card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .btn-primary {
            background: #865DFF;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #7048e8;
        }
        .btn-primary:focus-visible {
            outline: 2px solid #E384FF;
            outline-offset: 2px;
        }
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: border-color 0.2s ease;
        }
        .input-field:focus {
            border-color: #865DFF;
            outline: none;
            box-shadow: 0 0 0 2px rgba(134, 93, 255, 0.3);
        }
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="card rounded-lg p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary">Laundryku</h1>
                <p class="text-gray-400 mt-2">Buat akun baru</p>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <ul class="list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/auth/register" method="POST">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= old('name') ?>" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="<?= old('email') ?>" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Masukkan email">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">No. Telepon</label>
                    <input type="tel" name="phone" value="<?= old('phone') ?>" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Masukkan no. telepon">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                    <textarea name="address" rows="2"
                              class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                              placeholder="Masukkan alamat (opsional)"><?= old('address') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Masukkan password (min. 6 karakter)">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirm" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn-primary w-full py-3 rounded-lg font-semibold text-white">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    Sudah punya akun? 
                    <a href="/auth/login" class="text-primary hover:underline">
                        Masuk sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
