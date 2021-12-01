<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carrito_model extends CI_Model {

    function agregar($data)
    {
        $this->db->insert('carrito', $data);

        return TRUE;
    }
    

}

/* End of file Carrito_model.php */
/* Location: ./application/models/Carrito_model.php */
