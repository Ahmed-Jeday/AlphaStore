<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="content-area">

        <div class="mb-4">
            <h2 class="fw-800 mb-0">Analyse des Favoris</h2>
            <p class="text-muted mb-0" style="font-size:14px;">Produits les plus appréciés par les utilisateurs</p>
        </div>

        <div class="row g-3">
            <?php if (empty($mostFavorited)): ?>
            <div class="col-12">
                <div class="card p-5 text-center">
                    <i class="bi bi-heart fs-1 text-secondary d-block mb-2"></i>
                    <p class="text-muted">Aucun favori enregistré.</p>
                </div>
            </div>
            <?php else: ?>

            <!-- Podium top 3 -->
            <?php if (count($mostFavorited) >= 3): ?>
            <div class="col-12">
                <div class="card p-4">
                    <div class="card-header mb-3">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>Podium des favoris
                    </div>
                    <div class="row g-3 justify-content-center">
                        <?php
                        $medals = [
                            1 => ['emoji'=>'🥇','bg'=>'#fef9c3','border'=>'#fbbf24','text'=>'#92400e'],
                            2 => ['emoji'=>'🥈','bg'=>'#f1f5f9','border'=>'#94a3b8','text'=>'#475569'],
                            3 => ['emoji'=>'🥉','bg'=>'#fff7ed','border'=>'#fb923c','text'=>'#9a3412'],
                        ];
                        foreach (array_slice($mostFavorited, 0, 3) as $i => $p):
                            $rank = $i + 1;
                            $m = $medals[$rank];
                        ?>
                        <div class="col-md-4">
                            <div class="p-4 text-center rounded-3"
                                 style="background:<?= $m['bg'] ?>;border:2px solid <?= $m['border'] ?>;">
                                <div style="font-size:36px;"><?= $m['emoji'] ?></div>
                                <div class="fw-800 mt-2" style="font-size:16px;color:<?= $m['text'] ?>;">
                                    <?= htmlspecialchars($p['name']) ?>
                                </div>
                                <div style="font-size:13px;color:<?= $m['text'] ?>;">
                                    <?= number_format($p['price'], 2, ',', ' ') ?> €
                                </div>
                                <div class="mt-2" style="font-size:22px;font-weight:800;color:<?= $m['text'] ?>;">
                                    <i class="bi bi-heart-fill me-1" style="color:#dc2626;font-size:16px;"></i>
                                    <?= $p['favorites_count'] ?> favoris
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table complète -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-heart-fill text-danger me-2"></i>Classement complet
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Rang</th>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Favoris</th>
                                    <th>Popularité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $maxFav = $mostFavorited[0]['favorites_count'] ?? 1;
                                foreach ($mostFavorited as $i => $p):
                                    $pct = ($p['favorites_count'] / $maxFav) * 100;
                                ?>
                                <tr>
                                    <td>
                                        <span class="fw-800" style="font-size:18px;color:<?= $i < 3 ? '#b45309' : '#94a3b8' ?>;">
                                            #<?= $i + 1 ?>
                                        </span>
                                    </td>
                                    <td class="fw-600"><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= number_format($p['price'], 2, ',', ' ') ?> €</td>
                                    <td>
                                        <span class="fw-700" style="color:#dc2626;">
                                            <i class="bi bi-heart-fill me-1"></i><?= $p['favorites_count'] ?>
                                        </span>
                                    </td>
                                    <td style="min-width:160px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="flex:1;background:#e2e8f0;border-radius:99px;height:8px;">
                                                <div style="width:<?= $pct ?>%;height:8px;background:linear-gradient(90deg,#dc2626,#f97316);border-radius:99px;transition:width .5s;"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:700;min-width:30px;"><?= round($pct) ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
