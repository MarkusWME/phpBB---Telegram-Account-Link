<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2017 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @version   1.0.0
 */

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// Merge language data
$lang = array_merge($lang, array(
    'TELEGRAM'         => 'Telegram',
    'TELEGRAM_EXPLAIN' => 'Hier kannst du deinen Telegram Benutzernamen eingeben (nicht deine Telefonnummer).',
    'TELEGRAM_CONTACT' => 'Mit Telegram kontaktieren',
));
