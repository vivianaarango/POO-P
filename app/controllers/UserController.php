<?php


class UserController extends ControllerBase {

    /*
    * Registra un nuevo usuario
    */
    public function registerAction() {

        $dateTime = new \DateTime();
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

                $user = User::findFirst(array(
                    "conditions" => "email = ?1 ",
                    "bind" => array(1 => $dataRequest->email)
                ));

                if(isset($user->id_user)){
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::USER_ALREADY_EXIST,
                        "status" => ControllerBase::FAILED
                    ));
                } else {

                    $user = new User;
                    $user->name = $dataRequest->name;
                    $user->email = $dataRequest->email;
                    $user->password = $dataRequest->password;
                    $user->phone = $dataRequest->phone;
                    $user->image = null;
                    $user->register_date = $dateTime->format('Y-m-d H:i:s');

                    if ($user->save()){

                        for ($i = 1; $i <= 3; $i++) {
                            $user_dif = new UserDifficulty;
                            $user_dif->id_user = $user->id_user;
                            $user_dif->difficulty = $i;
                            $user_dif->is_approved = false;
                            $user_dif->save();

                            $difficulty[] = [
                                "id" => $user_dif->id,
                                "difficulty" => $user_dif->difficulty,
                                "is_approved" => $user_dif->is_approved
                            ];
                        }

                        $data = [
                            "id_user" => "$user->id_user",
                            "name" => $user->name,
                            "email" => $user->email,
                            "image" => $user->image,
                            "difficulty" => $difficulty
                        ];


                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "message" => UserConstants::SAVE_USER_SUCCESS,
                            "status" => ControllerBase::SUCCESS
                        ));
                    } else {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => UserConstants::SAVE_USER_FAILURE,
                            "status" => ControllerBase::FAILED
                        ));
                    }
                }

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

                $user = User::findFirst(array(
                    "conditions" => "email = ?1 and password = ?2",
                    "bind" => array(1 => $dataRequest->email,
                                    2 => $dataRequest->password)
                ));

                if (isset($user->id_user)){

                    $user_dif = UserDifficulty::find(array(
                        "conditions" => "id_user = ?1",
                        "bind" => array(1 => $user->id_user)
                    ));
                    

                    foreach($user_dif as $item){
                        $difficulty[] = [
                            "id" => $item->id,
                            "difficulty" => $item->difficulty,
                            "is_approved" => $item->is_approved
                        ];
                    }

                    $data = [
                        "id_user" => "$user->id_user",
                        "name" => $user->name,
                        "email" => $user->email,
                        "image" => $user->image,
                        "difficulty" => $difficulty
                    ];

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "message" => UserConstants::LOGIN_USER_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::LOGIN_USER_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}