<?php

/*    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

     */

if (!defined("IN_MYBB"))
{
    die("There was once a wise man named Lorem Ipsum. He was somewhat of an enigma. Nobody has met, nor understood this man. However, he traveled across the great Pacific Ocean long ago, landed in silicon valley, and became influencial across nearly every website ever made. <br /><br /> Experts have yet to figure out the mystery of his prevalence particularly for <i>development</i> websites. His presense is sorely missed by most developers of production systems, but it seems most users do not seem to care. It's an unusual and strange mystery.<br /><br /> Lorem Ipsum has been missing for four decades. If you find any clues, please email us at investigations@loremipsum.example.com."); // direct access to this file not allowed.
}

global $templatelist;
if (isset($templatelist))
{
    $templatelist .= ',';
    $templatelist .= 'bam_announcement,bam_announcement_container';
}

// Create hooks.
global $mybb;
if (isset($mybb->settings['bam_enabled']) && $mybb->settings['bam_enabled'] == 1)
{
    // Add hooks if BAM is enabled.
    if ($mybb->settings['bam_compatibility_mode'] != 1)
    {
        $plugins->add_hook("global_intermediate", "bam_announcements");
    }
    else
    {
        $plugins->add_hook("global_intermediate", "compatibility_BAM_announcements_setvariable"); // Bug fix for servers that display PHP notices.
        $plugins->add_hook("pre_output_page", "bam_announcements_compatibility");
    }
}

if (defined('IN_ADMINCP'))
{
    $plugins->add_hook('admin_config_settings_begin', 'bam_settings');
    $plugins->add_hook('admin_config_settings_change_commit', 'bam_build_css_files');
    $plugins->add_hook("admin_config_menu", "bam_config_menu");
    $plugins->add_hook("admin_config_action_handler", "bam_confighandler");
    $plugins->add_hook("admin_config_permissions", "bam_admin_config_permissions");
}


// BAM is incompatibile with the Google SEO function at this time. A stub function is included
// if experienced developers would like to help extend BAM's functionality. Uncomment this hook to enable this.
// $plugins->add_hook("bam_replace_urls", "bam_google_SEO_rewrites");

function bam_info()
{
    global $lang, $mybb, $cache;
    $lang->load('bam');
    $desc = $lang->bam_desc;

    // BAM has an in-place upgrader, which checks whether BAM requires database updates after the new version is uploaded.
    // If we detect that BAM 2.0's files have been uploaded without running the upgrade script yet...
    // ... We display a notice to the administrator, along with a one-click link to run the upgrade.
    // See /inc/plugins/bam/bam_upgrade.php for more information about how this works.

    // No need to check if we've upgraded the DB if we're not installed.
    if (bam_is_installed())
    {
        if (!bam_is_updated())
        {
            $desc = $lang->bam_info_upgrade . "<br />"; // Display link to database updater script
            $activePlugins = $cache->read("plugins");

            // Because bam_upgrade() launches from within /admin/modules/config/bam.php, we must make sure we are activated first.
            if (in_array("bam", $activePlugins['active']))
            {
                $desc = $lang->bam_info_upgrade_ready;
                $desc .= "<br /><b><a href='index.php?module=config-bam&action=upgrade&post_key=" . $mybb->post_code . "'>" . $lang->bam_upgrade_link_text_plugins_panel . "</a></b>";
            }
        }
    }

    return array(
        'name'          => $lang->bam_title,
        'description'   => $desc,
        'website'       => 'https://github.com/Darth-Apple/BAM',
        'author'        => 'Darth Apple',
        'authorsite'    => 'http://www.makestation.net',
        'version'       => '2.1',
        'codename'      => 'bam',
        "compatibility" => "18*"
    );
}

function bam_install()
{
    global $db, $lang;
    $lang->load('bam');
    if (!$db->table_exists('bam'))
    {

        $db->query(
            "CREATE TABLE " . TABLE_PREFIX . "bam (
                `PID` int unsigned NOT NULL auto_increment,
                `announcement` varchar(1024) NOT NULL DEFAULT '',
                `class` varchar(40) NOT NULL DEFAULT 'yellow',
                `link` varchar(160) DEFAULT '',
                `active` int unsigned NOT NULL DEFAULT 1,
                `disporder` INT NOT NULL DEFAULT 1,
                `groups` varchar(128) DEFAULT '1, 2, 3, 4, 5, 6',
                `date` int(10) NOT NULL,
                `pinned` INT UNSIGNED DEFAULT 0,
                `global` INT UNSIGNED DEFAULT 0,
                `random` INT UNSIGNED DEFAULT 0,
                `additional_display_pages` VARCHAR(512) DEFAULT NULL,
                `forums` VARCHAR(256) DEFAULT NULL,
                  PRIMARY KEY (PID)
                ) ENGINE=MyISAM
                " . $db->build_create_table_collation() . ";"
        );

        $inserts = array(
            'announcement' => $db->escape_string($lang->bam_welcome),
            'date' => (int) time(),
            'class' => 'yellow',
            'disporder' => 1,
            'link' => ''
        );
        $db->insert_query('bam', $inserts);
    }

    $templates = array(
        'bam_announcement' => '<div class="{$bam_unsticky} {$class}" id="announcement-{$bcprefix}{$announcement_id}">
    {$announcement}<span class="bam_date">{$date}</span><span class=\'close_bam_announcement {$display_close}\'>x</span>
</div>',
        'bam_announcement_container' => '<div class="bam_wrapper">
    <div class="bam_announcements {$slidedown}">
        {$announcements}
    </div>
</div>'
    );

    // Insert the templates into the database.
    foreach ($templates as $title => $template_new)
    {
        $template = array(
            'title' => $db->escape_string($title),
            'template' => $db->escape_string($template_new),
            'sid' => '-1',
            'dateline' => TIME_NOW,
            'version' => '1800'
        );
        $db->insert_query('templates', $template);
    }

    // Creates settings for BAM.
    $query = $db->simple_select('settinggroups', 'MAX(disporder) AS disporder');
    $disporder = (int)$db->fetch_field($query, 'disporder');

    $setting_group = array(
        'name' => 'bam',
        'title' => $db->escape_string($lang->bam_title),
        'description' => $db->escape_string($lang->bam_desc),
        'isdefault' => 0
    );

    $setting_group['disporder'] = ++$disporder;

    $gid = (int)$db->insert_query('settinggroups', $setting_group); // inserts new group for settings into the database.

    $cookiePrefix = rand(1, 999999);

    $settings = array(
        'enabled' => array(
            'optionscode' => 'yesno',
            'value' => 1
        ),
        'advanced_mode' => array(
            'optionscode' => 'onoff',
            'value' => 0
        ),
        'round_announcements' => array(
            'optionscode' => 'yesno',
            'value' => 0
        ),
        'enable_dismissal' => array(
            'optionscode' => 'select \n1= ' . $db->escape_string($lang->bam_dismissal_savecookie) . '\n3= ' . $db->escape_string($lang->bam_dismissal_savecookie_useronly) . '\n2= ' . $db->escape_string($lang->bam_dismissal_closeonly) . '\n0= ' . $db->escape_string($lang->bam_dismissal_disable),
            'value' => '1'
        ),
        'dismissal_days' => array(
            'optionscode' => 'numeric \nmin=1',
            'value' => '30'
        ),
        'slidedown_enable' => array(
            'optionscode' => 'yesno',
            'value' => 1
        ),
        'date_enable' => array(
            'optionscode' => 'yesno',
            'value' => 1
        ),
        'random' => array(
            'optionscode' => 'onoff',
            'value' => 1
        ),
        'random_dismissal' => array(
            'optionscode' => 'onoff',
            'value' => 0
        ),
        'random_max' => array(
            'optionscode' => 'numeric \nmin=1',
            'value' => '1'
        ),
        'random_group' => array(
            'optionscode' => 'groupselect',
            'value' => '-1'
        ),
        'index_page' => array(
            'optionscode' => 'text',
            'value' => 'index.php'
        ),
        'custom_css' => array(
            'optionscode' => 'textarea',
            'value' => '/* Replace this field with any custom CSS classes. */'
        ),
        'cookie_id_prefix' => array(
            'optionscode' => 'numeric',
            'value' => $cookiePrefix
        ),
        'compatibility_mode' => array(
            'optionscode' => 'onoff',
            'value' => 0
        )
    );

    $disporder = 0;

    // insert settings to the database.
    foreach ($settings as $name => $setting)
    {
        $name = "bam_{$name}";

        $setting['name'] = $db->escape_string($name);

        $lang_var_title = $name;
        $lang_var_description = "{$name}_desc";

        $setting['title'] = $db->escape_string($lang->{$lang_var_title});
        $setting['description'] = $db->escape_string($lang->{$lang_var_description});
        $setting['disporder'] = $disporder;
        $setting['gid'] = $gid;

        $db->insert_query('settings', $setting);
        ++$disporder;
    }
    rebuild_settings();

    bam_build_css_files(true);
}

