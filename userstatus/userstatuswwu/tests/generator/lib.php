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
 *
 * @package    userstatus_userstatuswwu
 * @category   test
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 *
 *
 * @package    userstatus_userstatuswwu
 * @category   test
 * @copyright
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class userstatus_userstatuswwu_generator extends testing_data_generator {
    /**
     * Creates Course, course members, groups and groupings to test the block.
     */
    public function test_create_preparation () {
        global $DB;
        $generator = advanced_testcase::getDataGenerator();
        $data = array();
        $course = $generator->create_course(array('name' => 'Some course'));
        $data['course'] = $course;
        $mytimestamp = time();

        $user = $generator->create_user(array('username' => 'e_user03', 'lastaccess' => $mytimestamp));
        $generator->enrol_user($user->id, $course->id);
        $data['e_user03'] = $user;

        $timestamponeyearago = $mytimestamp - 31536000;
        $userlongnotloggedin = $generator->create_user(array('username' => 'user', 'lastaccess' => $timestamponeyearago));
        $generator->enrol_user($userlongnotloggedin->id, $course->id);
        $data['user'] = $userlongnotloggedin;

        $timestampfifteendays = $mytimestamp - 1296000;
        $userfifteendays = $generator->create_user(array('username' => 'userm', 'lastaccess' => $timestampfifteendays));
        $generator->enrol_user($userfifteendays->id, $course->id);
        $data['userm'] = $userfifteendays;

        $userarchived = $generator->create_user(array('username' => 's_other07', 'lastaccess' => $mytimestamp, 'suspended' => 1));
        $DB->insert_record_raw('tool_deprovisionuser', array('id' => $userarchived->id, 'archived' => true, 'timestamp' => $mytimestamp), true, false, true);
        $generator->enrol_user($userarchived->id, $course->id);
        $data['s_other07'] = $userarchived;

        $neverloggedin = $generator->create_user(array('username' => 'r_theu9'));
        $generator->enrol_user($neverloggedin->id, $course->id);
        $data['r_theu9'] = $neverloggedin;

        $timestamponeyearnintydays = $mytimestamp - 39528000;
        $deleteme = $generator->create_user(array('username' => 'd_me09', 'lastaccess' => $timestamponeyearnintydays, 'suspended' => 1));
        $generator->enrol_user($deleteme->id, $course->id);
        $DB->insert_record_raw('tool_deprovisionuser', array('id' => $deleteme->id, 'archived' => true, 'timestamp' => $timestamponeyearnintydays), true, false, true);
        $data['d_me09'] = $deleteme;

        return $data; // Return the user, course and group objects.
    }
}