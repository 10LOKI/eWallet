<aside class="col-md-2 sidebar p-0">
  <h4 class="text-center py-4">WALLET</h4>

<?php $current = strtolower(basename($_SERVER['PHP_SELF'])); ?>

  <a class="<?= $current=='index.php'?'active':'' ?>" href="index.php">
    <i class="bi bi-speedometer2"></i> Dashboard
  </a>

  <a class="<?= $current=='depenses.php'?'active':'' ?>" href="Depenses.php">
    <i class="bi bi-cash"></i> Dépenses
  </a>

  <a class="<?= $current=='categories.php'?'active':'' ?>" href="Categories.php">
    <i class="bi bi-tags"></i> Catégories
  </a>

  <a class="<?= $current=='automatiques.php'?'active':'' ?>" href="Automatiques.php">
    <i class="bi bi-arrow-repeat"></i> Automatiques
  </a>

  <a class="<?= $current=='profil.php'?'active':'' ?>" href="Profil.php">
    <i class="bi bi-person"></i> Profil
  </a>
</aside>