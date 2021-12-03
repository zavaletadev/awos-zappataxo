<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carrito_model extends CI_Model {

    /**
     * [agregar description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function agregar($data)
    {
        $this->db->insert('carrito', $data);

        return TRUE;
    }

    /**
     * [lista description]
     * @param  [type] $usuario_id [description]
     * @return [type]             [description]
     */
    function lista($usuario_id)
    {
        //Creamos la consulta
        /*
        SELECT * FROM carrito 
        JOIN producto USING(producto_id) 
        WHERE producto.estatus = 1
        AND usuario_id = '$usuario_id'
         */
        $this->db->select('*');
        $this->db->from('carrito');
        $this->db->join(
            'producto', 
            'carrito.producto_id = producto.producto_id', 
            'left'
        );
        $this->db->where('producto.estatus', 1);
        $this->db->where('carrito.usuario_id', $usuario_id);
        $query = $this->db->get();

        /*
        Si no existen productos en el carrito para ese usuario 
        retornamos un arreglo vacío
         */
        return $query->num_rows() > 0 ? $query->result() : array();
    }

    /**
     * [eliminar description]
     * @param  [type] $carrito_id [description]
     * @return [type]             [description]
     */
    function eliminar($carrito_id)
    {
        $this->db->where('carrito_id', $carrito_id);        
        $this->db->delete('carrito');

        return TRUE;
    }

    /**
     * [actualizar description]
     * @param  [type] $data       [description]
     * @param  [type] $carrito_id [description]
     * @return [type]             [description]
     */
    function actualizar($data, $carrito_id)
    {
        $this->db->where('carrito_id', $carrito_id);        
        $this->db->update('carrito', $data);

        return TRUE;
    }


}

/* End of file Carrito_model.php */
/* Location: ./application/models/Carrito_model.php */
