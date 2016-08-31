<?php
function ativo( $a ) {
  if ( isset( $GLOBALS[ 'pagAtiva' ] ) && ( $GLOBALS[ 'pagAtiva' ] == $a ) ) {
    return ' class="tabAtiva"';
  } else {
    return '';
  }
}

?>
<!doctype html>
<html lang="pt-br">
<head>
  <title>DFA judge - Professor <?= $tituloPag ?></title>
  <meta charset="utf-8" />
  <meta name="description" content="DFA judge" />
  <link rel="icon" type="image/x-icon" href="../favicon.ico" />
  <link rel="icon" type="image/png" href="../favicon_x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="../favicon_x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="../favicon_x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="../favicon_x128.png" sizes="128x128" />
  <link rel="icon" type="image/png" href="../favicon_x196.png" sizes="196x196" />
  <link rel="stylesheet" type="text/css" href="../css/HdV.css" />
  <link rel="stylesheet" type="text/css" href="../css/prof.css" />
  <?php if ( isset( $DFAjs ) && $DFAjs ) { ?>

    <script src="../js/DFAdesigner.js"></script>
  <?php }
  if ( isset( $mathjax ) && $mathjax ) { ?>

    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML-full"></script>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({
        extensions: ["TeX/cancel.js"], // this allows use of the "cancel" macro
        tex2jax: {
          inlineMath: [ ['\\(','\\)'] ],
          displayMath: [ ['\\[','\\]'] ],
          processEscapes: true         // this allows me to use a literal dollar sign, \$, outside of math mode
        }
      });
    </script>
  <?php } ?>

</head>
<body>
<div class="HdV_page">
  <div class="HdV_header_verde">
    <div id="HdV_banner">
      <a href="http://www.ufabc.edu.br/" title="UFABC - Universidade Federal do ABC" target="_blank">
        <img src="../logos/UFABC.png" id="logoUFABC" />
      </a>
      <a href="home.php" title="DFA judge - Sistema de auxílio na aprendizagem da &#10;disciplina Linguagens Formais e Autômatos">
        <img src="../logos/LOGO.png" id="logoDFAjudge" />
      </a>
      <a href="http://cmcc.ufabc.edu.br/" title="CMCC - Centro de Matemática Computação e Cognição" target="_blank">
        <img src="../logos/cmcc_BRANCO_x185.png" id="logoCMCC" />
      </a>
    </div>
  </div>
  <nav class="HdV_menu prof">
    <ul>
      <li class="home">
        <a href="home.php" tooltip="Página Inicial"<?= ativo( 'HOME' ) ?>> HOME </a>
      </li>
      <li class="turmas">
        <a href="turmas.php" tooltip="Minhas Turmas"<?= ativo( 'TURMAS' ) ?>> TURMAS </a>
      </li>
      <li class="listas">
        <a href="listas.php" tooltip="Minhas Listas"<?= ativo( 'LISTAS' ) ?>> LISTAS </a>
      </li>
      <li class="questoes">
        <a href="questoes.php" tooltip="Minhas Questões"<?= ativo( 'QUESTOES' ) ?>> QUESTÕES </a>
      </li>
      <li class="usuarios">
        <a href="usuarios.php" tooltip="Usuários do Sistema"<?= ativo( 'USUARIOS' ) ?>> USUÁRIOS </a>
      </li>
      <li class="mais" style="float: right">
        <a href="#" tooltip="Mais Opções"<?= ativo( 'OPCOES' ) ?>>Opções</a>
        <ul>
          <li class="minhaconta">
            <a href="minhaConta.php" tooltip="Configurações da &#10;Minha Conta"> CONTA </a>
          </li>
          <li class="ajuda">
            <a href="ajuda.php" tooltip="Ajuda do Sistema"> AJUDA </a>
          </li>
          <li class="sobre">
            <a href="sobre.php" tooltip="Sobre o Sistema"> SOBRE </a>
          </li>
          <li class="sair">
            <a href="sair.php" tooltip="Sair do Sistema"> SAIR </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <div class="HdV_body">
    <div id="HdV_toolbar">
      <div class="back">
        <?php
        if ( isset( $backPag ) ) {
          if ( $backPag != null ) {
            echo '<a href="' . $backPag . '" tooltip="Voltar"><img src="../img/voltar_p.png"></a>';
          }
        }
        ?>
      </div>
      <div class="titulo">
        <h1><?= $tituloHeader; ?></h1>
      </div>
      <div class="add">
        <?php if ( isset( $addPag ) && isset( $addIcon ) ) {
          if ( $addPag != null && $addIcon != null ) {
            echo '<a href="' . $addPag . '" tooltip="' . $addTitle . '"><img src="' . $addIcon . '"></a>';
          }
        } ?>
      </div>
      <div class="more">
        <?php
        if ( isset( $morePag ) && isset( $moreIcon ) ) {
          if ( $morePag != null && $moreIcon != null ) {
            echo '<a href="' . $morePag . '" tooltip="' . $moreTitle . '"><img src="' . $moreIcon . '"></a>';
          }
        }
        ?>

      </div>
    </div>
