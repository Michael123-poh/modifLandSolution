<?php
require('views/template/header.php');
require('views/template/navbar.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2 text-primary-blue">Tableau de bord</h1>
        <button class="btn btn-outline-primary" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Actualiser
        </button>
    </div>

    <!-- Statistiques globales -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3 border-primary">
                <i class="bi bi-map-fill text-primary fs-1"></i>
                <h6 class="text-muted">Sites</h6>
                <h3 class="fw-bold"><?= $Totalesite ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3 border-success">
                <i class="bi bi-grid-fill text-success fs-1"></i>
                <h6 class="text-muted">Blocs</h6>
                <h3 class="fw-bold"><?= $Totalebloc ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3 border-info">
                <i class="bi bi-people-fill text-info fs-1"></i>
                <h6 class="text-muted">Acheteurs</h6>
                <h3 class="fw-bold"><?= $Totaleacheteur ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3 border-warning">
                <i class="bi bi-cash-stack text-warning fs-1"></i>
                <h6 class="text-muted">Total encaissé</h6>
                <h3 class="fw-bold"><?= number_format($Totaleencaisse, 0, ',', ' ') ?> FCFA</h3>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h6 class="mb-3">Répartition des ventes par site</h6>
                <canvas id="ventesParSite"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h6 class="mb-3">Paiements par mois</h6>
                <canvas id="paiementsMois"></canvas>
            </div>
        </div>
    </div>

    <!-- Dernières transactions -->
    <div class="card shadow-sm p-3 mb-5">
        <h6 class="mb-3">Dernières transactions</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Acheteur</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($derniereTransactions)) : ?>
                    <?php foreach ($derniereTransactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction->idTransaction ?></td>
                        <td><?= htmlspecialchars($transaction->nomAcheteur) ?></td>
                        <td><?= number_format($transaction->montantTransaction, 0, ',', ' ') ?> FCFA</td>
                        <td><?= date('d/m/Y', strtotime($transaction->dateTransaction)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucune transaction récente.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ventesParSiteCtx = document.getElementById('ventesParSite').getContext('2d');
const paiementsMoisCtx = document.getElementById('paiementsMois').getContext('2d');

const ventesParSite = new Chart(ventesParSiteCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_map(fn($v) => $v->numeroTitreFoncier, $ventesParSite)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($v) => (int)$v->total, $ventesParSite)) ?>,
            backgroundColor: ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#9333ea']
        }]
    }
});

const paiementsMois = new Chart(paiementsMoisCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($paiementsParMois)) ?>,
        datasets: [{
            label: 'Montant encaissé',
            data: <?= json_encode(array_values($paiementsParMois)) ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.2)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#2563eb'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>