<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferron Hoteles</title>
    <!-- Google Fonts Montserrat & Josefin Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="shortcut icon" href="./assets/images/crown.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/sidebar.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    <!-- navbar -->
    <div class="navbar">
        <input type="checkbox" class="checkbox" id="click" hidden>
        <!-- sidebar -->
        <div class="sidebar">
            <input type="checkbox" id="click">
            <label for="click">
                <dl class="menu-icon">
                    <dt class="line line-1"></dt>
                    <dt class="line line-2"></dt>
                    <dt class="line line-3"></dt>
                </dl>
            </label>
            <ul class="social-icons-list">
                <li>
                    <a href="" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a href="" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
            </ul>
            <div class="year">
                <p>&copy; 2024</p>
            </div>
        </div> <!-- end sidebar -->
        <!-- navigation -->
        <div class="navigation">
            <div class="navigation-header">
                <h1 class="navigation-heading">Ferron Hotels</h1>
                <form action="#" class="navigation-search">
                    <input type="text" class="navigation-search-input" name="#" id="#">
                    <button class="navigation-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <ul class="navigation-list">
                <li class="navigation-item">
                    <a href="index.php" class="navigation-link">home</a>
                </li>
                <li class="navigation-item">
                    <a href="#" class="navigation-link">about us</a>
                </li>
                <li class="navigation-item">
                    <a href="#" class="navigation-link">rooms</a>
                </li>
                <li class="navigation-item">
                    <a href="#" class="navigation-link">events</a>
                </li>
                <li class="navigation-item">
                    <a href="#" class="navigation-link">customers</a>
                </li>
                <li class="navigation-item">
                    <a href="#" class="navigation-link">contact</a>
                </li>
            </ul>
            <div class="copyright">
                <p>&copy; 2024 Ferron Hotels. All Rights Reserved.</p>
            </div>
        </div> <!-- end navigation -->
        <!-- ********************end navbar************************** -->
        <div class="main_container">
            <?php
            include 'frontcontroller.php';
            ?>
        </div>
        <!-- ********************end main_container************************** -->
    </div> <!-- end navbar -->
    <script src="./assets/js/main.js"></script>
</body>

</html>