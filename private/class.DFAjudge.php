<?php

class DFA {
  public $DFAespec = "DFA = { |Q|, &Sigma;, &delta;, q0, F }";
  public $numQ     = 0;
  public $alfab    = Array();
  public $Delta    = Array();
  public $g_q0     = -1;
  public $r_q0     = -1;
  public $F        = Array();

  public function DFA( $alfabeto, $Gaba, $Resp ) {
    $this->alfab = explode( ' ', $alfabeto );
    $Gaba        = explode( ' ', $Gaba );
    $Resp        = explode( ' ', $Resp );

    $q0 = Array();

    /// parse da Resposta
    foreach ( Array( $Gaba, $Resp ) as $ent ) {
      $p      = 0;
      $q      = $ent[ $p++ ];
      $qshift = $this->numQ;
      $this->numQ += $q;
      for ( $i = 1; $i <= $q; $i++ ) {
        $a = Array();
        for ( $j = 1; $j <= count( $this->alfab ); $j++ ) {
          $a[] = $ent[ $p++ ] + $qshift;
        }
        $this->Delta[] = $a;
        $this->F[]     = 0;
      }
      $q0[] = $ent[ $p++ ] + $qshift;
      $numF = $ent[ $p++ ];
      for ( $i = 1; $i <= $numF; $i++ ) {
        $this->F[ $ent[ $p++ ] + $qshift ] = 1;
      }
    }
    $this->g_q0 = $q0[ 0 ];
    $this->r_q0 = $q0[ 1 ];
  }

}

class UnionFind {
  private $N    = 0;
  private $elem = Array();

  public function init( $num ) {
    $this->N = $num;
    for ( $i = 0; $i < $num; $i++ )
      $this->elem[ $i ] = $i;
  }

  public function getElem() {
    return $this->elem;
  }

  public function merger( $A, $B ) {
    $raizA = $this->find( $A );
    $raizB = $this->find( $B );

    $this->elem[ $raizA ] = $raizB;
  }

  public function find( $el ) {
    if ( $this->elem[ $el ] == $el ) {
      return $el;
    }
    $this->elem[ $el ] = $this->find( $this->elem[ $el ] );

    return $this->elem[ $el ];
  }
}

class HKE {

  private $pHist = Array();

  public function corrigir( \DFA $dfa ) {
    $result = Array( 'resultado' => 0,
                     'palavra'   => '' );

    try {
      if ( $this->Executar( $dfa ) ) {
        $result[ 'resultado' ] = 1;
      } else {
        $result[ 'resultado' ] = 2;
        $result[ 'palavra' ]   = $this->palavraDistinguida( $dfa->alfab );
      }

      return $result;
    } catch ( Exception $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  private function Executar( \DFA $dfa ) {

    $p0 = $dfa->g_q0; // q0 do gaba
    $q0 = $dfa->r_q0; // q0 do resp

    $baldes = new UnionFind();
    $baldes->init( $dfa->numQ );

    $baldes->merger( $p0, $q0 );

    $pilha   = Array();
    $pilha[] = $this->par( $p0, $q0 );

    $this->pHist[] = $this->parH( -1, -1, $p0, $q0, -1 );
    if ( $dfa->F[ $p0 ] != $dfa->F[ $q0 ] ) {
      $this->pHist[] = $this->parH( -2, -2, -2, -2, -2 );

      return false;
    }

    while ( count( $pilha ) > 0 ) {
      $par = array_pop( $pilha );
      $q1  = $par[ 'p' ];
      $q2  = $par[ 'q' ];

      foreach ( $dfa->alfab as $i => $simbolo ) {
        $r1 = $baldes->find( $dfa->Delta[ $q1 ][ $i ] );
        $r2 = $baldes->find( $dfa->Delta[ $q2 ][ $i ] );

        $this->pHist[] = $this->parH( $q1, $q2, $r1, $r2, $i );

        if ( $r1 != $r2 ) {
          if ( $dfa->F[ $r1 ] != $dfa->F[ $r2 ] ) {
            $this->pHist[] = $this->parH( -2, -2, -2, -2, -2 );

            return false;
          }
          $baldes->merger( $r1, $r2 );
          $pilha[] = $this->par( $r1, $r2 );
        }
      }
    }

    return true;
  }

  private function par( $a, $b ) {
    return Array( 'p' => $a,
                  'q' => $b );
  }

  private function parH( $p1, $p2, $f1, $f2, $alf ) {
    return Array( 'p1'  => $p1,
                  'p2'  => $p2,
                  'f1'  => $f1,
                  'f2'  => $f2,
                  'alf' => $alf );
  }

  private function palavraDistinguida( $alf ) {
    $palavra = '';
    // pHist = [ (p1 p2 f1 f2 alf)... ]

    array_pop( $this->pHist ); // .top().pop() pq ta com a chave -2 no topo

    $h  = array_pop( $this->pHist );
    while ( count( $this->pHist ) ) {
      $p1 = $h[ 'p1' ];
      $p2 = $h[ 'p2' ];
      if ( $p1 == -1 && $p2 == -1 ) { // cheguei no p0 e q0
        break;
      }
      $palavra = $alf[ $h[ 'alf' ] ] . $palavra;
      do {
        $h = array_pop( $this->pHist );
      } while ( !( $h[ 'f1' ] == $p1 && $h[ 'f2' ] == $p2 ) );
    }
    if ( strlen( $palavra ) == 0 ) {
      $palavra = '&epsilon;';
    }

    return $palavra;
  }

}
