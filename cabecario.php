<link rel="stylesheet" href="assets/css/cabecario.css">
<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<header class="sidebar sidebar-closed">
    <nav>
        <div class="logo">
            <h5 class="logo-text">
                <i class="bi bi-code"></i>
                <span class="company-name">Somos Dev's</span>
            </h5>
            <i class="bi bi-list"></i>
        </div>

        <ul id="sidebar-menu">

            <li>
                <a href="home.php" class="sidebar-link">
                    <i class="bi bi-house"></i>
                    <span class="sidebar-text">Home</span>
                </a>
            </li>

            <li>
                <a href="dashboard.php" class="sidebar-link">
                    <i class="bi bi-bar-chart"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="meu-perfil.php" class="sidebar-link">
                    <i class="bi bi-person-circle"></i>
                    <span class="sidebar-text">My profile</span>
                </a>
            </li>

            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-pie-chart"></i>
                    <span class="sidebar-text">Analytics</span>
                </a>
            </li>

            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-archive"></i>
                    <span class="sidebar-text">Files</span>
                </a>
            </li>

            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-cart"></i>
                    <span class="sidebar-text">Order</span>
                </a>
            </li>

            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-gear"></i>
                    <span class="sidebar-text">Setting</span>
                </a>
            </li>

            
            
        </ul>
    </nav>

    <div class="setting-user">
        <div class="user-profile">
            <img src="assets/img/foto-my.jpg" alt="" class="image-user">
        </div>
        <div class="user-details">
            <p class="name-user">Gabriel Pereira</p>
            <p class="profissao-user">Full Staker</p>
        </div>

        <form action="#" method="post">
            <button type="submit" id="logout"><i class="bi bi-box-arrow-right"></i></button>
        </form>
        
    </div>
</header>

<script>
    // Obtém a lista de elementos li dentro da ul
    const menuItems = document.querySelectorAll('#sidebar-menu li');

    // Obtém o URL atual da página
    const currentURL = window.location.href;

    // Itera sobre os elementos li e adiciona a classe 'current-page' ao link correspondente
    menuItems.forEach((menuItem) => {
        const link = menuItem.querySelector('a');

        if (link.href === currentURL) {
            menuItem.classList.add('current-page');
        }
    });
</script>