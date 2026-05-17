<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monefy - Authentication')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    
    <style>
        .auth-page {
            min-height: 100vh;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
        }
        .auth-card {
            background-color: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(106, 76, 255, 0.08);
        }
        
        @media (max-width: 768px) {
            .auth-card {
                padding: 2rem;
            }
        }
        
        .auth-brand {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            text-decoration: none;
        }
        .form-control-custom {
            background-color: #F8F9FA;
            border: 1px solid #E2E8F0;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-control-custom:focus {
            background-color: white;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 4px rgba(106, 76, 255, 0.1);
            outline: none;
        }
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .auth-side-image {
            background: var(--gradient-card);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 15px 35px rgba(98, 66, 232, 0.2);
            height: 100%;
        }
        .auth-side-image img {
            max-width: 100%;
            display: block;
        }
        
        .btn-auth {
            width: 100%;
            padding: 0.8rem;
            border-radius: 12px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

    @yield('content')

    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
