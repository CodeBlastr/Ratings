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
if (!empty($model) && !empty($foreignKey)) : ?>
	<?php $RatingHelper = $this->Helpers->load('Ratings.Rating'); ?>
	<?php $ratings = $RatingHelper->getRatings($model, $foreignKey);  // dataForView contains all view variables including this that come with the element call ?>
	<?php //debug($ratings); ?>
	<?php if (!empty($ratings)) : ?>
		<?php foreach ($ratings as $rating) : ?>
			<div class="row">
				<div class="col-xs-2 text-center">
					<?php echo $this->element('Galleries.thumb', array('model' => 'User', 'foreignKey' => $rating['User']['id'], 'class' => 'img-thumbnail img-responsive', 'thumbSize' => 'large', 'thumbLink' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'view', $rating['User']['id']))); ?>
					<?php if (!strpos($rating['User']['username'], '@')) : // only use this if the username is not an email address ?>
						<p class="ellipsis">
							<?php echo $this->Html->link($rating['User']['username'], array('plugin' => 'users', 'controller' => 'users', 'action' => 'view', $rating['User']['id'])); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="col-xs-10">
					<?php echo $this->element('Ratings.stars', array('value' => $rating['Rating']['value'])); ?>
					<hr>
					<?php echo $this->Html->markdown($rating['Rating']['review']); ?>
				</div>
			</div>
			<hr>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="text-center">
			No ratings yet.
		</p>
	<?php endif; ?>
<?php else : ?>
	model & foreignKey required for ratings'
<?php endif; ?>
