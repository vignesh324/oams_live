<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= @basePath ?>USER/Dashboard" class="brand-link">
    <img src="<?= @basePath ?>admin_assets/dist/img/logo.png" alt="<?= @CompanyName ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">OAMS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= @basePath ?>admin_assets/dist/img/user-icon.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Sikkandar</a>
      </div>
    </div>

<?php
$permissions =session()->get('permissions');

$module_ids = array_column_recursive($permissions, 'module_id');
?>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->

        <li class="nav-item">
          <a href="<?= @basePath ?>USER/Dashboard" class="nav-link <?= @($activemenu == 'Dashboard') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
        <?php
        $master_array = array("Seller", "GardenCenter","CenterGarden", "Garden", "Area", "City", "State", "Buyer", "Grade", "Warehouse", "Center", "Category", "Hsn", "Package");
        ?>
        <li class="nav-item <?= @(in_array($activemenu, $master_array)) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= @(in_array($activemenu, $master_array)) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Masters
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
<?php
$module_id_to_check = 1;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/State" class="nav-link <?= @($activemenu == 'State') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>State Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 2;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/City" class="nav-link <?= @($activemenu == 'City') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>City Master</p>
              </a>
            </li>
            <?php endif ?>
            <?php
$module_id_to_check = 3;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Area" class="nav-link <?= @($activemenu == 'Area') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Area Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 4;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Seller" class="nav-link <?= @($activemenu == 'Seller') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Seller Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 5;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Garden" class="nav-link <?= @($activemenu == 'Garden') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Garden Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 6;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Buyer" class="nav-link <?= @($activemenu == 'Buyer') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Buyer Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 7;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Center" class="nav-link <?= @($activemenu == 'Center') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Center Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 8;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Warehouse" class="nav-link <?= @($activemenu == 'Warehouse') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Warehouse Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 9;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Category" class="nav-link <?= @($activemenu == 'Category') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Category Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 10;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Grade" class="nav-link <?= @($activemenu == 'Grade') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Grade Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 11;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Hsn" class="nav-link <?= @($activemenu == 'Hsn') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Hsn Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 12;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Package" class="nav-link <?= @($activemenu == 'Package') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Package Master</p>
              </a>
            </li>
            <?php endif;?>
            <?php
$module_id_to_check = 13;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/CenterGarden" class="nav-link <?= @($activemenu == 'CenterGarden') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Center Garden Master</p>
              </a>
            </li>
            <?php endif; ?>
            <?php
$module_id_to_check = 14;
if (in_array($module_id_to_check, $module_ids)):
  ?>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/GardenCenter" class="nav-link <?= @($activemenu == 'GardenCenter') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Garden Grade Master</p>
              </a>
            </li>
            <?php endif;?>
          </ul>
        </li>

        <?php
        $inward_array = array("Inward", "InwardReturn", "WarehouseStock", "WarehouseArchiveStock");
        ?>
        <li class="nav-item <?= @(in_array($activemenu, $inward_array)) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= @(in_array($activemenu, $inward_array)) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-warehouse"></i>
            

            <p>
              Warehouse Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Inward" class="nav-link <?= @($activemenu == 'Inward') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Inward</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/InwardReturn" class="nav-link <?= @($activemenu == 'InwardReturn') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>InwardReturn</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/WarehouseStock" class="nav-link <?= @($activemenu == 'WarehouseStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Warehouse Stock</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= @basePath ?>USER/WarehouseArchiveStock" class="nav-link <?= @($activemenu == 'WarehouseArchiveStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Warehouse Archive Stock</p>
              </a>
            </li>
          </ul>
        </li>

        <?php
        $inward_array = array("AuctionManagement", "AuctionStock", "BiddingSession", "SalesManagement");
        ?>

        <li class="nav-item <?= @(in_array($activemenu, $inward_array)) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= @(in_array($activemenu, $inward_array)) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-gavel"></i>
            <p>
              Auction
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/BiddingSession" class="nav-link <?= @($activemenu == 'BiddingSession') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Bidding Session</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionStock" class="nav-link <?= @($activemenu == 'AuctionStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Auction Stock</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/SalesManagement" class="nav-link <?= @($activemenu == 'SalesManagement') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Samples Receipt Management</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionManagement" class="nav-link <?= @($activemenu == 'AuctionManagement') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Auction Management</p>
              </a>
            </li>

          </ul>
        </li>


        <?php
        $inward_array = array("DeliveryManagement", "SoldStock", "Invoice");
        ?>
        <li class="nav-item <?= @(in_array($activemenu, $inward_array)) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= @(in_array($activemenu, $inward_array)) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-receipt"></i>
            
            <p>
              Post Sales
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/SoldStock" class="nav-link <?= @($activemenu == 'SoldStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Sold Stock</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/DeliveryManagement" class="nav-link <?= @($activemenu == 'DeliveryManagement') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Delivery Management</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Invoice" class="nav-link <?= @($activemenu == 'Invoice') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Invoice</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="<?= @basePath ?>USER/Settings" class="nav-link <?= @($activemenu == 'Roll') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-cog"></i>
             
            <p class="ml-2">
               Settings
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= @basePath ?>USER/Logout" class="nav-link <?= @($activemenu == 'Roll') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
                Logout
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
            Training
              <span class="right badge badge-danger"></span>
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-header">Reports</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Calendar
              <span class="badge badge-info right">2</span>
            </p>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>