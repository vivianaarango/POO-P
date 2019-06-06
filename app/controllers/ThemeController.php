<?php


class ThemeController extends ControllerBase {

    /*
    * Listar temas 
    */
    public function themeListAction() {

        $dateTime = new \DateTime();
        $dataRequest = $this->request->getJsonPost();

        $fields = array();
        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $themeList = Theme::find();

                foreach ($themeList as $value) {

                    $items = [];
                    $theme_item = ThemeItem::find(array(
                        "conditions" => "id_theme = ?1 ",
                        "bind" => array(1 => $value->id_theme)
                    ));

                    foreach ($theme_item as $item) {
                        $items[] = [
                            "id_item" => $item->id_item,
                            "name" => $item->name,
                            "type" => $item->type
                        ];
                    }
                    if (count($items) > 0){
                        $data[] = [
                            "id_theme" => $value->id_theme,
                            "name" => $value->name,
                            "items" => $items
                        ];
                    }
                }

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => true,
                    "data" => $data,
                    "message" => ThemeConstants::GET_THEME_SUCCESS,
                    "status" => ControllerBase::SUCCESS
                ));


            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }
}