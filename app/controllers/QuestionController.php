<?php


class QuestionController extends ControllerBase {

    /*
    * Obtener pregunta según dificultad
    */
    public function getQuestionAction() {

        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "difficulty" //3 = Dificil, 2 = Medio, 1 = facil
        );

        $optional = array(
            "id_game"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $question = new Question;

                if(isset($dataRequest->id_game)){

                } else {

                }
                
                $q = ($question->getQuestion($dataRequest->difficulty))->fetchAll();
       
                if (count($q) > 0){
          
                    $answer = Answer::find(array(
                        "conditions" => "id_question = ?1",
                        //"bind" => array(1 => $q[0]['id_question'])
                        "bind" => array(1 => 1)
                    ));

                    if (count($answer) > 0){

                        foreach($answer as $item){
                            $answer[] = [
                                "id_answer" => "$answer->id_answer",
                                "answer" => "$comment->answer",
                                "is_correct" => "$comment->is_correct",
                            ];
                        }

                    } else {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => QuestionConstants::GET_ANSWER_FAILURE,
                            "status" => ControllerBase::FAILED
                        ));
                    }

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => QuestionConstants::GET_QUESTION_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }


    /*
    * Asignar preguntas al usuario
    */
    // public function assignQuestionAction() {

    //     $dataRequest = $this->request->getJsonPost();

    //     $fields = array(
    //         "id_user", 
    //         "difficult" //3 = Dificil, 2 = Medio, 1 = facil
    //     );

    //     if ($this->_checkFields($dataRequest, $fields)) {

    //         try {

    //             $question = new Question;
    //             $q = $question->getQuestion($dataRequest->difficult, 10);
    //             print_r($q);die;

    //         } catch (Exception $e) {
    //             $this->logError($e, $dataRequest);
    //         }
    //     }
    // }

}