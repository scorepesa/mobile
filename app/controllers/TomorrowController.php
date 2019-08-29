<?php

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class TomorrowController extends ControllerBase
{
	public function initialize()
    {
        $this->tag->setTitle('About us');
    }

    public function indexAction()
    {
        $page = $this->request->get('page','int') ?: 0;
        if ($page<0) {
            $page=0;
        }
        $limit = $this->request->get('limit','int') ?: 60;
        $skip = $page*$limit;

        $keyword = $this->request->getPost('keyword');

//        $today=$this->rawSelect("select * from ux_todays_highlights where date(start_time) = date(now() + INTERVAL 1 DAY) order by m_priority desc, priority desc limit $skip,$limit");
//        $total = $this->rawSelect("select count(*) as total from ux_todays_highlights where date(start_time) = date(now() + INTERVAL 1 DAY) order by m_priority desc, priority desc limit 1");
//
//        if ($keyword) {
//            $today=$this->rawSelect("select * from ux_todays_highlights where date(start_time) = date(now() + INTERVAL 1 DAY) and game_id like '%$keyword%' or home_team like '%$keyword%' or away_team like '%$keyword%' order by m_priority desc, priority desc limit $skip,$limit");
//            $total=$this->rawSelect("select count(*) as total from ux_todays_highlights where date(start_time) = date(now() + INTERVAL 1 DAY) and game_id like '%$keyword%' or home_team like '%$keyword%' or away_team like '%$keyword%' order by m_priority desc, priority desc limit 1");
//        }

        list($today, $total, $sCompetitions) = $this->getGames($keyword, $skip, $limit, '', 'm_priority desc, priority desc');
        
        $total = $total['0']['total'];

        $pages = ceil($total/$limit)-1;

        if ($pages>5) {
            $pages = 5;
        }

        $theBetslip = $this->session->get("betslip");
        $tab = 'tomorrow';

        $this->view->setVars(['matches'=>$today,'theBetslip'=>$theBetslip,'total'=>$total,'tab'=>$tab,'pages'=>$pages,'page'=>$page]);

        $this->tag->setTitle('Bikosports - The #1 Online & SMS sports Betting Website In Kenya');

        $this->view->pick('index/index');
    }

}

