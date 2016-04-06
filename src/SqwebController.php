<?php
/*
 * SQweb PHP SDK
 * @author Pierre Lavaux <pierre@sqweb.com>
 * @author Bastien Botella <bastien@sqweb.com>
 * @author Mathieu Darrigade <mathieu@sqweb.com>
 * @link https://github.com/SQweb-team/SQweb-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Sqweb\Laravel_sdk;

use \DateTime;
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
        $config = config('sqweb');
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
                    CURLOPT_USERAGENT => 'SQweb/SDK 1.0.4',
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

    public function transparent($text, $percent = 0)
    {
        if (self::checkCredits() == 0 && $percent >= 0 && $percent <= 100) {
            if ( 0 == $percent ) {
                return '';
            }
            $array_text = explode(' ', $text);
            $words = count(explode(' ', $text));
            $nbr = ceil($words / 100 * $percent);
            $lambda = (1 / $nbr);
            $alpha = 1;
            $begin = 0;
            while ( $begin < $nbr ) {
                $final[ $begin ] = '<span style="opacity: '. $alpha .'">'. $array_text[ $begin ] .'</span>';
                $begin++;
                $alpha -= $lambda;
            }
            $final = implode(' ', $final);
            return $final;
        }
        return $text;
    }

    public function postLimitArticle()
    {
        if (self::checkCredits() == 0) {
            if (!isset($_COOKIE['sqw_blob']) || (isset($_COOKIE['sqw_blob']) && $_COOKIE['sqw_blob'] != -12345678)) {
                $ip2 = ip2long($_SERVER['REMOTE_ADDR']);
                if (!isset($_COOKIE['sqw_blob'])) {
                    $sqw_blob = 1;
                } else {
                    $sqw_blob = ($_COOKIE['sqw_blob'] / 2) - $ip2 - 2 + 1;
                }
                if ($this->limit_article > 0 && $sqw_blob <= $this->limit_article) {
                    $tmp = ($sqw_blob + $ip2 + 2) * 2;
                    setcookie('sqw_blob', $tmp, time()+60*60*24);
                } else {
                    setcookie('sqw_blob', -12345678, time()+60*60*24);
                }
            }
        }
    }

    public function isTimestamp( $string ) {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }

    public function dateBeforeDisplay($text, $waitDate, $publicationDate)
    {
        if (self::checkCredits() == 0) {
            $waitDate = $waitDate * 86400;
            if (self::isTimestamp($publicationDate) == false) {
                $publicationDate = strToTime($publicationDate);
            }
            $final = $publicationDate + $waitDate;
            if ($final <= time()) {
                return $text;
            }
            return '';
        }
        return $text;
    }
}