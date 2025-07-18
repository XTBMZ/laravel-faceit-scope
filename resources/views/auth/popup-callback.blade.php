<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion FACEIT</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f0f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            text-align: center;
            max-width: 400px;
        }
        .success {
            color: #10b981;
        }
        .error {
            color: #ef4444;
        }
        .spinner {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        @if($success)
            <div class="success">
                <h2>✅ Connexion réussie !</h2>
                <p>Bienvenue {{ $user['nickname'] ?? 'sur FACEIT Scope' }} !</p>
                <p><small>Cette fenêtre va se fermer automatiquement...</small></p>
            </div>
        @else
            <div class="error">
                <h2>❌ Erreur de connexion</h2>
                <p>{{ $error ?? 'Une erreur est survenue' }}</p>
                <p><small>Cette fenêtre va se fermer automatiquement...</small></p>
            </div>
        @endif
        
        <div id="loading" style="display: none;">
            <span class="spinner">⟳</span> Fermeture...
        </div>
    </div>

    <script>
        // Données à transmettre à la fenêtre parent
        const authResult = {
            success: {{ $success ? 'true' : 'false' }},
            @if($success)
            user: @json($user),
            @endif
            @if(!$success)
            error: {{ json_encode($error ?? 'Erreur inconnue') }},
            @endif
        };

        // Fonction pour fermer la popup et transmettre les données
        function closePopup() {
            try {
                // Transmettre le résultat à la fenêtre parent
                if (window.opener) {
                    window.opener.postMessage({
                        type: 'FACEIT_AUTH_RESULT',
                        data: authResult
                    }, window.location.origin);
                }
                
                // Fermer la popup
                window.close();
            } catch (error) {
                console.error('Erreur fermeture popup:', error);
                // Si la fermeture automatique échoue, afficher un message
                document.querySelector('.container').innerHTML = `
                    <div class="error">
                        <h3>Veuillez fermer cette fenêtre manuellement</h3>
                        <button onclick="window.close()" style="
                            background: #ff5500; 
                            color: white; 
                            border: none; 
                            padding: 10px 20px; 
                            border-radius: 8px; 
                            cursor: pointer;
                            margin-top: 10px;
                        ">Fermer</button>
                    </div>
                `;
            }
        }

        // Attendre un court délai puis fermer
        setTimeout(() => {
            document.getElementById('loading').style.display = 'block';
            setTimeout(closePopup, 1000);
        }, 2000);

        // Fermer immédiatement si l'utilisateur clique
        document.addEventListener('click', closePopup);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Enter') {
                closePopup();
            }
        });
    </script>
</body>
</html>