function bam_is_installed()
{
    global $db;
    if ($db->table_exists('bam'))
    {
        return true;
    }
    return false;
}

// Uninstallation removes templates and drops the database table.
function bam_uninstall()
{
    global $db;
    $info = bam_info();

    // Delete the table.
    if ($db->table_exists('bam'))
    {
        $db->drop_table('bam');
    }

    // Remove old templates.
    $db->delete_query('templates', "title LIKE 'bam_%'");

    // Clear out old settings.
    $db->delete_query("settinggroups", "name='bam'");
    $db->delete_query("settings", "name LIKE 'bam_%'");
    rebuild_settings();
}

// Activate: Create template modifications required for BAM to work.
function bam_activate()
{
    require MYBB_ROOT . '/inc/adminfunctions_templates.php';
    find_replace_templatesets('header', '#{\$awaitingusers}#', '{$awaitingusers} <!-- BAM -->{$bam_announcements}<!-- /BAM -->');
    change_admin_permission("config", "bam_announcements_admin_permissions", 1);
}

// Reverse template modifications.
function bam_deactivate()
{
    require MYBB_ROOT . '/inc/adminfunctions_templates.php';
    find_replace_templatesets('header', '#\<!--\sBAM\s--\>(.+)\<!--\s/BAM\s--\>#is', '', 0);
    change_admin_permission("config", "bam_announcements_admin_permissions", -1);
}

function bam_settings()
{
    global $lang;
    $lang->load('bam');
}

// This function manually checks if its database has been updated. Use bam_is_updated_noquery() for most cases.
// This is used to display the upgrade script link in the plugin's description if an update is required.

function bam_is_updated()
{
    global $db;
    $query = $db->query("SHOW COLUMNS FROM " . TABLE_PREFIX . "bam LIKE 'random';");
    if ($db->fetch_array($query))
    {
        return true;
    }
    return false;
}

// This function checks if BAM is updated without the use of an additional query.
// This does not work on the plugin info page, but saves a query everywhere else!

function bam_is_updated_noquery()
{
    global $mybb;
    return (isset($mybb->settings['bam_advanced_mode']) && isset($mybb->settings['bam_enable_dismissal']));
}

// Manage admin permissions (new in BAM 2.0)!
function bam_admin_config_permissions(&$admin_permissions)
{
    global $lang;
    $admin_permissions['bam_announcements_admin_permissions'] = $lang->bam_admin_permissions;
}


// Primary BAM announcements function. Parses announcements on forum pages.

