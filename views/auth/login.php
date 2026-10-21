<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">SENN AI-X</h3>
            <p class="text-muted small">Gestão Logística de Última Milha</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small" role="alert">
                <?= sanitizeOutput($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?route=login" method="POST">
            <!-- Token CSRF para segurança (RNF01) -->
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

            <div class="mb-3">
                <label for="email" class="form-label text-secondary small fw-semibold">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seu email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-secondary small fw-semibold">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="123456" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Entrar no Sistema</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">SENAI DEV EXPERIENCE 2026</small>
        </div>
    </div>
</div>

</body>
</html>