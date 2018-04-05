<?php

namespace App\Models\Entities;


use DateTime;
use Exception;

class CPMActivity
{
    public $id;
    public $name;
    public $duration;
    public $est;
    public $lst;
    public $eet;
    public $let;
    public $successors = [];
    public $predecessors = [];
    public $isCritical = false;
    public $start_date;
    public $end_date;
    public $activity_id;
    public $float;
    public $lst_date;
    public $let_date;
    public $status_id;
    public $delivery_date;
    public $sql;
    public $isNeeded = true;

    /**
     * @return bool
     */
    public function getIsNeeded()
    {
        return $this->isNeeded;
    }
    /**
     * @param bool $isNeeded
     * @return $this
     */
    public function setIsNeeded($isNeeded)
    {
        $this->isNeeded = $isNeeded;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param $sql
     * @return $this
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * @param $delivery_date
     * @return $this
     */
    public function setDeliveryDate($delivery_date)
    {
        $this->delivery_date = $delivery_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->status_id;
    }
    /**
     * @param $status_id
     * @return $this
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
        return $this;
    }
    public function getFloat()
    {
        return $this->float;
    }
    public function setFloat($float)
    {
        $this->float = $float;
        return $this;
    }
    public function getActivityId()
    {
        return $this->activity_id;
    }
    public function setActivityId($activity_id)
    {
        $this->activity_id = $activity_id;
        return $this;
    }
    public function getLstDate()
    {
        return $this->lst_date;
    }
    public function setLstDate($lst_date)
    {
        $this->lst_date = $lst_date;
        return $this;
    }
    public function getLetDate()
    {
        return $this->let_date;
    }
    public function setLetDate($let_date)
    {
        $this->let_date = $let_date;
        return $this;
    }
    public function getStartDate()
    {
        return $this->start_date;
    }
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
        return $this;
    }
    public function getEndDate()
    {
        return $this->end_date;
    }
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }
    public function getIsCritical()
    {
        return $this->isCritical;
    }
    public function setIsCritical($isCritical)
    {
        $this->isCritical = $isCritical;
        return $this;
    }
    /**
     * base start time
     * @var
     */
    public $ost;
    /**
     * adjusted start time
     * @var
     */
    public $ast;

    /**
     * @return mixed
     */
    public function getOst()
    {
        return $this->ost ?? 0;
    }

    /**
     * @param mixed $ost
     */
    public function setOst($ost)
    {
        $this->ost = $ost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAst()
    {
        return $this->ast ?? 0;
    }

    /**
     * @param mixed $ast
     */
    public function setAst($ast)
    {
        $this->ast = $ast;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CPMActivity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return CPMActivity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration ?? 0;
    }

    /**
     * @param mixed $duration
     * @return CPMActivity
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEst()
    {
        return $this->est ?? 0;
    }

    /**
     * @param mixed $est
     * @return CPMActivity
     */
    public function setEst($est)
    {
        $this->est = $est;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLst()
    {
        return $this->lst ?? 0;
    }

    /**
     * @param mixed $lst
     * @return CPMActivity
     */
    public function setLst($lst)
    {
        $this->lst = $lst;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEet()
    {
        return $this->eet ?? 0;
    }

    /**
     * @param mixed $eet
     * @return CPMActivity
     */
    public function setEet($eet)
    {
        $this->eet = $eet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLet()
    {
        return $this->let ?? 0;
    }

    /**
     * @param mixed $let
     * @return CPMActivity
     */
    public function setLet($let)
    {
        $this->let = $let;
        return $this;
    }

    /**
     * @return array
     */
    public function getPredecessors()
    {
        return $this->predecessors;
    }

    /**
     * @param array $predecessors
     * @return CPMActivity
     */
    public function setPredecessors($predecessors)
    {
        $this->predecessors = $predecessors;
        return $this;
    }

    /**
     * @return array
     */
    public function getSuccessors()
    {
        return $this->successors;
    }
    /**
     * @param array $successors
     * @return CPMActivity
     */
    public function setSuccessors($successors)
    {
        $this->successors = $successors;
        return $this;
    }

    /**
     * CPMActivity constructor.
     */
    public function __construct()
    {
        //
    }
    /**
     * Performs a check to verify if an activity exists.
     * @param $activities
     * @param $id
     * @return null
     */
    public function CheckActivity($activities, $id)
    {
        foreach($activities as $activity)
        {
          if ($activity->getActivityId() == $id)
          {
              return $activity;
          }
        }
        return null;
    }
    public function GetIndex($activities, $activity)
    {
        foreach($activities as $idx => $aux)
        {
            if ($aux->getActivityId() == $activity->getActivityId())
            {
                return $idx;
            }
        }
        return 0;
    }
    public function setSuccessor($aux, $activity)
    {
        $successors = $this->getSuccessors();
        $successors[] = $activity;
        return $aux;
    }
    public function AddSuccessor($activity)
    {
        if (!$activity) return $this;
        $successors = $this->getSuccessors();
        foreach($successors as $successor)
        {
            if ($successor->id == $activity->id)
            {
                return $this;
            }
        }
        $successors[] = $activity;
        $this->setSuccessors($successors);
        return $this;
    }
    public function AddPredecessor($activity)
    {
        if (!$activity) return $this;
        $predecessors = $this->getPredecessors();
        foreach($predecessors as $predecessor)
        {
            if ($predecessor->id == $activity->id)
            {
                return $this;
            }
        }
        $predecessors[] = $activity;
        $this->setPredecessors($predecessors);
        return $this;
    }
    public function __toString()
    {
        return get_class($this);
    }
}