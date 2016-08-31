<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

$tituloPag    = 'HOME';
$tituloHeader = 'Bem-vindo ao DFA<sub>judge</sub>';
$backPag      = null;

//$DFAjs   = true;
//$mathjax = true;

$pagAtiva = 'HOME';
include '_header.php';
?>
  <div class="bemvindo">
    <img id="LOGOhome"
         src="../logos/LOGOhome.png">
    <p>
      Primeira vez utilizado o sistema? <br>
      Visite a página de <b>AJUDA</b> no menu OPÇÕES.</p>
    <p>
      Para gerenciar suas <b>Turmas</b>, <b>Listas</b>, <b>Questões</b> e todos os <b>Usuários</b>,
      navegue pelo <b>MENU</b>. </p>
    <p>
      Para alterar sua senha de login, acesse a página <b>CONTA</b> no menu OPÇÕES. </p>
  </div>

<?php include '_footer.php'; ?>