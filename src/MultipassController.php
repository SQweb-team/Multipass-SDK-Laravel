<?php
/*
 * Multipass Laravel SDK v1.3.2
 * @author Pierre Lavaux <pierre@multipass.net>
 * @author Mathieu Darrigade <mathieu@multipass.net>
 * @author Nicolas Verdonck <nicolas@sqweb.com>
 * @author Bastien Botella <bastien@sqweb.com>
 * @author Matthieu Borde <matthieu@multipass.net>
 * @link https://github.com/SQweb-team/Multipass-SDK-Laravel
 * @license http://opensource.org/licenses/GPL-3.0
 */

namespace Multipass\LaravelSDK;

define('SDK', 'Multipass/SDK-Laravel 1.3.2');

use Multipass\Core\Lib;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use App\Http\Controllers\Controller;

class MultipassController extends Controller
{
    /**
     * @var int The user's credits
     */
    private $user_credits;

    /**
     * @var array The configuration array
     */
    private $config;

    public function __construct()
    {
        $this->config = config('multipass') ?: config('multipass-default');

        if (!$this->config['id_site']) {
            throw new \Exception('A website ID is required by Multipass. Please check your configuration.');
        }

        if (!$this->config['sitename']) {
            throw new \Exception('A website name is required by Multipass. Please check your configuration.'); 
        }
    }

    /**
     * Queries the API for a user's credits status.
     *
     * @return int
     */
    public function checkCredits()
    {
        if ($this->user_credits !== null) {
            return $this->user_credits;
        }

        if (isset($_COOKIE['sqw_z'])) {
            return $this->user_credits = Lib::getUserCredits(
                $_COOKIE['sqw_z'],
                $this->config['id_site'],
                SDK
            );
        }

        return 0;
    }

    /**
     * Outputs the Multipass JavaScript tag.
     *
     * @return string
     */
    public function script()
    {
        return Lib::generateScript($this->config, SDK);
    }

    /**
     * Outputs an 'article lock', and optionally splits in half
     * and gives your text a fade-out effect.
     *
     * @param string $text The text to manipulate, optional. Defaults to null. 
     * @param bool $check Check if the user is premium, optional. Defaults to true.
     * @return string OR null if $check is set to true and the user is premium.
     */
    public function lockingBlock($text = null, $check = true)
    {
        if ($check === true && $this->checkCredits()) {
            return $text ?: null;
        }

        if (!empty($text)) {
            $text = Lib::generateFadeOutText($text, 50);
        }

        return $text . Lib::generateArticleLock($this->config['lang']);
    }

    /**
     * Outputs an End-of-Article block.
     *
     * @param bool $check Check if the user is premium, optional. Defaults to true.
     * @return string OR null if $check is set to true and the user is premium.
     */
    public function supportBlock($check = true)
    {
        return $check === true && $this->checkCredits()
            ? null
            : Lib::generateEndOfArticle($this->config['lang']);
    }

    /**
     * Create a Multipass sign-in/sign-up button.
     *
     * @param string $size The size of the button ('large', 'slim' or 'tiny'), optional.
     * Defaults to null (a regular size).
     * @return string
     */
    public function button($size = null)
    {
        return Lib::generateButton($size);
    }

    /**
     * Gives your text a fade-out effect and cuts it based on a percentage.
     *
     * @param   string $text    The string to manipulate. Can contain HTML tags.
     * @param   int $percentage The percentage of text to display, optional. Defaults to 50%.
     * @param   bool $check     Check if the user is premium, optional. Defaults to true.
     * @return  string
     */
    public function transparent($text, $percentage = 50, $check = true)
    {
        return $this->checkCredits()
            ? $text
            : Lib::generateFadeOutText($text, $percentage);
    }

    /**
     * Limits the number of pages a non-premium user can read per day.
     *
     * @return bool
     */
    public function limitArticle()
    {
        return $this->checkCredits()
            ? true
            : Lib::isUriLimited($this->config['content_limit']);
    }

    /**
     * Checks if your content should be displayed at a later date to non-premium users.
     *
     * @param string $published_at The date when the content is to be/was published.
     * Date format must be ISO (YYYY-MM-DD).
     * @param int $delay An integer representing the days to delay the content before showing
     * it to non-premium users, optional. Defaults to 5 days.
     * @return bool
     */
    public function waitToDisplay($published_at, $delay = 5)
    {
        return $this->checkCredits()
            ? true
            : Lib::isDateGreaterThanNow($published_at, $delay);
    }
}
