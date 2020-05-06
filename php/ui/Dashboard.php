<?php


class Dashboard {

    function getNavigation($id) {

        $string[] = array();
        $string[0] = "";
        $string[1] = "";
        $string[2] = "";
        $string[3] = "";
        $string[4] = "";
        $string[5] = "";
        $string[6] = "";
        $string[7] = "";
        $string[8] = "";
        $string[9] = "";
        $string[$id] = "active";

        $hide = "";

        /*
        if($UserType == "EMPLOYEE") {
            $hide = "d-none";
        } else {
            $hide = "";
        }

        */

        echo "<nav class='navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white' id='sidenav-main'>
    <div class='container-fluid'>
        <!-- Toggler -->
        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#sidenav-collapse-main' aria-controls='sidenav-main' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
        <!-- Brand -->
        <a class='navbar-brand pt-2 mb-lg--4 ' style='font-family: 'Roboto', sans-serif;'>
            <img src='../assets/images/logo.png' class='img-center text-primary' alt='whizQbank logo'/>
        </a>
        <!-- User -->
        <ul class='nav align-items-center d-md-none'>
            <li class='nav-item dropdown'>
                <a class='nav-link' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fa fa-user fa-lg text-dark'></i>
                </a>
                <div class='dropdown-menu dropdown-menu-arrow dropdown-menu-right'>
                    <div class='dropdown-header'>
                        <h6 class='text-overflow m-0'>Welcome!</h6>
                    </div>
                    <div class='dropdown-divider'></div>
                    <a href='logout.php' class='dropdown-item'>
                        <i class='ni ni-user-run'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class='collapse navbar-collapse ' id='sidenav-collapse-main'>
            <!-- Collapse header -->
            <div class='navbar-collapse-header d-md-none'>
                <div class='row'>
                    <div class='col-6 collapse-brand'>
                        <a>
                            <h2 class='text-primary'>" . SITE_NAME . "</h2>
                        </a>
                    </div>
                    <div class='col-6 collapse-close'>
                        <button type='button' class='navbar-toggler' data-toggle='collapse' data-target='#sidenav-collapse-main' aria-controls='sidenav-main' aria-expanded='false' aria-label='Toggle sidenav'>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <hr class='my-3 mt-lg--2 mt-md--4 d-none d-md-block'/>
            <h6 class='navbar-heading text-muted'>Dashboard</h6>
            <!-- Navigation -->
            <ul class='navbar-nav'>
                <li class='nav-item'>
                    <a class='nav-link " . $string[0] . "' href=' ./dashboard.php'> <i class='ni ni-tv-2 text-indigo'></i> Dashboard
                    </a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link " . $string[1] . "' href=' ./points.php'> <i class='fas fa-medal text-orange'></i> Points
                    </a>
                </li>
            </ul>
            <hr class='my-3'>
            <h6 class='navbar-heading text-muted'>Student</h6>
            <ul class='navbar-nav'>    
                <li class='nav-item'>
                    <a class='nav-link " . $string[2] . "' href='./bank.php'>
                        <i class='fas fa-university text-blue'></i> Question Bank
                    </a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link " . $string[3] . "' href='./categories.php'>
                        <i class='ni ni-bullet-list-67 text-red'></i> Points
                    </a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link " . $string[4] . "' href='./products.php'>
                        <i class='fa fa-archive text-orange'></i> Products
                    </a>
                </li>
                <li class='nav-item $hide'>
                    <a class='nav-link " . $string[5] . "' href='./stores.php'>
                        <i class='fa fa-store text-purple'></i> Stores
                    </a>
                </li>
                <li class='nav-item $hide'>
                    <a class='nav-link " . $string[6] . "' href='./orders.php'>
                        <i class='fa fa-shopping-cart text-info'></i> Orders
                    </a>
                </li>
                <li class='nav-item $hide'>
                    <a class='nav-link " . $string[7] . "' href='./users.php'>
                        <i class='ni ni-single-02 text-green'></i> Users
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class='my-3 $hide'>
            <!-- Navigation -->
            <ul class='navbar-nav mb-md-3'>
                <li class='nav-item $hide'>
                    <div class='row m-0 p-0'>
                        <div class='col-1'></div>
                        <div class='col-10'>
                            <input type='checkbox' checked data-toggle='toggle' data-onstyle='primary' data-size='normal' data-on='Light Mode' data-off='Dark Mode'/>
                        </div>
                        <div class='col-1'></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>";
    }

    function getHeader() {

        $Whiz = new Aider();
        $responseUserModal = $Whiz->getUserModal()->getName();

        if($responseUserModal['error']) {
            $name = "";
        } else {
            $name = $responseUserModal['data'];
        }

        echo "<!-- Navbar -->
    <nav class='navbar navbar-top navbar-expand-md navbar-dark' id='navbar-main'>
        <div class='container-fluid'>
            <!-- Brand -->
            <a class='h4 mb-0 text-white d-none d-lg-inline-block' href='./dashboard.php'>" . SITE_NAME . "</a>

            <!-- User -->
            <ul class='navbar-nav align-items-center d-none d-md-flex'>
                <li class='nav-item dropdown'>
                    <a class='nav-link pr-0' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <div class='media align-items-center'>
                <span class='avatar avatar-sm rounded-circle bg-transparent'>
                    <i class='fa fa-user-circle fa-2x text-white'></i>
                </span>
                            <div class='media-body ml-2 d-none d-lg-block'>
                                <span class='mb-0 text-sm  font-weight-bold'>" . $name . "</span>
                            </div>
                        </div>
                    </a>
                    <div class='dropdown-menu dropdown-menu-arrow dropdown-menu-right'>
                        <div class=' dropdown-header noti-title'>
                            <h6 class='text-overflow m-0'>Welcome!</h6>
                        </div>
                        <div class='dropdown-divider'></div>
                        <a href='logout.php' class='dropdown-item'>
                            <i class='ni ni-user-run'></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>";
    }

    function checkSessionLogic() {
        if(!isset($_SESSION['User_Email'])) {
            header('Location: ../index.php');
        }
    }

}