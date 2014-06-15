air-php
=======

One file MVC framework for PHP

The main file is index.php the syntax is similar to CodeIgniter.
config.php contains different routes.

Sample
======

Load and use a model in controller

    $this->load->model('categories');
		$data['categories'] = $this->categories->getAllActive();
		
Load a view

    $this->load->view('general', $data);


Put a view in another view

    $general['main'] 		= $this->load->view('browsing/category', $data, true); 
		$this->load->view('general', $general);
