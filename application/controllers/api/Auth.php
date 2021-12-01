<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    /**
     * [__construct description]
     */
    function __construct()
    {
        parent::__construct();
        //Cargamos el modelo de autenticación
        $this->load->model('auth_model');
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    function index()
    {
        die('
            <center>
            <br>
            <br>
            <br>
            <h1>ZAPPATAXO</h1>
            <hr>
            <h2>API</h2>
            <br>
            <br>
            <h3>Auth API</h3>
            </center>
            ');
    }

    /**
     * [login_app_movil description]
     * @return [type] [description]
     * @example [POST(usuario, password)] https://zavaletazea.dev/labs/awos-dapps-zappataxo/api/auth/login_app_movil
     * @data [usuario, password]
     */
    function login_app_movil()
    {
        /*
        En application/helpers/app_helper.php 
        creamos una función que indique que el resultado
        de esta función es en formato json
         */
        json_header();
        /*
        Realizamos las validaciones correspondientes para indicar
        que nuestro servicio fue invocado mediante POST
         */
        
        //Validamos que cuando se mande llamar esta funcion, venga un dato 
        //de tipo post que se llame usuario
        $this->form_validation->set_rules('usuario', 'usuario', 'trim|required');

        //Validamos que cuando se mande llamar esta funcion, venga un dato 
        //de tipo post que se llame usuario
        $this->form_validation->set_rules('password', 'password', 'trim|required|exact_length[32]');

        /*
        Si la validación se cumple
         */
        if ($this->form_validation->run()) {
            /*
            Tomamos las variables post  que nos mandaron 
            desde android
             */
            $usuario  = $this->input->post('usuario');
            $password = $this->input->post('password');           
            

            /*
            Invocar a un modelo para revisar si esxiste un 
            usuario con ese correo o telefono
             */
            $datos_usuario = $this->auth_model->login($usuario, $rol_id = 3);

            /*
            Si el usuario tiene datos (existe en la tabla usuario)
             */
            if (!is_null($datos_usuario)) {
                /*
                Por seguridad la contraseña se valida desde el código fuente

                Si el usuario y la contraseña son correctos, 
                entonces devolvemos un objeto json 
                con los datos del usuario
                 */
                if ($datos_usuario->pass_auth === $password) {
                    /*
                    Si el estatus dle usuario es activo (1)
                     */
                    if ($datos_usuario->estatus == 1) {
                        /*
                        Retornamos los datos públicos del usuario
                        */
                        echo json_encode(
                            array(
                                'code'    => 200, 
                                'datos_usuario' => array(
                                    'usuario_id' => $datos_usuario->usuario_id,
                                    'rol_id'     => $datos_usuario->rol_id,
                                    'email'      => $datos_usuario->email_auth, 
                                    'telefono'   => $datos_usuario->telefono_auth
                                )
                            )
                        );
                    }

                    /*
                    Si el usuario está deshabilitado
                     */
                    else if ($datos_usuario->estatus == 2) {
                        echo json_encode(
                            array(
                                'code' => 403
                            )
                        );
                    }

                    /*
                    Si es cualquier otro estaus
                     */
                    else {
                        echo json_encode(
                            array(
                                'code' => 404
                            )
                        );
                    }

                }

                //Contraseña no coincide
                else {
                    echo json_encode(
                        array(
                            'code' => 404
                        )
                    );
                }
            }

            //Usuario no encontrado 
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

    /**
     * [auto_login_movil description]
     * @return [type] [description]
     */
    function auto_login_movil()
    {
        json_header();

        /*
        Validamos los datos del servicio
         */
        $this->form_validation->set_rules('id', 'id', 'trim|required|exact_length[32]');
        $this->form_validation->set_rules('user_key', 'user_key', 'trim|required|exact_length[32]');

        if ($this->form_validation->run()) {
            $id = $this->input->post('id');
            $user_key = $this->input->post('user_key');

            /*
            Validamos que exista el id y el password del usuario en formato md5
             */
            $existe_token = $this->auth_model->existen_tokens($id, $user_key);

            /*
            En caso de que los tokens existan retornamos verdaderol de lo contrario retornamos 
            falso
             */
            echo json_encode(
                array(
                    'code' => $existe_token ? 200 : 404
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

/* End of file Auth.php */
/* Location: ./application/controllers/api/Auth.php */
