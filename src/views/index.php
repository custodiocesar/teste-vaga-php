<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/DataTables/jquery.dataTables.min.css">
    <script src="/public/DataTables/jquery-3.5.1.min.js"></script>
    <script src="/public/DataTables/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Sistema de Recrutamento</title>
</head>
<body>
    <div class="container mt-5">
        <!-- Nav Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="vagas-tab" data-bs-toggle="tab" data-bs-target="#vagas" type="button">Vagas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="candidatos-tab" data-bs-toggle="tab" data-bs-target="#candidatos" type="button">Candidatos</button>
            </li>
        </ul>
        <!-- Vagas Tab -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="vagas">
                <div class="table-responsive mt-3">
                    <?php include_once '../../src/vdata.php'; ?>
                    <table id="vagaTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pausada</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)) {
                                foreach ($data as $row) { ?>
                                    <tr data-id="<?= htmlspecialchars($row["id"]) ?>">
                                        <td><?= htmlspecialchars($row["id"]) ?></td>
                                        <td><?= htmlspecialchars($row["titulo"]) ?></td>
                                        <td><?= htmlspecialchars($row["tipo"]) ?></td>
                                        <td class="text-center status-cell">
                                            <span class="badge <?= ($row["status"] === "pausada") ? 'bg-secondary' : 'bg-success' ?>">
                                                <?= htmlspecialchars($row["status"]) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        <?= ($row["status"] === "pausada") ? 'checked' : '' ?>
                                                        onchange="trocaStatus(<?= $row['id'] ?>, this.checked)">
                                                </div>
                                            </div>
                                        </td>  
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editVaga(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" data-bs-toggle="modal" data-bs-target="#modalEditVaga">Editar</button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelVag('<?= $row['id'] ?>')">Excluir</button>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr><td colspan="5">Nenhuma vaga encontrada.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalVaga">Nova Vaga</button>
            </div>
            <div class="tab-pane fade" id="candidatos">
                <div class="table-responsive mt-3">
                    <?php include_once '../../src/cdata.php'; ?>
                    <table id="candidatoTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Habilidades</th>
                                <th>Vagas Inscritas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data1)) {
                                foreach ($data1 as $row) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row["id"]) ?></td>
                                        <td><?= htmlspecialchars($row["nome"]) ?></td>
                                        <td><?= htmlspecialchars($row["email"]) ?></td>
                                        <td><?= htmlspecialchars($row["habilidades"]) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row["vagas_inscritas"]) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editCandidato(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" data-bs-toggle="modal" data-bs-target="#modalEditCandidato">Editar</button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelCand('<?= $row['id'] ?>')">Excluir</button>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr><td colspan="6">Nenhum candidato encontrado.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCandidato">Novo Candidato</button>
            </div>
        </div>
    </div>

    <!-- Modal Vaga -->
    <div class="modal fade" id="modalVaga">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalVagaForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Vaga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" id="id">
                            <label class="form-label">Título*</label>
                            <input type="text" class="form-control" id="titulo" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição*</label>
                            <textarea class="form-control" id="descricao" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo*</label>
                            <select class="form-select" id="tipo" required>
                                <option value="">Selecione</option>
                                <option>CLT</option>
                                <option>PJ</option>
                                <option>Freelancer</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status">
                            <label class="form-check-label" for="status">Vaga Pausada</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Candidato -->
    <div class="modal fade" id="modalCandidato">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalCandidatoForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Candidato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome*</label>
                            <input type="text" class="form-control" id="nome" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Habilidades*</label>
                            <textarea class="form-control" id="habilidades" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vagas</label>
                            <select class="form-select" id="vagas_list" multiple>
                                <?php
                                include_once '../vdata.php';
                                if (!empty($data)) {
                                    foreach ($data as $vaga) {
                                        echo "<option value='{$vaga['id']}'>{$vaga['titulo']}</option>";
                                    }
                                } else {
                                    echo "<option disabled>Nenhuma vaga encontrada</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/public/js/candidato.js"></script>
    <script src="/public/js/vaga.js"></script>
    <script>
        $(document).ready(function() {
            $("#vagaTable, #candidatoTable").DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                }
            });
        });
    </script>
</body>
</html>