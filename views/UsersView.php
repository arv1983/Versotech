<?php
if (!isset($users)) {
    $users = [];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .container-custom {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        .color-checkbox {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            margin-right: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            position: relative;
        }
        .color-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        .color-checkbox.checked {
            border: 2px solid #000;
        }
    </style>
</head>
<body>
<div class="container-custom">
    <h1 class="mb-4 text-center">Usuários</h1>
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success" onclick="openUserModal()">Novo Usuário</button>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <button type="button"
                            class="btn btn-primary btn-sm me-2"
                            onclick='openUserModal(<?= json_encode($user) ?>)'>
                        Editar
                    </button>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="confirmDelete(<?= htmlspecialchars($user['id']) ?>, '<?= htmlspecialchars(addslashes($user['name'])) ?>')">
                        Excluir
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" action="index.php">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="confirmDeleteLabel">Confirmação</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir o usuário <strong id="userName"></strong>?
      </div>
      <input type="hidden" name="id" id="userIdInput" value="">
      <input type="hidden" name="action" value="deleteUser">
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Excluir</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de RSPOSTA -->
<?php if (isset($_SESSION['message'])): ?>
  <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-<?=$_SESSION['message']['type']?>">
        <div class="modal-header bg-<?=$_SESSION['message']['type']?> text-white">
          <h5 class="modal-title" id="feedbackModalLabel">Aviso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <?= htmlspecialchars($_SESSION['message']['text']) ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!-- Modal de Criação/Edição -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" action="index.php">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="createUserLabel">Novo Usuário</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name" class="form-label">Nome</label>
          <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Cores favoritas</label>
          <div id="colorCheckboxes" class="d-flex flex-wrap">
            <?php foreach ($colors as $color): ?>
              <label class="color-checkbox me-2 mb-2" style="background-color: <?= $color ?>;">
                <input type="checkbox" name="colors[]" value="<?= $color ?>">
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <input type="hidden" name="id" id="editUserId" value="">
        <input type="hidden" name="action" id="userAction" value="newUser">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(userId, userName) {
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    document.getElementById('userName').textContent = userName;
    document.getElementById('userIdInput').value = userId;
    modal.show();
}

function openUserModal(user = null) {
    const modal = new bootstrap.Modal(document.getElementById('createUserModal'));
    document.getElementById('createUserLabel').textContent = user ? 'Editar Usuário' : 'Novo Usuário';
    document.getElementById('name').value = user ? user.name : '';
    document.getElementById('email').value = user ? user.email : '';
    document.getElementById('editUserId').value = user ? user.id : '';
    document.getElementById('userAction').value = user ? 'editUser' : 'newUser';
    document.querySelectorAll('#colorCheckboxes .color-checkbox').forEach(cb => {
        cb.classList.remove('checked');
        cb.querySelector('input').checked = false;
    });
    if (user && user.id) {
        fetch(`index.php?action=getColorsUser&id=${user.id}`)
            .then(res => res.json())
            .then(data => {
                const colors = data.colors;
                document.querySelectorAll('#colorCheckboxes input').forEach(input => {
                    const isChecked = colors.includes(input.value);
                    input.checked = isChecked;
                    input.parentElement.classList.toggle('checked', isChecked);
                });
            })
            .catch(err => {
                alert('Erro ao carregar cores do usuário.');
                console.error(err);
            });
    }
    modal.show();
}

window.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('feedbackModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        setTimeout(() => modal.hide(), 3000);
    }

    document.querySelectorAll('#colorCheckboxes .color-checkbox input').forEach(input => {
        input.addEventListener('change', (e) => {
            const parent = e.target.parentElement;
            if (e.target.checked) {
                parent.classList.add('checked');
            } else {
                parent.classList.remove('checked');
            }
        });
    });
});
</script>
</body>
</html>
