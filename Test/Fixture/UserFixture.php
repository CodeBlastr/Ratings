<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Ratings Plugin
 *
 * User fixture
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.fixtures
 */
 
if (!class_exists('User')) {
	class User extends Model {
	}
}
class UserFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'User';

/**
 * Table
 *
 * @var array $table
 * @access public
 */
	public $table = 'users';

	
/**
 * Import
 *
 * @var array
 */
	public $import = array('config' => 'Users.User');
	
/**
 * Records
 *
 * @var array $records
 * @access public
 */
	public $records = array(
		array(
			'id'  => '1',
			'account_type'  => 'local',
			'url'  => '/user/phpnut',
			'slug' => 'phpnut',
			'username'  => 'phpnut',
			'email' => 'larry.masters@cakedc.com',
			'email_authenticated' => 1,
			'email_token' => 'testtoken',
			'email_token_expires' => '2008-03-25 02:45:46',
			'passwd'  => 'test', // test
			'password_token'  => 'testtoken',
			'tos' => 1,
			'active' => 1,
			'public_master_key'  => '',
			'public_session_key'  => '',
			'private_session_key'  => '',
			'last_activity'  => '2008-03-25 02:45:46',
			'created'  => '2008-03-25 02:45:46',
			'modified'  => '2008-03-25 02:45:46'
		),
		array(
			'id'  => '2',
			'account_type'  => 'remote',
			'url'  => '/user/floriank',
			'slug' => 'floriank',
			'username'  => 'floriank',
			'email' => 'florian.kraemer@cakedc.com',
			'email_authenticated' => '1',
			'email_token' => '',
			'email_token_expires' => '2008-03-25 02:45:46',
			'passwd'  => 'secretkey', // secretkey
			'password_token'  => '',
			'tos' => 1,
			'active' => 1,
			'public_master_key'  => '',
			'public_session_key'  => '',
			'private_session_key'  => '',
			'last_activity'  => '2008-03-25 02:45:46',
			'created'  => '2008-03-25 02:45:46',
			'modified'  => '2008-03-25 02:45:46'
		),
		array(
			'id'  => '3',
			'account_type'  => 'remote',
			'url'  => '/user/user1',
			'slug' => 'user1',
			'username'  => 'user1',
			'email' => 'testuser1@testuser.com',
			'email_authenticated' => 0,
			'email_token' => 'testtoken2',
			'email_token_expires' => '2008-03-28 02:45:46',
			'passwd'  => 'newpass', // newpass
			'password_token'  => '',
			'tos' => 0,
			'active' => 0,
			'public_master_key'  => '',
			'public_session_key'  => '',
			'private_session_key'  => '',
			'last_activity'  => '2008-03-25 02:45:46',
			'created'  => '2008-03-25 02:45:46',
			'modified'  => '2008-03-25 02:45:46'
		),
		array(
			'id' => '4',
			'account_type' => 'local',
			'url'  => '/user/oidtest',
			'slug' => 'oistest',
			'username'  => 'oidtest',
			'email' => 'oidtest@testuser.com',
			'email_authenticated' => 0,
			'email_token' => 'testtoken2',
			'email_token_expires' => '2008-03-28 02:45:46',
			'passwd'  => 'newpass', // newpass
			'password_token'  => '',
			'tos' => 0,
			'active' => 0,
			'public_master_key'  => '',
			'public_session_key'  => '',
			'private_session_key'  => '',
			'last_activity'  => '2008-03-25 02:45:46',
			'created'  => '2008-03-25 02:45:46',
			'modified'  => '2008-03-25 02:45:46'
		)
	);

/**
 *
 */
	public function __construct() {
		parent::__construct();
		App::uses('Security', 'Utility');
		foreach ($this->records as &$record) {
			$record['passwd'] = Security::hash($record['passwd'], null, true);
		}
	}

}
?>