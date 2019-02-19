<?php


class QuestionController extends ControllerBase {

    /*
    * Obtener pregunta según dificultad
    */
    public function getQuestionAction() {

        $dateTime = new \DateTime();
        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "difficulty", //3 = Dificil, 2 = Medio, 1 = facil
            "id_user"
        );

        $optional = array(
            "id_game"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $question = new Question;

                if(isset($dataRequest->id_game)){

                    $game_id = $dataRequest->id_game;
                    goto getQuestion;

                    getQuestion: {
                        $q = ($question->getQuestion($dataRequest->difficulty))->fetchAll();

                        $question_game = QuestionGame::findFirst(array(
                            "conditions" => "id_question = ?1",
                            "bind" => array(1 => $q[0]['id_question'])
                        ));

                        if(isset($question_game->id)){
                            goto getQuestion;
                        }
                    }

                } else {

                    $game = new Game;
                    $game->id_user = $dataRequest->id_user;
                    $game->register_date = $dateTime->format('Y-m-d H:i:s');
                    $game->difficulty = $dataRequest->difficulty;

                    if($game->save()){

                        $q = ($question->getQuestion($dataRequest->difficulty))->fetchAll();

                    } else {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => QuestionConstants::CREATE_GAME_FAILURE,
                            "status" => ControllerBase::FAILED
                        ));
                    }

                    $game_id = $game->id_game;
                    
                }

                $validate = $this->validateGameDifficulty($game_id, $dataRequest->difficulty);
                print_r($validate);die;

                if (count($q) > 0){
                    $answer = Answer::find(array(
                        "conditions" => "id_question = ?1",
                        "bind" => array(1 => $q[0]['id_question'])
                    ));

                    if (count($answer) > 0){

                        foreach($answer as $item){

                            $answers[] = [
                                "id_answer" => $item->id_answer,
                                "answer" => $item->answer,
                                "is_correct" => $item->is_correct
                            ];
                        }

                        $data = [
                            "id_game" => $game_id,
                            "id_question" => $q[0]['id_question'],
                            "question" => $q[0]['question'],
                            "answers" => $answers
                        ];

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "message" => QuestionConstants::CREATE_GAME_SUCCESS,
                            "status" => ControllerBase::SUCCESS
                        ));

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

    public function validateGameDifficulty($id_game, $difficulty){

        $question = new Question;
        $answer_correct = ($question->getTotalAnswerSuccess($id_game, $difficulty))->fetchAll();

        if($answer_correct[0][0] == 15){
            return false;
        } else {
            return $answer_correct[0][0];
        }

    }


    /*
    * Valida si la respuesta es correcta o incorrecta
    */
    public function validateAnswerAction() {


        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_question", //3 = Dificil, 2 = Medio, 1 = facil
            "id_answer",
            "id_game"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $answer_correct = Answer::findFirst(array(
                    "conditions" => "id_question = ?1 and is_correct = ?2",
                    "bind" => array(1 => $dataRequest->id_question,
                                    2 => 1)
                ));

                if(isset($answer_correct->id_answer)){

                    $question_game = new QuestionGame;
                    $question_game->id_game = $dataRequest->id_game;
                    $question_game->id_question = $dataRequest->id_question;
                    
                    if ($answer_correct->id_answer == $dataRequest->id_answer){
                        $question_game->result = true;

                        if($question_game->save()){
                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => QuestionConstants::ANSWER_CORRECT,
                                "status" => ControllerBase::SUCCESS
                            ));
                        }

                    } else {
                        $question_game->result = false;

                        if($question_game->save()){
                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => QuestionConstants::ANSWER_FAIL,
                                "status" => ControllerBase::SUCCESS
                            ));
                        }
                    }

                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => QuestionConstants::ANSWER_NOT_FOUND,
                        "status" => ControllerBase::FAILED
                    ));
                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}