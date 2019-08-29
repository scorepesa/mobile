<?php

/**
 * Class SportsController
 */
class SportsController extends ControllerBase
{
    /**
     *
     */
    public function IndexAction()
    {
        $sportType = $this->view->sportType;
        $this->view->setVars([
            'sports'    => $this->rawQueries("select ux.ux_id, ux.sport_name, ux.category as category, ux.competition_name as competition_name, ux.sport_id, ux.category_id, ux.competition_id, country_code, (select count(*) from `match` m where competition_id = ux.competition_id and m.bet_closure > now() and m.status=1)as games, (select count(*) from `match` mm where competition_id in (select competition_id from competition cc where category_id=ux.category_id and mm.bet_closure > now() and mm.status=1 ))as cat_games from ux_categories ux inner join category c on c.category_id = ux.category_id having games > 0 order by ux.sport_name asc, ux.category asc"),
            'men'       => 'sports',
            'sportType' => $sportType,
        ]);
    }

    /**
     *
     */
    public function threewayAction()
    {
        $id = $this->request->get('id', 'int');

        $matches = $this->rawSelect("SELECT c.priority, (SELECT count(DISTINCT e.sub_type_id) FROM event_odd e INNER JOIN odd_type o ON (o.sub_type_id = e.sub_type_id AND o.parent_match_id = e.parent_match_id) WHERE e.parent_match_id = m.parent_match_id AND o.active = 1) AS side_bets, o.sub_type_id, MAX(CASE WHEN o.odd_key = m.home_team THEN odd_value END) AS home_odd, MAX(CASE WHEN o.odd_key = 'draw' THEN odd_value END) AS neutral_odd,      MAX(CASE WHEN o.odd_key = m.away_team THEN odd_value END) AS away_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or ', m.away_team)  THEN odd_value END) AS double_chance_12_odd, MAX(CASE WHEN o.odd_key = concat('draw or ', m.away_team)  THEN odd_value END) AS double_chance_x2_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or draw')  THEN odd_value END) AS double_chance_1x_odd, MAX(CASE WHEN o.odd_key = 'over 2.5' and o.sub_type_id=18  THEN odd_value END) AS over_25_odd, MAX(CASE WHEN o.odd_key = 'under 2.5' and o.sub_type_id=18  THEN odd_value END) AS under_25_odd,m.game_id, m.match_id, m.start_time, m.away_team, m.home_team, m.parent_match_id,c.competition_name,c.category FROM `match` m INNER JOIN event_odd o ON m.parent_match_id = o.parent_match_id INNER JOIN competition c ON c.competition_id = m.competition_id INNER JOIN sport s ON s.sport_id = c.sport_id WHERE c.competition_id=? AND m.start_time > now() AND o.sub_type_id in (1,18, 10) AND m.status <> 3 GROUP BY m.parent_match_id ORDER BY m.priority DESC, c.priority DESC , m.start_time LIMIT 60");

        $theCompetition = $this->rawSelect("select competition_name,competition_id,category,sport_id from competition where competition_id='$id' limit 1");

        $sport_id = $theCompetition['0']['sport_id'];

        $sport = $this->rawSelect("select sport_name from sport where sport_id='$sport_id' limit 1");
        $sport = $sport['0']['sport_name'];

        $theBetslip = $this->session->get("betslip");

        $title = $sport . ' > ' . $theCompetition['0']['competition_name'] . ", " . $theCompetition['0']['category'];

        $theCompetition = $theCompetition['0'];

        $pages = 0;

        $this->tag->setTitle($title);

        $this->view->setVars([
            'matches' => $matches,
            'title'   => $title,
            'pages'   => $pages,
        ]);

        $this->view->pick("sports/threeway");
    }

