<?php


class QualificationController extends ControllerBase {


    /*
    * Obtener lista de materias con sus respectivas calificaciones
    */
    public function listAction() {


        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_user"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $subject = Subject::find(array(
                    "conditions" => "id_user = ?1",
                    "bind" => array(1 => $dataRequest->id_user)
                ));

                if (count($subject) > 0){

                    foreach ($subject as $item) {

                        $cuts = [];
                        $qualification = Qualification::find(array(
                            "conditions" => "id_subject = ?1",
                            "bind" => array(1 => $item->id_subject),
                            "order" => "cut"
                        ));

                        $count = count($qualification);
                        $accumulated = 0;

                        if ($count > 0){
                            foreach ($qualification as $value) {
                                
                                if ($value->cut != 3)
                                    $percetant = 35;
                                else
                                    $percetant = 30;

                                $total = ($percetant * $value->qualification)/100;    
                                $cuts[] = [
                                    "id_qualification" => $value->id_qualification,
                                    "cut" => 'Corte '.$value->cut,
                                    "qualification" => round($value->qualification),
                                    "value" => $total,
                                    "is_calculed" => 0 
                                ];

                                $accumulated = $accumulated + $total;
                            }
                        }
                        
                        if ((3 - $count) != 0){

                            $needs = 30 - $accumulated; 
                            $quantity = $needs/(3 - $count);
                            $cut = $count;
                            for ($i = $count; $i < 3;  $i++) {
                                $cut = $cut+1;
                                if ($cut != 3)
                                    $percetant = 35;
                                else
                                    $percetant = 30;
                                
                                $note = ($quantity * 100)/$percetant;    
                                $cuts[] = [
                                    "id_qualification" => 0,
                                    "cut" => 'Corte '.$cut,
                                    "qualification" => round($note),
                                    "value" => $quantity,
                                    "is_calculed" => 1 // 1 = calculado
                                ];
                            }
                        }

                        $data[] = [
                            "id_subject" => $item->id_subject,
                            "name" => $item->name,
                            "id_user" => $item->id_user,
                            "cuts" => $cuts,
                            "total" => $accumulated
                        ];

                    }
                   
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "message" => 'Lista de materias',
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => 'No tienes materias agregadas',
                        "status" => ControllerBase::FAILED
                    ));
                }                
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

    /*
    * Crear materia y guardar calificaciones
    */
    public function createAction() {


        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_user",
            "name"
        );

        $optionals = array(
            "cut_one",
            "cut_two",
            "cut_three",
            "id_subject"
        );

        if ($this->_checkFields($dataRequest, $fields, $optionals)) {

            try {

                $subjects = Subject::find(array(
                    "conditions" => "id_user = ?1",
                    "bind" => array(1 => $dataRequest->id_user)
                ));

                if (count($subjects) < 10){

                    if (isset($dataRequest->id_subject)){
                        $subject = Subject::findFirst(array(
                            "conditions" => "id_subject = ?1",
                            "bind" => array(1 => $dataRequest->id_subject)
                        ));
                    } else {
                        $subject = new Subject;
                        $subject->id_user = $dataRequest->id_user;
                        $subject->name = $dataRequest->name;
                        $subject->save();
                    }

                    if (isset($dataRequest->cut_one)){
                        $val_one = Qualification::findFirst(array(
                            "conditions" => "id_subject = ?1 and cut = 1",
                            "bind" => array(1 => $subject->id_subject)
                        ));

                        if (!isset($val_one->id_qualification)){
                            $qualification = new Qualification;
                            $qualification->id_subject = $subject->id_subject;
                            $qualification->cut = 1;
                            $qualification->qualification = $dataRequest->cut_one;
                            $qualification->save();
                        }
                    }
                    
                    if (isset($dataRequest->cut_two)){
                        $val_two = Qualification::findFirst(array(
                            "conditions" => "id_subject = ?1 and cut = 2",
                            "bind" => array(1 => $subject->id_subject)
                        ));

                        if (!isset($val_two->id_qualification)){
                            $qualification = new Qualification;
                            $qualification->id_subject = $subject->id_subject;
                            $qualification->cut = 2;
                            $qualification->qualification = $dataRequest->cut_two;
                            $qualification->save();
                        }
                    }

                    if (isset($dataRequest->cut_three)){
                        $val_three = Qualification::findFirst(array(
                            "conditions" => "id_subject = ?1 and cut = 3",
                            "bind" => array(1 => $subject->id_subject)
                        ));

                        if (!isset($val_three->id_qualification)){
                            $qualification = new Qualification;
                            $qualification->id_subject = $subject->id_subject;
                            $qualification->cut = 3;
                            $qualification->qualification = $dataRequest->cut_three;
                            $qualification->save();
                        }
                    }

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => 'Se ha creado correctamente',
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => 'No se pudo guardar la informacion',
                        "status" => ControllerBase::FAILED
                    ));
                
                }
              
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}