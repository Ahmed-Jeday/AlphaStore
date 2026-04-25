<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

// Préparer données Chart.js
$labels7days  = array_column($dailySales, 'sale_date');
$sales7days   = array_column($dailySales, 'order_count');
$revenue7days = array_column($dailySales, 'daily_revenue');

// Revenus mensuels — remplir tous les 12 mois
$monthNames = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
$revenueByMonth = array_fill(0, 12, 0);
foreach ($monthlyRevenue as $row) {
    $revenueByMonth[(int)$row['month'] - 1] = (float)$row['revenue'];
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="content-area">

        <!-- ── KPI Cards ───────────────────────────────────────── -->
        <div class="row g-3 mb-4">
            <!-- Revenus -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Chiffre d'affaires</div>
                            <div class="stat-value"><?= number_format($stats['revenue'], 0, ',', ' ') ?> €</div>
                            <div class="stat-trend text-success mt-2">
                                <i class="bi bi-arrow-up-right-circle-fill me-1"></i>+12% ce mois
                            </div>
                        </div>
                        <div class="stat-icon" style="background:#dcfce7;">
                            <i class="bi bi-currency-euro" style="color:#16a34a;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commandes -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Commandes</div>
                            <div class="stat-value"><?= $stats['orders'] ?></div>
                            <div class="stat-trend text-primary mt-2">
                                <i class="bi bi-arrow-up-right-circle-fill me-1"></i>+5 cette semaine
                            </div>
                        </div>
                        <div class="stat-icon" style="background:#dbeafe;">
                            <i class="bi bi-receipt-cutoff" style="color:#1d4ed8;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilisateurs -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Utilisateurs</div>
                            <div class="stat-value"><?= $stats['users'] ?></div>
                            <div class="stat-trend text-info mt-2">
                                <i class="bi bi-people-fill me-1"></i>actifs
                            </div>
                        </div>
                        <div class="stat-icon" style="background:#e0f2fe;">
                            <i class="bi bi-people-fill" style="color:#0369a1;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Produits</div>
                            <div class="stat-value"><?= $stats['products'] ?></div>
                            <div class="stat-trend text-warning mt-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <?= count($lowStockAlerts) ?> en stock faible
                            </div>
                        </div>
                        <div class="stat-icon" style="background:#fef9c3;">
                            <i class="bi bi-box-seam-fill" style="color:#b45309;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Alertes stock faible ──────────────────────────────── -->
        <?php if (!empty($lowStockAlerts)): ?>
        <div class="alert d-flex align-items-center gap-2 mb-4"
             style="background:#fffbeb;border:1px solid #fed7aa;border-radius:12px;color:#92400e;">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong><?= count($lowStockAlerts) ?> produit(s) en stock faible :</strong>
                <?php foreach ($lowStockAlerts as $p): ?>
                    <span class="badge" style="background:#fed7aa;color:#92400e;margin-left:4px;">
                        <?= htmlspecialchars($p['name']) ?> (<?= $p['stock'] ?>)
                    </span>
                <?php endforeach; ?>
                <a href="index.php?page=products" class="ms-2 fw-600" style="color:#b45309;font-size:13px;">
                    Gérer les stocks →
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Graphiques ──────────────────────────────────────────── -->
        <div class="row g-3 mb-4">
            <!-- Ventes 7 derniers jours -->
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Ventes — 7 derniers jours</span>
                        <span class="badge bg-primary-subtle text-primary" style="font-size:11px;">Commandes</span>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenus mensuels -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bar-chart-fill me-2 text-success"></i>Revenus mensuels</span>
                        <span class="badge bg-success-subtle text-success" style="font-size:11px;"><?= date('Y') ?></span>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <canvas id="monthlyChart" height="160"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Commandes récentes + Top produits ─────────────────── -->
        <div class="row g-3">
            <!-- Commandes récentes -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-clock-history me-2"></i>Commandes récentes</span>
                        <a href="index.php?page=orders" class="btn btn-sm btn-outline-primary" style="font-size:12px;">
                            Voir tout
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><span class="fw-700 text-primary">#<?= $order['id'] ?></span></td>
                                    <td><?= htmlspecialchars($order['user_name']) ?></td>
                                    <td class="fw-600"><?= number_format($order['total'], 2, ',', ' ') ?> €</td>
                                    <td>
                                        <span class="status-badge status-<?= $order['status'] ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:12px;">
                                        <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top produits -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-trophy-fill text-warning me-2"></i>Top produits vendus</span>
                        <a href="index.php?page=stats" class="btn btn-sm btn-outline-warning" style="font-size:12px;">Stats</a>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($topProducts as $i => $p): ?>
                        <div class="d-flex align-items-center gap-3 px-4 py-3"
                             style="border-bottom: 1px solid #f1f5f9;">
                            <span class="fw-800 text-muted" style="min-width:20px;font-size:13px;">
                                <?= $i + 1 ?>
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-600" style="font-size:13px;">
                                    <?= htmlspecialchars($p['name']) ?>
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    <?= $p['total_sold'] ?> vendus
                                </div>
                            </div>
                            <span class="fw-700 text-success" style="font-size:13px;">
                                <?= number_format($p['revenue'], 0, ',', ' ') ?> €
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.content-area -->
</div><!-- /.main-wrapper -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const primary = '#4f46e5';
const green   = '#16a34a';

// ── Graphique ventes 7 jours ──────────────────────────────────────────────
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($labels7days) ?>,
        datasets: [{
            label: 'Commandes',
            data: <?= json_encode($sales7days) ?>,
            borderColor: primary,
            backgroundColor: 'rgba(79,70,229,0.08)',
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: primary,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { size: 12 },
                bodyFont: { size: 13 },
                padding: 10,
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { size: 11 }, color: '#94a3b8',
                    callback: function(val, i) {
                        const d = new Date(this.getLabelForValue(val));
                        return d.toLocaleDateString('fr-FR', {day:'2-digit', month:'short'});
                    }
                }
            }
        }
    }
});

// ── Graphique revenus mensuels ────────────────────────────────────────────
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthNames) ?>,
        datasets: [{
            label: 'Revenus (€)',
            data: <?= json_encode(array_values($revenueByMonth)) ?>,
            backgroundColor: (ctx) => {
                const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                g.addColorStop(0, 'rgba(22,163,74,0.85)');
                g.addColorStop(1, 'rgba(22,163,74,0.1)');
                return g;
            },
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                callbacks: {
                    label: ctx => ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' €'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: {
                    font: { size: 10 }, color: '#94a3b8',
                    callback: v => v >= 1000 ? (v/1000)+'k€' : v+'€'
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 10 }, color: '#94a3b8' }
            }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
