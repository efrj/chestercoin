<?php $title = "Login"; ?>

<?php if (isset($generatedKeys) && $generatedKeys): ?>
    <div class="alert alert-success">
        <strong>Sua carteira foi criada!</strong><br>
        <strong>Chave Pública (Hash):</strong> <?= htmlspecialchars($generatedKeys['publicHash']) ?><br>
        <strong>Chave Privada:</strong> <?= htmlspecialchars($generatedKeys['privateKey']) ?><br><br>
        Guarde bem sua chave privada!
    </div>
    <a href="/login" class="btn btn-primary">Ir para Carteira</a>
<?php else: ?>
    <h2 class="text-center mb-4">Login com Chave Privada</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="private_key" class="form-label">Digite sua Chave Privada</label>
            <input type="text" name="private_key" id="private_key" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <hr class="my-4">

    <p class="text-center">
        <a href="/new-wallet" class="btn btn-success">Criar Nova Carteira</a>
    </p>
<?php endif; ?>