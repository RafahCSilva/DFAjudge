<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

$tituloPag    = 'Sobre o Sistema';
$tituloHeader = 'Sobre o Sistema';
$pagAtiva     = 'OPCOES';
$backPag      = 'home.php';
$mathjax      = true;
include '_header.php';

include '../tutorial/sobre.html';

include '_footer.php'; ?>