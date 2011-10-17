<?php
class RatingsController extends RatingsAppController {

	var $name = 'Ratings';

	function delete($id){
		if ($this->Rating->delete($id)) {
			$this->flash(__('Successfully deleted', true));
			$this->redirect($this->referer());
		} else {
			$this->flash(__('Could not be deleted', true));
			$this->redirect($this->referer());
		}
	}
	

}
?>