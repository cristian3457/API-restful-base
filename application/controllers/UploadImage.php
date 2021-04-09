<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'/libraries/REST_Controller.php' );
 
use Restserver\libraries\REST_Controller;

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	//header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: Content-Type');
	exit;
}

class UploadImage extends REST_Controller {	
	
	function __construct() {
		parent::__construct();
		$this->load->database();
        $this->load->model('Usuario_model');
    } 
	function upload_post() {
		if ($this->input->method()) {
			if(!$_FILES) {
				$this->response('Please choose a file', 500);
				return;
			}
			$id = $this->uri->segment(3);
			$tipo = $this->uri->segment(4);
			$campo = $this->uri->segment(5);
			
			$upload_path = './uploads/'.$tipo.'/';
			//file upload destination
			$config['upload_path'] = $upload_path;
			//allowed file types. * means all types
			$config['allowed_types'] = 'gif|jpg|jpeg|png|GIF|JPG|JPEG|PNG|webp|WEBP';
			//allowed max file size. 0 means unlimited file size
			$config['max_size'] = '0';
			//max file name size
			$config['max_filename'] = '255';
			//whether file name should be encrypted or not
			$config['encrypt_name'] = TRUE;
			
			$this->load->library('upload', $config);
			
			if (file_exists($upload_path . $_FILES['file']['name'])) {
				$this->response('File already exists => ' . $upload_path . $_FILES['file']['name']);
				return;
			} else {
				if (!file_exists($upload_path)) {
					mkdir($upload_path, 0777, true);
				}
				
				if($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
                    $uploadedFile = $uploadData['file_name'];
					if( $tipo == 'usuarios'){
						$respuesta = $this->Usuario_model->update_imagen($id, $campo, $uploadedFile);
					}
					if( $respuesta['err'] ){
						$this->response( $respuesta, 400 );
					}else{
						$this->response( $respuesta );
					}
				} else {
					$this->response('Error during file upload => "' . $this->upload->display_errors(), 500);
					return;
				}
			}
		}
	}	
}
