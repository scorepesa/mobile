<?php

use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\View;

class LiveController extends ControllerBase {

    public function indexAction() {


        $theBetslip[] = '';

        $betslip = $this->session->get("betslip");

        foreach ($betslip as $slip) {
            if ($slip['bet_type'] == 'jackpot') {
                $theBetslip[$slip['match_id']] = $slip;
            }
        }

        $slipCountJ = sizeof($theBetslip);

        $this->tag->setTitle('Live Games');

        $this->view->setVars(["games" => $games, 'slipCount' => $slipCountJ, 'theBetslip' => $theBetslip, 'jackpotSlip'=> 1]);
    }

}