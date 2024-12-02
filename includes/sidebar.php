<?php
$currentFile = basename($_SERVER['PHP_SELF']);
?>

<?php if(isset($sidebar_opener) && $sidebar_opener) : ?>
<!-- <div class="wrapper"> -->
  <aside id="sidebar">
    <!-- <div class="custom-menu">
      <button type="button" id="sidebarCollapse" class="btn btn-primary">
        <i class="fa fa-bars"></i>
        <span class="sr-only">Toggle Menu</span>
      </button>
    </div> -->
    <nav class="">
      <div class="title ps-4">
        <h4>
          <a href="/" class='nav-link' class="logo">
            Rumah <span class="retret-text">Retret</span>
          </a>
        </h4>
      </div>

      <ul class="list-unstyled navbar-nav" id="navbar-nav">
          <p>MENU</p>
        <li class="nav-item <?php echo $currentFile == 'index.php' ? 'active' : '' ?>">
          <a href="./" class="nav-link"><i class="fa fa-home me-3"></i> Home</a>
        </li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') : ?>
        <li class="nav-item <?php echo $currentFile == 'retret-house.php' ? 'active' : '' ?>">
          <a href="./retret-house.php" class="nav-link"><i class="fa fa-home me-3"></i> Rumah Retret</a>
        </li>
        <li class="nav-item <?php echo $currentFile == 'room.php' ? 'active' : '' ?>">
          <a href="./room.php" class="nav-link"><i class="fa fa-square me-3"></i> Kamar</a>
        </li>
         <li class="nav-item <?php echo $currentFile == 'facility.php' ? 'active' : '' ?>">
          <a href="./facility.php" class="nav-link"><i class="fa fa-briefcase me-3"></i> Fasilitas</a>
        </li>
        <li class="nav-item <?php echo $currentFile == 'reservation.php' ? 'active' : '' ?>">
          <a href="./reservation.php" class="nav-link"><i class="fa fa-sticky-note me-3"></i> Pemesanan</a>
        </li>
        <li class="nav-item <?php echo $currentFile == 'review.php' ? 'active' : '' ?>">
          <a href="./review.php" class="nav-link"><i class="fa fa-comment me-3"></i> Ulasan</a>
        </li>
        <li class="nav-item <?php echo $currentFile == 'user.php' ? 'active' : '' ?>">
          <a href="./user.php" class="nav-link"><i class="fa fa-users me-3"></i> Pengguna</a>
        </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'visitor') : ?>
          <li class="nav-item <?php echo $currentFile == 'reservation.php' ? 'active' : '' ?>">
            <a href="./reservation.php" class="nav-link"><i class="fa fa-sticky-note me-3"></i> Pemesanan</a>
          </li>
        <?php endif;?>
          <p>LAINNYA</p>
        <li class="nav-item <?php echo $currentFile == 'account.php' ? 'active' : '' ?>">
          <a href="./account.php" class="nav-link"><i class="fa fa-user me-3"></i> Akun</a>
        </li>
        <li id="logoutButton">
          <a href="#" class="nav-link"><i class="fa fa-sign-out me-3"></i> Keluar</a
          >
        </li>
      </ul>
    </aside>

  <!-- Content -->
  <section class="content">
    <header>
      <div class="d-flex justify-content-end w-100 h-100 align-items-center px-4">
        <h5 id="avatar-name" class="me-4"></h5>
        <div class="avatar-small">
          <img id="avatar-img" src="/assets/images/icon/user.svg" height="40" width="40" />
        </div>
      </div>
    </header>
    <main>
<?php endif; ?>

<?php if(isset($sidebar_closer) && $sidebar_closer) : ?>
  </main>
  </section>
<?php endif; ?>
