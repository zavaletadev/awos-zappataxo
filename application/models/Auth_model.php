<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {


    /**
     * Función para iniciar sesión de cualquier tipo de  usuario
     * @param  [type] $usuario  [description]
     * @param  [type] $password [description]
     * @param  [type] $rol_id   [description]
     * @param  [type] $estatus  [description]
     * @return [type]           [description]
     */
    function login($usuario, $rol_id = NULL)
    {
        //SELECT * FROM usuario WHERE (email_auth LIKE BINARY '$usuario' OR telefono_auth='$usuario') AND estatus = $estatus AND rol_id = $rol_id;
        $this->db->where("(email_auth LIKE BINARY '$usuario' OR telefono_auth LIKE BINARY '$usuario')");

        //Si se envia un rol lo evaluamos
        if (!is_null($rol_id)) {
            $this->db->where('rol_id', $rol_id);
        }
        
        //Ejecutamos la consulta
        $query = $this->db->get('usuario');

        //Si encontramos un usuario, lo devolvemos, de lo contrario
        //devolvemos nulo
        return ($query->num_rows() === 1) ? $query->row() : NULL;
    }

    /**
     * [existen_tokens description]
     * @param  [type] $md5_id   [description]
     * @param  [type] $md5_pass [description]
     * @return [type]           [description]
     */
    function existen_tokens($md5_id, $md5_pass)
    {
        $this->db->where('md5(usuario_id) LIKE BINARY', $md5_id);        
        $this->db->where('pass_auth LIKE BINARY', $md5_pass);

        $query = $this->db->get('usuario');

        return $query->num_rows() === 1 ? TRUE : FALSE;
    }

    /**
     * [get_id_by_md5id description]
     * @param  [type] $md5_id [description]
     * @return [type]         [description]
     */
    function get_id_by_md5id($md5_id)
    {
        $this->db->where('md5(usuario_id) LIKE BINARY', $md5_id);
        $query = $this->db->get('usuario');

        return $query->num_rows() === 1 ? $query->row()->usuario_id : NULL;
    }

}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */
