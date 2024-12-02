<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary ">
        <div class="container flex">
                <a href="/" class='nav-link navbar-brand' class="logo">
                    Rumah <span class="retret-text">Retret</span>
                </a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        </ul>
                        <div class="d-flex">
                            <?php if (isset($_SESSION['user_id'])) : ?>
                            <a href='/dashboard' class="btn btn-primary" >Dashboard</a>
                            <?php endif; ?>

                            <?php if (!isset($_SESSION['user_id'])) : ?>
                            <a href="/sign-up.php" class="btn btn-outline-primary me-3" type="submit"  >Daftar</a>
                            <a href='/sign-in.php' class="btn btn-primary" type="submit">Masuk</a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
        </div>
    </nav>
</header>