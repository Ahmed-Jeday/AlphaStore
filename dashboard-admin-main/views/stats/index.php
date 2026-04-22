<?php
$pageTitle  = 'Statistiques';
$breadcrumb = 'Statistiques';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

// Préparer données graphiques
$daysLabels   = array_column($dailySales, 'sale_date');
$daysRevenue  = array_column($dailySales, 'daily_revenue');
$daysOrders   = array_column($dailySales, 'order_count');

$monthNames = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
$monthlyData = array_fill(0, 12, 0);
foreach ($monthlyRevenue as $row) {
    $monthlyData[(int)$row['month'] - 1] = (float)$row['revenue'];
}

// Top produits pour graphique donut
$topLabels  = array_column($topProducts, 'name');
$topQtys    = array_column($topProducts, 'total_sold');
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="content-area">

        <div class="mb-4">
            <h2 class="fw-800 mb-0">Statistiques & Analyses</h2>
            <p class="text-muted mb-0" style="font-size:14px;">Vue d'ensemble des performances</p>
        </div>

        <!-- ── KPIs ──────────────────────────────────────────────── -->
        <div class="row g-3 mb-4">
            <?php
            $kpis = [
                ['label'=>'Chiffre d\'affaires', 'value'=>number_format($totalRevenue,2,',',' ').' €',  'icon'=>'bi-currency-euro', 'bg'=>'#dcfce7','ic'=>'#16a34a'],
                ['label'=>'Total commandes',      'value'=>$totalOrders,                                'icon'=>'bi-receipt-cutoff','bg'=>'#dbeafe','ic'=>'#1d4ed8'],
                ['label'=>'Utilisateurs',          'value'=>$totalUsers,                                'icon'=>'bi-people-fill',   'bg'=>'#e0f2fe','ic'=>'#0369a1'],
                ['label'=>'Produits',              'value'=>$totalProducts,                             'icon'=>'bi-box-seam-fill', 'bg'=>'#fef9c3','ic'=>'#b45309'],
            ];
            foreach ($kpis as $k): ?>
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label mb-2"><?= $k['label'] ?></div>
                            <div class="stat-value"><?= $k['value'] ?></div>
                        </div>
                        <div class="stat-icon" style="background:<?= $k['bg'] ?>;">
                            <i class="bi <?= $k['icon'] ?>" style="color:<?= $k['ic'] ?>;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── Graphiques ligne + barres ────────────────────────── -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Revenus sur 30 jours
                    </div>
                    <div class="card-body p-4">
                        <canvas id="revenueChart" height="90"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-pie-chart-fill me-2 text-success"></i>Top 5 produits vendus
                    </div>
                    <div class="card-body p-4 d-flex align-items-center justify-content-center">
                        <canvas id="donutChart" style="max-height:220px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Revenus mensuels ──────────────────────────────────── -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bar-chart-line-fill me-2 text-warning"></i>Revenus mensuels <?= date('Y') ?>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="monthlyChart" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Table Top produits ────────────────────────────────── -->
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>Top produits — Ventes détaillées
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produit</th>
                                    <th class="text-center">Qté vendue</th>
                                    <th class="text-end">Revenu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topProducts as $i => $p): ?>
                                <tr>
                                    <td>
                                        <span class="fw-800" style="font-size:16px;color:<?= $i < 3 ? '#b45309' : '#94a3b8' ?>;">
                                            <?= $i + 1 ?>
                                        </span>
                                    </td>
                                    <td class="fw-600"><?= htmlspecialchars($p['name']) ?></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div style="flex:1;background:#e2e8f0;border-radius:99px;height:6px;max-width:80px;">
                                                <div style="width:<?= min(100, ($p['total_sold'] / max(1, $topProducts[0]['total_sold'])) * 100) ?>%;
                                                            height:6px;background:#4f46e5;border-radius:99px;"></div>
                                            </div>
                                            <span class="fw-600"><?= $p['total_sold'] ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end fw-700 text-success">
                                        <?= number_format($p['revenue'], 2, ',', ' ') ?> €
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Favoris -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-heart-fill text-danger me-2"></i>Produits les plus aimés
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($mostFavorited as $i => $p): ?>
                        <div class="d-flex align-items-center gap-3 px-4 py-3"
                             style="border-bottom:1px solid #f1f5f9;">
                            <span class="fw-800 text-muted" style="min-width:20px;"><?= $i+1 ?></span>
                            <div class="flex-grow-1">
                                <div class="fw-600" style="font-size:13px;"><?= htmlspecialchars($p['name']) ?></div>
                                <div class="text-muted" style="font-size:11px;"><?= $p['price'] ?> €</div>
                            </div>
                            <span style="color:#dc2626;font-weight:700;font-size:13px;">
                                <i class="bi bi-heart-fill me-1"></i><?= $p['favorites_count'] ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Revenus 30 jours ─────────────────────────────────────────────────────
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($daysLabels) ?>,
        datasets: [
            {
                label: 'Revenus (€)',
                data: <?= json_encode(array_map('floatval', $daysRevenue)) ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.07)',
                borderWidth: 2.5, tension: 0.4, fill: true,
                yAxisID: 'y', pointRadius: 3, pointHoverRadius: 5
            },
            {
                label: 'Commandes',
                data: <?= json_encode(array_map('intval', $daysOrders)) ?>,
                borderColor: '#16a34a',
                backgroundColor: 'transparent',
                borderWidth: 2, tension: 0.4,
                yAxisID: 'y2', pointRadius: 3, borderDash: [5,4]
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { font:{size:12} } },
            tooltip: { backgroundColor: '#0f172a', cornerRadius: 8 }
        },
        scales: {
            y:  { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{font:{size:11},color:'#94a3b8',callback:v=>v+'€'} },
            y2: { beginAtZero:true, position:'right', grid:{display:false}, ticks:{font:{size:11},color:'#16a34a'} },
            x:  { grid:{display:false}, ticks:{font:{size:10},color:'#94a3b8',maxTicksLimit:10,
                  callback: function(val,i){ const d=new Date(this.getLabelForValue(val)); return d.toLocaleDateString('fr-FR',{day:'2-digit',month:'short'}); }
                }}
        }
    }
});

// ── Donut top 5 produits ─────────────────────────────────────────────────
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_slice($topLabels, 0, 5)) ?>,
        datasets: [{
            data: <?= json_encode(array_slice($topQtys, 0, 5)) ?>,
            backgroundColor: ['#4f46e5','#7c3aed','#16a34a','#d97706','#dc2626'],
            borderWidth: 0, hoverOffset: 8
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font:{size:11}, padding:12, boxWidth:12 } }
        }
    }
});

// ── Revenus mensuels ─────────────────────────────────────────────────────
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthNames) ?>,
        datasets: [{
            label: 'Revenus (€)',
            data: <?= json_encode(array_values($monthlyData)) ?>,
            backgroundColor: (ctx) => {
                const g = ctx.chart.ctx.createLinearGradient(0,0,0,250);
                g.addColorStop(0,'rgba(79,70,229,0.85)');
                g.addColorStop(1,'rgba(79,70,229,0.1)');
                return g;
            },
            borderRadius: 6, borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend:{display:false},
            tooltip:{ backgroundColor:'#0f172a', callbacks:{label:ctx=>' '+ctx.parsed.y.toLocaleString('fr-FR')+' €'} }
        },
        scales: {
            y: { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{font:{size:11},color:'#94a3b8',callback:v=>v>=1000?(v/1000)+'k€':v+'€'} },
            x: { grid:{display:false}, ticks:{font:{size:11},color:'#94a3b8'} }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
