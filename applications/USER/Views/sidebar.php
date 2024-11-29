<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= @basePath ?>USER/Dashboard" class="brand-link bg-white">
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
        <a href="#" class="d-block"><?php echo strtoupper(session()->get('user_name')) ?></a>
      </div>
    </div>

    <?php
    $permissions = session()->get('permissions');

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
        $master_array = array("Seller", "SampleQuantity", "GardenCenter", "CenterGarden", "Garden", "Area", "City", "State", "Buyer", "Grade", "Warehouse", "Center", "Category", "Hsn", "Package", "Settings");
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
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/State" class="nav-link <?= @($activemenu == 'State') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>State Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 2;
            if (in_array($module_id_to_check, $module_ids)) :
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
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Area" class="nav-link <?= @($activemenu == 'Area') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Area Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 8;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Warehouse" class="nav-link <?= @($activemenu == 'Warehouse') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Warehouse Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 7;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Center" class="nav-link <?= @($activemenu == 'Center') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Center Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 9;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Category" class="nav-link <?= @($activemenu == 'Category') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 4;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Seller" class="nav-link <?= @($activemenu == 'Seller') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Seller Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 5;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Garden" class="nav-link <?= @($activemenu == 'Garden') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Garden Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 6;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Buyer" class="nav-link <?= @($activemenu == 'Buyer') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buyer Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            $module_id_to_check = 10;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <li class="nav-item">
                <a href="<?= @basePath ?>USER/Grade" class="nav-link <?= @($activemenu == 'Grade') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Grade Master</p>
                </a>
              </li>
            <?php endif; ?>
            <?php
            //$module_id_to_check = 11;
            //if (in_array($module_id_to_check, $module_ids)) :
            ?>
            <!-- <li class="nav-item">
                <a href="<?= @basePath ?>USER/Hsn" class="nav-link <?= @($activemenu == 'Hsn') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>HSN Master</p>
                </a>
              </li> -->
            <?php //endif; 
            ?>
            <?php
            $module_id_to_check = 12;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <!-- <li class="nav-item">
                <a href="<?= @basePath ?>USER/Package" class="nav-link <?= @($activemenu == 'Package') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Package Master</p>
                </a>
              </li> -->
            <?php endif; ?>
            <!-- <li class="nav-item">
              <a href="<?= @basePath ?>USER/SampleQuantity" class="nav-link <?= @($activemenu == 'SampleQuantity') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Sample Quantity Master</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/Settings" class="nav-link <?= @($activemenu == 'Settings') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>

                <p class="ml-2">
                  Settings
                  <span class="right badge badge-danger"></span>
                </p>
              </a>
            </li>

            <?php
            $module_id_to_check = 13;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <!-- <li class="nav-item">
                <a href="<?= @basePath ?>USER/CenterGarden" class="nav-link <?= @($activemenu == 'CenterGarden') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Center Garden Master</p>
                </a>
              </li> -->
            <?php endif; ?>
            <?php
            $module_id_to_check = 14;
            if (in_array($module_id_to_check, $module_ids)) :
            ?>
              <!-- <li class="nav-item">
                <a href="<?= @basePath ?>USER/GardenCenter" class="nav-link <?= @($activemenu == 'GardenCenter') ? 'active' : '' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Garden Grade Master</p>
                </a>
              </li> -->
            <?php endif; ?>
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

            <!-- <li class="nav-item">
              <a href="<?= @basePath ?>USER/WarehouseArchiveStock" class="nav-link <?= @($activemenu == 'WarehouseArchiveStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Warehouse Archive Stock</p>
              </a>
            </li> -->
          </ul>
        </li>

        <?php
        $inward_array = array("AuctionManagement", "AuctionStock", "BiddingSession", "SampleReceipt", "completedAuctions");
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
                <p>Auction Creation</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionStock" class="nav-link <?= @($activemenu == 'AuctionStock') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Auction Stock</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="<?= @basePath ?>USER/SampleReceipt" class="nav-link <?= @($activemenu == 'SampleReceipt') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Samples Receipt Management</p>
              </a>
            </li> -->
            <!-- <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionManagement" class="nav-link <?= @($activemenu == 'AuctionManagement') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Live Biddings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/completedAuctions" class="nav-link <?= @($activemenu == 'completedAuctions') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Completed Biddings</p>
              </a>
            </li> -->

          </ul>
        </li>


        <?php
        $inward_array = array("DeliveryManagement", "SoldStock", "Invoice", "AuctionToBuyerInvoice", "AuctionToSellerInvoice");
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
                <p>Seller To Buyer Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionToBuyerInvoice" class="nav-link <?= @($activemenu == 'AuctionToBuyerInvoice') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Auctioner To Buyer Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AuctionToSellerInvoice" class="nav-link <?= @($activemenu == 'AuctionToSellerInvoice') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Auctioner To Seller Invoice</p>
              </a>
            </li>
          </ul>
        </li>


        <li class="nav-item">
          <a href="<?= @basePath ?>USER/ProductLog" class="nav-link <?= @($activemenu == 'Log') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Product Log
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= @basePath ?>USER/ActivityLog" class="nav-link <?= @($activemenu == 'ActivityLog') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Activity Log
            </p>
          </a>
        </li>

        <?php
        $report_array = array(
          "InwardReport", "SellerReport",
          "ManualBidReport", "AutoBidReport", "BuyerPurchaseReport",
          "GardenBuyerPurchaseReport", "StateCityBuyerPurchaseReport", "BuyerSellerGardenSoldReport",
          "GardenGradeAvgPriceSaleReport", "SellerSoldStockReport", "BuyerGardenSoldStockReport",
          "GardenSoldStockReport", "SellerGardenGradeAvgPriceReport", "PriceRangeWiseReport",
          "GardenCompareSoldStockReport"
        );
        ?>
        <li class="nav-item <?= @(in_array($activemenu, $report_array)) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= @(in_array($activemenu, $report_array)) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

          <!-- <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/InwardReport" class="nav-link <?= @($activemenu == 'InwardReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Inward Report</p>
              </a>
            </li>
          </ul> -->

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/SellerReport" class="nav-link <?= @($activemenu == 'SellerReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Seller/Garden Report</p>
              </a>
            </li>
          </ul>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/BuyerPurchaseReport" class="nav-link <?= @($activemenu == 'BuyerPurchaseReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Buyer Purchase Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/GardenBuyerPurchaseReport" class="nav-link <?= @($activemenu == 'GardenBuyerPurchaseReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Garden Buyer Purchase Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/StateCityBuyerPurchaseReport" class="nav-link <?= @($activemenu == 'StateCityBuyerPurchaseReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>State City Buyer Purchase Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/BuyerSellerGardenSoldReport" class="nav-link <?= @($activemenu == 'BuyerSellerGardenSoldReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Buyer/Seller/Garden Sold Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/GardenGradeAvgPriceSaleReport" class="nav-link <?= @($activemenu == 'GardenGradeAvgPriceSaleReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Garden/Grade Avgerage Price Sale Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/SellerSoldStockReport" class="nav-link <?= @($activemenu == 'SellerSoldStockReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Seller Sold Stock Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/BuyerGardenSoldStockReport" class="nav-link <?= @($activemenu == 'BuyerGardenSoldStockReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Buyer/Garden Sold Stock Report</p>
              </a>
            </li>
          </ul>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/GardenCompareSoldStockReport" class="nav-link <?= @($activemenu == 'GardenCompareSoldStockReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Garden Compare Sold Stock Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/SellerGardenGradeAvgPriceReport" class="nav-link <?= @($activemenu == 'SellerGardenGradeAvgPriceReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Seller Garden Grade Avgerage Price Report</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/PriceRangeWiseReport" class="nav-link <?= @($activemenu == 'PriceRangeWiseReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Price Range Report</p>
              </a>
            </li>
          </ul>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/ManualBidReport" class="nav-link <?= @($activemenu == 'ManualBidReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>ManualBid Report</p>
              </a>
            </li>
          </ul>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= @basePath ?>USER/AutoBidReport" class="nav-link <?= @($activemenu == 'AutoBidReport') ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>AutoBid Report</p>
              </a>
            </li>
          </ul>

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