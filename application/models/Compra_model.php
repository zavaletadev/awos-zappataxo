<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compra_model extends CI_Model {

    /**
     * [inserta_compra description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function inserta_compra($data)
    {
        $this->db->insert('venta', $data);        

        //Retornamos el id de la compra
        return $this->db->insert_id();
    }

    /**
     * [inserta_detalle_compra description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    function inserta_compra_detalle($data)
    {
        $this->db->insert('venta_detalle', $data);        

        return TRUE;
    }

    /**
     * [elimina_carrito_compras description]
     * @param  [type] $usuario_id [description]
     * @return [type]             [description]
     */
    function elimina_carrito_compras($usuario_id)
    {
        //DELETE FROM carrito WHERE usuario_id = ?
        $this->db->where('usuario_id', $usuario_id);
        $this->db->delete('carrito');        

        return TRUE;
    }

    /**
     * [mis_compras description]
     * @param  [type] $usuario_id [description]
     * @return [type]             [description]
     */
    function mis_compras($usuario_id)
    {
        /*
        SELECT * FROM venta WHERE usuario_id  = ?
         */        
        $cmd = "SELECT venta.*, (SELECT count(*) FROM venta_detalle WHERE venta_detalle.venta_id = venta.venta_id) AS numero_prod FROM venta WHERE usuario_id  = '$usuario_id'";
        $query = $this->db->query($cmd);

        return $query->num_rows() > 0 ? $query->result() : array();
    }

}

/* End of file Compra_model.php */
/* Location: ./application/models/Compra_model.php */
