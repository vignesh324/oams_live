<script>
  let BASE_URL = "<?= @basePath ?>";
</script>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
  <div class="container">
    <a href="#" class="navbar-brand">
      <img src="<?= @basePath ?>admin_assets/dist/img/logo.png" alt="<?= @CompanyName ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
    </a>
    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse order-3" id="navbarCollapse">

      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="<?= @basePath ?>BUYER/Dashboard" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="<?= @basePath ?>BUYER/upcoming-auctions" class="nav-link">Upcoming Auctions</a>
        </li>
        <li class="nav-item">
          <a href="<?= @basePath ?>BUYER/completed-auctions" class="nav-link">Completed Auctions</a>
        </li>

      </ul>


    </div>

    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a href="#" data-toggle="dropdown" href="#" style="text-decoration: none;" aria-expanded="false">
          <img src="<?= @basePath ?>admin_assets/dist/img/user-icon.jpg" style="width: 40px !important;" class="img-circle elevation-2 mx-2" alt="User Image">
          <b><?php echo ucfirst(session()->get('buyer_name')) ?></b>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="nav-link" href="<?= @basePath ?>BUYER/Profile" role="button">
            <i class="fas fa-user-cog"></i> Profile
          </a>
          <a class="nav-link" href="<?= @basePath ?>BUYER/Logout" role="button">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>

  </div>
</nav>