<?php

class MatchController extends ControllerBase
{
    public function IndexAction()
    {
        $id = $this->request->get('id', 'int');

        $style = [
            '208' => 'one-row',
            '55'  => 'one-row',
            '201' => 'one-row',
            '235' => 'one-row',
            '46'  => 'three-rows',
            '270' => 'three-rows',
            '323' => 'three-rows',
            '246' => 'one-row',
            '10'  => 'one-row',
            '390' => 'one-row',
            '41'  => 'one-row',
            '42'  => 'one-row',
        ];


        $matchInfo = $this->rawQueries("select m.home_team, m.game_id, m.away_team,m.start_time,c.competition_name,c.category,m.parent_match_id from `match` m left join competition c on m.competition_id=c.competition_id where match_id=$id  and m.start_time > now() limit 1");
        $matchInfo = array_shift($matchInfo);

        $subTypes = $this->rawQueries("select o.priority, m.match_id, e.odd_key as display, o.name, e.odd_key, e.odd_value, e.sub_type_id, e.special_bet_value from event_odd e inner join odd_type o on (o.sub_type_id = e.sub_type_id AND e.parent_match_id = o.parent_match_id) inner join `match` m on m.parent_match_id = e.parent_match_id where m.start_time > now() and match_id = '$id' and o.live_bet = 0 and o.active = 1 and e.odd_key <> '-1' and e.max_bet IN (20000,1) and CASE WHEN e.sub_type_id = 18 THEN e.odd_key REGEXP '2.5$' WHEN e.sub_type_id = 68 THEN e.odd_key REGEXP '[.]5$' WHEN e.sub_type_id = 90 THEN e.odd_key REGEXP '[.]5$' ELSE 1=1 END group by e.sub_type_id, e.odd_key, e.special_bet_value order by o.priority desc, sub_type_id, special_bet_value, FIELD(e.odd_key,m.home_team,'draw',m.away_team, concat(m.home_team, ' or ', m.away_team), concat(m.home_team, ' or draw'), concat('draw or ', m.away_team)), odd_key asc");

        $theBetslip = $this->session->get("betslip");
        $title = $matchInfo['competition_name'] ." - ". $matchInfo['home_team']." VS ".$matchInfo['away_team'];

        $this->tag->setTitle($title);

        $this->view->setVars(
            ['subTypes'=>$subTypes,
            'matchInfo'=>$matchInfo,'theBetslip'=>$theBetslip]);

    }

}

?>