function bam_announcements($compatibility = null)
{
    global $mybb, $db, $templates, $bam_announcements, $lang, $plugins; //, $theme; //, $bam_announcement_container;

    require_once MYBB_ROOT . '/inc/class_parser.php';
    $parser = new postParser();

    // Determine whether BAM's settings allow HTML. New setting in BAM 2.0. Bug fix from BAM 1.
    $allowHTML = 0;
    if ($mybb->settings['bam_advanced_mode'] == 1)
    {
        $allowHTML = "yes";
    }

    // Use the parser for what it does best.
    // It supports BBcode, automatic newline to break codes, MyCode, and optionally, HTML.
    $parser_options = array(
        'allow_html' => $allowHTML,
        'allow_mycode' => 'yes',
        'allow_smilies' => 'yes',
        'allow_imgcode' => 'yes',
        'filter_badwords' => '',
        'nl2br' => 'yes'
    );

    $class_select = array('green', 'yellow', 'red', 'blue', 'silver', 'magenta', 'orange'); // list of programmed BAM classes.
    // OLD: ORDER BY pinned DESC, disporder ASC, PID ASC
    $query = $db->query("
        SELECT *
        FROM " . TABLE_PREFIX . "bam
        WHERE `active` = 1
        ORDER BY pinned DESC, PID ASC
    ");

    $data = array();
    $count = 0;
    $announcement = '';
    $announcements = '';
    $unpinned_ids = array();
    $announcementCount = 0; // used to cache templates.

    $class_select = $plugins->run_hooks("bam_announcements_begin_classes", $class_select);

    // Fetch announcements from database and render them.
    while ($querydata = $db->fetch_array($query))
    {

        // Parse directives that can determine specific conditions for displaying announcments.
        $tagParser = bam_build_directives($querydata['announcement']);

        // Only parse the announcement if it is allowed to be displayed.
        if (bam_display_conditions($querydata, $tagParser[1]))
        {
            $announcement = $tagParser[0]; // announcement.

            // Make the announcement a link if it has a URL field defined.
            if (!empty($querydata['link']))
            {
                $announcement = '[url=' . htmlspecialchars($querydata['link'], ENT_QUOTES) . ']' . htmlspecialchars($querydata['announcement'], ENT_QUOTES) . "[/url]";
            }

            // Run announcements through the post parser to process BBcode, images, HTML (if enabled), etc.
            $announcement = $parser->parse_message(html_entity_decode($announcement), $parser_options);

            // Get announcement ID for cookies. Used for saving dismissed announcements.
            $announcement_id = (int) $querydata['PID'];
            $bcprefix = (int) $mybb->settings['bam_cookie_id_prefix']; // Used to reset dismissals if BAM is reinstalled.

            $announcement = bam_parse_user_variables($announcement, $querydata['link']);
            $dismissClass = bam_build_dismiss_class($querydata);

            $display_close = $dismissClass[0];
            $bam_unsticky = $dismissClass[1];
            $announcementTemplate = 'bam_announcement';

            $class = 'bam_announcement ' . htmlspecialchars($querydata['class'], ENT_QUOTES); // parse class/style
            if ($mybb->settings['bam_round_announcements'])
            {
                $class = $class . " bam_round";
            }

            $forums = $querydata['forums']; // fetch forum list, if enabled.

            $date = bam_parse_date($querydata);

            // Save an array of unpinned announcements. This allows us to re-order and display these later without running another query.

            $data[$count]['date'] = $date;
            $data[$count]['themesEnabled'] = $tagParser[1]['themesEnabled'];
            $data[$count]['altForums'] = $tagParser[1]['altForums'];
            $data[$count]['languagesEnabled'] = $tagParser[1]['languagesEnabled'];
            $data[$count]['class'] = $class;
            $data[$count]['display_close'] = $display_close;
            $data[$count]['template'] = $tagParser[1]['template'];
            $data[$count]['forums'] = $forums; // list of forums enabled, if set.
            $data[$count]['bam_unsticky'] = $bam_unsticky;
            $data[$count]['announcement'] = $announcement; // Parsed text for the announcement.
            $data[$count]['PID'] = (int) $announcement_id; // Used to create an element ID. Needed for javascript cookies.
            $data[$count]['additional_display_pages'] = $querydata['additional_display_pages']; // Additional functionality in BAM 2.0. Used for advanced mode.
            $data[$count]['random'] = (int) $querydata['random'];    // - added functionality in BAM 2.0
            $data[$count]['global'] = (int) $querydata['global'];   // - added functionality in BAM 2.0

            // Detect if BAM is running on BAM 1's database or on BAM 2's database.
            // This allows BAM 2.0 to render announcements properly even if the database hasn't been migrated.
            // This is required because BAM must be activated to upgrade. This prevents interuptions on the forum!

            if (!bam_is_updated_noquery())
            {
                if (($querydata['pinned'] == 0) && $mybb->settings['bam_random'] == 1)
                {
                    $unpinned_ids[] = $count;
                    // $total_unpinned++;
                }
                if (($querydata['pinned'] == 1) || ($mybb->settings['bam_random'] == 0))
                {
                    eval("\$announcements .= \"" . $templates->get("bam_announcement") . "\";");
                }
            }
            // Render announcements normally. BAM 2.0 has been properly installed and upgraded.
            else
            {
                // Are we trying to render a random mode announcement?
                if (($mybb->settings['bam_random'] == 1) && ($querydata['random'] == 1))
                {
                    $unpinned_ids[] = $count;
                }
                // Are we rendering a normal announcement?
                else
                {
                    $data[$count] = $plugins->run_hooks("bam_announcements_render", $data[$count]);
                    eval("\$announcements .= \"" . $templates->get($tagParser[1]['template']) . "\";");
                }
            }

            $count++;
        }
    }

    // Remaining announcements that passed bam_display_conditions() are random. Shuffle these and select one randomly.
    $count_unpinned = 0;
    shuffle($unpinned_ids); // place unpinned announcements into a random order.
    if (bam_display_permissions($mybb->settings['bam_random_group']))
    {
        foreach ($unpinned_ids as $ID)
        {
            // if (($count_unpinned >= $total_unpinned) || ($count_unpinned >= $mybb->settings['bam_random_max'])) {
            if (($count_unpinned >= count($unpinned_ids)) || ($count_unpinned >= $mybb->settings['bam_random_max']))
            {
                break;
            }
            $date = $data[$ID]['date'];
            $announcement = $data[$ID]['announcement'];
            $class = $data[$ID]['class'];
            $announcement_id = $data[$ID]['PID'];

            // handle whether random announcements can be closed:

            if ($mybb->settings['bam_random_dismissal'] == 1)
            {
                $bam_unsticky = 'bam-unsticky';
                $display_close = 'bam-close-notification'; // alternative close function used in javascript.
            }
            else
            {
                // Dismissals of random announcements are disabled. Make sure we don't display close button.
                $bam_unsticky = '';
                $display_close = 'bam_nodismiss';
            }
            eval("\$announcements .= \"" . $templates->get($data[$ID]['template']) . "\";");

            $count_unpinned++;
        }
    }

    if (!empty($announcements))
    {
        bam_headerincludes();

        // Enable or disable jquery slidedown effect.

        $slidedown = "";
        if ($mybb->settings['bam_slidedown_enable'] == 1)
        {
            $slidedown = "bam_slidedown";
        }

        $bam_announcements = ""; // Bug fix for weird servers.

        eval('$bam_announcements = "' . $templates->get('bam_announcement_container') . '";');
    }
    $bam_announcements = $plugins->run_hooks("bam_announcements_end", $bam_announcements);
    // Check if we are using the pre_output_page hook instead.
    // This forces announcements into the page and guesses if it can't find the variable.
    // This gives less control on where announcements get posted, but can get BAM working without further template modifications on themes where the activation modifications fail.
    if ($compatibility != null)
    {
        return $bam_announcements;
    }
}

// Compatibility function that uses pre_output_page hook instead. This may work better for certain
// forums or themes, but is disabled by default as it imposes a slight performance penalty.
function bam_announcements_compatibility(&$page)
{

    $announcements = bam_announcements(1); // fetch announcements as normal.
    $bam_page = strtr($page, array('<!-- BAM -->' => $announcements)); // add to page output

    // Check if the replacement failed. If so, try to guess on where to put announcements.
    if ($bam_page == $page)
    {
        // MyBB, by default, adds comments to the output page at template start/ends. Unless this setting
        // is disabled, BAM will always be able to guess on where to put announcements (even without the variable).
        $bam_page = strtr($page, array('<!-- start: nav -->' => $announcements . ' <!-- start: nav -->'));
    }
    return $bam_page;
}

// This is a bug fix for servers that display notices. In compatibility mode, the template variable
// $bam_announcements is unset. We set it explicitely here to avoid the notice.
function compatibility_BAM_announcements_setvariable()
{
    global $bam_announcements;
    $bam_announcements = "";
    return;
}

function bam_build_css_files($initial = false)
{
    global $mybb, $db, $gid;

    $query = $db->simple_select('settinggroups', 'gid', "name = 'bam'", array('limit' => 1));
    $sgroup = (int)$db->fetch_field($query, 'gid');

    if($sgroup != $gid && $initial !== true)
    {
        return false;
    }

    $stylesheet = @file_get_contents(__DIR__ . "/bam/bam_default.css");
    if (!$stylesheet)
    {
        return false;
    }

    require_once MYBB_ROOT . $mybb->config['admin_dir'] . '/inc/functions_themes.php';

    $stylesheet = trim($stylesheet);

    if (!empty($mybb->settings['bam_custom_css']))
    {
        $stylesheet = $stylesheet . "\n" . trim($mybb->settings['bam_custom_css']);
    }

    $stylesheet_min = minify_stylesheet($stylesheet);

    @file_put_contents(__DIR__ . "/bam/bam.css", $stylesheet);
    @file_put_contents(__DIR__ . "/bam/bam.min.css", $stylesheet_min);

    unset($stylesheet, $stylesheet_min);

    return true;
}

function bam_headerincludes()
{
    global $mybb, $stylesheets;

    $bam_css_name = 'bam.css';
    if ($mybb->settings['minifycss'])
    {
        $bam_css_name = str_replace('.css', '.min.css', $bam_css_name);
    }

    $bam_css_path = 'inc/plugins/bam/' . $bam_css_name;

    if (file_exists(MYBB_ROOT . $bam_css_path))
    {
        $stylesheets .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$mybb->settings['bburl']}/{$bam_css_path}\" />\n";
    }

    // Set some variables that we use in the javascript to create the cookies.
    $bam_cookie_expire = (int) $mybb->settings['bam_dismissal_days'] * 24 * 60 * 60 * 1000;
    $bam_cookie_path = $mybb->settings['cookiepath'];

    $add_js_header = '<script type="text/javascript">
<!--
    $(document).ready(function(){
        $(".bam_slidedown").delay(100).slideDown(325);
    });
    // Allow me to give credit. This was great:  https://lifeofadesigner.com/javascript/hide-dismissed-notifications-with-jquery-and-cookies

    $(document).ready(function () {
        if (GetCookie("dismissed-notifications")) {
            $(GetCookie("dismissed-notifications")).hide();
        }
        $(".dismiss-notification").click(function () {
            var alertId = $(this).closest(".bam-unsticky").attr("id");
            var dismissedNotifications = GetCookie("dismissed-notifications") + ",#" + alertId;
            $(this).closest(".bam-unsticky").fadeOut("slow");
            SetCookie("dismissed-notifications",dismissedNotifications.replace("null,","")) //update cookie
        });

        // Same as above, but close only. Don\'t set a cookie.
        $(".bam-close-notification").click(function () {
            var alertId = $(this).closest(".bam-unsticky").attr("id");
            var dismissedNotifications = GetCookie("dismissed-notifications") + ",#" + alertId;
            $(this).closest(".bam-unsticky").fadeOut("slow");
        });

    function SetCookie(sName, sValue) {
        const d = new Date();
        d.setTime(d.getTime() + ' . $bam_cookie_expire . ');
        let expires = "expires="+ d.toUTCString();
        document.cookie = sName + "=" + escape(sValue) + ";" + expires + ";path=' . $bam_cookie_path . '";
    }

    function GetCookie(sName) {
        var aCookie = document.cookie.split("; ");
        for (var i=0; i < aCookie.length; i++) {
            var aCrumb = aCookie[i].split("=");
            if (sName == aCrumb[0])
                return unescape(aCrumb[1]);
        }
        return null;
    }
    });
// -->
</script>';

    $stylesheets .= $add_js_header . "\n";
}

