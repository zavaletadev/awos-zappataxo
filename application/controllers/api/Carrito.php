<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carrito extends CI_Controller {

    /**
     * [__construct description]
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('carrito_model');
        $this->load->model('auth_model');
    }

    function index()
    {
        die(
            '
            <br />
            <br />
            <br />
            <center>
            <h1>           
            Carrito API
            <hr />
            </h1>
            </center>'
        );                
    }

    /**
     * [agregar description]
     * @example [POST(usuario_id,producto_id,cantidad,talla_prod,precio_prod)] https://zavaletazea.dev/labs/awos-dapps-zappataxo/api/carrito/agregar
     *          
     * @return [type] [description]
     */
    function agregar()
    {
        header('Content-Type: application/json; charset=utf-8');

        /*
        Aplicamos la validación
         */
        $this->form_validation->set_rules('id', 'id', 'trim|required|exact_length[32]');
        $this->form_validation->set_rules('producto_id', 'producto_id', 'trim|required|integer');
        $this->form_validation->set_rules('cantidad', 'cantidad', 'trim|required|integer');
        $this->form_validation->set_rules('talla_prod', 'talla_prod', 'trim|required|numeric');
        $this->form_validation->set_rules('precio_prod', 'precio_prod', 'trim|required|numeric');

        if ($this->form_validation->run()) {

            /*
            Tommamos el id encriptado desde android
             */
            $id          = $this->input->post('id');
            $producto_id = $this->input->post('producto_id');
            $cantidad    = $this->input->post('cantidad');
            $talla_prod  = $this->input->post('talla_prod');
            $precio_prod = $this->input->post('precio_prod');            

            $this->carrito_model->agregar(
                array(
                    //a aprtir del id encriptado buscamos el id sin encriptar
                    "usuario_id"  => $this->auth_model->get_id_by_md5id($id), 
                    "producto_id" => $producto_id, 
                    "cantidad"    => $cantidad, 
                    "talla_prod"  => $talla_prod, 
                    "precio_prod" => $precio_prod
                )
            );

            echo json_encode(
                array(
                    'code' => 200
                )
            );

        }

        /*
        Si la validación no se cumple
         */
        else {
            //Eliminamos las etiquetas de los códigos de error
            $this->form_validation->set_error_delimiters('', '');
            echo json_encode(
                array(
                    'code' => 400, 
                    'errors' => validation_errors()
                )
            );
        }
    }

    /**
     * [lista description]
     * @return [type] [description]
     */
    function lista()
    {
        $this->form_validation->set_rules('id', 'id', 'trim|required|exact_length[32]');

        if ($this->form_validation->run()) {

        }

        /*
        Si la validación no se cumple
         */
        else {
            //Eliminamos las etiquetas de los códigos de error
            $this->form_validation->set_error_delimiters('', '');
            echo json_encode(
                array(
                    'code' => 400, 
                    'errors' => validation_errors()
                )
            );
        }
    }

}

/* End of file Carrito.php */
/* Location: ./application/controllers/api/Carrito.php */
