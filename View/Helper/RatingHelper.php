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
 * Rating helper
 *
 * @package 	ratings
 * @subpackage 	ratings.views.helpers
 */
class RatingHelper extends AppHelper {

/**
 * helpers variable
 *
 * @var array
 */
	public $helpers = array ('Html', 'Form', 'Js' => 'Jquery');

/**
 * Allowed types of html list elements
 *
 * @var array $allowedTypes
 */
	public $allowedTypes = array('ul', 'ol', 'radio');

/**
 * Default settings
 *
 * @var array $defaults
 */
	public $defaults = array(
		'stars' => 5,
		'foreignKey' => null,
		'value' => 0,
		'type' => 'radio',
		'createForm' => false,
		'url' => array(),
		'link' => true,
		'redirect' => true,
		'class' => 'rating',
		'readonly' => false
	);

/**
 * Constructor method
 *
 */
    public function __construct(View $View, $settings = array()) {
    	$this->View = $View;
    	$this->defaults = array_merge($this->defaults, $settings);
		parent::__construct($View, $settings);
    }



/**
 * Displays a bunch of rating links wrapped into a list element of your choice
 *
 * @param array $options
 * @param array $urlHtmlAttributes Attributes for the rating links inside the list
 * @return string markup that displays the rating options
 */
	public function display($options = array(), $urlHtmlAttributes = array()) {
     	$options = array_merge($this->defaults, $options);

		if (empty($options['foreignKey'])) {
			throw new Exception(__d('ratings', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}

		if ($options['type'] == 'radio') {
			return $this->starForm($options, $urlHtmlAttributes);
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$link = null;
			if ($options['link'] == true) {
				$url = array_merge($options['url'], array('rate' => $options['foreignKey'], 'rating' => $i));
				if ($options['redirect']) {
					$url['redirect'] = 1;
				}
				$link = $this->Html->link($i, $url, $urlHtmlAttributes);
			}
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
		}

		if (in_array($options['type'], $this->allowedTypes)) {
			$type = $options['type'];
		} else {
			$type = 'ul';
		}

		$stars = $this->Html->tag($type, $stars, array('class' => $options['class'] . ' ' . 'rating-' . round($options['value'], 0)));
		return $stars;
	}

/**
 * Handle data method
 *
 * Might be a break from the MVC pattern but the benefits seem too high.
 * 1. Being able to just do $this->element('ratable', array('model' => 'MyModel', 'foreignKey' => $someForeignKey))
 * 2. We don't need to load a bunch of components and helpers and worry about whether it is need for multi-sites
 * 3. Commence beating me :)
 */
 	public function handleData($options = array()) {
     	$options = array_merge($this->defaults, $options);

		$default['Rating']['model'] = $options['model'];
		$default['Rating']['foreign_key'] = $options['foreignKey'];
		$default['Rating']['value'] = 0;
		$default['Rating']['user_id'] = CakeSession::read('Auth.User.id');

		App::uses('Rating', 'Ratings.Model');
		$Rating = new Rating;

		if ($this->request->is('post')) {
			// save the rating
			$this->request->data = Set::merge($default, $this->request->data);
			if ($Rating->saveAll($this->request->data)) {
				// do nothing (maybe we'll add a redirect option some day)
			} else {
				$default['Rating']['result'] =  __('Rating failed to save. %s', $Rating->validate[key($Rating->invalidFields())]['message']);
			}
		}
		$data = $Rating->find('all', array(
			'conditions' => array(
				'Rating.model' => $options['model'],
				'Rating.foreign_key' => $options['foreignKey'],
				'Rating.parent_id' => null
				),
			'fields' => array(
				'Rating.id',
				'Rating.model',
				'Rating.foreign_key',
				'Rating.parent_id',
				'Rating.value',
				'AVG(Rating.value) AS RatingAverage'
				)
			));
		return Set::merge($default, $data);
 	}

/**
 * Bar rating
 *
 * @param integer value
 * @param integer total amount of rates
 * @param array options
 * @return string
 */
	public function bar($value = 0, $total = 0, $options = array()) {
		$defaultOptions = array(
			'innerClass' => 'inner',
			'innerHtml' => '<span>%value%</span>',
			'innerOptions' => array(),
			'outerClass' => 'barRating',
			'outerOptions' => array(),
			'element' => null);
		$options = array_merge($defaultOptions, $options);

		$percentage = $this->percentage($value, $total);

		if (!empty($options['element'])) {
			$View =& ClassRegistry:: getObject('view');
			return $View->element($options['element'], array(
				'value' => $value,
				'percentage' => $percentage,
				'total' => $total));
		}

		$options['innerOptions']['style'] = 'width: ' . $percentage . '%';
		$innerContent = str_replace('%value%', $value, $options['innerHtml']);
		$innerContent = str_replace('%percentage%', $percentage, $innerContent);
		$inner = $this->Html->div($options['innerClass'], $innerContent, $options['innerOptions']);

		return $this->Html->div($options['outerClass'], $inner, $options['outerOptions']);
	}

/**
 * Calculates the percentage value
 *
 * @param integer value
 * @param integer total amount
 * @param integer precision of rounding
 * @return mixed float or integer based on the precision value
 */
	public function percentage($value = 0, $total = 0, $precision = 2) {
		if ($total) {
			return (round($value / $total, $precision) * 100);
		}
		return 0;
	}

/**
 * Displays a star form
 *
 * @param array $options
 * @param array $urlHtmlAttributes Attributes for the rating links inside the list
 * @return string markup that displays the rating options
 */
	public function starForm($options = array(), $urlHtmlAttributes = array()) {
		$options = array_merge($this->defaults, $options);

		$flush = false;
		if (empty($options['foreignKey'])) {
			trigger_error(__d('ratings', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}
		$result = '';
		if ($options['createForm']) {
			$result .= $this->Form->create('Rating', $options['createForm']) . "\n";
		}
		$inputField = 'value';
		if (!empty($options['inputField'])) {
			$inputField = $options['inputField'];
		}
		$result .= $this->Form->input($inputField, array(
			'type' => 'radio',
			'legend' => false,
			'value' => isset($options['value']) ? round($options['value']) : 0,
			'class' => isset($options['readonly']) ? 'readonly star' : 'star',
			'options' => array_combine(range(1, $options['stars']), range(1, $options['stars']))));
		if ($options['createForm']) {
			if (!empty($options['target']) && !empty($options['createForm']['url']) && !empty($options['createForm']['ajaxOptions'])) {
				$result .= $this->Js->submit(__d('ratings', 'Rate!'), array_merge(array('url' => $options['createForm']['url']), $options['createForm']['ajaxOptions'])) . "\n";
				$flush = true;
			} else {
				$result .= $this->Form->submit(__d('ratings', 'Rate!')) . "\n";
			}
			$result .= $this->Form->end() . "\n";
			if ($flush) {
				$this->Js->writeBuffer();
			}
		}
		return $result;
	}

/**
 * Given a $modelName & $foreignKey, will return find('threaded')
 *
 * @param string $modelName
 * @param char $foreignKey
 * @return array
 */
	public function getRatings($modelName, $foreignKey, $options = array()) {
		// $options // not used currently but prepping it for later :)
		$contain = array('User');
		App::uses('Rating', 'Ratings.Model');
		$Rating = new Rating;

		if (CakePlugin::loaded('Comments')) { /** @todo This really needs to be "if Ratings are Commentable" **/
			$contain[] = 'Comment';
			$Rating->bindModel(array('hasMany' => array(
			'Comment' => array(
				'className' => 'Comment',
				'foreignKey' => 'foreign_key',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'dependent' => true,
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''))), false);
		}

		return $Rating->find('threaded', array(
			'conditions' => array(
				'Rating.model' => $modelName,
				'Rating.foreign_key' => $foreignKey
			),
			'contain' => $contain
		));
	}

/**
 * $options	array
 *			['days'] Number of days ago to limit results to
 *			['limit'] Number of results to limit by
 *
 * @param string $modelName
 * @param array $options (see above)
 * @return array
 */
	public function highestRated($modelName, $options = array('days' => 7, 'limit' => 3, 'contain' => array())) {
		App::uses('Rating', 'Ratings.Model');
		$Rating = new Rating;
		return $Rating->find('threaded', array(
			'conditions' => array(
				'Rating.model' => $modelName,
				'Rating.created >' => date('Y-m-d H:i:s', strtotime("{$options['days']} days ago")),
				'Rating.parent_id' => null
			),
			'order' => array('Rating.value DESC'),
			'limit' => $options['limit'],
			'fields' => array("DISTINCT Rating.foreign_key, Rating.*, $modelName.*"),
			'contain' => Hash::merge($options['contain'], array('_auto'))
		));
	}

/**
 * @param string $modelName
 * @param array $options
 * @return array
 */
	public function mostRated($modelName, $options = array('days' => 7, 'limit' => 3, 'contain' => array())) {
		App::uses('Rating', 'Ratings.Model');
		$Rating = new Rating;
		return $Rating->find('all', array(
			'conditions' => array(
				'Rating.model' => $modelName,
				'Rating.created >' => date('Y-m-d H:i:s', strtotime("{$options['days']} days ago")),
				'Rating.parent_id' => null
			),
			'fields' => array("DISTINCT Rating.foreign_key, $modelName.*,  COUNT(*) AS occurrences "),
			'order' => array('occurrences DESC'),
			'group' => array('Rating.foreign_key'),
			'limit' => $options['limit'],
			'contain' => Hash::merge($options['contain'], array('_auto'))
		));
	}

}
