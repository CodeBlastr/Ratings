<?php $RatingHelper = $this->Helpers->load('Ratings.Rating'); ?>	
<?php if (!empty($model) && !empty($foreignKey) && empty($value)) : ?>
	<?php $data = $RatingHelper->handleData($dataForView);  // dataForView contains all view variables including this that come with the element call ?>
	<?php $value = !empty($data[0][0]['RatingAverage']) ? $data[0][0]['RatingAverage'] : 0; ?>
<?php endif; ?>

<?php $options = array_merge($RatingHelper->defaults, $dataForView); ?>
<?php echo $this->Html->css('Ratings.jquery.rating'); ?>
<?php echo $this->Html->script('Ratings.jquery.rating.pack'); ?>
<div class="stars">
	<div class="input radio">
		<span class="star-rating-control">
			<?php for ($i=1; $i <= $options['stars']; $i++) : ?>
				<?php $on = $value - $i >= 0 ? 'star-rating-on' : null; ?>
				<div role="text" aria-label="" class="star-rating rater-0 readonly star star-rating-applied star-rating-live <?php echo $on; ?>" id="Value<?php echo $i; ?>">
					<a title="<?php echo $i; ?>"><?php echo $i; ?></a>
				</div>
			<?php endfor; ?>
		</span>
	</div>
</div>