    /**
     *
     */
    public function twowayAction()
    {
        $id = $this->request->get('id', 'int');
        $theCompetition = $this->rawQueries("select competition_name,competition_id,category, sport_name, sport_id from competition c inner join sport s using(sport_id) where competition_id='$id' limit 1");

        $title = $theCompetition['0']['sport_name'] .' - '.$theCompetition['0']['competition_name'].", ".$theCompetition['0']['category'];
        $sport_id = $theCompetition['0']['sport_id'];
        $this->tag->setTitle($title);

        $allowed_types = ['186' =>[
            'market'=>'Winner',
            'display' => '1, 2' ]
        ];

        if(array_key_exists($sport_id,
            $this->view->defaultDisplayIds)){
            $allowed_types = $this->view->defaultDisplayIds[$sport_id] ;
        }
        $types = implode(",", array_keys($allowed_types));

        $matches = $this->rawSelect("SELECT c.priority, (SELECT count(DISTINCT e.sub_type_id) FROM event_odd e INNER JOIN odd_type o ON (o.sub_type_id = e.sub_type_id AND o.parent_match_id = e.parent_match_id) WHERE e.parent_match_id = m.parent_match_id AND o.active = 1) AS side_bets, o.sub_type_id, MAX(CASE WHEN o.odd_key = m.home_team THEN odd_value END) AS home_odd, MAX(CASE WHEN o.odd_key = 'draw' THEN odd_value END) AS neutral_odd,      MAX(CASE WHEN o.odd_key = m.away_team THEN odd_value END) AS away_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or ', m.away_team)  THEN odd_value END) AS double_chance_12_odd, MAX(CASE WHEN o.odd_key = concat('draw or ', m.away_team)  THEN odd_value END) AS double_chance_x2_odd, MAX(CASE WHEN o.odd_key = concat(m.home_team, ' or draw')  THEN odd_value END) AS double_chance_1x_odd, MAX(CASE WHEN o.odd_key = 'over 2.5' and o.sub_type_id=18  THEN odd_value END) AS over_25_odd, MAX(CASE WHEN o.odd_key = 'under 2.5' and o.sub_type_id=18  THEN odd_value END) AS under_25_odd,m.game_id, m.match_id, m.start_time, m.away_team, m.home_team, m.parent_match_id,c.competition_name,c.category FROM `match` m INNER JOIN event_odd o ON m.parent_match_id = o.parent_match_id INNER JOIN competition c ON c.competition_id = m.competition_id INNER JOIN sport s ON s.sport_id = c.sport_id WHERE c.competition_id=? AND m.start_time > now() AND o.sub_type_id in ($types) AND m.status <> 3 GROUP BY m.parent_match_id ORDER BY m.priority DESC, c.priority DESC , m.start_time LIMIT 60");
        
        $theBetslip = $this->session->get("betslip");
        $this->tag->setTitle($title);

        $this->view->setVars([
            'matches' => $matches,
            'title'   => $title,
            'theBetslip' => $theBetslip,
        ]);

        $this->view->pick("sports/twoway");
    }

    /**
     *
     */
    public function upcomingAction()
    {
        $page = $this->request->get('page', 'int') ?: 0;
        if ($page < 0) {
            $page = 0;
        }
        $limit = $this->request->get('limit', 'int') ?: 60;
        $skip = $page * $limit;

        $keyword = $this->request->getPost('keyword');

        list($today, $total, $sCompetitions) = $this->getGames($keyword, $skip, $limit);


        $total = $total['0']['total'];

        $pages = ceil($total / $limit);

        $theBetslip = $this->session->get("betslip");

//        var_dump($pages);
//        die;

        $this->view->setVars([
            'matches'       => $today,
            'theBetslip'    => $theBetslip,
            'sCompetitions' => $sCompetitions,
            'total'         => $total,
            'pages'         => $pages > 14 ? 14 : $pages,
            'page'          => $page,
        ]);

        $this->tag->setTitle('ScorePesa - The leading sports Betting Website In Kenya');
//
//        $url = 'https://api.Bikosports.com/v1/sports/matches';
//        $data = [
//            'page'  => 1,
//            'limit' => 20,
//        ];
//        $data = json_encode($data);
//
//        $matches = $this->getData($url, $data);
//
//        $matches = $matches->data;
//
//        //$matches = $matches->data;
//
//        $title = 'Upcoming Events - Rugby, Ice Hockey, Darts, Basketball';
//
//        $pages = 0;
//        $page = 0;
//
//        $this->view->setVars([
//            'matches' => $matches,
//            'title'   => $title,
//            'pages'   => $pages,
//            'page'    => $page,
//        ]);
//
//        $this->view->disable;

    }
}

?>