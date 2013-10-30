<?php debug($this->request->data); ?>
<?php debug($ratee); ?>
<?php debug($rator); ?>
<div class="row-fluid">
	<div class="span3 userProfileInfoBox">
		<div>
			<?php echo $this->Element('thumb', array('thumbClass' => 'media-object', 'model' => 'User', 'foreignKey' => $this->request->data['User']['id']), array('plugin' => 'galleries')); ?>
		</div>
		<div>
			<b>Registered: </b><?php echo $this->Time->nice($this->request->data['User']['created'])?>
			<br />
			<b>Last Sign In: </b><?php echo $this->Time->timeAgoInWords($this->request->data['User']['last_login'])?>
		</div>
		<div>
			<h3>Member Email</h3>
			<?php echo $this->request->data['User']['email']; ?>
		</div>
		<div>
			<h3>Contact Number</h3>
			<?php echo $this->request->data['User']['phone']; ?>
		</div>
	</div>
	<div class="span9 userProfileFeedbackBox">
		<div class="row">
			<div class="row userProfileFeedbackBoxHeader">
				<span>Positive feedback: <b>X%</b></span>
				<span> | </span>
				<span>Feedback score: <b>X</b></span>
			</div>
				
			<div class="span12">

				<div style="padding: 15px;">
			
				<div class="row">
					<h1><?php echo $this->request->data['User']['full_name']; ?> *</h1>
				</div>
				<div class="row">
					<div class="span6">
						<div><b>Member Quick Links</b></div>
						<div>(<b>X</b>) Items for sale</div>
						<div><a href="mailto:<?php echo $this->request->data['User']['email']?>">Contact Member</a></div>
					</div>
				</div>
				<div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab1" data-toggle="tab">Feedback as Seller</a></li>
							<li><a href="#tab2" data-toggle="tab">Feedback as Buyer</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">
								<table class="table">
									<thead>
										<tr><th>Feedback</th><th>From / Price</th><th>Date</th></tr>
										<?php
										 
										?>
									</thead>
								</table>
							</div>
							<div class="tab-pane" id="tab2">
								<table class="table">
									<thead>
										<tr><th>Feedback</th><th>From / Price</th><th>Date</th></tr>
										<?php
										 
										?>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
		
	</div>
</div>
<?php #debug($this->request->data) ?>
