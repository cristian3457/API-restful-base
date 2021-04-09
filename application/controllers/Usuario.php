<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'/libraries/REST_Controller.php' );
 
use Restserver\libraries\REST_Controller;

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	// header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: Content-Type,X-Amz-Date,Authorization,X-Api-Key,Origin,Accept,Access-Control-Allow-Headers,Access-Control-Allow-Methods,Access-Control-Allow-Origin');
	exit;
}

class Usuario extends REST_Controller {

    public function __construct(){
        //Llamado del constructor del padre
        parent::__construct();
        $this->load->database();
        $this->load->model('Usuario_model');
        // $this->load->helper('utilidades');
    }

    public function paginar_get(){
        $this->load->helper('paginacion');
        $pagina = $this->uri->segment(2);
        $por_pagina = $this->uri->segment(3);
        $respuesta = paginar_todo( 'usuarios', $pagina, $por_pagina );
        $this->response( $respuesta );
    }
    public function usuario_get(){

        $usuario_id = $this->uri->segment(2);

        if( !isset( $usuario_id ) ){
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Es necesario el ID del usuario'
            );

            $this->response( $respuesta, 400 );
            return;
        }
        $usuario = $this->Usuario_model->get_usuario($usuario_id);

        if( isset( $usuario) ){
            unset($usuario->password);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro cargado corectamente.',
                'usuario' => $usuario
            );
            $this->response( $respuesta );
        }else{
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'El registro con el id '. $usuario_id .', no existe.',
                'usuario' => null
            );
            $this->response( $respuesta, 404 );
        }
    }
    public function usuario_post(){
        $data = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data( $data );
        // TRUE :: TODO BIEN       FALSE :: Falla una regla
        if( $this->form_validation->run( 'usuario_post' ) ) {
            // Todo bien
            $usuario = $this->Usuario_model->set_datos( $data );
            $respuesta = $usuario->insert();
            if( $respuesta['err'] ){
                $this->response( $respuesta, 400 );
            }else{
                $this->response( $respuesta );
            }

        }else{
            //Algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Hay errores en el envio de información',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response( $respuesta, 400 );
        }
    }
    public function usuario_put(){
        $usuario_id = $this->uri->segment(3);
        $data = $this->put();
        $data['id'] = $usuario_id;
        $this->load->library('form_validation');
        $this->form_validation->set_data( $data );
        // TRUE :: TODO BIEN       FALSE :: Falla una regla
        if( $this->form_validation->run( 'usuario_put' ) ) {
            // Todo bien
            $usuario = $this->Usuario_model->set_datos( $data );
            $respuesta = $usuario->update();
            if( $respuesta['err'] ){
                $this->response( $respuesta, 400 );
            }else{
                $this->response( $respuesta );
            }
        }else{
            //Algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Hay errores en el envio de información',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response( $respuesta, 400 );
        }
    }
    public function usuario_delete(){
        $usuario_id = $this->uri->segment(3);
        $respuesta = $this->Usuario_model->delete( $usuario_id );
        $this->response( $respuesta );
    }
    public function login_post(){
        $data = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data( $data );
        if( $this->form_validation->run( 'login_post' ) ) {
            $usuario = $this->Usuario_model->get_usuario_correo($data['correo']);
            if( isset( $usuario) ){
                if(password_verify($data['password'], $usuario->password) ){
                    $respuesta = array(
                        'err' => FALSE,
                        'mensaje' => 'Credenciales correctas',
                    );
                    $this->response( $respuesta );
                }else{
                    $respuesta = array(
                        'err' => TRUE,
                        'mensaje' => 'Credenciales incorrectas',
                    );
                    $this->response( $respuesta, 404 );
                }
            }else{
                $respuesta = array(
                    'err' => TRUE,
                    'mensaje' => 'Credenciales incorrectas',
                );
                $this->response( $respuesta, 404 );
            }
        }else{
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Hay errores en el envio de información',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response( $respuesta, 400 );
        }
    }

}