<?php $title = "Login"; ?>

<style>
.card {
    border-radius: 15px;
    transition: transform 0.2s;
}

.form-control:focus {
    border-color: var(--chc-gold);
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

@media (max-width: 576px) {
    .card-body {
        padding: 1.5rem;
    }
}
</style>

<?php if (isset($generatedKeys) && $generatedKeys): ?>
    <div class="alert alert-success">
        <strong>Sua carteira foi criada!</strong><br>
        <strong>Chave Pública (Hash):</strong> <?= htmlspecialchars($generatedKeys['publicHash']) ?><br>
        <strong>Chave Privada:</strong> <?= htmlspecialchars($generatedKeys['privateKey']) ?><br><br>
        Guarde bem sua chave privada!
    </div>
    <a href="/login" class="btn btn-chc-gold">Ir para Carteira</a>
<?php else: ?>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="logo-wrapper mb-4">
                            <img src="/assets/img/logo.png" alt="Chestercoin Logo" height="80" class="logo-img">
                        </div>
                        <h2 class="fw-bold" style="color: var(--chc-gold)">Login com Chave Privada</h2>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-4">
                            <label for="private_key" class="form-label fw-bold">Digite sua Chave Privada</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="private_key" 
                                       id="private_key" 
                                       class="form-control form-control-lg border-2" 
                                       style="border-color: var(--chc-border)"
                                       placeholder="Digite sua chave privada aqui"
                                       required>
                            </div>
                            <div class="form-text text-muted">
                                Sua chave privada nunca será compartilhada
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-chc-gold btn-lg w-100 mb-3">
                            Entrar na Carteira
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-3">Ainda não tem uma carteira?</p>
                        <a href="/new-wallet" class="btn btn-chc-turquoise w-100">
                            Criar Nova Carteira
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
