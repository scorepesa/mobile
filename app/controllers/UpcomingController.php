<?php

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class UpcomingController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('About us');
    }

    public function indexAction()
    {

        $page = $this->request->get('page', 'int') ?: 0;
        if ($page < 0) {
            $page = 0;
        }
        $limit = $this->request->get('limit', 'int') ?: 60;
        $skip = $page * $limit;

        $keyword = $this->request->getPost('keyword');

        list($today, $total, $sCompetitions) = $this->getGames($keyword, $skip, $limit, ' and date(start_time) = curdate() ', 'm_priority desc, priority desc');

        $total = $total['0']['total'];

        $pages = ceil($total / $limit);

        if ($pages > 5) {
            $pages = 5;
        }

        $theBetslip = $this->session->get("betslip");

        $tab = 'upcoming';

        $this->view->setVars([
            'matches'    => $today,
            'tab'        => $tab,
            'theBetslip' => $theBetslip,
            'total'      => $total,
            'pages'      => $pages,
            'page'       => $page,
        ]);

        $this->tag->setTitle('ScorePesa - The leading sports Betting Website In Kenya');

        $this->view->pick('index/index');
    }

}

