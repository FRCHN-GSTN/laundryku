<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laundryku' ?></title>
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
                        'soft-pink': '#FFA3FD',
                        'hot-pink': '#B3005E',
                        'magenta': '#E90064',
                        'rose': '#FF5F9E',
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
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-btn {
            background: linear-gradient(135deg, #865DFF 0%, #E384FF 100%);
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            box-shadow: 0 0 20px rgba(134, 93, 255, 0.5);
            transform: translateY(-2px);
        }
        .sidebar {
            background: #060047;
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(134, 93, 255, 0.2);
            border-left: 3px solid #865DFF;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #865DFF;
            box-shadow: 0 0 10px rgba(134, 93, 255, 0.3);
            outline: none;
        }
    </style>
</head>
<body class="text-white min-h-screen">
    <?= $this->renderSection('content') ?>
</body>
</html>
