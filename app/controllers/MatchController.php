<?php

class MatchController extends ControllerBase
{
    public function IndexAction()
    {
        $id = $this->request->get('id','int');

        $matchInfo = $this->rawQueries("select m.home_team, m.game_id, m.away_team,m.start_time,c.competition_name,c.category,m.parent_match_id from `match` m left join competition c on m.competition_id=c.competition_id where match_id=$id  and m.start_time > now() limit 1");
        $matchInfo = array_shift($matchInfo);
            
        $subTypes = $this->rawQueries("select o.priority, m.match_id, e.odd_key as display, o.name, e.odd_key, e.odd_value, e.sub_type_id, e.special_bet_value from event_odd e inner join odd_type o on (o.sub_type_id = e.sub_type_id AND e.parent_match_id = o.parent_match_id) inner join `match` m on m.parent_match_id = e.parent_match_id left join market_priority mp on o.sub_type_id = mp.sub_type_id where m.start_time > now() and match_id = '$id' and o.active = 1 and e.odd_key <> '-1' and e.max_bet IN (20000,1) and e.sub_type_id not in (select sub_type_id from prematch_disabled_market where status=1) and CASE WHEN e.sub_type_id = 18 THEN e.odd_key REGEXP '[.]5$' WHEN e.sub_type_id = 68 THEN e.odd_key REGEXP '[.]5$' WHEN e.sub_type_id = 90 THEN e.odd_key REGEXP '[.]5$' ELSE 1=1 END and case when e.sub_type_id =1 then e.odd_key in (m.home_team, 'draw', m.away_team) else 1=1 end group by e.sub_type_id, e.odd_key, e.special_bet_value order by mp.priority desc, sub_type_id, FIELD(e.odd_key,m.home_team,'draw',m.away_team, concat(m.home_team, ' or ', m.away_team), concat(m.home_team, ' or draw'), concat('draw or ', m.away_team)), special_bet_value asc");

        $theBetslip = $this->session->get("betslip");
        $title = $matchInfo['competition_name'] ." - ". $matchInfo['home_team']." VS ".$matchInfo['away_team'];

        $this->tag->setTitle($title);

        $this->view->setVars(["topLeagues"=>$this->topLeagues(),'subTypes'=>$subTypes,'matchInfo'=>$matchInfo,'theBetslip'=>$theBetslip]);

    }

}

?>