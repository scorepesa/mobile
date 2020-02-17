<?php

use Firebase\JWT\JWT;
use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 */
class ControllerBase extends Controller
{
    /**
     *
     */
    const JWT_KEY = "5eBOGiKXt7dsKwaaJcRX8owIH7BbJ8F9";
    /**
     *
     */
    const BASE_URI = "http://35.187.20.191:8787";


    /**
     * @param $dispatcher
     */
    public function beforeExecuteRoute($dispatcher)
    {
        if ($this->cookies->has('auth')) {
            $token = $this->cookies->get('auth');
            if (!$this->session->has("auth")) {
                try {
                    $user = JWT::decode($token, self::JWT_KEY, ['HS256']);
                    $user = $user->user;
                    if ($user->remember == '1' || $user->device == '1') {
                        $user = [
                            'id'       => $user->id,
                            'name'     => $user->name,
                            'mobile'   => $user->mobile,
                            'device'   => $user->device,
                            'remember' => $user->remember,
                        ];
                        $this->_registerSession($user);
                    }

                } catch (Exception $e) {
                    $decoded = $e->getMessage();
                }

            }
        }

        $betslip = $this->betslip('prematch');

        if(count($betslip) == 0){

            $betslip = $this->betslip('jackpot');
        }

        $slipCount = sizeof($betslip);

        $sportType = [
            '79' => 'competition',
            '85' => 'twoway',
            '97' => 'competition',
            '84' => 'twoway',
            '82' => 'competition',
            '83' => 'competition',
            '86' => 'competition',
            '96' => 'competition',
            '98' => 'twoway',
            '99' => 'competition',
            '100' => 'competition',
            '102' => 'competition',
            '104' => 'competition',
            '105' => 'competition',
            '106' => 'competition',
            '107' => 'competition',
            '108' => 'competition',
            '109' => 'competition',
            '162' => 'competition',
            '163' => 'competition',
            '164' => 'competition',
            '165' => 'twoway',
            '80' => 'twoway',
            '239'=> 'competition',
        ];

        $default_display_ids = [
            '79' => ['1' =>  ['market'=>'3 Way','display'=>'HOME, DRAW, AWAY'],
                '10' => ['market' => 'Double Chance','display'=>'1 OR X, X OR 2, 1 OR 2'],
                '18' => ['market'=>'Over/Under 2.5', 'display'=>'OVER, UNDER'],
                ],
            '85' => ['219' =>['market'=>'Winner (incl. overtime)',
                        'display' => '1, 2' ],],
            '162' => ['219' =>['market'=>'Winner (incl. overtime)','display' => '1, 2' ],],
            '84' => ['186' =>['market'=>'Winner', 'display' => '1, 2' ], ],
            '99' => ['186' =>['market'=>'Winner','display' => '1, 2' ], ],
            '165' => ['186' =>['market'=>'Winner','display' => '1, 2' ], ],
            '80' => ['186' =>['market'=>'Winner','display' => '1, 2' ],],
            '98' => ['340' =>['market'=>'Winner (incl. super over)','display' => '1, 2' ],],

            ];


        $refURL = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $this->view->setVars([
            'slipCount' => $slipCount,
            'sportType' => $sportType,
            'refURL'    => $refURL,
            'betslip'   => $betslip,
            'defaultDisplayIds'=>$default_display_ids
        ]);
    }

    /**
     * @param $user
     */
    protected function registerAuth($user)
    {
        $exp = time() + (3600 * 24 * 5);
        $token = $this->generateToken($user, $exp);
        $this->cookies->set('auth', $token, $exp);
        $this->_registerSession($user);
    }

    /**
     * @param null $bet_type
     *
     * @return array
     */
    protected function betslip($bet_type = null)
    {
        $betslip = $this->session->get("betslip") ?: [];
        $prematch = [];

        foreach ($betslip as $key => $value) {
            if ($value['bet_type'] == $bet_type) {
                $prematch[$key] = $value;
            }
        }

        return @$prematch;
    }

    /**
     * @param $bet_type
     *
     * @return bool
     */
    protected function betslipUnset($bet_type)
    {
        $betslip = $this->session->get("betslip");

        foreach ($betslip as $key => $value) {
            if ($value['bet_type'] == $bet_type) {
                unset($betslip[$key]);
            }
        }

        $this->session->set("betslip", $betslip);

        return true;
    }

    /**
     * @param $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', $user);
    }

    /**
     * @param $data
     * @param $exp
     *
     * @return string
     */
    protected function generateToken($data, $exp)
    {
        $token = [
            "iss"  => "http://scorepesa.co.ke",
            "iat"  => 1356999524,
            "nbf"  => 1357000000,
            "exp"  => $exp,
            "user" => $data,
        ];

        $jwt = JWT::encode($token, self::JWT_KEY);

        return $jwt;
    }

    /**
     * @param $token
     *
     * @return object
     */
    protected function decodeToken($token)
    {
        $decoded = JWT::decode($token, self::JWT_KEY, ['HS256']);

        return $decoded;
    }

