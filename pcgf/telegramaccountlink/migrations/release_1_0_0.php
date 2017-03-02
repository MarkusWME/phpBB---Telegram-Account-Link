<?php

/**
 * @author    MarkusWME <markuswme@pcgamingfreaks.at>
 * @copyright 2017 MarkusWME
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace pcgf\telegramaccountlink\migrations;

use phpbb\db\migration\migration;

/** version 1.0.0 */
class release_1_0_0 extends migration
{
    /**
     * Function that check if the extension is effectively installed
     *
     * @access public
     * @since  1.0.0
     *
     * @return bool If the extension is effectively installed
     */
    public function effectively_installed()
    {
        return isset($this->config['pcgf_telegramaccountlink']);
    }

    /**
     * Function for building the dependency tree
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Array for building the dependency tree
     */
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v31x\v311');
    }

    /**
     * Function that updates extension data
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Array that defines which data should be updated
     */
    public function update_data()
    {
        return array(
            array('config.add', array('pcgf_telegramaccountlink', 1)),
            array('custom', array(array($this, 'insert_profile_field_data'))),
        );
    }

    /**
     * Function that inserts the Telegram profile field data
     *
     * @access public
     * @since  1.0.0
     */
    public function insert_profile_field_data()
    {
        $query = 'SELECT MAX(field_order) + 1 AS field_order
            FROM ' . PROFILE_FIELDS_TABLE;
        $result = $this->db->sql_query($query);
        $insert_data = array(
            'field_name'          => 'telegram',
            'field_type'          => 'profilefields.type.string',
            'field_ident'         => 'telegram',
            'field_length'        => '15',
            'field_minlen'        => '5',
            'field_maxlen'        => '30',
            'field_novalue'       => '',
            'field_default_value' => '',
            'field_validation'    => '[\p{Lu}\p{Ll}0-9_]+',
            'field_required'      => 0,
            'field_show_on_reg'   => 0,
            'field_hide'          => 0,
            'field_no_view'       => 0,
            'field_active'        => 1,
            'field_order'         => $this->db->sql_fetchfield('field_order', false, $result),
            'field_show_profile'  => 1,
            'field_show_on_vt'    => 1,
            'field_show_novalue'  => 0,
            'field_show_on_pm'    => 1,
            'field_show_on_ml'    => 1,
            'field_is_contact'    => 1,
            'field_contact_desc'  => 'TELEGRAM_CONTACT',
            'field_contact_url'   => 'https://telegram.me/%s',
        );
        $this->db->sql_freeresult($result);
        $query = 'INSERT INTO ' . PROFILE_FIELDS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $insert_data);
        $this->db->sql_query($query);
        $field_id = $this->db->sql_nextid();
        $query = 'SELECT lang_id
            FROM ' . LANG_TABLE;
        $result = $this->db->sql_query($query);
        $insert_data = array();
        while ($id = $this->db->sql_fetchrow($result))
        {
            array_push($insert_data, array(
                'field_id'           => $field_id,
                'lang_id'            => $id['lang_id'],
                'lang_name'          => 'TELEGRAM',
                'lang_explain'       => 'TELEGRAM_EXPLAIN',
                'lang_default_value' => '',
            ));
        }
        $this->db->sql_freeresult($result);
        $this->db->sql_multi_insert(PROFILE_LANG_TABLE, $insert_data);
    }

    /**
     * Function that reverts extension data
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Array that defines how data should be removed
     */
    public function revert_data()
    {
        return array(
            array('custom', array(array($this, 'remove_profile_field_data'))),
        );
    }

    /**
     * Function that removes the Telegram profile field data
     *
     * @access public
     * @since  1.0.0
     */
    public function remove_profile_field_data()
    {
        $query = 'SELECT field_id
            FROM ' . PROFILE_FIELDS_TABLE . '
            WHERE field_ident = "telegram"';
        $result = $this->db->sql_query($query);
        $query = 'DELETE FROM ' . PROFILE_LANG_TABLE . ' WHERE field_id = ' . $this->db->sql_fetchfield('field_id', false, $result);
        $this->db->sql_freeresult($result);
        $this->db->sql_query($query);
        $query = 'DELETE FROM ' . PROFILE_FIELDS_TABLE . ' WHERE field_ident = "telegram"';
        $this->db->sql_query($query);
    }

    /**
     * Function for updating the database schema
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Array that defines how the schema should be updated
     */
    public function update_schema()
    {
        return array(
            'add_columns' => array(
                PROFILE_FIELDS_DATA_TABLE => array(
                    'pf_telegram' => array('VCHAR:255', null),
                ),
            ),
        );
    }

    /**
     * Function for reverting the database schema
     *
     * @access public
     * @since  1.0.0
     *
     * @return array Array that defines how the schema should be reverted
     */
    public function revert_schema()
    {
        return array(
            'drop_columns' => array(
                PROFILE_FIELDS_DATA_TABLE => array(
                    'pf_telegram',
                ),
            ),
        );
    }
}
