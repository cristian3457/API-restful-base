<?php 
if( ! defined('BASEPATH') ) exit('No direct script access allowed');


$config = array(

	'usuario_post' => array(
		array( 'field'=>'correo', 'label'=>'correo electronico','rules'=>'trim|required|valid_email' ),
		array( 'field'=>'nombre', 'label'=>'nombre','rules'=>'trim|required|min_length[2]|max_length[100]' ),
		array( 'field'=>'apellidos', 'label'=>'apellidos','rules'=>'trim|required|min_length[2]|max_length[100]' ),
		array( 'field'=>'password', 'label'=>'password','rules'=>'trim|required|min_length[8]|max_length[16]' )
	),
	'usuario_put' => array(
		array( 'field'=>'id', 'label'=>'cliente id','rules'=>'trim|required|integer' ),
		array( 'field'=>'correo', 'label'=>'correo electronico','rules'=>'trim|required|valid_email' ),
		array( 'field'=>'nombre', 'label'=>'nombre','rules'=>'trim|required|min_length[2]|max_length[100]' ),
		array( 'field'=>'apellidos', 'label'=>'apellidos','rules'=>'trim|required|min_length[2]|max_length[100]' ),
		array( 'field'=>'password', 'label'=>'password','rules'=>'trim|required|min_length[8]|max_length[16]' )
	),
	'usuarioImagen_post' => array(
		array( 'field'=>'img', 'label'=>'img','rules'=>'trim|required|min_length[5]|max_length[200]' ),
	),
	'login_post' => array(
		array( 'field'=>'correo', 'label'=>'correo electronico','rules'=>'trim|required|valid_email' ),
		array( 'field'=>'password', 'label'=>'password','rules'=>'trim|required|min_length[8]|max_length[16]' )
	),

);




?>