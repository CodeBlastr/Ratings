<?php
/**
 * Ratable element
 * 
 * How to use : Simply echo out the element and give it a model and foreignkey, of what you want to show ratings for and have rated.
 * This element will handle both the saving of data, and the display of data without having to load any additional helpers or components etc.
 * 
 * ex. echo $this->element('ratable', array('model' => 'MyModel', 'foreignKey' => $someForeignKey));
 * 
 * ex2. echo $this->element('Ratings.ratable', array('createForm' => array('id' => 'ratingsform'), 'model' => 'MyModel', 'foreignKey' => $someForeignKey));
 * 
 * There are a lot of additional options, but they are all contained in the Ratings/View/Helper/RatingHelper.php.
 * Additional settings given in the element call are passed directly from this element to the construct method of the helper. 
 * 
 * @author Richard Kersey
 * @author Buildrr LLC
 * @license MIT
 */
if ((!empty($model) && !empty($foreignKey)) || (!empty($foreignKey) && !empty($value))) : ?>
	<?php $RatingHelper = $this->Helpers->load('Ratings.Rating'); ?>
	<?php $data = empty($value) ? $RatingHelper->handleData($dataForView) : array(array(array('RatingAverage' => $value)));  // dataForView contains all view variables including this that come with the element call ?>
	<?php // $dataForView['value'] = $data['Rating']['value']; ?>
	<?php $dataForView['value'] = $data[0][0]['RatingAverage']; ?>
	<?php echo $this->Html->css('Ratings.jquery.rating'); ?>
	<?php echo $this->Html->script('Ratings.jquery.rating.pack'); ?>
	<?php // echo !empty($data['Rating']['result']) ? $data['Rating']['result'] : null; // no idea what this is, its not even in the database ?>
	<div class="stars">
		<?php echo $RatingHelper->display($dataForView); ?>
	</div>
<?php else : ?>
	model & foreignKey or foreignKey & rating value is required for ratable element
<?php endif; ?>
