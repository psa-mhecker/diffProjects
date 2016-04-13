<?php
namespace Citroen\Perso\Score;

use Symfony\Component\EventDispatcher\Event;

class ScoreEvent extends Event{
    const SAVE = 'score.save';
    
    public $productUser;
    
    public function __construct($productUser=null){
        $this->productUser=$productUser;
    }
    
}
