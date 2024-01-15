<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferron Hoteles</title>
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts Montserrat & Josefin Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="shortcut icon" href="./assets/images/crown.png" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/sidebar.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    <!-- navbar -->
    <div class="navbar">

        <input type="checkbox" class="checkbox" id="click" hidden>

        <!-- sidebar -->
        <div class="sidebar">
            <label for="click">
                <div class="menu-icon">
                    <div class="line line-1"></div>
                    <div class="line line-2"></div>
                    <div class="line line-3"></div>
                </div>
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

                <form action="" class="navigation-search">
                    <input type="text" class="navigation-search-input" name="" id="">
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
    </div> <!-- end navbar -->
    <!-- ********************end navbar************************** -->
    <div class="main_container">
        <?php
        include 'frontcontroller.php';
        ?>
    </div>
    <!-- ********************end main_container************************** -->
    <script src="./assets/js/main.js"></script>


</body>

</html>