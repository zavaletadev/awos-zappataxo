<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {

    /**
     * [__construct description]
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('producto_model');
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    function index()
    {
        die(
            '
            <br />
            <br />
            <br />
            <center>
            <h1>           
            Productos API
            <hr />
            </h1>
            </center>'
        );        
    }

    /**
     * [lista description]
     * @return [type] [description]
     * @example [GET] https://zavaletazea.dev/labs/awos-dapps-zappataxo/api/productos/lista
     */
    function lista()
    {
        header('Content-Type: application/json; charset=utf-8');

        /*
        Seleccionamos todos los productos activos (estatus 1)
         */
        $info_productos = $this->producto_model->get_productos(1);
        $lista_productos = array();

        /*
        Si tenemos productos
         */
        if (!is_null($info_productos)) {
            /*
            Recorremos todos los productos para agregar sus tallas
             */
            foreach($info_productos as $producto) {

                /*
                Seleccionamos las tallas de cada producto
                 */
                $tallas_prod = $this->producto_model->get_tallas($producto->producto_id);                

                /*
                Creamos un arreglo para agregar la talla a la información 
                del producto
                 */
                $producto->tallas = array();

                /*
                Recorremos todas las tallas de cada producto
                 */
                foreach($tallas_prod as $tallas) {
                    /*
                     gregamos al arreglo de tallas
                     */
                     $producto->tallas[] = $tallas->talla;
                 }

                //Agregamos el arreglo de las tallas al objeto dle producto
                 $lista_productos[] = $producto;
             }
         }

         echo json_encode(
            array(
                'code' => 200, 
                'data' => $lista_productos
            )
        );

     }

    /**
     * [detalle_producto description]
     * @example [POST(producto_id)] 
     * @return [type] [description]
     */
    function detalle()
    {
        //Header
        header('Content-Type: application/json; charset=utf-8');

        /*
        Indicamos las validaciones del producto
         */
        $this->form_validation->set_rules('producto_id', 'producto_id', 'trim|required|numeric');

        /*
        Validamos que se cumpla la validación
         */
        if ($this->form_validation->run()) {

            $producto_id = $this->input->post('producto_id');

            /*
            Tomamos la información del producto
             */
            $info_producto = $this->producto_model->get_producto($producto_id);

            /*
            Si existe información de este producto
             */
            if (!is_null($info_producto)) {
                /*
                Seleccionamos las tallas de cada producto
                */
                $tallas_prod = $this->producto_model->get_tallas($info_producto->producto_id);                

                /*
                Creamos un arreglo para agregar la talla a la información 
                del producto
                */
                $info_producto->tallas = array();

                /*
                Recorremos todas las tallas del producto
                 */
                foreach($tallas_prod as $tallas) {
                    /*
                     Agregamos al arreglo de tallas
                     */
                     $info_producto->tallas[] = $tallas->talla;
                 }

                 /*
                 Mostramos los datos del producto, in
                  */
                 echo json_encode(
                    array(
                        'code' => 200, 
                        'data' => $info_producto
                    )
                );
             }

            /*
            Si no se encuentra información de un producto por medio 
            del id
             */
            else {
                echo json_encode(
                    array(
                        'code' => 404
                    )
                );
            }
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

/* End of file Productos.php */
/* Location: ./application/controllers/api/Productos.php */