    /**
     * @param $statement
     *
     * @param array $bindings
     *
     * @return mixed
     */
    protected function rawSelect($statement, $bindings = [])
    {
        $connection = $this->di->getShared("db");
        $success = $connection->query($statement, $bindings);
        $success->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $success = $success->fetchAll($success);

        return $success;
    }

    /**
     * @param $statement
     *
     * @param array $bindings
     *
     * @return mixed
     */
    protected function rawQueries($statement, $bindings = [])
    {
        $connection = $this->di->getShared("db");
        $success = $connection->query($statement, $bindings);
        $success->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $success = $success->fetchAll($success);

        return $success;
    }


    /**
     * @param $statement
     *
     * @return mixed
     */
    protected function rawInsert($statement)
    {
        $connection = $this->di->getShared("db");
        $success = $connection->query($statement);

        $id = $connection->lastInsertId();

        return $id;
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function flashError($message)
    {
//        return "<div class='alert alert-error alert-dismissible'>$message</div>";
        return $message;
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function flashSuccess($message)
    {
//        return "<div class='alert alert-success alert-dismissible' role='alert'>$message</div>";
        return $message;
    }

    /**
     * @param $number
     *
     * @return bool|string
     */
    protected function formatMobileNumber($number)
    {
        $regex = '/^(?:\+?(?:[1-9]{3})|0)?([7]([0-9]{8}))$/';
        if (preg_match_all($regex, $number, $capture)) {
            $msisdn = '254' . $capture[1][0];
        } else {
            $msisdn = false;
        }

        return $msisdn;
    }

    /**
     * @param $url
     * @param $data
     *
     * @return mixed
     */
    protected function getData($url, $data)
    {
        $httpRequest = curl_init($url);
        curl_setopt($httpRequest, CURLOPT_URL, $url);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ',
        ]);
        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);
        //decode response
        $response = json_decode($results);

