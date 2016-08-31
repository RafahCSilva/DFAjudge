<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

$tituloPag    = 'Home';
$tituloHeader = 'Seja bem-vindo ao DFA<sub>judge</sub>';
$backPag      = null;
$pagAtiva     = 'HOME';
include '_header.php';
?>
<style type="text/css">
  .HdV_body{
    padding: 20px 0 100px 0;
  }
  #HdV_toolbar{
    padding-left: 20px;
  }
</style>
  <div class="bemvindo">
    <img id="LOGOhome" src="../logos/LOGOhome.png">
    <p>
      Primeira vez utilizado o sistema? <br>
      Visite a página de <b>AJUDA</b> no menu OPÇÕES. </p>
    <p>
      Para acessar sua turma, clique na aba <b>TURMAS</b> no menu. </p>
    <p>
      Para alterar sua senha de login, acesse a página <b>CONTA</b> no menu OPÇÕES. </p>
    <p>
      <b>Fique atento ao prazo para submeter suas respostas.</b> </p>
  </div>
<?php include '_footer.php'; ?>