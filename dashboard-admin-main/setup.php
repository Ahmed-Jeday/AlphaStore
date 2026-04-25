<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .container-setup {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .setup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .setup-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .setup-header p {
            color: #64748b;
            font-size: 14px;
        }
        .status-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #4f46e5;
        }
        .status-box h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .status-item {
            font-size: 14px;
            padding: 8px 0;
            color: #64748b;
        }
        .status-item.success {
            color: #16a34a;
        }
        .status-item.error {
            color: #dc2626;
        }
        .status-item.pending {
            color: #d97706;
        }
        .btn-setup {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-setup:hover:not(:disabled) {
            background: #3730a3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }
        .btn-setup:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
            vertical-align: middle;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message-box {
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
            display: none;
        }
        .message-box.success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            display: block;
        }
        .message-box.error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container-setup">
        <div class="setup-header">
            <h1>🔧 Installation Dashboard</h1>
            <p>Initialisation de la base de données</p>
        </div>

        <div class="status-box">
            <h3>État du système</h3>
            <div class="status-item pending" id="db-status">
                ⏳ Vérification de la base de données...
            </div>
            <div class="status-item pending" id="tables-status">
                ⏳ Vérification des tables...
            </div>
        </div>

        <button class="btn-setup" id="setupBtn" onclick="importSchema()">
            <span class="loader" id="loader"></span>
            Importer les tables
        </button>

        <div class="message-box" id="messageBox"></div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="../../login.php" style="color: #4f46e5; text-decoration: none; font-size: 14px;">
                ← Retour à la connexion
            </a>
        </div>
    </div>

    <script>
    // Vérifier le statut lors du chargement
    document.addEventListener('DOMContentLoaded', function() {
        checkStatus();
    });

    function checkStatus() {
        fetch('check_status.php')
            .then(r => r.json())
            .then(data => {
                // Mettre à jour le statut de la base de données
                const dbStatus = document.getElementById('db-status');
                if (data.db_exists) {
                    dbStatus.className = 'status-item success';
                    dbStatus.textContent = '✓ Base de données alphastore OK';
                } else {
                    dbStatus.className = 'status-item error';
                    dbStatus.textContent = '✗ Base de données introuvable';
                }

                // Mettre à jour le statut des tables
                const tablesStatus = document.getElementById('tables-status');
                if (data.tables_count > 0) {
                    tablesStatus.className = 'status-item success';
                    tablesStatus.textContent = `✓ ${data.tables_count} table(s) créée(s)`;
                    document.getElementById('setupBtn').disabled = true;
                    document.getElementById('setupBtn').textContent = 'Installation déjà complétée ✓';
                } else {
                    tablesStatus.className = 'status-item pending';
                    tablesStatus.textContent = '⏳ Tables non créées';
                }
            })
            .catch(err => {
                document.getElementById('db-status').className = 'status-item error';
                document.getElementById('db-status').textContent = '✗ Erreur de vérification';
            });
    }

    function importSchema() {
        const btn = document.getElementById('setupBtn');
        const loader = document.getElementById('loader');
        const msgBox = document.getElementById('messageBox');

        btn.disabled = true;
        loader.style.display = 'inline-block';

        fetch('config/import_schema.php')
            .then(r => r.json())
            .then(data => {
                loader.style.display = 'none';
                
                if (data.success) {
                    msgBox.className = 'message-box success';
                    msgBox.innerHTML = `
                        <strong>✓ Succès!</strong><br>
                        ${data.message}<br>
                        <small>Tables créées: ${data.tables_created.join(', ')}</small>
                    `;
                    btn.textContent = 'Installation réussie ✓';
                    setTimeout(() => {
                        window.location.href = '../../login.php';
                    }, 2000);
                } else {
                    msgBox.className = 'message-box error';
                    msgBox.innerHTML = `<strong>✗ Erreur:</strong> ${data.error}`;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                msgBox.className = 'message-box error';
                msgBox.innerHTML = `<strong>✗ Erreur réseau:</strong> ${err.message}`;
                btn.disabled = false;
            });
    }
    </script>
</body>
</html>
