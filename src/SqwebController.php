<?php
/*
 * SQweb PHP SDK
 * @link https://github.com/SQweb-team/SQweb-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Sqweb\Laravel_sdk;

define('SDK', 'SQweb/SDK-Laravel 1.2.2');

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
                    CURLOPT_URL => 'https://api.multipass.net/token/check',
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
                    sitename: "' . $this->config['sitename'] .'",
                    debug: '. $this->config['debug'] .',
                    adblock_modal: '. $this->config['adblock_modal'] .',
                    targeting: '. $this->config['targeting'] .',
                    beacon: '. $this->config['beacon'] .',
                    dwide: '. $this->config['dwide'] .',
                    i18n: "'. $this->config['lang'] .'",
                    msg: "'. $this->config['message'] .'"};
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src = "https://cdn.multipass.net/multipass.js";
                document.getElementsByTagName("head")[0].appendChild(script);
            </script>';
    }

    /*
     * Display a footer div asking to subscribe.
     */
    public function supportBlock()
    {
        switch ($this->config['lang']) {
            case 'fr':
            case 'fr_fr':
                $wording = array(
                    'warning'       => 'La suite de cet article est reservée.',
                    'already_sub'   => 'Déjà abonné ? ',
                    'login'         => 'Connexion',
                    'unlock'        => 'Débloquez ce contenu avec',
                    'desc'          => 'Multipass est un abonnement multi-sites, sans engagement.',
                    'href'          => 'https://www.multipass.net/fr/sites-partenaires-premium-sans-pub-ni-limites',
                    'discover'      => 'Découvrir tous les partenaires'
                );
                break;

            default:
                $wording = array(
                    'warning'       => 'The rest of this article is restricted.',
                    'already_sub'   => 'Already a member? ',
                    'login'         => 'Sign in',
                    'unlock'        => 'Unlock this content, get your ',
                    'desc'          => 'Multipass is a multisite subscription, with no commitment.',
                    'href'          => 'https://www.multipass.net/en/premium-partners-website-without-ads-nor-restriction',
                    'discover'      => 'Discover all the partners'
                );
                break;
        }
        echo '
            <div class="footer__mp__normalize footer__mp__button_container">
                <div class="footer__mp__button_header">
                    <div class="footer__mp__button_header_title">' . $wording['warning'] . '</div>
                    <div onclick="sqw.modal_first()" class="footer__mp__button_signin">' . $wording['already_sub'] . '<span class="footer__mp__button_login footer__mp__button_strong">' . $wording['login'] . '</span></div>
                </div>
                <div onclick="sqw.modal_first()" class="footer__mp__normalize footer__mp__button_cta">
                    <a href="#" class="footer__mp__cta_fresh">' . $wording['unlock'] . '</a>
                </div>
                <div class="footer__mp__normalize footer__mp__button_footer">
                    <p class="footer__mp__normalize footer__mp__button_p">' . $wording['desc'] . '</p>
                    <a target="_blank" class="footer__mp__button_discover footer__mp__button_strong" href="' . $wording['href'] . '"><span>></span> <span class="footer__mp__button_footer_txt">' . $wording['discover'] . '</span></a>
                </div>
            </div>';
    }

    /**
     * Create the target button div.
     * @param null $size
     */

    public function button($size = null)
    {
        if ($size === 'tiny') {
            echo '<div class="sqweb-button multipass-tiny"></div>';
        } elseif ($size === 'slim') {
            echo '<div class="sqweb-button multipass-slim"></div>';
        } elseif ($size === 'large') {
            echo '<div class="sqweb-button multipass-large"></div>';
        } else { // multipass-regular
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

    /**
     * Put opacity to your text
     * Returns text  with opcaity style.
     * @param $text  Text you want to limit.
     * @param int $percent Percent of your text you want to show.
     * @return string
     */
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

    /**
     * Limit the number of articles free users can read per day.
     * @param $limitation int The number of articles a free user can see.
     * @return bool
     */
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

    /**
     * Display your premium content at a later date to non-paying users.
     * @param  string  $date  When to publish the content on your site. It must be an ISO format(YYYY-MM-DD).
     * @param  integer $wait  Days to wait before showing this content to free users.
     * @return bool
     */
    public function waitToDisplay($date, $wait = 0)
    {
        if ($wait == 0 || self::checkCredits() == 1) {
            return true;
        }
        return Carbon::now()->gte(Carbon::createFromFormat('Y-m-d', $date)->addDay($wait)) == false ? false : true;
    }
}
