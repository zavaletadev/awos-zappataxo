<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compra extends CI_Controller {

    /**
     * [__construct description]
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('compra_model');
        $this->load->model('auth_model');
    }

    function index()
    {
        die('<center>
            <h1>Api Comras <h1>
            <hr/>
            </center>
            ');
    }

    /**
     * [realiza_compra description]
     * @example [POST(id,total_venta,arr_productos)] https://zavaletazea.dev/labs/awos-dapps-zappataxo/api/compra/realiza_compra
     * @return [type] [description]
     */
    function realiza_compra()
    {
        header('Content-Type: application/json; charset=utf-8');
        /*
        El servicio validará los datos de:
        usuario_id (viene encriptado en md5)
        total_venta
        fecha_venta
         */
        $this->form_validation->set_rules('id', 'id', 'trim|required|exact_length[32]');
        $this->form_validation->set_rules('total_venta', 'total_venta', 'trim|required|numeric');
        $this->form_validation->set_rules('arr_productos', 'arr_productos', 'trim|required');

        /*
        Si pasamos la validación
         */
        if ($this->form_validation->run()) {
            $id = $this->input->post('id');
            $total_venta = $this->input->post('total_venta');
            $fecha_venta = date('Y-m-d H:i:s'); //Fecha y hora del momento
            $arr_productos_json = $this->input->post('arr_productos');
            $estatus = 1;

            /*
            Invocamos la inserción de compra desde el modelo
             */
            $arr_inserta_compra = array(
                "usuario_id"  => $this->auth_model->get_id_by_md5id($id), 
                "total_venta" => $total_venta, 
                "fecha_venta" => $fecha_venta,
                "estatus"     => $estatus
            );

            $compra_id = $this->compra_model->inserta_compra($arr_inserta_compra);

            /*
            Convertir el arreglo de productos en formato JSON a un arreglo asociativo de PHP            
             */
            $lista_prodcutos = json_decode($arr_productos_json);

            /*
            Iteramos el arreglo de productos del carito para insertarse en 
            el detalle de la venta
             */
            foreach($lista_prodcutos as $producto) {
                /*
                Datos a insertar
                venta_id
                producto_id
                cantidad
                talla_prod
                precio_prod
                 */                
                $arr_detalle_prod = array(
                    "venta_id"    => $compra_id, 
                    "producto_id" => $producto->producto_id,
                    "cantidad"    => $producto->cantidad, 
                    "talla_prod"  => $producto->talla_prod,
                    "precio_prod" => $producto->precio_prod
                );

                //Insertamos cada detalle de la compra de productos
                $this->compra_model->inserta_compra_detalle($arr_detalle_prod);                
            }

            //Eliminamos los productos del carrito
            //a aprtir de su id de usuario encriptado
            $this->compra_model->elimina_carrito_compras(
                $this->auth_model->get_id_by_md5id($id)
            );

            echo json_encode(                
                array(
                    "code" => 200
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
     * [mis_compras description]
     * @example [POST(id)] https://zavaletazea.dev/labs/awos-dapps-zappataxo/api/compra/mis_compras
     * @return [type] [description]
     */
    function mis_compras()
    {
        header('Content-Type: application/json; charset=utf-8');
        /*
        Validacion
         */
        $this->form_validation->set_rules('id', 'id', 'trim|required|exact_length[32]');

        if ($this->form_validation->run()) {
            $id = $this->input->post('id');

            $lista_compras = $this->compra_model->mis_compras(
                $this->auth_model->get_id_by_md5id($id)
            );

            echo json_encode(
                array(
                    'code' => 200, 
                    'data' => $lista_compras
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

}

/* End of file Compra.php */
/* Location: ./application/controllers/api/Compra.php */