function bam_parse_date($querydata)
{
    global $mybb;

    if ($mybb->settings['bam_date_enable'] == 1)
    {
        // Technically, we should have some sort of plugin setting for the date since we aren't using the MyBB default, but to save space in announcements, this plugin doesn't display the year unless necessary. This solution seems to be working well enough for now. Perhaps a future version will "fix" this issue.
        if (date("Y") != my_date('Y', $querydata['date']))
        {
            // Not the current year, display the year.
            return ('(' . my_date('F d, Y', htmlspecialchars($querydata['date'], ENT_QUOTES)) . ')');
        }
        else
        {
            // We are on the current year, so we don't display the year on the announcement.
            return ('(' . my_date('F d', htmlspecialchars($querydata['date'], ENT_QUOTES)) . ')');
        }
    }
    return '';
}


function bam_build_dismiss_class($querydata)
{
    global $mybb, $plugins;
    // If the announcement is not stickied and dismissals are enabled, set whether dismissal closes the announcement permanently or temporarily.
    // If the announcement is stickied, never allow dismissals.
    if (($querydata['pinned'] == 0) && (int) $mybb->settings['bam_enable_dismissal'] > 0)
    {
        $bam_unsticky = 'bam-unsticky';

        // Set dismissals are permanent.
        if ((int) $mybb->settings['bam_enable_dismissal'] == 1)
        {
            $display_close = 'dismiss-notification';
        }

        // Set dismissals as temporary. When dismissed, the announcement returns on the next page load.
        else if ((int) $mybb->settings['bam_enable_dismissal'] == 2)
        {
            $display_close = 'bam-close-notification';
        }

        // BAM is set to dismiss with a cookie, but only if the user is logged in. This is the default setting.
        else if ((int) $mybb->settings['bam_enable_dismissal'] == 3)
        {
            if (!empty($mybb->user['uid']))
            {
                $display_close = 'dismiss-notification'; // close and dismiss with cookie.
            }
            else
            {
                $display_close = 'bam-close-notification'; // user is a guest. Close only.
            }
        }
        // Invalid value defined in setting. Handle this by disabling dismissal.
        else
        {
            $display_close = 'bam_nodismiss';
        }

        // If the announcement is "sticky," never show the dismissal button.
    }
    else
    {
        $display_close = 'bam_nodismiss';
        $bam_unsticky = '';
    }
    $bundleDismissals = array($display_close, $bam_unsticky);
    $bundleDismissals = $plugins->run_hooks("bam_build_dismissal", $bundleDismissals);
    return $bundleDismissals;
}

// This function checks the user's permissions, and determines if the user's group is in $display_groups
// Returns true to display the announcement. False if the user is not permitted to view it.

function bam_display_permissions($display_groups)
{
    global $mybb;

    // No need to check for permissions if no groups are allowed.
    if (empty($display_groups))
    {
        return false;
    }

    // No need to check for permissions if all groups are allowed.
    if ($display_groups == '-1')
    {
        return true;
    }

    // Create an array of all usergroups that the current user is a member of.
    $usergroup = $mybb->user['usergroup'];
    $allowed = explode(',', $display_groups);
    $groups = array();
    $groups[0] = (int)$usergroup;
    $add_groups = explode(',', $mybb->user['additionalgroups']);
    $count = 1;
    foreach ($add_groups as $new_group)
    {
        $groups[$count] = $new_group;
        $count++;
    }

    // Check if the user is in a member of an allowed group for this announcement. Return True if permitted.
    foreach ($allowed as $allowed_group)
    {
        if (in_array($allowed_group, $groups))
        {
            return true;
        }
    }
    // User is not in a valid usergroup to view this announcement. Return false.
    return false;
}

// Checks ALL display conditions for whether an announcement in $querydata, with directives $directives, should be displayed.
// This calls checkAnnouncementDisplay for global/index/special page checks, and also checks usergroup permissions and directives

function bam_display_conditions($querydata, $directives)
{
    global $mybb, $plugins;

    // We are running on BAM 1's database and templates. Do a legacy check for whether to display the announcement.
    if (!bam_is_updated_noquery())
    {
        if (bam_display_permissions($querydata['groups']) && (global_display($querydata['pinned'])))
        {
            return true;
        }
        return false;
    }

    // We are running on a properly updated BAM 2 database and template set. Enable additional features.
    else
    {
        if ($directives['disabled'])
        {
            return false;
        }

        // Plugin hooks: Set the $query['returnFalse'] element to create your own conditions.
        // Use standard BAM display checks for returning true. Create artificial return false conditions here.
        // If you need special return true conditions, modify other data within $querydata so that the announcement
        // is guaranteed to return true. Note that this isn't advisable unless you absolutely need to do this,
        // as it could potentially cause compatibility issues between multiple BAM plugins.

        $querydata = $plugins->run_hooks("bam_announcements_display_conditions", $querydata);
        if (isset($querydata['returnFalse']))
        {
            return false;
        }

        // New in BAM 2.0: Random announcements are no longer rendered as normal announcements if random mode is disabled.
        if (bam_display_permissions($querydata['groups']) && checkAnnouncementDisplay($querydata, $directives))
        {
            // Check if there are theme tags or langage tags.
            if (bamThemeEnabled($directives['themesEnabled']) && bamLanguageEnabled($directives['languagesEnabled']))
            {
                return true;
            }
        }
        return false;
    }
}

// New in BAM 2.0. Tags are now supported to enable announcements only for specific themes and languages.
// These preg_replace statements remove the tag itself once its value has been parsed.
// We only attempt to check for directives if we have [@ in an announcement. Performance optimization.

function bam_build_directives($announcement)
{
    $themesEnabled = 0;
    $languagesEnabled = 0;
    $announcementTemplate = 'bam_announcement';
    $disabled = false;
    $altForums = null; // For use in alternate, unofficial forum tag.

    if (strpos("-" . $announcement, '[@'))
    {
        $themesEnabled = bamExplodeThemes($announcement);
        // print("Themes Exploded: " . var_dump($themesEnabled) . "<br />");
        $languagesEnabled = bamExplodeLanguages($announcement);
        // $altForums = bamExplodeForumTag($announcement);
        $announcement = preg_replace('/\[@themes:([a-zA-Z0-9 ,_]*)\]/', "", $announcement);
        $announcement = preg_replace('/\[@languages:([a-zA-Z0-9 ,_]*)\]/', "", $announcement);

        // Parse a special directive that disables an announcement. Unofficial feature.
        if (strpos("-" . $announcement, '[@disabled]'))
        {
            $disabled = true;
        }

        // [2.1] This is an alternative, unofficial forumdisplay tag with custom functionality
        // Unlike the mainline setting, this disables display on newthread/reply pages.
        if (strpos("-" . $announcement, '[@forums:'))
        {
            $altForums = bamExplodeForumTag($announcement);
            $announcement = preg_replace('/\[@forums:([a-zA-Z0-9 ,_]*)\]/', "", $announcement);
        }

        // New tag: [@displayall]
        // Directive allows you to define a different template for this announcement. Useful if you need javascript in announcement.
        if (strpos("-" . $announcement, '[@template:'))
        {
            $announcementTemplate = bamExplodeTemplates($announcement);
            $announcement = preg_replace('/\[@template:([a-zA-Z0-9_ ]*)\]/', "", $announcement);
        }
    }

    $directives = array(
        'themesEnabled' => $themesEnabled,
        'languagesEnabled' => $languagesEnabled,
        'disabled' => $disabled,
        'altForums' => $altForums,
        'template' => $announcementTemplate,
    );

    return array($announcement, $directives);
}

// Function replaces deprecated global_display() in BAM 1.0.
// Checks if a specific announcement is enabled on the current page that the user is browsing.

