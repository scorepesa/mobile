<?php

class JackpotController extends ControllerBase
{
    public function indexAction()
    {
    	 $jackpot = $this->rawQueries("SELECT jackpot_type,jackpot_event_id,total_games FROM jackpot_event WHERE status = 'ACTIVE' ORDER BY 1 DESC LIMIT 1");

        $jpStatus = $jackpot['0']['status'];
        $total_games = $jackpot['0']['total_games'];
        $jackpotID = $jackpot['0']['jackpot_event_id'];
        $jackpotType = $jackpot['0']['jackpot_type'];

    	$games =  "select j.game_order as pos, jackpot_match_id,e.sub_type_id, group_concat(concat(odd_value)) as threeway, m.game_id, m.match_id, m.start_time, m.parent_match_id, m.away_team, m.home_team, c.competition_name, c.category from jackpot_match j inner join `match` m on m.parent_match_id = j.parent_match_id INNER JOIN competition c ON m.competition_id = c.competition_id inner join event_odd e on e.parent_match_id = m.parent_match_id where j.jackpot_event_id='$jackpotID' and j.status='ACTIVE'  and e.sub_type_id=1 group by j.parent_match_id order by game_order";

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

        $this->view->setVars(["games"=>$games,'slipCountJ'=>$slipCountJ,'theBetslip'=>$theBetslip,'men'=>'jackpot','startTime'=>$startTime,'jackpotID'=>$jackpotID, 
            'jackpotType'=>$jackpotType,'jpactive' => $jpStatus == 'ACTIVE' ]);

    }
    
}

?>