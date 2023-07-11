<?php
include("conexao.php"); 
?>

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
                <a href="ver-produtos.php" class="sidebar-link">
                    <i class="bi bi-eye"></i>
                    <span class="sidebar-text">Ver Produtos</span>
                </a>
            </li>


            <li>
                <a href="produtos.php" class="sidebar-link">
                    <i class="bi bi-box-seam"></i>
                    <span class="sidebar-text">Produtos</span>
                </a>
            </li>
<!-- 
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-archive"></i>
                    <span class="sidebar-text">Fornecedores</span>
                </a>
            </li> -->
            
            <li>
                <a href="venda-produto.php" class="sidebar-link">
                    <i class="bi bi-cart"></i>
                    <span class="sidebar-text">Order</span>
                </a>
            </li>
            
            <li>
                <a href="meu-perfil.php" class="sidebar-link">
                    <i class="bi bi-person-circle"></i>
                    <span class="sidebar-text">My profile</span>
                </a>
            </li>

            <li>
                <a href="setting-users.php" class="sidebar-link">
                    <i class="bi bi-person-gear"></i>
                    <span class="sidebar-text">Setting Users</span>
                </a>
            </li>
            
            
        </ul>
    </nav>

    <div class="setting-user">
        <?php
            // Consulta para obter as informações do usuário
            $userId = $_SESSION['codusuario']; // Supondo que você tenha a ID do usuário armazenada na sessão
            $sql = "SELECT u.nome_usuario, c.nomecargo, u.foto_perfil FROM usuario u
                    INNER JOIN cargo c ON u.codcargo = c.codcargo
                    WHERE u.codusuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($nomeUsuario, $nomeCargo, $fotoPerfil);
            $stmt->fetch();
            $stmt->close();
        ?>


        <div class="user-profile">
            <img src="<?php echo $fotoPerfil; ?>" alt="" class="image-user">
        </div>
        <div class="user-details">
            <?php
            // Separa o nome completo em partes
            $partesNome = explode(" ", $nomeUsuario);
            
            // Obtém o primeiro nome
            $primeiroNome = $partesNome[0];
            
            // Obtém o último nome
            $ultimoNome = end($partesNome);
            
            // Formata o último nome com apenas a primeira letra
            $ultimoNomeFormatado = substr($ultimoNome, 0, 1) . ".";
            
            // Imprime o primeiro nome e o último nome formatado
            echo '<p class="name-user">' . $primeiroNome . ' ' . $ultimoNomeFormatado . '</p>';
            ?>
            <p class="profissao-user"><?php echo $nomeCargo; ?></p>
        </div>
        <form action="logout" method="post">
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