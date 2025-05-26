<?php $title = "Importar Carteira"; ?>

<h2 class="text-center mb-4">Importar Carteira (.json)</h2>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="wallet_file" class="form-label">Selecione o arquivo JSON da carteira:</label>
        <input type="file" class="form-control" name="wallet_file" id="wallet_file" accept=".json" required>
    </div>
    <button type="submit" class="btn btn-primary">Importar</button>
</form>