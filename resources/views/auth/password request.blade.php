<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - FEL</title>
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
            background-color: #f9fafb;
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
            background: linear-gradient(to bottom right, #000000, #111827);
            color: #fff;
        }

        .left-panel .brand {
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            margin: 0;
        }

        .left-panel .content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .left-panel .eyebrow {
            font-size: 0.875rem;
            letter-spacing: 0.075em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0;
        }

        .left-panel h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
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
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            border: 1px solid #fff;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-outline:hover {
            background-color: #fff;
            color: #000;
        }

        .left-panel .footer-note {
            font-size: 0.75rem;
            color: #6b7280;
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
        }

        .form-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .form-header p {
            margin-top: 0.75rem;
            margin-bottom: 0;
            color: #4b5563;
        }

        .status-message {
            margin-bottom: 1.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #166534;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.5rem;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
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
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field input:focus {
            border-color: transparent;
            box-shadow: 0 0 0 2px #000;
        }

        .error-message {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc2626;
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            background-color: #000;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1f2937;
        }

        .back-to-login {
            margin-top: 1rem;
            text-align: center;
        }

        .back-to-login a {
            font-size: 0.875rem;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-to-login a:hover {
            color: #000;
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

        <!-- Partie gauche (Présentation) -->
        <div class="left-panel">
            <div>
                <h3 class="brand">Faso Expresse Livraison</h3>
            </div>
            <div class="content">
                <p class="eyebrow">Bienvenue sur notre plateforme</p>
                <h1>Une solution unique<br>pour gérer et suivre<br>vos commandes</h1>
                <p class="description">
                    Que vous soyez une entreprise, un livreur ou un client, profitez d'une expérience simple et fluide pour atteindre vos objectifs.
                </p>
                <a href="{{ route('login.page') }}" class="btn-outline">Se connecter</a>
            </div>
            <div class="footer-note">© 2026 FEL. Tous droits réservés.</div>
        </div>

        <!-- Partie droite (Formulaire de réinitialisation) -->
        <div class="right-panel">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>Mot de passe oublié</h1>
                    <p>Entrez votre email et nous vous enverrons un lien de réinitialisation</p>
                </div>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="field">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Adresse email">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary">Envoyer le lien de réinitialisation</button>

                    <div class="back-to-login">
                        <a href="{{ route('login.page') }}">&larr; Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>