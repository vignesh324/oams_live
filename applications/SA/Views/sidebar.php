<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?=@basePath?>SA/Dashboard" class="brand-link">
    <img src="<?=@basePath?>admin_assets/dist/img/logo.png" alt="<?=@CompanyName?>" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">OAMS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?=@basePath?>admin_assets/dist/img/user-icon.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo strtoupper(session()->get('user_name')) ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
              
        <li class="nav-item">
          <a href="<?=@basePath?>SA/DashboardSA" class="nav-link <?=@($activemenu == 'DashboardSA')?'active':''?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
            Dashboard
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=@basePath?>SA/Users" class="nav-link <?=@($activemenu == 'Users')?'active':''?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Users Management
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=@basePath?>SA/roles" class="nav-link <?=@($activemenu == 'roles')?'active':''?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Role Management
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=@basePath?>SA/Logout" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Logout
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>