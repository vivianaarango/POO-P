<?php


class CalendarController extends ControllerBase {


    /*
    * Obtener lista de fechas y/o eventos guardados
    */
    public function listAction() {


        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_user"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $calendar = Calendar::find(array(
                    "conditions" => "id_user = ?1",
                    "bind" => array(1 => $dataRequest->id_user),
                    "order" => "fecha"
                ));

                if (count($calendar) > 0 ) {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $calendar,
                        "message" => UserConstants::CALENDAR_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::CALENDAR_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));

                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

    /*
    * Obtener lista de fechas y/o eventos guardados
    */
    public function createAction() {


        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_user",
            "description",
            "date"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $calendar = new Calendar;
                $calendar->description = $dataRequest->description;
                $calendar->id_user = $dataRequest->id_user;
                $calendar->date = $dataRequest->date;

                if ($calendar->save()){
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => UserConstants::CREATE_CALENDAR_SUCCESS,
                        "status" => ControllerBase::SUCCESS
                    ));
                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => UserConstants::CREATE_CALENDAR_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}