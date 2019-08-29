<?php

/**
 * Created by PhpStorm.
 * User: mxgel
 * Date: 03/02/2018
 * Time: 15:32
 */
class CompetitionController extends ControllerBase
{

    /**
     *
     */
    public function indexAction()
    {
       $id = $this->request->get('id', 'int');

       $sCompetitions = $this->getCompetitions();

       $matches = $this->rawQueries("SELECT c.priority, (SELECT count(DISTINCT e.sub_type_id) FROM event_odd e INNER JOIN odd_type o ON (o.sub_type_id = e.sub_type_id AND o.parent_match_id = e.parent_match_id) WHERE e.parent_match_id = m.parent_match_id AND o.active = 1) AS side_bets, o.sub_type_id, MAX(CASE WHEN o.odd_key = m.home_team THEN odd_value END) AS home_odd, MAX(CASE WHEN o.odd_key = 'draw' THEN odd_value END) AS neutral_odd,      MAX(CASE WHEN o.odd_key = m.away_team THEN odd_value END) AS away_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or ', m.away_team)  THEN odd_value END) AS double_chance_12_odd, MAX(CASE WHEN o.odd_key = concat('draw or ', m.away_team)  THEN odd_value END) AS double_chance_x2_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or draw')  THEN odd_value END) AS double_chance_1x_odd, MAX(CASE WHEN o.odd_key = 'over 2.5' and o.sub_type_id=18  THEN odd_value END) AS over_25_odd, MAX(CASE WHEN o.odd_key = 'under 2.5' and o.sub_type_id=18  THEN odd_value END) AS under_25_odd,m.game_id, m.match_id, m.start_time, m.away_team, m.home_team, m.parent_match_id,c.competition_name,c.category FROM `match` m INNER JOIN event_odd o ON m.parent_match_id = o.parent_match_id INNER JOIN competition c ON c.competition_id = m.competition_id INNER JOIN sport s ON s.sport_id = c.sport_id WHERE c.competition_id=? AND m.start_time > now() AND o.sub_type_id in (1,18, 10) AND m.status <> 3 GROUP BY m.parent_match_id ORDER BY m.priority DESC, c.priority DESC , m.start_time LIMIT 60", [
            $id,
        ]);


        $theCompetition = $this->rawQueries("select competition_name,competition_id,category from competition where competition_id='$id' limit 1");

        $theBetslip = $this->session->get("betslip");

        $title = $theCompetition['0']['competition_name'] . ", " . $theCompetition['0']['category'];

        $this->tag->setTitle($title);

        $this->view->setVars([
            'matches'        => $matches,
            'sCompetitions'  => $sCompetitions,
            'theCompetition' => $theCompetition,
            'theBetslip'     => $theBetslip,
            'title'          => $title,
        ]);

        $this->view->pick("sports/threeway");
    }
}