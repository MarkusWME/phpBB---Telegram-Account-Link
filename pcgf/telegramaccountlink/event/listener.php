<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2017 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\telegramaccountlink\event;

use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @version 1.0.0 */
class listener implements EventSubscriberInterface
{
    /** @var user $user The user object */
    protected $user;

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     *
     * @param user $user The user object
     */
    public function __construct(user $user)
    {
        $this->user = $user;
    }

    /**
     * Function that defines which events should be subscribed to
     *
     * @access public
     * @since  1.0.0
     *
     * @return array An array which defines to which events the extension should listen
     */
    static public function getSubscribedEvents()
    {
        return array(
            'core.generate_profile_fields_template_data_before' => 'load_language_data',
            'core.ucp_profile_modify_profile_info'              => 'load_language_data',
        );
    }

    /**
     * Function that load the language data of the extension
     *
     * @access public
     * @since  1.0.0
     */
    public function load_language_data()
    {
        $this->user->add_lang_ext('pcgf/telegramaccountlink', 'telegramaccountlink');
    }
}
