<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Land-Solution</h4>
                    </div>
                    <ul class="nav flex-column">
                        <?php 
                            if(F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00005'))
                            {
                        ?>
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'Y_dashboardController.php' ? 'active' : '' ?>" href="<?= contructUrl('Y_dashboard' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                       <?php }?>
                        <?php if(F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00002') || F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00003') || F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00004'))
                        {
                        ?> 
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'H_prospectController.php' ? 'active' : '' ?>" href="<?= contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-search me-2"></i>
                                Prospection
                            </a>
                        </li>
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'Y_acheteurController.php' ? 'active' : '' ?>" href="<?= contructUrl('Y_acheteur' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-people me-2"></i>
                                Acheteurs
                            </a>
                        </li>
                        <?php }?>

                         <?php if(F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00001'))
                        {
                        ?> 
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'H_employesController.php' ? 'active' : '' ?>" href="<?= contructUrl('H_employes' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-people me-2"></i>
                                Gestion Employés
                            </a>
                        </li>
                        <?php }?>

                        <?php if(F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00011')|| F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00012')){ ?>
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'Y_dossierController.php' ? 'active' : '' ?>" href="<?= contructUrl('Y_dossier' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-folder me-2"></i>
                                Dossiers
                            </a>
                        </li>
                        <?php }?> 
                        <?php if(F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00006') || F_gestionPrivilege($_SESSION['H_idEmploye'], 'PRI00007'))
                            {
                        ?>
                        <li class="nav-item">
                            <a <?php if(isset($_SESSION['H_idEmploye']) && ($_SESSION['H_employeConnecte']==='connected'))?> class="nav-link <?= $currentPage === 'Y_siteController.php' ? 'active' : '' ?>" href="<?= contructUrl('Y_site' , ['H_idEmploye'=>$_SESSION['H_idEmploye']])?>">
                                <i class="bi bi-geo-alt me-2"></i>
                                Sites & Blocs
                            </a>
                        </li>
                        <?php }?>
                    </ul>
                </div>
            </nav>