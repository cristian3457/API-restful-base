<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model{

    public $id;
    public $nombre;
    public $apellidos;
    public $correo;
    public $password;
    public $status;
    public $img;

    public function get_usuario( $id ){
        $this->db->where( array('id'=> $id) );
        $query = $this->db->get('usuarios');
        $row = $query->custom_row_object(0, 'Usuario_model');
        if( isset( $row )){
            $row->id = intval( $row->id );
        }
        return $row;
    }
    public function get_usuario_correo( $correo ){
        $this->db->where( array('correo'=> $correo) );
        $query = $this->db->get('usuarios');
        $row = $query->custom_row_object(0, 'Usuario_model');
        if( isset( $row )){
            $row->id = intval( $row->id );
        }
        return $row;
    }

    public function set_datos( $data_cruda ){
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            if( property_exists( 'Usuario_model', $nombre_campo) ){
                $this->$nombre_campo = $valor_campo;
            }
        }
        if( $this->status == NULL ){
            $this->status = 'activo';
        }
        return $this;
    }

    public function insert(){
        // Verifica el correo
        $query = $this->db->get_where( 'usuarios', array('correo' => $this->correo ));
        $usuario_correo = $query->row();
        if( isset( $usuario_correo ) ){
            //Existe
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'El correo elctronico ya esta registrado',
            );
            return $respuesta;
        }
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $hecho = $this->db->insert( 'usuarios', $this );
        if( $hecho ){
            //Insertado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'usuario_id' => $this->db->insert_id()
            );

        }else{
            //Si no sucedio
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }

    public function update( ){
        // Verifica el correo
        $this->db->where( 'correo =', $this->correo );
        $this->db->where( 'id !=', $this->id );
        $query = $this->db->get( 'usuarios');
        $usuario_correo = $query->row();

        if( isset( $usuario_correo ) ){
            //Existe
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'El correo elctronico ya esta registrado por otro usuario',
            );
            return $respuesta;
        }
        $this->db->reset_query();
        $this->db->where( 'id', $this->id );
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $hecho = $this->db->update( 'usuarios', $this );
        if( $hecho ){
            //Insertado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro actualizado correctamente',
                'usuario_id' => $this->id
            );

        }else{
            //Si no sucedio
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }

    public function update_imagen($id, $campo, $nombreImg){
        $this->db->set($campo, $nombreImg);
        $this->db->where( 'id', $id );
        $hecho = $this->db->update('usuarios');
        if( $hecho ){
            //Insertado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro actualizado correctamente',
                'usuario_id' => $this->id
            );

        }else{
            //Si no sucedio
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }
    public function delete( $usuario_id ){
        $this->db->set( 'status', 'borrado');
        $this->db->where( 'id', $usuario_id);
        $hecho = $this->db->update( 'usuarios');
        if( $hecho ){
            //Borrado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro eliminado correctamente'
            );
        }else{
            //Si no sucedio
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al borrar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }
}