<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TinyMCE admin settings
 *
 * @package    editor_tinymce
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('editorsettings', new admin_category('editortinymce', new lang_string('pluginname', 'editor_tinymce')));

$settings = new admin_settingpage('editorsettingstinymce', new lang_string('settings', 'editor_tinymce'));
if ($ADMIN->fulltree) {
    require_once(__DIR__.'/adminlib.php');
    $settings->add(new tiynce_subplugins_settings());
    $settings->add(new admin_setting_heading('tinymcegeneralheader', new lang_string('settings'), ''));
    $settings->add(new admin_setting_configtextarea('editor_tinymce/customtoolbar',
        get_string('customtoolbar', 'editor_tinymce'), get_string('customtoolbar_desc', 'editor_tinymce', 'http://www.tinymce.com/wiki.php/Buttons/controls'), '', PARAM_RAW, 100, 6));
    $settings->add(new admin_setting_configtextarea('editor_tinymce/fontselectlist',
        get_string('fontselectlist', 'editor_tinymce'), '',
        'Trebuchet=Trebuchet MS,Verdana,Arial,Helvetica,sans-serif;Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;Wingdings=wingdings', PARAM_RAW));
}
$ADMIN->add('editortinymce', $settings);
unset($settings);

$subplugins = get_plugin_list('tinymce');
$disabled = array(); // Disabling of subplugins to be implemented later.
foreach ($subplugins as $name=>$dir) {
    if (file_exists("$dir/settings.php")) {
        $settings = new admin_settingpage('tinymce'.$name.'settings', new lang_string('pluginname', 'tinymce_'.$name), 'moodle/site:config', in_array($name, $disabled));
        // settings.php may create a subcategory or unset the settings completely.
        include("$dir/settings.php");
        if ($settings) {
            $ADMIN->add('editortinymce', $settings);
        }
    }
}
unset($subplugins);
unset($disabled);

// TinyMCE does not have standard settings page.
$settings = null;
