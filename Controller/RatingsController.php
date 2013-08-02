<?php
App::uses('RatingsAppController', 'Ratings.Controller');

class RatingsController extends RatingsAppController {
	
	public $allowedActions = array('rated');
	
	public $uses = array('Ratings.Rating');
	
/**
 * @todo In order to get this to work, we need to re-work how ratings are saved. 
 * We need to make ratings automatically use the Tree Behavior, where saving a
 * single rating creates a parent and a child.  The parent would have the average
 * of all the children as the value, and the user_id field would be a list of all 
 * user_id's who have rated.  That way, we can substantially improve the finding
 * of rating values, and the users that rated it. 
 */
	public function rated($modelName) {
		$this->Rating->bindModel(
        	array('belongsTo' => array(
                $modelName => array(
                    'className' => ZuhaInflector::pluginize($modelName) . '.' . $modelName,
                    'foreignKey' => 'foreign_key',
                    //'conditions' => array('Rating.model' => $modelName)
                	)
            	)
        	));
			// not worth working this way... look at the @todo above
		debug($this->Rating->find('all', array('order' => 'rating', 'limit' => 20, 'fields' => array('avg(Rating.value) AS rating', $modelName. '.name'), 'contain' => $modelName)));
		break;
	}
}
