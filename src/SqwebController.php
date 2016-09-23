<?php
/*
 * SQweb PHP SDK
 * @link https://github.com/SQweb-team/SQweb-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Sqweb\Laravel_sdk;

define('SDK', 'SQweb/SDK-Laravel 1.0.1');

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
        $config = !empty(config('sqweb')) ? config('sqweb') : config('sqweb_default_config');
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Query the API for auth and credits status.
     * Returns the credits, or 0.
     * @return int
     */
    public function checkCredits()
    {
        if (empty($this->response)) {
            if (isset($_COOKIE['sqw_z']) && null !== $this->id_site) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.sqweb.com/token/check',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT_MS => 1000,
                    CURLOPT_TIMEOUT_MS => 1000,
                    CURLOPT_USERAGENT => SDK,
                    CURLOPT_POSTFIELDS => array(
                        'token' => $_COOKIE['sqw_z'],
                        'site_id' => $this->id_site,
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
        echo '<script>
            var _sqw = {
                id_site: '. $this->id_site .',
                debug: '. $this->debug .',
                targeting: '. $this->targeting .',
                beacon: '. $this->beacon .',
                dwide: '. $this->dwide .',
                i18n: "'. $this->lang .'",
                msg: "'. $this->message .'"};
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "//cdn.sqweb.com/sqweb-beta.js";
            document.getElementsByTagName("head")[0].appendChild(script);
        </script>';
    }

    /**
     * Create the target button div.
     * @param null $color
     */

    public function button($color = null)
    {
        if ('grey' === $color) {
            echo '<div class="sqweb-button sqweb-grey"></div>';
        } else {
            echo '<div class="sqweb-button"></div>';
        }
    }

    function sqw_balise($balise, $match) {
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

    function transparent($text, $percent = 100) {
        if (self::checkCredits() == 1 || $percent == 100 || empty($text)) {
            return $text;
        }
        if ($percent == 0) {
            return '';
        }
        $array_text = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach (array_keys($array_text, ' ', true) as $key) {
            unset($array_text[ $key ]);
        }
        $array_text = array_values($array_text);
        $words = count($array_text);
        $nbr = ceil($words / 100 * $percent);
        $lambda = (1 / $nbr);
        $alpha = 1;
        $begin = 0;
        $balise = array();
        while ($begin < $nbr) {
            if (isset($array_text[$begin + 1])) {
                if (preg_match('/<.+?>/', $array_text[ $begin ], $match)) {
                    $balise = sqw_balise($balise, $match[0]);
                    $final[] = $array_text[ $begin ];
                    $nbr++;
                } else {
                    $final[] = '<span style="opacity: ' . number_format($alpha, 5, '.', '') . '">' . $array_text[ $begin ] . '</span>';
                    $alpha -= $lambda;
                }
                $begin++;
            }
        }
        foreach ($balise as $value) {
            $final[] = '</' . $value . '>';
        }
        $final = implode(' ', $final);
        return $final;
    }

    public function postLimitArticle($limitation = 0)
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

    public function waitToDisplay($content, $date, $format, $wait = 0)
    {
        if ($wait == 0 || self::checkCredits() == 1) {
            return $content;
        }
        return Carbon::now()->gte(Carbon::createFromFormat($format, $date)->addDay($wait)) == false ? '' : $content;
    }
}
