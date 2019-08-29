<?php

class JackpotController extends ControllerBase
{
    public function indexAction()
    {
    	 $jackpotID = $this->rawQueries("SELECT jackpot_event_id, status, total_games FROM jackpot_event ORDER BY 1 DESC LIMIT 1");
        $jpStatus = $jackpotID['0']['status'];
        $total_games = $jackpotID['0']['total_games'];
        $jackpotID = $jackpotID['0']['jackpot_event_id'];

    	$games =  "select j.game_order as pos, e.sub_type_id, group_concat(concat(odd_value)) as ht_ft, m.game_id, m.match_id, m.start_time, m.parent_match_id, m.away_team, m.home_team,  j.jackpot_event_id, o.winning_outcome from jackpot_match j inner join `match` m on m.parent_match_id = j.parent_match_id inner join event_odd e on e.parent_match_id = m.parent_match_id left join outcome o on o.parent_match_id=e.parent_match_id and o.sub_type_id=37 and o.special_bet_value='2.5' and o.is_winning_outcome=1 where j.jackpot_event_id='$jackpotID'  and e.sub_type_id='37'  group by j.parent_match_id order by pos";

        $games = $this->rawQueries($games);

    	$theBetslip[] = '';

    	$betslip = $this->session->get("betslipJ");

        unset($theBetslip);     

        foreach ($betslip as $slip) {
            if ($slip['bet_type']=='jackpot') {
                $theBetslip[$slip['match_id']]=$slip;
            }
        }

        $startTime = '';

        foreach ($games as $game) {
        	if($game['pos']==1){
        		$startTime = date('d/m H:i', strtotime($game['start_time']));
        	}
        }

        $slipCountJ = sizeof($theBetslip);

    	$this->tag->setTitle('Weekly Jackpot - scorepesa.co.ke');

        $this->view->setVars(["games"=>$games,'slipCountJ'=>$slipCountJ,'theBetslip'=>$theBetslip,'men'=>'jackpot','startTime'=>$startTime,'jackpotID'=>$jackpotID, 'jpactive' => $jpStatus == 'ACTIVE' ]);

    }
    
}

?>