<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Land Solution - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0D1B2A;
            --primary-green: #FF6B35;
            --light-blue: rgba(13, 27, 42, 0.5);
            --light-green: rgba(255, 107, 53, 0.5);
        }
        
        .bg-primary-blue { background-color: var(--primary-blue) !important; }
        .bg-primary-green { background-color: var(--primary-green) !important; }
        .text-primary-blue { color: var(--primary-blue) !important; }
        .text-primary-green { color: var(--primary-green) !important; }

        body {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            padding: 2rem;
            text-align: center;
            border-bottom: none;
        }

        .card-body {
            padding: 2rem;
        }

        .form-floating > label {
            color: #6c757d; /* Bootstrap default muted color */
        }

        .btn-login {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #138a3f; /* Slightly darker green for hover */
            border-color: #138a3f;
        }

        .text-link {
            color: var(--primary-blue);
            transition: color 0.3s ease;
        }

        .text-link:hover {
            color: #1a4ed8; /* Slightly darker blue for hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header bg-primary-blue text-white">
                        <h2 class="mb-0">Land-Solution</h2>
                        <p class="lead mb-0">Connectez-vous à votre compte</p>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-floating mb-3">
                                <input type="name" name="noms" class="form-control" id="name" placeholder="john" required>
                                <label for="name">Pseudo</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" name="mdp" class="form-control" id="password" placeholder="Mot de passe" required>
                                <label for="password">Mot de passe</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" name="connecter" class="btn btn-lg btn-login text-white">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Se connecter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
