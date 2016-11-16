<?php
/*
 * SQweb PHP SDK
 * @link https://github.com/SQweb-team/SQweb-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Sqweb\Laravel_sdk;

define('SDK', 'SQweb/SDK-Laravel 1.2.0');

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use App\Http\Controllers\Controller;

class SqwebController extends Controller
{
    private $response;

    /**
     * @var Repository
     */
    protected $config;

    public function __construct()
    {
        $this->config = !empty(config('sqweb')) ? config('sqweb') : config('sqweb_default_config');
    }

    /**
     * Query the API for auth and credits status.
     * Returns the credits, or 0.
     * @return int
     */
    public function checkCredits()
    {
        if (empty($this->response)) {
            if (isset($_COOKIE['sqw_z']) && null !== $this->config['id_site']) {
                $curl = curl_init();
                curl_setopt_array($curl, array(

                    CURLOPT_URL => 'https://api.sqweb.com/token/check',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT_MS => 1000,
                    CURLOPT_TIMEOUT_MS => 1000,
                    CURLOPT_USERAGENT => SDK,
                    CURLOPT_POSTFIELDS => array(
                        'token' => $_COOKIE['sqw_z'],
                        'site_id' => $this->config['id_site'],
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $this->response = json_decode($response);
            }
        }
        if ($this->response !== null && $this->response->status === true && $this->response->credit > 0) {
            return ($this->response->credit);
        }
        return (0);
    }

    public function script()
    {
        if ($this->config['targeting'] && $this->config['beacon']) {
            $this->beacon = 0;
        }

        echo '<script>
            var _sqw = {
                id_site: '. $this->config['id_site'] .',
                    debug: '. $this->config['debug'] .',
                    targeting: '. $this->config['targeting'] .',
                    beacon: '. $this->config['beacon'] .',
                    dwide: '. $this->config['dwide'] .',
                    i18n: "'. $this->config['lang'] .'",
                    msg: "'. $this->config['message'] .'"};
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "https://cdn.sqweb.com/sqweb.js";
                document.getElementsByTagName("head")[0].appendChild(script);
            </script>';
    }

    /**
     * Create the target button div.
     * @param null $color
     */

    public function button($size = null)
    {
        if ('slim' === $size) {
            echo '<div class="sqweb-button multipass-slim"></div>';
        } else {
            echo '<div class="sqweb-button"></div>';
        }
    }

    public function sqwBalise($balise, $match)
    {
        if (preg_match('/<(\w+)(?(?!.+\/>).*>|$)/', $match, $tmp)) {
            if (!isset($balise)) {
                $balise = array();
            }
            $balise[] = $tmp[1];
        }
        foreach ($balise as $key => $value) {
            if (preg_match('/<\/(.+)>/', $value, $tmp)) {
                unset($balise[ $key ]);
            }
        }
        return $balise;
    }

    public function transparent($text, $percent = 100)
    {
        if (self::checkCredits() == 1 || $percent == 100 || empty($text)) {
            return $text;
        }
        if ($percent == 0) {
            return '';
        }
        $arr_txt = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach (array_keys($arr_txt, ' ', true) as $key) {
            unset($arr_txt[ $key ]);
        }
        $arr_txt = array_values($arr_txt);
        $words = count($arr_txt);
        $nbr = ceil($words / 100 * $percent);
        $lambda = (1 / $nbr);
        $alpha = 1;
        $begin = 0;
        $balise = array();
        while ($begin < $nbr) {
            if (isset($arr_txt[$begin + 1])) {
                if (preg_match('/<.+?>/', $arr_txt[ $begin ], $match)) {
                    $balise = self::sqwBalise($balise, $match[0]);
                    $final[] = $arr_txt[ $begin ];
                    $nbr++;
                } else {
                    $tmp = number_format($alpha, 5, '.', '');
                    $final[] = '<span style="opacity: ' . $tmp . '">' . $arr_txt[ $begin ] . '</span>';
                    $alpha -= $lambda;
                }
            }
            $begin++;
        }
        foreach ($balise as $value) {
            $final[] = '</' . $value . '>';
        }
        $final = implode(' ', $final);
        return $final;
    }

    public function limitArticle($limitation = 0)
    {
        if (self::checkCredits() == 0 && $limitation != 0) {
            if (!isset($_COOKIE['sqwBlob']) || (isset($_COOKIE['sqwBlob']) && $_COOKIE['sqwBlob'] != -7610679)) {
                $ip2 = ip2long($_SERVER['REMOTE_ADDR']);
                if (!isset($_COOKIE['sqwBlob'])) {
                    $sqwBlob = 1;
                } else {
                    $sqwBlob = ($_COOKIE['sqwBlob'] / 2) - $ip2 - 2 + 1;
                }
                if ($limitation > 0 && $sqwBlob <= $limitation) {
                    $tmp = ($sqwBlob + $ip2 + 2) * 2;
                    setcookie('sqwBlob', $tmp, time()+60*60*24);
                    return true;
                } else {
                    setcookie('sqwBlob', -7610679, time()+60*60*24);
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public function isTimestamp($string)
    {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }

    public function waitToDisplay($date, $format, $wait = 0)
    {
        if ($wait == 0 || self::checkCredits() == 1) {
            return true;
        }
        return Carbon::now()->gte(Carbon::createFromFormat($format, $date)->addDay($wait)) == false ? false : true;
    }
}
