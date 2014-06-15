<?php
class start extends controller {

	public function test(){
		//This action is visible at http://{YOUR URL}/start/test

		$this->load->model('testmodel');

		$date['text'] = $this->testmodel->test();

		$this->load->view('show', $date);
	}
	
	public function index(){
		echo 'Hello world!';
	}
}