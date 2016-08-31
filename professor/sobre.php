<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

$tituloPag    = 'Sobre o Sistema';
$tituloHeader = 'Sobre o Sistema';
$backPag      = 'home.php';
$pagAtiva     = 'OPCOES';
$mathjax      = true;
include '_header.php';

include '../tutorial/sobre.html';

include '_footer.php'; ?>