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
 * Rating fixture
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.fixtures
 */
class RatingFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string
 * @access pulbic
 */
	public $name = 'Rating';

/**
 * Table
 *
 * @var string
 * @access public
 */
	public $table = 'ratings';
	
/**
 * Import
 *
 * @var array
 */
	public $import = array('config' => 'Ratings.Rating');

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => '1',
			'foreign_key' => '1', // first article
			'model' => 'Article',
			'value' => 1,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12'),
		array(
			'id' => 2,
			'user_id' => '1',
			'foreign_key' => '1', // first post
			'model' => 'Post',
			'value' => 1,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12'),
		array(
			'id' => 3,
			'user_id' => '1',
			'foreign_key' => '2', // second post
			'model' => 'Post',
			'value' => 3,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12'));
}
?>