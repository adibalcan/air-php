<?php
class start extends controller {

	public function teste(){
		$this->load->model('testmodel');

		$date['text'] = $this->testmodel->test();

		$this->load->view('vezi', $date);
	}
	
	public function index(){
		echo 'Asta e marele index';
	}
}