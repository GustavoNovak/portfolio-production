<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/icone_aba.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/icone_aba.png" type="image/x-icon">
        <title>Novak Financial</title>
        <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo BASE_URL; ?>assets/css/template.css" rel="stylesheet" />
        <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet" />
        <link href="<?php echo BASE_URL; ?>assets/css/simple-sidebar.css" rel="stylesheet" />
        <link href="<?php echo BASE_URL; ?>assets/css/select2.min.css" rel="stylesheet" />
        <link href="<?php echo BASE_URL; ?>assets/css/novak.style.css" rel="stylesheet" />
    </head>
    <body>
         <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="<?php echo BASE_URL; ?>">
                            DashBoard
                        </a>
                    </li>
                    <?php if($viewData['tipo'] == 1): ?>
                    <li><!-- Link with dropdown items -->
                    <a class="menu_lateral" href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Cadastro<span class="caret"></span></a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/usuario">Usuario</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/fundo">Fundo</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/conta">Conta</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/valores_conta">Conta valores</a></li>
                    </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($viewData['tipo'] == 1): ?>
                    <li><!-- Link with dropdown items -->
                    <a class="menu_lateral" href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false">Editar cadastro<span class="caret"></span></a>
                    <ul class="collapse list-unstyled" id="homeSubmenu1">
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/editar_usuario">Usuario</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/editar_fundo">Fundo</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cadastro/editar_conta">Conta</a></li>
                    </ul>
                    </li>
                    <?php endif; ?>
                    <li><!-- Link with dropdown items -->
                    <a class="menu_lateral" href="#homeSubmenu2" data-toggle="collapse" aria-expanded="false">Lançamentos<span class="caret"></span></a>
                    <ul class="collapse list-unstyled" id="homeSubmenu2">
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>lancamento/cadastrar_pagamento">Inserir pagamento</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>lancamento/cadastrar_recebimento">Inserir recebimento</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>lancamento/gerenciar">Gerenciar</a></li>
                    </ul>
                    </li>
                    <li><!-- Link with dropdown items -->
                    <a class="menu_lateral" href="#homeSubmenu3" data-toggle="collapse" aria-expanded="false">Cenários<span class="caret"></span></a>
                    <ul class="collapse list-unstyled" id="homeSubmenu3">
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cenarios/criar">Cadastro cenário</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cenarios/excluir_conta">Excluir conta de cenário</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cenarios/cadastrar_pagamento">Inserir pagamento</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cenarios/cadastrar_recebimento">Inserir recebimento</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>cenarios/gerenciar">Gerenciar</a></li>
                    </ul>
                    </li>                   
                    <li><!-- Link with dropdown items -->
                    <a class="menu_lateral" href="#homeSubmenu4" data-toggle="collapse" aria-expanded="false">Relatórios<span class="caret"></span></a>
                    <ul class="collapse list-unstyled" id="homeSubmenu4">
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>relatorios/previsao_real">Previsão real</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>relatorios/visualizar_cenarios">Visualizar cenários</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>relatorios/cartoes_credito">Cartões de crédito</a></li>
                        <li><a class="sub_menu_lateral" href="<?php echo BASE_URL; ?>relatorios/por_conta">Por conta</a></li>
                    </ul>
                    </li>
                </ul>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-inverse" style="border-radius:0px;margin-bottom:0px">
                <div class="container-fluid">
                    <div id="navbar">
                        <ul class="nav navbar-nav navbar-left">
                            <li><a href="#menu-toggle" id="menu-toggle"><img src="<?php echo BASE_URL; ?>assets/images/icone_menu_abrir.png" width="25" /></a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <?php echo $viewData['nome']; ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo BASE_URL; ?>login/sair">Sair</a></li>
                                </ul>   
                            </li>
                        </ul>
                    </div>
                </div>
                </nav>
                <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery.min.js"></script>
                <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
                <div class="container-fluid" style="padding-left:40px;padding-right:40px;margin-bottom:50px">
                    <?php
                    $this->loadViewInTemplate($viewName,$viewData);
                    ?>
                </div>
            </div>
            <!-- /#page-content-wrapper -->
        </div>

    </body>
</html>