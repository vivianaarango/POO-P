<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Question extends Model
{

    /**
     *
     * @var integer
     */
    public $id_question;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var integer
     */
    public $difficulty;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }


    public function getQuestion($difficulty, $limit = 1)
    {
        $sql = " SELECT *
                 FROM question q
                 WHERE q.difficulty = $difficulty
                 ORDER BY random() LIMIT $limit ";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        return $prepare;
    }

    public function getTotalAnswerSuccess($id_game, $difficulty)
    {
        $sql = " SELECT COUNT(qg.id) total           
                 FROM question_game qg
                 INNER JOIN question q ON qg.id_question = q.id_question
                 WHERE qg.id_game = $id_game AND qg.result = TRUE AND q.difficulty = $difficulty";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        return $prepare;
    }



}
