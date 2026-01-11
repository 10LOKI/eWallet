<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Database;
use App\Wallet;
use App\Depense;

$db = new Database();
$userId = $_SESSION['user_id'] ?? 1;

$wallet = new Wallet($db);
$depenseObj = new Depense($db->getConnection());

$budgetInfo = $wallet -> getBudgetByUserId($userId);
$totalDepenses = $depenseObj -> getTotalMonth($userId , date('m'), date('Y'));
$recentExpenses = $depenseObj -> getRecentByUserId($userId, 10);

$montantBudget = $budgetInfo['budget'] ?? 0;
$soldeRestant = $montantBudget - $totalDepenses;
$percent = ($montantBudget > 0) ? ($totalDepenses / $montantBudget) * 100 : 0;


$categoriesStmt = $db->getConnection()->query("SELECT id, name FROM categories ORDER BY name");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

    

    
    <main class="col-md-10 p-4">

      
      <?php include __DIR__ . '/../includes/topbar.php'; ?>

      
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card stat-card p-4">
            <div class="d-flex justify-content-between">
              <div>
                <small class="text-muted">Budget</small>
                <h3><?php echo number_format($montantBudget, 2); ?> MAD</h3>
              </div>
              <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            </div>
            <div class="progress mt-3">
              <div class="progress-bar" style="width: 100%"></div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card stat-card p-4">
            <div class="d-flex justify-content-between">
              <div>
                <small class="text-muted">Dépenses</small>
                <h3><?php echo number_format($totalDepenses, 2); ?> MAD</h3>
              </div>
              <div class="stat-icon"><i class="bi bi-graph-down"></i></div>
            </div>
            <div class="progress mt-3">
              <div class="progress-bar" style="width: <?php echo min($percent, 100); ?>%"></div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card stat-card p-4">
            <div class="d-flex justify-content-between">
              <div>
                <small class="text-muted">Solde restant</small>
                <h3><?php echo number_format($soldeRestant, 2); ?> MAD</h3>
              </div>
              <div class="stat-icon"><i class="bi bi-piggy-bank"></i></div>
            </div>
          </div>
        </div>
      </div>

      
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Dernières dépenses</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpense"><i class="bi bi-plus"></i> Ajouter</button>
        </div>

        <table class="table align-middle">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Date</th>
              <th class="text-end">Montant</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($recentExpenses)): ?>
              <?php foreach($recentExpenses as $expense): ?>
              <tr>
                <td><?php echo htmlspecialchars($expense['title']); ?> <?php if($expense['is_automatic']): ?><span class="auto">AUTO</span><?php endif; ?></td>
                <td><span class="badge-category"><?php echo htmlspecialchars($expense['categorie_nom']); ?></span></td>
                <td><?php echo date('d/m', strtotime($expense['expense_date'])); ?></td>
                <td class="text-end">-<?php echo number_format($expense['amount'], 2); ?></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Aucune dépense pour ce mois</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>


<div class="modal fade" id="addExpense">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Nouvelle dépense</h5>
      </div>
      <div class="modal-body">
        <input id="expenseTitle" class="form-control mb-2" placeholder="Titre" required>
        <input id="expenseAmount" class="form-control mb-2" type="number" step="0.01" placeholder="Montant" required>
        <input id="expenseDate" class="form-control mb-2" type="date" required>
        <select id="categoryId" class="form-select">
          <?php foreach($categories as $cat): ?>
          <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <small class="text-danger d-none mt-2" id="errorMsg"></small>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button id="submitExpense" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('submitExpense').addEventListener('click', function() {
    const title = document.getElementById('expenseTitle').value.trim();
    const amount = parseFloat(document.getElementById('expenseAmount').value);
    const date = document.getElementById('expenseDate').value;
    const categoryId = document.getElementById('categoryId').value;
    const errorMsg = document.getElementById('errorMsg');

    if(!title || !amount || !date) {
        errorMsg.textContent = 'Veuillez remplir tous les champs';
        errorMsg.classList.remove('d-none');
        return;
    }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('amount', amount);
    formData.append('expense_date', date);
    formData.append('category_id', categoryId);

    fetch('ajouter_depense.php', {
        method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addExpense')).hide();
            location.reload();
        } else {
            errorMsg.textContent = data.message;
            errorMsg.classList.remove('d-none');
        }
    })
    .catch(error => {
        errorMsg.textContent = 'Erreur de communication';
        errorMsg.classList.remove('d-none');
        console.error('Error:', error);
    });
});


document.getElementById('expenseDate').valueAsDate = new Date();
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
