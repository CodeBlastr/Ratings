<?php
/**
 * Ratable element
 * 
 * How to use : Simply echo out the element and give it a model and foreignkey, of what you want to show ratings for and have rated.
 * This element will handle both the saving of data, and the display of data without having to load any additional helpers or components etc.
 * 
 * ex. echo $this->element('ratable', array('model' => 'MyModel', 'foreignKey' => $someForeignKey));
 * 
 * There are a lot of additional options, but they are all contained in the Ratings/View/Helper/RatingHelper.php.
 * Additional settings given in the element call are passed directly from this element to the construct method of the helper. 
 * 
 * @author Richard Kersey
 * @author Buildrr LLC
 * @license MIT
 */
if (!empty($model) && !empty($foreignKey)) {
	$___dataForView['createForm']['id'] = !empty($createForm['id']) ? $createForm['id'] : __('rate%s%s', $model, $foreignKey);
	
	$RatingHelper = $this->Helpers->load('Ratings.Rating', $___dataForView);
	
	echo $this->Html->css('Ratings.jquery.rating', null, array('inline'=>false));
	echo $this->Html->script('Ratings.jquery.rating.pack', array('inline'=>false));
	$data = $RatingHelper->handleData(array('model' => $model, 'foreignKey' => $foreignKey));  // we do this instead of in the helper, so that this element is more customizable
	echo !empty($data['Rating']['result']) ? $data['Rating']['result'] : null;
	echo  '<div class="stars">';
	echo $RatingHelper->display(array(
	    'foreignKey' => $data['Rating']['foreign_key'],
	    'value' =>  $data['Rating']['value']
	    ));
	echo '</div>';
} else {
	echo __('model & foreignKey required for ratings');
}