function checkAnnouncementDisplay($announcement, $directives = array())
{
    global $mybb, $current_page, $plugins;

    // Plugins: Set $announcement['returnFalse] to any value to force this function to deny displaying an announcement.
    // If you need to force this function to return true, modify $announcement such that this function is guaranteed to return true.
    // Announcement is not returned. Only a true or false value to determine whether the announcement displays. You can do as you please here.

    $announcement = $plugins->run_hooks("bam_checkAnnouncementDisplay", $announcement);

    // [2.1] Unofficial template tag that overrides certain default behaviors of the mainline forum display tag.
    if (isset($directives['altForums']))
    {
        $announcement['forums'] = implode(',', $directives['altForums']);
    }

    if (isset($announcement['returnFalse']))
    {
        return false;
    }

    // Handle random mode announcements, which only display on the index page.
    if ($announcement['random'] == 1)
    {
        if (isIndexPage($announcement))
        {
            return true;
        }
        return false;
    }

    // Check if the user has defined an alternative page. If so, run the check to see if this page is valid.
    // If this alternative page is not valid, we don't display the page, regardless of whether it is global.

    if (($announcement['additional_display_pages'] != null))
    {
        return isAlternatePageValid($announcement);
    }

    // Check if announcement is in forum-display mode. (global = 2)
    // If so, we need to check if this announcement is in a forum ID that is enabled.
    else if (($announcement['forums'] != null)  && ($announcement['global'] == 2))
    {

        // Check if all forums are enabled. If so, enable the announcement.
        if (($announcement['forums'] == '*' || $announcement['forums'] == '-1') && ((int) $_GET['fid'] != null))
        {
            return true;
        }

        // User hasn't enabled announcement for every board. Check if the board we are on is in the list of enabled boards.
        else
        {
            $explodedForums = explode(',', $announcement['forums']);
            if (isset($mybb->input['fid']) && (in_array((int) $_GET['fid'], $explodedForums)))
            {

                // Mainline functionality
                if (!isset($directives['altForums']))
                {
                    return true;
                }
                // Execute alternate functionality (unofficial 2.1 feature)
                else
                {
                    return check_forumdisplay_denylist();
                }
            }
            else
            {
                // User is browsing a thread. Get FID and see if it matches.
                if (isset($mybb->input['tid']) && $mybb->input['tid'] != 0)
                {
                    $tid = (int) $mybb->input['tid'];
                    $fid = bam_TIDManager::bam_getFIDfromTID($tid);

                    if (in_array($fid, $explodedForums))
                    {
                        // Mainline functionality
                        if (!isset($directives['altForums']))
                        {
                            return true;
                        }
                        // Execute alternate functionality (unofficial 2.1 feature)
                        else
                        {
                            return check_forumdisplay_denylist();
                        }
                    }
                    else
                    {
                        return false;
                    }
                }

                // MyBB sometimes links to specific posts instead of threads. Get the TID and FID, and see if it matches.
                else if (isset($mybb->input['pid']) && $mybb->input['pid'] != null)
                {
                    $pid = (int) $mybb->input['pid'];
                    $tid = bam_TIDManager::bam_getTIDfromPID($pid);
                    $fid = bam_TIDManager::bam_getFIDfromTID($tid);

                    if (in_array($fid, $explodedForums))
                    {
                        // Mainline functionality
                        if (!isset($directives['altForums']))
                        {
                            return true;
                        }
                        // Execute alternate functionality (unofficial 2.1 feature)
                        else
                        {
                            return check_forumdisplay_denylist();
                        }
                    }
                    else
                    {
                        return false;
                    }
                }

                // Support announcements explicitely as well.
                else if (isset($mybb->input['aid']) && $mybb->input['aid'] != null)
                {
                    $aid = (int) $mybb->input['aid'];
                    $fid = bam_TIDManager::bam_getFIDfromAID($aid);

                    if (in_array($fid, $explodedForums))
                    {
                        // Mainline functionality
                        if (!isset($directives['altForums']))
                        {
                            return true;
                        }
                        // Execute alternate functionality (unofficial 2.1 feature)
                        else
                        {
                            return check_forumdisplay_denylist();
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                return false; // This board isn't enabled. Return false.
            }
        }
    }

    // We aren't on a custom alternative page or forum mode. So we will check if we are on the index page.
    // With no alternative page set: Announcements are always displayed on the index page, regardless of whether they are global, random, or otherwise.

    else if (isIndexPage($announcement))
    {
        return true; // this is the index page. No need to check for global announcement settings.
    }
    else if ($announcement['global'] == 1)
    {
        return true;
    }

    // This announcement can't be displayed under any conditions.
    // We aren't on the index, no forums match, the announcement isn't global, and we aren't on an alternative page. Return false.
    else
    {
        return false;
    }
}

// This function determines if the current page is considered an "index page" for the plugin.
// New in BAM 2.0: You can now have multiple comma delimited values for the index page.

function isIndexPage($otherPage = null)
{
    global $mybb, $current_page;

    if ($otherPage['additional_display_pages'] == null)
    {
        $indexPage = $mybb->settings['bam_index_page'];
    }

    // Get an array of all pages BAM considers the index page. BAM 2.0 now allows more than one page to be set as an index.
    $explodedIndexPage = explode(',', $indexPage);
    $processed_indexValues = array_map('trim', $explodedIndexPage);

    if (in_array($current_page, $processed_indexValues))
    {
        return true;
    }
    return false;
}


// New in BAM 2.0: Determines if an announcement is set to display on the current page that the user has visited.
// Only called if the additional_url_parameters setting is set with a value.
// Note that there is an alternative parser for specific showthread.php links involving a PID. See bam_parsePIDURLs()

function isAlternatePageValid($announcement)
{
    global $mybb, $cache, $current_page, $additional_page_parameters;

    // [2.1] This adds an internal interface allowing a future extension to override the default URL parser.
    // This will ONLY run if both "bam_google_seo_compatibility" and "google_seo" are installed and activated.
    // ("bam_google_seo_compatibility" does not exist yet. Will be written in the future to bridge compatibility problems.)
    if (function_exists("bam_google_seo_compatibility"))
    {
        $activePlugins = $cache->read("plugins");
        // Make sure both plugins are actually activated (reject overrides from untrusted sources)
        if (in_array("google_seo", $activePlugins['active']) && in_array("bam_google_seo_extension", $activePlugins['active']))
        {
            return bam_google_seo_compatibility($announcement);
        }
    }

    // Developers: If you are using this plugin and your URL settings are not being accepted, you can add
    // new acceptable parameteres here. However, please be aware that this is a whitelist that is intended
    // to prevent unexpected or insecure behavior. This setting was explicitely ommitted on the ACP for
    // this reason. Please be mindful and add parameters as needed, but do not remove the whitelist for your forum.
    $additional_page_parameters = array('fid', 'action', 'uid', 'tid', 'gid', 'aid');

    $explodedPages = explode(',', $announcement['additional_display_pages']);
    $processedPages = array_map('trim', $explodedPages);
    $acceptPage = false;

    // This parameter allows multiple URLs to be set. Check for each URL that is given.
    foreach ($processedPages as $additional_display_page)
    {

        // Handle search engine friendly URLs. We rewrite the URLs in PHP with regex!
        // Note that this only supports MyBB's default htaccess settings.
        // Custom SEO plugins require regex to be updated in bam_reverse_rewrite();
        $additional_display_page = bam_reverse_rewrite($additional_display_page);

        // Explicitely block improperly formatted URLs (e.g. forumdisplay.php?action=something?tid=somethingelse)
        if (substr_count($additional_display_page, '?') > 1)
        {
            break;
        }

        // This plugin explicitely parses the URL given by the announcement's settings to extract only the file name.
        // This functionality should not be reverted. Otherwise, rogue URLs (index.php?fid=forumdisplay.php) could cause
        // this plugin to display on pages that it has not been designed to display on.

        $url_parameters = parse_url($additional_display_page);
        $announcementFileName = basename($url_parameters['path']);

        // MyBB handles PIDs strangely and sometimes links to them instead of TIDs. Handle this specifically.
        // This calls an alternative URL parser ONLY when PIDs are detected instead of TIDs to handle this.

        if ($announcementFileName == "showthread.php" && $current_page == "showthread.php")
        {
            if ((isset($mybb->input['pid']) && ($mybb->input['pid'] != 0)) || (strpos($additional_display_page, "pid=")))
            {
                // if (true) {
                if (!bam_parsePIDURLs($additional_display_page))
                {
                    break; // Explicitely reject.
                }
                else
                {
                    $acceptPage = true;
                    break;
                }
            }
        }

        // First, we check to see if we are on the correct PHP file/page (e.g. index.php, forumdisplay.php, etc.)
        if ($announcementFileName == $current_page)
        {

            // By default, we assume that we found the required URL parameters. We then check to see if any do not match.
            $paramCheck = true;

            // Loop through each whitelisted parameter and check for mismatches.
            foreach ($additional_page_parameters as $parameter)
            {

                // We first check if the $_GET parameter we are currently checking exists on the page/URL the user is visiting.
                // If it does, we check to see if it matches the additional_page parameter's value.
                // [2.1 - switching to $mybb->input for better compatibility with third party plugins]

                if (isset($mybb->input[$parameter]) && !empty($mybb->input[$parameter]))
                {

                    // We found the parameter in the URL of this page. Get its value.
                    $paramValue = $_GET[$parameter];

                    // Next, we must check if the parameter was defined in the announcement's settings.
                    // If so, we check to see if it matches the URL that we are on.
                    // If it is not found, the announcement does not care about additional parameters that may exist. We ignore it.

                    if (strpos($additional_display_page, $parameter))
                    {

                        // Block out rogue URLs (e.g. forumdisplay.php?action=index.php_tid=3)
                        $paramSearchString1 = "?$parameter=" . $paramValue;
                        $paramSearchString2 = "&$parameter=" . $paramValue;

                        if (strpos($additional_display_page, $paramSearchString1) === false && strpos($additional_display_page, $paramSearchString2) === false)
                        {
                            $paramCheck = false;
                        }
                    }
                }

                // Check to see if an unset parameter is a part of the additional_display_pages setting. If so, reject the announcement.
                else
                {

                    // Scan additional_display_pages to see if the URL parameter exists in the setting. If so, reject the announcement.
                    $unsetURLParam = $parameter . '=';
                    if (strstr($additional_display_page, $unsetURLParam, false))
                    {
                        $paramCheck = false;
                    }
                }
            }

            // Check to see if we found a valid match within the announcement's settings for this page. If not, keep checking.
            if ($paramCheck == true)
            {
                $acceptPage = true;
                break; // We found a valid page. Not necessary to keep checking other pages.
            }
        }
    } // End loop for URLs.
    return $acceptPage;
}

// MyBB will occasionally (very oddly) link to the PID instead of the TID for specific showthread links.
// MyBB parses these normally and fetches the TID from within. BAM must do the same for these links.
// This parser runs INSTEAD of the normal parser if this is detected, and manually fixes this behavior.

function bam_parsePIDURLs($additional_display_page)
{
    global $db, $mybb;

    // *** First, we must get the PID/TID data from the announcement itself. ***

    // Check if additional_display_page has a TID set. If so, use this directly instead.
    if (strpos($additional_display_page, "tid="))
    {
        $matches = array();
        preg_match('/tid=[0-9]*/', $additional_display_page, $matches);
        if (isset($matches[0]))
        {
            $matches = strtr($matches[0], array('tid=' => ''));
            $displayTID = (int) $matches;
        }
    }

    // Additional_display_page provides a PID instead. Use this and convert to TID.
    else if (strpos($additional_display_page, "pid="))
    {
        $matches = array();
        preg_match('/pid=[0-9]*/', $additional_display_page, $matches);
        if (isset($matches[0]))
        {
            $matches = strtr($matches[0], array('pid=' => ''));
            $displayPID = (int) $matches;
        }
        $displayTID = bam_TIDManager::bam_getTIDfromPID($displayPID);
    }

    // additional_display_page provides neither. In such cases, announcement should display on all threads.
    // (additional_display_page is likely showthread.php. No further details are provided to check against).
    else
    {
        return true;
    }

    // *** Next, we must check the URL itself and get TID/PID information from the current page. ***

    // Check if the URL provides a TID. If so, we use this directly instead.
    if (isset($mybb->input['tid']) && $mybb->input['tid'] != 0)
    {
        $tid = (int) $mybb->input['tid'];
    }

    // URL contains a PID and no TID. we must convert any URL parameters to TIDs.
    else if (isset($mybb->input['pid']) && $mybb->input['pid'])
    {
        $pid = (int) $mybb->input['pid'];
        $tid = (int) bam_TIDManager::bam_getTIDfromPID($pid);
    }

    // MyBB never generates URLs in showthread.php without either a PID or a TID.
    // Reject announcement if such an invalid page is reached.
    else
    {
        return false;
    }

    // URL provided a PID or TID, but we couldn't find a valid thread associated with it.
    // Reject this announcement. Invalid URL.
    if ($tid == 0)
    {
        return false;
    }

    // Check if the TID we generated from the URL matches the TID we generated from the announcement.
    if ($tid == $displayTID)
    {
        return true;
    }
    return false;
}

/* VARIABLES, THEMES, DIRECTIVES, LANGUAGES */

// Returns whether the user is using a theme that is in $themes.
// Themes list is generated by bamExplodeThemes, which checks for the [@themes:1,2,3] tag.

function bamThemeEnabled($themes)
{
    global $mybb, $cache;
    // return true;
    if (!isset($mybb->user['style']))
    {
        $userTheme = $cache->read('default_theme');
        $userTheme = (int) $userTheme['tid'];
    }
    else
    {
        $userTheme = $mybb->user['style'];
    }
    if ($themes != null)
    {
        if (in_array($userTheme, $themes))
        {
            return true;
        }
        return false;
    }
    return true;
}

// Search the announcement's text for a theme tag. If so, return an array with a list of themes.

function bamExplodeThemes($announcementText)
{
    $matched_themes_raw = "";
    if (preg_match('/\[@themes:([a-zA-Z0-9 ,_]*)\]/', $announcementText, $matched_themes_raw))
    {
        $matched_themes_raw = strtr($matched_themes_raw[0], array('[@themes:' => '', ']' => ''));
        return array_map('trim', explode(',', $matched_themes_raw));
    }
    return null;
}


// Returns whether the user is using a language that is in $languages.
// Themes list is generated by bamExplodeThemes, which checks for the [@themes:english, espanol, etc] tag.

function bamLanguageEnabled($languages)
{
    global $mybb;

    // If the user is on the default language and this language is set for the announcement, display the announcement.
    if (!isset($mybb->user['language']) || $mybb->user['language'] == null)
    {
        if ($languages != null)
        {
            if (in_array($mybb->settings['bblanguage'], $languages))
            {
                return true;
            }
            // user is on default language, but announcement specifies a different language.
            return false;
        }
    }

    // Check if the user's board language matches an enabled language in the announcement
    if ($languages != null)
    {
        if (in_array($mybb->user['language'], $languages))
        {
            return true;
        }
        return false;
    }
    return true;
}

// Search the announcement's text for a theme tag. If so, return an array with a list of themes.

function bamExplodeLanguages($announcementText)
{
    $matched_languages_raw = "";
    if (preg_match('/\[@languages:([a-zA-Z0-9 ,_]*)\]/', $announcementText, $matched_languages_raw))
    {
        $matched_languages_raw = strtr($matched_languages_raw[0], array('[@languages:' => '', ']' => ''));
        $explodedLanguages = explode(',', $matched_languages_raw);
        return array_map('trim', $explodedLanguages);
    }
    return null;
}

// [2.1] Unofficial alternative forumdisplay tag with custom functionality.
// Unlike the mainline setting, this setting explicitely disables newreply/newthread display.
function bamExplodeForumTag($announcementText)
{
    $matched_forums_raw = "";
    if (preg_match('/\[@forums:([a-zA-Z0-9 ,_]*)\]/', $announcementText, $matched_forums_raw))
    {
        $matched_forums_raw = strtr($matched_forums_raw[0], array('[@forums:' => '', ']' => ''));
        $explodedForums = explode(',', $matched_forums_raw);
        return array_map('trim', $explodedForums);
    }
    return null;
}

// Search the announcement's text for a templates tag. If so, return an array with a single template.

function bamExplodeTemplates($announcementText)
{
    $matched_template_raw = '';

    if (preg_match('/\[@template:([a-zA-Z0-9 _]*)\]/', $announcementText, $matched_template_raw))
    {
        $matched_template = strtr($matched_template_raw[0], array('[@template:' => '', ']' => ''));

        // Remove non alphanumeric characters for security.
        return trim(preg_replace('/[\W]/', '', $matched_template));
    }
    return null;
}

// Parses {username} and {newmember*} variables.

function bam_parse_user_variables($announcement, $link)
{
    global $lang, $mybb, $plugins;

    // Parse username and newmember variables.
    // We only need to check for variables if we have { in the announcement. Performance optimizaiton.

    if (strpos('-' . $announcement, '{'))
    {
        // Parse the {$username} variable within announcements. Parses to "Guest" if the user is not logged in.
        if (!empty($mybb->user['uid']))
        {
            $username = htmlspecialchars($mybb->user['username'], ENT_QUOTES); // allows {$username} to be replaced with the user's username.
        }
        else
        {
            $username = $lang->guest; // user is not logged in. Parse as "Guest" instead.
        }

        if (strpos("-" . $announcement, "{newestmember"))
        { // Added character at beginning before searching to fix bug. (Allows strpos to return true even if the tag begins at the first character)
            $newUser = getNewestMember();
            $announcement = strtr($announcement, array('{newestmember}' => htmlspecialchars($newUser['username'], ENT_QUOTES)));
            $announcement = strtr($announcement, array('{newestmember_uid}' => (int) $newUser['uid']));

            // Prevent BAM from trying to nest a link inside of a link.
            if (!$link)
            {
                $announcement = strtr($announcement, array('{newestmember_link}' => "<a href='member.php?action=profile&uid=" . (int) $newUser['uid'] . "'>" . htmlspecialchars($newUser['username']) . "</a>"));
            }
            else
            {
                $announcement = strtr($announcement, array('{newestmember_link}' => htmlspecialchars($newUser['username'], ENT_QUOTES)));
            }
        }

        // Parse additional variables.
        $announcement = strtr($announcement, array('{username}' => $username));
        $announcement = parseThreadVariables($announcement);    // parses {threadreplies} to thread reply count.
    }
    $announcement = $plugins->run_hooks("bam_parse_user_variables", $announcement);
    return $announcement;
}

// This function is only called on showthread.php, and parses some extra variables.
// Currently, {threadreplies} and {countingthread} are parsed. These are experimental, but work as expected.

function parseThreadVariables($announcementText)
{
    global $current_page, $mybb, $plugins;

    // Check to make sure we are on showthread.php and we have a thread to display.

    if ($current_page == 'showthread.php' && (((int) $mybb->input['tid'] != 0) || ($mybb->input['pid'] != 0)))
    {

        // Get the thread from the database.
        if (isset($mybb->input['tid']) && $mybb->input['tid'] != 0)
        {
            $threadID = (int) $mybb->input['tid'];
        }

        // URL is a PID instead of a TID. Parse this correctly.
        else if (isset($mybb->input['pid']) && $mybb->input['pid'] != 0)
        {
            $threadID = (int) bam_TIDManager::bam_getTIDfromPID($mybb->input['pid']);
        }

        $thread = get_thread($threadID);

        // Parse number of replies in thread. Primarily useful for forum games.
        if (strpos("-" . $announcementText, '{threadreplies}'))
        {
            $announcementText = strtr($announcementText, array('{threadreplies}' => number_format((int) $thread['replies'])));
        }

        // Parse the counting thread. This is similar to above, but attempts to correct invalid counts.
        if (strpos("-" . $announcementText, '{countingthread}'))
        {

            // We are going to try to determine the correct count for the counting thread based on previous replies.
            // This is an easter egg feature! Very useful for forum games where users frequently get off count.
            $threadData = getThreadData($threadID);
            $arrayofNumbers = array();
            $maxLen = 0;
            $leadingNumber = 0;

            // We need to extract the number from each post generated from the getThreadData query.
            // If a number doesn't exist, it simply gets put in as a 0 in the array.
            // This function depends on counts being in every post. It can handle a few missing counts, but behaves unpredictably if more are missing.
            foreach ($threadData as $post)
            {
                $arrayofNumbers[] = parseForumGameCounter($post);
            }

            // Next, we must explode these into arrays of consecutive numbers.
            $results = getConsecutiveNumbers($arrayofNumbers);
            foreach ($results as $row)
            {
                // We must fetch the largest set of consecutive numbers from recent posts. This will serve as the basis for the correct count.
                if (count($row) > $maxLen)
                {
                    $maxLen = count($row);
                    $leadingRow = $row;
                }
            }

            // Get the correct count based on offsets from the largest consecutive set. Parse the variable for the announcement.
            $leadingKey = array_search($leadingRow[0], $arrayofNumbers);
            $numPostsAway = count($arrayofNumbers) - $leadingKey;
            $finalValue = number_format((int) ($arrayofNumbers[$leadingKey] + $leadingKey));
            $announcementText = strtr($announcementText, array('{countingthread}' => $finalValue));
        }
    }

    // Return the announcement with changes or return it unchanged.
    $announcementText = $plugins->run_hooks("bam_parse_user_variables", $announcementText);
    return $announcementText;
}


/* HELPER FUNCTIONS */

function getNewestMember()
{
    global $db;
    $querydata = $db->fetch_array($db->query('SELECT uid FROM ' . TABLE_PREFIX . 'users ORDER BY uid DESC LIMIT 1'));
    return get_user($querydata['uid']);
}

// Credit: https://stackoverflow.com/questions/28614124/php-number-of-consecutive-elements-in-array

function getConsecutiveNumbers($array)
{

    // This function creates a multidimensional array of lists of consecutive numbers from the input $array.
    // This can be used to determine the correct count in counting threads, and to correct the wrong count being posted.

    $array = array_unique($array);
    $previous = null;
    $result = array();
    $consecutiveArray = array();

    // Get consecutive sequences where the next number is exactly 1 less than the previous number.
    foreach ($array as $number)
    {
        if ($number == $previous - 1)
        {
            $consecutiveArray[] = $number;
        }
        else
        {
            $result[] = $consecutiveArray;
            $consecutiveArray = array($number);
        }
        $previous = $number;
    }
    $result[] = $consecutiveArray;
    return $result;
}

function parseForumGameCounter($post)
{
    // This function extracts a number/count from a post in counting threads. It returns the number found, or 0 if not found.
    $match = "";

    preg_match('/([0-9]+)/', $post['message'], $match);
    if ($match[0] != 0)
    {
        return (int) $match[0];
    }
    return 0;
}

// Helper function that returns the thread data. Used for parseThreadVariables (above)
function getThreadData($threadID)
{
    global $db;
    $tid = (int) $threadID;

    // Get the most recent posts from the database by thread ID.
    return $db->query("
    SELECT p.message, p.tid, p.dateline
    FROM " . TABLE_PREFIX . "posts p WHERE p.tid='$tid'
    ORDER BY p.dateline DESC LIMIT 0,50"); // Timed this query. Runs in about 300 microseconds!
}

// This function queries the database to get the associated thread ID with a particular post ID.
// Used only in rare cases when the URL specifies a PID instead of a TID.
// MyBB parses these like normal (and very oddly so). BAM must do the same to prevent unusual behavior.

// This class caches results from fetching TIDs, FIDs, etc.

class bam_TIDManager
{

    public static $fid;
    public static $tid;

    public static function bam_getTIDfromPID($pid)
    {
        global $db;
        if (isset(self::$tid) && self::$tid != 0)
        {
            return self::$tid;
        }
        // First, we must convert any URL parameters to TIDs.
        if ($pid != 0)
        {
            $pid = (int) $pid;
            $querydata = $db->query('SELECT tid FROM ' . TABLE_PREFIX . 'posts WHERE PID = ' . $pid . ';');
            $tid = $db->fetch_array($querydata);
            $tid = (int) $tid['tid'];
            self::$tid = $tid;
            return $tid;
        }
        return false;
    }

    // This function takes a TID as an input, and returns the associated FID.
    // This is used for forum display for announcements, and allows announcements to display on threads as well.
    public static function bam_getFIDfromTID($tid)
    {
        global $db;

        if (isset(self::$fid) && self::$fid != 0)
        {
            return self::$fid;
        }
        $tid = (int) $tid;
        $fidDB = $db->query('SELECT `fid` FROM ' . TABLE_PREFIX . 'threads WHERE `tid` = ' . $tid . ';');
        $fid = $db->fetch_array($fidDB);
        self::$fid = (int) $fid['fid'];
        return (int) $fid['fid'];
    }

    // This function takes an AID (announcement ID, standard MyBB core announcements) as an input, and returns the associated FID.
    // This is used for forum display for announcements, and allows announcements to display on forum announcements as well.
    public static function bam_getFIDfromAID($aid)
    {
        global $db;
        if (isset(self::$fid) && self::$fid != 0)
        {
            return self::$fid;
        }
        $aid = (int) $aid;
        $fidDB = $db->query('SELECT fid FROM ' . TABLE_PREFIX . 'announcements WHERE `aid` = ' . $aid . ';');
        $fid = $db->fetch_array($fidDB);
        self::$fid = (int) $fid['fid'];
        return (int) $fid['fid'];
    }
}

/* ADMIN CP HOOKS */

function bam_config_menu(&$sub_menu)
{
    global $lang;
    $lang->load("bam");
    $sub_menu[] = array(
        "id" => "bam",
        "title" => $lang->bam_announcements_menu,
        "link" => "index.php?module=config-bam"
    ); // create memnu link in ACP
}

// Hook for ACP menu.
function bam_confighandler(&$actions)
{
    $actions['bam'] = array('active' => 'bam', 'file' => 'bam.php');
    return $actions;
}

// Legacy function that is not used in BAM 2.0. Only used if BAM 2.0 is uploaded to a server and the upgrade script has not run.
// This allows BAM 2.0 to properly display old BAM 1 announcements before they are migrated.
// See comments in inc/plugins/bam/bam_upgrade.php for more info on how this works.

function global_display($pinned)
{
    global $mybb, $current_page;
    if ($current_page == $mybb->settings['bam_index_page'])
    {
        return true; // this is the index page. No need to check for global announcement settings.
    }

    if ($mybb->settings['bam_global'] == 'global_all')
    {
        return true;
    }
    else if (($mybb->settings['bam_global'] == 'global_pinned') && ($pinned == "1"))
    {
        return true;
    }
    else
    {
        return false;
    }
}

// This function rewrites SEO-friendly URLs from MyBB's included HTACCESS file.
// These links are converted internally to native URLs and parsed as such.
// BAM includes plugin hooks to add additional directives. A stub function has been provided to
// demonstrate extensibility with additional URL rewrites.

// Note: Google SEO plugin is NOT supported at this time. A future release may expand upon this!

function bam_reverse_rewrite($url)
{
    global $plugins;

    // How to use custom HTACCESS settings:
    // Apache's htaccess directives can (almost) be copy/pasted into the array below.

    // 1. Remove the RewriteRule directive at the beginning.
    // 2. Replace '^' with '/' at the beginning.
    // 3. Remove the $ at the end of the pattern, replace it with '/'
    // 4. Remove [L,QSA] (and any other directives) from the replacement string.
    // 5. Add to the array below.

    $replacements = array(
        '/forum-([0-9]+)\.html/' => 'forumdisplay.php?fid=$1',
        '/forum-([0-9]+)-page-([0-9]+)\.html/' => 'forumdisplay.php?fid=$1&page=$2',
        '/thread-([0-9]+)\.html/' => 'showthread.php?tid=$1 [L,QSA]',
        '/thread-([0-9]+)-page-([0-9]+)\.html/' =>  'showthread.php?tid=$1&page=$2',
        '/thread-([0-9]+)-lastpost\.html/' => 'showthread.php?tid=$1&action=lastpost',
        '/thread-([0-9]+)-nextnewest\.html/' => 'showthread.php?tid=$1&action=nextnewest',
        '/thread-([0-9]+)-nextoldest\.html/' => 'showthread.php?tid=$1&action=nextoldest',
        '/thread-([0-9]+)-newpost\.html/' => 'showthread.php?tid=$1&action=newpost',
        '/thread-([0-9]+)-post-([0-9]+)\.html/' => 'showthread.php?tid=$1&pid=$2',
        '/post-([0-9]+)\.html/' => 'showthread.php?pid=$1',
        '/announcement-([0-9]+)\.html/' => 'announcements.php?aid=$1',
        '/user-([0-9]+)\.html/' => 'member.php?action=profile&uid=$1',
        '/calendar-([0-9]+)\.html/' => 'calendar.php?calendar=$1',
        '/calendar-([0-9]+)-year-([0-9]+)\.html/' => 'calendar.php?action=yearview&calendar=$1&year=$2',
        '/calendar-([0-9]+)-year-([0-9]+)-month-([0-9]+)\.html/' => 'calendar.php?calendar=$1&year=$2&month=$3',
        '/calendar-([0-9]+)-year-([0-9]+)-month-([0-9]+)-day-([0-9]+)\.html/' => 'calendar.php?action=dayview&calendar=$1&year=$2&month=$3&day=$4',
        '/calendar-([0-9]+)-week-(n?[0-9]+)\.html/' => 'calendar.php?action=weekview&calendar=$1&week=$2',
        '/event-([0-9]+)\.html/' => 'calendar.php?action=event&eid=$1'
    );

    $plugins->run_hooks("bam_replace_urls", $replacements);
    $url_preg = $url;
    foreach ($replacements as $key => $value_raw)
    {
        $value = $value_raw;
        $url_preg = preg_replace($key, $value, $url);

        // We only need to do one replacement, as ALL htaccess directives are L,QSA
        if (strlen($url_preg) != strlen($url))
        {
            break;
        }
    }
    return $url_preg;
}

// Note: This is completely unsupported! This does not work in the current version of BAM. This is
// included so that administrators can more easily modify their forums with custom rewrite rules.
// For your convenience, The URL rewrites in (plugin) Google SEO's HTACCESS file have already been converted to
// PHP's preg_replace regex below. If you would like further support, please reach out to me via PM
// on MyBB's community support forums (Darth Apple) or at http://makestation.net (Darth-Apple).
// I will be happy to provide pointers on how to further extend BAM's URL rewriting functionality!

function bam_google_SEO_rewrites($replacements)
{
    global $mybb, $plugins, $cache;
    // Stub function that can be extended to enable compatibility with the Google SEO MyBB Plugin.
    // Disabled at this time. Uncomment the hook near the top of this file to enable this.

    // Check if the Google SEO plugin is activated.
    $activePlugins = $cache->read("plugins");

    if (in_array("google_seo", $activePlugins['active']))
    {
        $additionalReplacements = array(
            /*'^([^&]*)&(.*)$' => 'http://yoursite/MyBB/$1?$2', */ // From the HTaccess file. Add back in if needed.
            '/sitemap-([^./]+)\.xml/' => 'misc.php?google_seo_sitemap=$1',
            '/Forum-([^./]+)/' => 'forumdisplay.php?google_seo_forum=$1',
            '/Thread-([^./]+)/' => 'showthread.php?google_seo_thread=$1',
            '/Announcement-([^./]+)/' => 'announcements.php?google_seo_announcement=$1',
            '/User-([^./]+)/' => 'member.php?action=profile&google_seo_user=$1',
            '/Calendar-([^./]+)/' => 'calendar.php?google_seo_calendar=$1',
            '/Event-([^./]+)/' => 'calendar.php?action=event&google_seo_event=$1',
        );

        // This is not functional at this time, and must be extended. A future release may expand upon this!
        $replacements = array_merge($additionalReplacements, $replacements);
    }

    return $replacements;
}


// Helper function that determines if we are in a deny-listed page for forumdisplay announcements
// This is an unofficial feature that requires template directives to enable
function check_forumdisplay_denylist()
{
    // Execute override functionality ([@forums])

    if ((defined('THIS_SCRIPT')) && (THIS_SCRIPT != 'editpost.php') && (THIS_SCRIPT != "newreply.php") && (THIS_SCRIPT != "newthread.php"))
    {
        return true; // Not on deny-listed page. Render as normal.
    }
    else
    {
        return false; // On a blocked page (new thread/edit post/new reply). Don't display.
    }
}

// Thank you for using, developing for, and viewing BAM's source. If you have any questions or would like to contribute,
// please send me (Darth-Apple) a message on github or on the MyBB community forums!
// Regards,
// -Darth Apple