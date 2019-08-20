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

                        for ($i = 1; $i <= 4; $i++) {
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
                        "bind" => array(1 => $user->id_user),
                        "order" => "difficulty ASC"
                    ));
                    

                    foreach($user_dif as $item){

                        if ( $item->is_approved == true ){
                            $level = '1';
                        } else {
                            $level = '0';
                        }
                        
                        $difficulty[] = [
                            "id" => $item->id,
                            "difficulty" => $item->difficulty,
                            "is_approved" => $level
                        ];
                    }

                    $game = new Game;
                    $countSuccess_1 = ($game->countAnswerTrue($user->id_user, 1))->fetchAll();
                    $countSuccess_2 = ($game->countAnswerTrue($user->id_user, 2))->fetchAll();
                    $countSuccess_3 = ($game->countAnswerTrue($user->id_user, 3))->fetchAll();
                    $countSuccess_4 = ($game->countAnswerTrue($user->id_user, 4))->fetchAll();

                    $countTotal_1 = ($game->countAnswer($user->id_user, 1))->fetchAll();
                    $countTotal_2 = ($game->countAnswer($user->id_user, 2))->fetchAll();
                    $countTotal_3 = ($game->countAnswer($user->id_user, 3))->fetchAll();
                    $countTotal_4 = ($game->countAnswer($user->id_user, 4))->fetchAll();

                    if ($countTotal_1[0]['count'] != 0)
                        $percentage_1 = ($countSuccess_1[0]['count']*100)/$countTotal_1[0]['count'];
                    else
                        $percentage_1 = 0;
                        
                    if ($countTotal_2[0]['count'] != 0)
                        $percentage_2 = ($countSuccess_2[0]['count']*100)/$countTotal_2[0]['count'];
                    else
                        $percentage_2 = 0;

                    if ($countTotal_3[0]['count'] != 0)
                        $percentage_3 = ($countSuccess_3[0]['count']*100)/$countTotal_3[0]['count'];
                    else
                        $percentage_3 = 0;
                        
                    if ($countTotal_4[0]['count'] != 0)
                        $percentage_4 = ($countSuccess_4[0]['count']*100)/$countTotal_4[0]['count'];
                    else
                        $percentage_4 = 0;

                    $data = [
                        "id_user" => "$user->id_user",
                        "name" => $user->name,
                        "email" => $user->email,
                        "image" => $user->image,
                        "difficulty" => $difficulty,
                        "result_1" => round($percentage_1),
                        "result_2" => round($percentage_2),
                        "result_3" => round($percentage_3),
                        "result_4" => round($percentage_4)
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


    public function restorePasswordMailAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "email"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $user = User::findFirst(array(
                    "conditions" => "email = ?1",
                    "bind" => array(1 => $dataRequest->email)
                ));

                if (isset($user->id_user)){

                    include_once ControllerBase::URLMAIL;
                    include_once ControllerBase::URLMAILCONFIG;
    
                    $code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTVWXYZ" . uniqid()),0,6);

                    $user->code = $code;
                    //$user->save();

                    $msg = file_get_contents('../public/mailing/mail.html');
                    $msg = str_replace("[1]", $user->name, $msg);
                    $msg = str_replace("[2]", $code, $msg);
                    $mail->From = "poopsistem2019@gmail.com";
                    $mail->FromName = 'POOP';
                    $mail->Subject = "Verificacion de usuario";
                    $mail->AltBody = "Verificacion de usuario";
                    $mail->MsgHTML($msg);
                    $mail->AddAddress($user->email, $user->name);
                    $mail->IsHTML(true);
                    $mail->send();

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => UserConstants::SEND_EMAIL_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::SEND_EMAIL_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }


    public function changePasswordAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "email",
            "password",
            "code"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $user = User::findFirst(array(
                    "conditions" => "email = ?1 and code = ?2",
                    "bind" => array(1 => $dataRequest->email,
                                    2 => $dataRequest->code)
                ));

                if (isset($user->id_user)){

                    $user->password = $dataRequest->password;
                    $user->save();
                    
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => UserConstants::PASSWORD_UPDATE_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::PASSWORD_UPDATE_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}