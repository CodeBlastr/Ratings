<div class="ratingsElement">
  <?php if (!empty($ratingEntries)) { ?>
  <div class="ratings index">
    <div class="indexContainer">
 	<h2><?php echo __('Ratings'); ?></h2>
	<?php
		foreach ($ratingEntries as $ratingEntry) {
	?>
    	<div class="indexRow" id="headingRow">
        	<div class="indexCell columnHeading"><?php echo __('Full Name'); ?></div>
        	<div class="indexCell columnHeading"><?php echo __('Rating'); ?></div>
        	<div class="indexCell columnHeading"><?php echo __('Comment'); ?></div>
        </div>
        <div class="indexRow">
        	<div class="indexCell" id="gallery"><?php echo $this->element('snpsht', array('plugin' => 'users', 'useGallery' => true, 'userId' => $ratingEntry['User']['id'], 'thumbSize' => 'small', 'thumbLink' => '/users/users/view/'.$ratingEntry['User']['id'], 'showFirstName' => true, 'showLastName' => true));  ?></div>
			<div class="indexCell" id="value"><?php echo $ratingEntry['Rating']['value']; ?></div>
			<div class="indexCell" id="comment"><?php echo $ratingEntry['Rating']['comment']; ?></div>
    	</div>
    <?php
		}
	?>
    </div>
	<p><?php echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')); ?></p>
  </div>
  <?php } ?>
    
    
	<div class="ratingsForm">
    <h2><?php echo __('Rate'); ?></h2>
	<?php 
	$model = Inflector::classify($this->request->params['controller']);
	$viewData = strtolower($model);
		echo $this->Html->script('admin/jquery-ui-1.8.custom.min');
		echo $this->Html->script('jquery.stars');
		
		echo $this->Rating->display(array(
	   		'item' => $___dataForView[$viewData][$model]['id'],
			'type' => 'radio',
			'stars' => 5,
			 /* 'value' => $item['id'], don't quite understand what this is for right now. */
			'createForm' => array(
				'url' => array(
					$___dataForView[$viewData][$model]['id'], 
					'rate' => true, 
					'redirect' => true,
				),
				'id' => 'ratingform',
			)
		));
	/*
	<!--script type="text/javascript">
	$(function() {
		$('#ratingform').stars({
		    split:2,
		    cancelShow:false,
		    callback: function(ui, type, value) {
		        ui.$form.submit();
		    }
		});
	});
	</script--> */
	?>
	</div>
</div>