        return $response;
    }

    /**
     * @return string
     */
    protected function getDevice()
    {
        $device = '2';
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $device = '1';
        }

        return $device;
    }

    /**
     * @return array|false|string
     */
    function getClientIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    /**
     * @param $data
     * @param $url
     *
     * @return array
     */
    protected function betJackpot($data) {

        $URL = "http://35.187.20.191:8787/jp/bet";
        $bet = json_encode($data);
        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $bet);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($bet)));
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = ["status_code" => $status_code, "message" => $results];
        return $response;
    }

    /**
     * @param $transaction
     *
     * @return array
     */
    protected function betTransaction($transaction)
    {
        $URL = self::BASE_URI . "/bet";

        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, "$transaction");
        curl_setopt($httpRequest, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = [
            "status_code" => $status_code,
            "message"     => $results,
        ];

        return $response;
    }

    /**
     * @param $sms
     *
     * @return mixed
     */
    protected function sendSMS($sms)
    {

        $URL = self::BASE_URI . "/sendsms";

        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, "$sms");
        curl_setopt($httpRequest, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = json_decode($results);

        return $status_code;
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function bet($data)
    {
        $URL = self::BASE_URI . "/bet";

        $bet = json_encode($data);

        $httpRequest = curl_init($URL);

        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $bet);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($bet),
        ]);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = [
            "status_code" => $status_code,
            "message"     => $results,
        ];

        return $response;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected function topup($data)
    {

        $URL = "http://35.198.157.196:1580/kibeti";

        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, "$data");
        curl_setopt($httpRequest, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = json_decode($results);

        return $status_code;
    }

    /**
     * @param $transaction
     *
     * @return mixed
     */
    protected function withdraw($transaction)
    {   


        $URL = self::BASE_URI . "/macatm";

        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, "$transaction");
        curl_setopt($httpRequest, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = json_decode($results);
        return $status_code;
    }

    /**
     * @param $transaction
     *
     * @return mixed
     */
    protected function bonus($transaction)
    {

        $URL = self::BASE_URI . "/profilemgt";

        $httpRequest = curl_init($URL);
        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, "$transaction");
        curl_setopt($httpRequest, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);

        $response = json_decode($results);

        return $status_code;
    }


    /**
     * @param $keyword
     * @param $skip
     * @param $limit
     * @param string $filter
     * @param string $orderBy
     *
     * @return array
     */
    protected function getGames($keyword, $skip, $limit, $filter = "", $orderBy = "start_time asc")
    {
        $where = "where start_time > now() " . $filter;
//        $where = "where 1";
        if ($keyword) {
            $bindings = ['keyword' => "%$keyword%"];
            $whereClause = "$where and (game_id like :keyword or home_team like :keyword or away_team like :keyword or competition_name like :keyword)";
            $today = $this->rawSelect("select * from ux_todays_highlights $whereClause order by $orderBy limit $skip,$limit", $bindings);
            $items = $this->rawSelect("select count(*) as total from ux_todays_highlights $whereClause limit 1", $bindings);
        } else {
            $whereClause = "$where";
            $today = $this->rawSelect("select * from ux_todays_highlights $whereClause order by $orderBy limit $skip,$limit");
            $items = $this->rawSelect("select count(*) as total from ux_todays_highlights $whereClause limit 1");
        }

        return [
            $today,
            $items,
            $this->getCompetitions(),
        ];
    }

    /**
     * @return array
     */
    protected function getCompetitions()
    {
        return [
            'UEFA Youth League, Group A'                     => 'UEFA Youth',
            'UEFA Youth League, Group B'                     => 'UEFA Youth',
            'UEFA Youth League, Group C'                     => 'UEFA Youth',
            'UEFA Youth League, Group D'                     => 'UEFA Youth',
            'UEFA Youth League, Group E'                     => 'UEFA Youth',
            'UEFA Youth League, Group F'                     => 'UEFA Youth',
            'UEFA Youth League, Group H'                     => 'UEFA Youth',
            'UEFA Youth League, Group G'                     => 'UEFA Youth',
            'Int. Friendly Games, Women'                     => 'Women',
            'U17 European Championship, Group B'             => 'U17',
            'U17 European Championship, Qualification Gr. 9' => 'U17',
            'U21'                                            => 'U21',
            'Premier League 2, Division 1'                   => 'Amateur',
            'Premier League 2, Division 2'                   => 'Amateur',
            'Youth League'                                   => 'Youth',
            'Development League'                             => 'Youth',
            'U19 European Championship Qualif. - Gr. 1'      => 'U19',
            'U19 European Championship Qualif. - Gr. 2'      => 'U19',
            'U19 European Championship Qualif. - Gr. 3'      => 'U19',
            'U19 European Championship Qualif. - Gr. 4'      => 'U19',
            'U19 European Championship Qualif. - Gr. 5'      => 'U19',
            'U19 European Championship Qualif. - Gr. 6'      => 'U19',
            'U19 European Championship Qualif. - Gr. 7'      => 'U19',
            'U19 European Championship Qualif. - Gr. 8'      => 'U19',
            'U19 European Championship Qualif. - Gr. 9'      => 'U19',
            'U19 European Championship Qualif. - Gr. 10'     => 'U19',
            'U19 European Championship Qualif. - Gr. 11'     => 'U19',
            'U19 European Championship Qualif. - Gr. 12'     => 'U19',
            'U19 European Championship Qualif. - Gr. 13'     => 'U19',
            'U19 Int. Friendly Games'                        => 'U19',
            'U20 Friendly Games'                             => 'U20',
            'U21 Friendly Games'                             => 'U21',
            'U21 EURO, Qualification, Group 1'               => 'U21',
            'U21 EURO, Qualification, Group 2'               => 'U21',
            'U21 EURO, Qualification, Group 3'               => 'U21',
            'U21 EURO, Qualification, Group 4'               => 'U21',
            'U21 EURO, Qualification, Group 5'               => 'U21',
            'U21 EURO, Qualification, Group 6'               => 'U21',
            'U21 EURO, Qualification, Group 7'               => 'U21',
            'U21 EURO, Qualification, Group 8'               => 'U21',
            'UEFA Youth League, Knockout stage'              => 'UEFA Youth',
            'U21 EURO, Qualification, Group 9'               => 'U21',
            'U21 EURO, Qualification, Playoffs'              => 'U21',
            'UEFA Champions League, Women'                   => 'W',
            'Africa Cup Of Nations, Women, Group A'          => 'W',
            'Primera Division Femenina'                      => 'W',
            'W-League'                                       => 'W',
            'A-Jun-BL West'                                  => 'U19',
            'Bundesliga, Women'                              => 'W',
            'Campionato Primavera, Girone A'                 => 'Youth',
            'Campionato Primavera, Girone B'                 => 'Youth',
            'Campionato Primavera, Girone C'                 => 'Youth',
        ];
    }

    /**
     * @return array
     */
    protected function getSportTypes()
    {
        return [
            '14' => 'competition',
            '41' => 'competition',
            '56' => 'twoway',
            '31' => 'twoway',
            '30' => 'twoway',
            '37' => 'twoway',
            '29' => 'competition',
            '28' => 'twoway',
            '44' => 'twoway',
            '34' => 'twoway',
            '39' => 'twoway',
            '42' => 'twoway',
            '33' => 'competition',
            '79' => 'competition',
            '35' => 'twoway',
        ];
    }

    /**
     * @param $message
     *
     * @return mixed
     */
    protected function formatMessage($message)
    {
        return preg_replace('/(KES\s+)?[+-]?[0-9]{1,3}(?:,?[0-9]{3})(\.[0-9]{2})?/', '<b>$0</b>', $message);
    }


    protected function reference() {

        if (!$this->cookies->has('referenceID')) {

            $crypt = new Crypt();

            $key = 'FbxH8j7SPeeVDE7i';
            $text = $_SERVER['HTTP_USER_AGENT'] . time() . uniqid();

            $key = $crypt->encryptBase64($text, $key);

            $this->cookies->set('referenceID', $key, time() + 15 * 86400);
        }

        $referenceID = $this->cookies->get('referenceID');

        $referenceID = $referenceID->getValue();

        return $referenceID;
    }


    protected function deleteReference() {

        $this->cookies->set('referenceID');
    }


}
