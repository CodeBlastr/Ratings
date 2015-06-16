<?php
App::uses('RatingsAppModel', 'Ratings.Model');
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
 * Rating model
 *
 * @package 	ratings
 * @subpackage 	ratings.models
 */
class AppRating extends RatingsAppModel {

/**
 * Acts as
 *
 * @var string
 */
	public $actsAs = array('Tree');

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Rating';

/**
 * Validation rules
 *
 * @var array $validate
 */
	public $validate = array(
		'user_id' => array(
			'rule' => 'notempty',
			'message' => 'Must be logged in to rate.'
			),
		'model' => array(
			'rule' => 'notempty',
			'message' => 'Failed to relate this rating to an object.'
			),
		'foreign_key' => array(
			'rule' => 'notempty',
			'message' => 'Failed to relate this rating to a record.'
			),
		'value' => array(
			'rule' => 'notempty',
			'message' => 'No rating value was provided.'
			),
        );

/**
 * belongsTo associations
 *
 * @var array $belongsTo
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		);

/**
 * hasMany associations
 *
 * @var array $hasMany
 */
	public $hasMany = array(
		'ChildRating' => array(
			'className' => 'Ratings.Rating',
			'foreignKey' => 'parent_id',
			'dependent' => true
			)
		);

/**
 * Constructor
 *
 * Set the translateable validation messages in the constructor.
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}
	
/**
 * beforeSave callback
 * 
 * @todo allow options for whether the user should update their rating, not be able to rate twice, or provide multiple ratings
 * right now it defaults to not letting the user rate more than once
 * @todo probably should move this to a validation call? 
 */
 	public function beforeSave($options = array()) {
		if (empty($this->data['Rating']['id']) && !empty($this->data['Rating']['user_id']) && !empty($this->data['Rating']['model']) && !empty($this->data['Rating']['foreign_key'])) {
			$id = $this->field('Rating.id', array('Rating.user_id' => $this->data['Rating']['user_id'], 'Rating.model' => $this->data['Rating']['model'], 'Rating.foreign_key' => $this->data['Rating']['foreign_key']));
			if (!empty($id)) {
				$this->validationErrors[] = 'User has already rated this item.';
				return false;
			}
		}
		return parent::beforeSave($options);
	}

/**
 * Save method
 *
 * Have to overwrite so that the tree behavior gets the parent_id  (beforeSave() gets fired after behaviors)
 */
	public function saveAll($data = array(), $options = array()) {
		$data = $this->cleanData($data);
		return parent::saveAll($data, $options);
	}

/**
 * After save method
 *
 */
	public function afterSave($created, $options = array()) {
		$this->calculateRating();
	}

/**
 * Calculate Rating method
 * Finds a given id, and it's children and averages out the ratings for the parent.
 * And updates the rating count.
 *
 * @param uuid
 */
 	public function calculateRating($parentId = null) {
 		$id = $this->id; // saving you for later
 		if(!empty($this->data['Rating']['parent_id']) || !empty($parentId)) {
	 		$parentId = !empty($parentId) ? $parentId : $this->data['Rating']['parent_id'];
			$ratings = $this->find('first', array('conditions' => array('Rating.id' => $parentId), 'contain' => array('ChildRating')));
			if (!empty($ratings['ChildRating'])) {
				$values = Set::extract('/value', $ratings['ChildRating']);
				$count = count($values);
				$value = $this->average($values);

				$data['Rating'] = array(
					'id' => $parentId,
					'value' => $value,
					'count' => $count,
					'test' => 'stop'
					);
				// save the given parent with no callbacks
				$this->create();
				if ($this->saveAll($data, array('callbacks' => false))) {
					// nothing... this is aftersave
				} else {
					throw new Exception(__('Could not calculate rating value'));
				}
			}
 		}
		$this->id = $id;
 	}

/**
 * Average method
 * Takes an array of values and returns the average of those values
 *
 */
 	public function average($values) {
 		return array_sum($values) / count($values);
 	}

/**
 * Clean data method
 * 
 */
 	public function cleanData($data) {
		// see if there is a parent, if so add it to the data
  		if(empty($data['Rating']['parent_id']) && !empty($data['Rating']['model']) && !empty($data['Rating']['foreign_key'])) {
  			$parentId = $this->field('Rating.id', array('Rating.parent_id' => null, 'Rating.model' => $data['Rating']['model'], 'Rating.foreign_key' => $data['Rating']['foreign_key']));
			if (!empty($parentId)) {
				$data['Rating']['id'] = null;
				$data['Rating']['parent_id'] = $parentId;
			}
		}
		// add a child so that the parent calculations turn out right
		if (empty($parentId) && empty($data['ChildRating']) && !empty($data['Rating']['user_id']) && !empty($data['Rating']['model']) && !empty($data['Rating']['foreign_key']) && !empty($data['Rating']['value'])) {
			$data['ChildRating'][0]['user_id'] = $data['Rating']['user_id'];
			$data['ChildRating'][0]['model'] = $data['Rating']['model'];
			$data['ChildRating'][0]['foreign_key'] = $data['Rating']['foreign_key'];
			$data['ChildRating'][0]['value'] = $data['Rating']['value'];
		}
		return $data;
 	}

	public function find($type = 'first', $params = array()) {
		if (!empty($params['contain'])) {
			if($params['contain'] === '_auto' || array_search('_auto', $params['contain']) !== false) {
				if(is_string($params['contain'])) {
					$params['contain'] = array($params['contain']);
				}
				//Passes params to autobind.
				$params = $this->_autoBind($params);
			}
		}
		return parent::find($type, $params);
	}
	/**
	 * This is another version of autobind. This is more of an all or nothing approach
	 * this does add another query and could cause some memory and performance issues
	 * params can be passed in the contain array
	 *
	 * @param $params = find $params
	 * @return $params
	 */
	protected function _autoBind($params) {
		$associations = array();
		$dbAssociations = $this->find('all', array(
				'fields' => array('DISTINCT model'),
		));
		if ($dbAssociations) {
			$associations = Hash::extract($dbAssociations, '{n}.{s}.model');
		}
		$associations = Hash::merge($associations, array($params['conditions']['Rating.model']));
		if ($associations) {
			foreach ($associations as $association) {
				$this->bindModel(array('belongsTo' => array($association => array('foreignKey' => 'foreign_key'))));
				if(!isset($params['contain'][$association])) {
					$params['contain'][] = $association;
				}
			}
		}
		unset($params['contain'][array_search('_auto', $params['contain'])]);
		
		return $params;
	}

}

if (!isset($refuseInit)) {
	class Rating extends AppRating {}
}
