<?php
use Phalcon\Tag;


/**
 * Class IndexController
 */
class IndexController extends ControllerBase
{
    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
        $hour = date('H');

        if ($hour == 23) {
            return $this->response->redirect('upcoming');
            $this->view->disable();
        } else {
            $this->tag->setDoctype(Tag::HTML5);

            $page = $this->request->get('page', 'int') ?: 0;
            if ($page < 0) {
                $page = 0;
            }
            $limit = $this->request->get('limit', 'int') ?: 40;
            $skip = $page * $limit;

            $keyword = $this->request->getPost('keyword');

//            $today = $this->rawSelect("select * from ux_todays_highlights where start_time > now() and date(start_time) = date(now()) order by m_priority desc, priority desc limit $skip,$limit");
//            $total = $this->rawSelect("SELECT count(*) AS total FROM ux_todays_highlights WHERE start_time > now() AND date(start_time) = date(now()) ORDER BY start_time, m_priority DESC, priority DESC LIMIT 1");
//
//            if ($keyword) {
//                $today = $this->rawSelect("select * from ux_todays_highlights where start_time > now() and date(start_time) = date(now()) and game_id like '%$keyword%' or home_team like '%$keyword%' or away_team like '%$keyword%' order by m_priority desc, priority desc limit $skip,$limit");
//                $total = $this->rawSelect("select count(*) from ux_todays_highlights where start_time > now() and date(start_time) = date(now()) and game_id like '%$keyword%' or home_team like '%$keyword%' or away_team like '%$keyword%' order by m_priority desc, priority desc limit $skip,$limit");
//            }

            list($today, $total, $sCompetitions) = $this->getGames($keyword, $skip, $limit, '', 'm_priority desc, priority desc');

            $total = $total['0']['total'];

            $pages = ceil($total / $limit);

            if ($pages > 5) {
                $pages = 5;
            }

            $theBetslip = $this->session->get("betslip");

            $tab = 'highlights';
            $men = 'home';

            $this->view->setVars([
                'matches'    => $today,
                'theBetslip' => $theBetslip,
                'total'      => $total,
                'pages'      => $pages,
                'page'       => $page,
                'tab'        => $tab,
                'men'        => $men,
            ]);

            $this->tag->setTitle('Wekeleabet - Leading sports Betting site In Kenya');
        }
    }

}

?>