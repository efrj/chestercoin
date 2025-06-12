<?php $title = "Importar Carteira"; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white p-4">
                <h2 class="card-title mb-0 text-center">
                    <i class="fas fa-file-import me-2"></i>
                    Importar Carteira
                </h2>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="import-icon-wrapper mb-3">
                        <i class="fas fa-wallet fa-3x text-primary"></i>
                    </div>
                    <p class="lead text-muted">
                        Importe sua carteira existente através de um arquivo JSON
                    </p>
                </div>

                <form method="post" enctype="multipart/form-data" class="import-form">
                    <div class="mb-4">
                        <div class="file-upload-wrapper">
                            <label for="wallet_file" class="form-label fw-bold">
                                <i class="fas fa-file-code me-2"></i>
                                Arquivo da Carteira
                            </label>
                            <div class="input-group">
                                <input type="file" 
                                       class="form-control form-control-lg" 
                                       name="wallet_file" 
                                       id="wallet_file" 
                                       accept=".json" 
                                       required>
                                <span class="input-group-text">
                                    <i class="fas fa-file-json"></i>
                                </span>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Apenas arquivos .json são aceitos
                            </small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-chc-gold btn-lg">
                            <i class="fas fa-file-import me-2"></i>
                            Importar Carteira
                        </button>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Como Importar
                </h5>
            </div>
            <div class="card-body">
                <ol class="import-instructions">
                    <li>Certifique-se que você possui o arquivo JSON da sua carteira</li>
                    <li>Clique em "Escolher arquivo" e selecione o arquivo .json</li>
                    <li>Clique em "Importar Carteira" para concluir o processo</li>
                </ol>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-shield-alt me-2"></i>
                    Seus dados são processados localmente e de forma segura
                </div>
            </div>
        </div>
    </div>
</div>
