<?php


class UserController extends ControllerBase {

    /*
    * Registra un nuevo usuario
    */
    public function registerAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "name",
            "email",
            "password",
            "phone"
        );

        $optional = array(
            "image"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {
                $user = new User;
                $user->name = $dataRequest->name;
                $user->email = $dataRequest->email;
                $user->password = $dataRequest->password;
                $user->phone = $dataRequest->phone;

                

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

    /*
    * Valida ingreso de usuario
    */
    public function loginAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "email",
            "password"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}