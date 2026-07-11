<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            color: #111827;
        }

        .page {
            display: flex;
            height: 100%;
        }

        /* ---------- Left panel ---------- */
        .left-panel {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 50%;
            padding: 3rem;
            background-color: #000;
            color: #fff;
        }

        .left-panel .brand {
            margin: 1.25rem 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .left-panel .content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .left-panel .eyebrow {
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .left-panel h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.15;
            margin: 0;
        }

        .left-panel p.description {
            max-width: 28rem;
            color: #d1d5db;
            margin: 0;
        }

        .btn-outline {
            display: inline-block;
            align-self: flex-start;
            margin-top: 1rem;
            padding: 0.5rem 2rem;
            color: #fff;
            border: 2px solid #fff;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-outline:hover {
            background-color: #fff;
            color: #000;
        }

        /* ---------- Right panel / form ---------- */
        .right-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            padding: 2rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 28rem;
            margin: 0 auto;
            text-align: center;
        }

        .form-header {
            margin-bottom: 7rem;
        }

        .form-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
        }

        .form-header p {
            margin-top: 1rem;
            margin-bottom: 0;
            color: #4b5563;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            text-align: left;
        }

        .field {
            position: relative;
        }

        .field .icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            display: flex;
        }

        .field input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.875rem;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            outline: none;
        }

        .field input:focus {
            border-color: #000;
        }

        .field.has-toggle input {
            padding-right: 3rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            color: #9ca3af;
            cursor: pointer;
            display: flex;
        }

        .toggle-password:hover {
            color: #4b5563;
        }

        .toggle-password svg.hidden {
            display: none;
        }

        .error-message {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc2626;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #000;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
        }

        .remember-me label {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: #000;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #fff;
            background: linear-gradient(to top, #000000, #000000e8);
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: linear-gradient(to bottom, #000000, #000000e8);
        }

        .divider {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.875rem;
            height: 1.25rem;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px solid #e5e7eb;
        }

        .divider span {
            position: relative;
            z-index: 1;
            padding: 0 0.5rem;
            background-color: #fff;
            color: #6b7280;
        }

        .btn-google {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 0.75rem;
            font-size: 0.875rem;
            color: #111827;
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .btn-google:hover {
            background-color: #f3f4f6;
        }

        .btn-google svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .form-footer {
            font-size: 0.875rem;
            text-align: center;
            color: #4b5563;
            margin: 0;
        }

        .form-footer a {
            font-weight: 500;
            color: #000;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* ---------- Responsive: show left panel on large screens ---------- */
        @media (min-width: 1024px) {
            .left-panel {
                display: flex;
            }

            .right-panel {
                width: 50%;
                padding: 0 4rem;
            }
        }

        @media (min-width: 1280px) {
            .right-panel {
                padding: 0 6rem;
            }
        }
    </style>
</head>
<body>
    <div class="page">

        <!-- ------------------------------- Left panel ------------------------------- -->
        <div class="left-panel">
            <div>
                <h3 class="brand">Faso Livraison Expresse</h3>
            </div>
            <div class="content">
                <p class="eyebrow">BIENVENUE SUR NOTRE PLATEFORME</p>
                <h1>Une solution unique<br>pour gérer et suivre<br>vos commandes</h1>
                <p class="description">
                    Que vous soyez une entreprise, un livreur ou un client, profitez d'une expérience simple et fluide pour atteindre vos objectifs.
                </p>
                <a href="{{ route('register.page') }}" class="btn-outline">S'inscrire</a>
            </div>
            <div></div>
        </div>

        <!-- ------------------------------- Login form ------------------------------- -->
        <div class="right-panel">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>Connexion</h1>
                    <p>Entrez votre email et mot de passe pour accéder à votre compte</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="field">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="email" name="email" required placeholder="Entrez votre email">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field has-toggle">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" required placeholder="Entrez votre mot de passe">
                        <button type="button" class="toggle-password" onclick="togglePassword(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="show-eye hidden">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hide-eye">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié?</a>
                    </div>

                    <button type="submit" class="btn-primary">Se connecter</button>

                    <div class="divider">
                        <span>Ou</span>
                    </div>

                    <a href="{{ route('google.redirect') }}" class="btn-google">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        Se connecter avec Google
                    </a>

                    <p class="form-footer">
                        Vous n'avez pas de compte?
                        <a href="{{ route('register.page') }}">S'inscrire</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(button) {
            const field = button.closest('.field');
            const input = field.querySelector('input[name="password"]');
            const showEye = button.querySelector('.show-eye');
            const hideEye = button.querySelector('.hide-eye');

            input.type = input.type === 'password' ? 'text' : 'password';

            showEye.classList.toggle('hidden');
            hideEye.classList.toggle('hidden');
        }
    </script>
</body>
</html>