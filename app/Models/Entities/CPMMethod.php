<?php

namespace App\Models\Entities;


use App\Models\Repositories\CpmOrderMatrixRepository;
use App\Models\Services\BusinessCalendarHolidayService;
use App\Models\Utils\BusinessCalendar;
use Carbon\Carbon;
use DateTime;
use League\Flysystem\Exception;
use PhpParser\Builder\Class_;
use stdClass;

class CPMMethod
{
    // Number of activities
    public $na;
    public $totalDays;
    public $baseStartDate;
    public $forecastFinishDate;
    // Activities of Critical Path
    public $cpm = [];
    public $numOfCpmActivity = 0;

    public function __construct()
    {
        $this->na = 0;
        $this->baseStartDate = null;
        $this->forecastFinishDate = null;
        $this->cpm = [];
        $this->totalDays = 0;
        $this->numOfCpmActivity = 0;
        $this->allActivities = [];
    }
    /**
     * check whether all the predecessors are in the ordered list
     * if yes means the activity can be added to the ordered list
     * @param $orderedList
     * @param $predecessors
     * @return bool
     */
    public static function addable($orderedList, $predecessors)
    {
        $ret = true;
        foreach($predecessors as $predecessor)
        {
            if (!isset($orderedList[$predecessor->id]))
            {
                $ret = false;
                break;
            }
        }
        return $ret;
    }
    /** identify the dependencies and re-order automatically
     * @param $activityList
     * @return array
     */
    public static function orderByDependencies($activityList)
    {
        $totalCount = count($activityList);
        $orderedList = [];
        while (count($orderedList) < $totalCount)
        {
            foreach($activityList as $key => $activity)
            {
                $predecessors = $activity->getPredecessors();
                // if are starters or all predecessors are in the list
                if ((count($predecessors) == 0) || (self::addable($orderedList, $predecessors)))
                {
                    if (!isset($orderedList[$activity->id]))
                    {
                        $orderedList[$activity->id] = $activity;
                    }
                    // remove from $activityList
                    unset($activityList[$key]);
                }
            }
        }
        return $orderedList;
    }
    /**
     * @param $activities
     * @param $revisedActivities
     * @param $stateId
     * @param $startDate
     * @return CPMMethod
     */
    public static function calculateCPM($activities, $revisedActivities = [], $stateId, $startDate = null)
    {
        $cpm = new CPMMethod();
        if ($activities->count() == 0)
            return $cpm;
        // Array to store the activities that'll be evaluated.
        $activityList = $cpm->GetActivities($activities, $revisedActivities);
        // order dependencies
        $orderedList = self::orderByDependencies($activityList);

        $items = [];
        foreach($orderedList as $item)
        {
            $items[] = $item;
        }
        $numOfActivity = count($items);
        $cpm->na = $numOfActivity;

        $baseStartDate = $startDate ?? $items[0]->getStartDate();
        if ($baseStartDate == null)
            $baseStartDate = Carbon::today();
        else
        {
            if (!$baseStartDate instanceof Carbon)
                $baseStartDate = Carbon::createFromFormat('Y-m-d H:i:s.u', $baseStartDate);
        }

        $cpm->baseStartDate =  $baseStartDate;
        $items = $cpm->WalkListAhead($items);
        $items = $cpm->WalkListAback($items);
        $cpm->CriticalPath($items);
        // return the result
        $retCpm = new CPMMethod();
        $retCpm->na = $cpm->na;
        $retCpm->totalDays = $cpm->totalDays;
        $retCpm->baseStartDate = $cpm->baseStartDate;
        $retCpm->numOfCpmActivity = $cpm->numOfCpmActivity;
        $allActivities = [];
        $freeWeekDays = array(BusinessCalendar::SATURDAY, BusinessCalendar::SUNDAY);
        $year = $baseStartDate->year;
        $publicHolidays = BusinessCalendarHolidayService::getPublicHolidays($stateId, $year);
        $businessCalendar = new BusinessCalendar($freeWeekDays, $publicHolidays);
        $businessCalendar->setStartDate($baseStartDate);
        $retCpm->forecastFinishDate = clone $businessCalendar->addBusinessDays(intval($cpm->totalDays))->getDate();
        foreach($cpm->allActivities as $activity)
        {
            // adjust start and end date
            $est = intval($activity->getEst());
            $businessCalendar->setStartDate($baseStartDate);
            $startDate = clone $businessCalendar->addBusinessDays($est)->getDate();
            $eet = intval($activity->getEet());
            $businessCalendar->setStartDate($baseStartDate);
            $endDate = clone $businessCalendar->addBusinessDays($eet)->getDate();

            $lst = intval($activity->getLst());
            $businessCalendar->setStartDate($baseStartDate);
            $lstDate = clone $businessCalendar->addBusinessDays($lst)->getDate();
            $let = intval($activity->getLet());
            $businessCalendar->setStartDate($baseStartDate);
            $letDate = clone $businessCalendar->addBusinessDays($let)->getDate();


            $act = new CPMActivity();
            $act->setId($activity->getId());
            $act->setActivityId($activity->getActivityId());
            $act->setName($activity->getName());
            $act->setDuration($activity->getDuration());
            $act->setEst($activity->getEst());
            $act->setLst($activity->getLst());
            $act->setEet($activity->getEet());
            $act->setLet($activity->getLet());
            $act->setOst($activity->getOst());
            $act->setAst($activity->getAst());
            $act->setFloat($activity->getFloat());
            $act->setIsCritical($activity->getIsCritical());
            $act->setStartDate($startDate->format('Y-m-d H:i:s.u'));
            $act->setEndDate($endDate->format('Y-m-d H:i:s.u'));
            $act->setLstDate($lstDate->format('Y-m-d H:i:s.u'));
            $act->setLetDate($letDate->format('Y-m-d H:i:s.u'));
            $allActivities[] = $act;
        }
        $retCpm->allActivities = $allActivities;

        $calculatedCpm = [];
        foreach($cpm->cpm as $cpmActivity)
        {
            // adjust start and end date
            $act = new CPMActivity();
            $act->setId($cpmActivity->getId());
            $act->setActivityId($cpmActivity->getActivityId());
            $act->setName($cpmActivity->getName());
            $act->setDuration($cpmActivity->getDuration());
            $act->setEst($cpmActivity->getEst());
            $act->setLst($cpmActivity->getLst());
            $act->setEet($cpmActivity->getEet());
            $act->setLet($cpmActivity->getLet());
            $act->setOst($cpmActivity->getOst());
            $act->setFloat($cpmActivity->getFloat());
            $act->setIsCritical($cpmActivity->getIsCritical());
            $act->setStartDate($cpmActivity->getStartDate());
            $act->setEndDate($cpmActivity->getEndDate());
            $act->setLstDate($cpmActivity->getLstDate());
            $act->setLetDate($cpmActivity->getLetDate());
            $calculatedCpm[] = $act;
        }
        $retCpm->cpm = $calculatedCpm;
        return $retCpm;
    }
    public static function getUniqueActivities($activities)
    {
        $listOfActivity = [];
        foreach($activities as $activity)
        {
            $aux = new CPMActivity();
            $aux = $aux->CheckActivity($listOfActivity, $activity->activity_id);
            if (!$aux)
            {
                // add the new activity to the list
                $cpmActivity = new CPMActivity();
                $cpmActivity->setId($activity->activity_id);
                $cpmActivity->setActivityId($activity->activity_id);
                $cpmActivity->setName($activity->activity->name);
                $duration = $activity->activity->duration;
                $startDate = $activity->activity->start_date;
                $endDate = $activity->activity->end_date;
                $cpmActivity->setDuration(doubleval($duration));
                $cpmActivity->setStartDate($startDate);
                $cpmActivity->setEndDate($endDate);
                $cpmActivity->setSql($activity->activity->sql_statement);
                $cpmActivity->setIsNeeded(true);
            }
            else
            {
                $cpmActivity = $aux;
            }
            if ($activity->predecessor)
            {
                $auxNew = new CPMActivity();
                $auxNew = $auxNew->CheckActivity($listOfActivity, $activity->predecessor->id);
                if ($auxNew)
                {
                    $cpmActivity->AddPredecessor($auxNew);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew->AddSuccessor($cpmActivity);
                }
                else
                {
                    // add the new activity to the list
                    $auxNew = new CPMActivity();
                    $auxNew->setId($activity->predecessor->id);
                    $auxNew->setActivityId($activity->predecessor->id);
                    $auxNew->setName($activity->predecessor->name);
                    $duration = $activity->predecessor->duration;
                    $startDate = $activity->predecessor->start_date;
                    $endDate = $activity->predecessor->end_date;
                    $auxNew->setDuration(doubleval($duration));
                    $auxNew->setStartDate($startDate);
                    $auxNew->setEndDate($endDate);
                    $auxNew->setSql($activity->predecessor->sql_statement);
                    $cpmActivity->setIsNeeded(true);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew;
                    $cpmActivity->AddPredecessor($auxNew);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew->AddSuccessor($cpmActivity);
                }
            }
            $listOfActivity[$activity->activity_id] = $cpmActivity;
        }
        return $listOfActivity;
    }
    /**
     * Gets the activities that'll be evaluated by the critical path method.
     * @param $activities
     * @param array $revisedActivities
     * @return array
     */
    public function GetActivities($activities, $revisedActivities = [])
    {
        $listOfActivity = [];
        foreach($activities as $activity)
        {
            $aux = new CPMActivity();
            $aux = $aux->CheckActivity($listOfActivity, $activity->activity_id);
            if (!$aux)
            {
                // add the new activity to the list
                $cpmActivity = new CPMActivity();
                $cpmActivity->setId($activity->activity_id);
                $cpmActivity->setActivityId($activity->activity_id);
                $cpmActivity->setName($activity->activity->name);
                $revisedActivity = $this->getRevisedActivity($activity, $revisedActivities);
                $duration = $revisedActivity? $revisedActivity['duration'] : $activity->duration;
                $startDate = $revisedActivity?  $revisedActivity['start_date'] : $activity->start_date;
                $endDate = $revisedActivity?  $revisedActivity['end_date'] : $activity->end_date;
                $cpmActivity->setDuration(doubleval($duration));
                $cpmActivity->setStartDate($startDate);
                $cpmActivity->setEndDate($endDate);
            }
            else
            {
                $cpmActivity = $aux;
            }
            if ($activity->predecessor)
            {
                $auxNew = new CPMActivity();
                $auxNew = $auxNew->CheckActivity($listOfActivity, $activity->predecessor->id);
                if ($auxNew)
                {
                    $cpmActivity->AddPredecessor($auxNew);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew->AddSuccessor($cpmActivity);
                }
                else
                {
                    // add the new activity to the list
                    $auxNew = new CPMActivity();
                    $auxNew->setId($activity->predecessor->id);
                    $auxNew->setActivityId($activity->predecessor->id);
                    $auxNew->setName($activity->predecessor->name);
                    $revisedActivity = $this->getRevisedActivity($activity->predecessor, $revisedActivities);
                    $duration = $revisedActivity? $revisedActivity['duration'] : $activity->predecessor->duration;
                    $startDate = $revisedActivity?  $revisedActivity['start_date'] : $activity->predecessor->start_date;
                    $endDate = $revisedActivity?  $revisedActivity['end_date'] : $activity->predecessor->end_date;
                    $auxNew->setDuration(doubleval($duration));
                    $auxNew->setStartDate($startDate);
                    $auxNew->setEndDate($endDate);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew;
                    $cpmActivity->AddPredecessor($auxNew);
                    $listOfActivity[$auxNew->getActivityId()] = $auxNew->AddSuccessor($cpmActivity);
                    //throw new Exception('Please make sure the order of the activities is right!');
                }
            }
            $listOfActivity[$activity->activity_id] = $cpmActivity;
        }
        return $listOfActivity;
    }
    /**
     * Performs the walk ahead inside the array of activities calculating for each
     * activity its earliest start time and earliest end time.
     * @param $activities Array storing the activities already entered.
     * @return mixed
     */
    private function WalkListAhead($activities)
    {
        $activities[0]->eet = round($activities[0]->est + $activities[0]->duration, 1);
        for ($i = 1; $i < $this->na; $i++)
        {
            foreach($activities[$i]->predecessors as $activity)
            {
                if ($activities[$i]->est < $activity->eet)
                {
                    // select the biggest one in its predecessors
                    $activities[$i]->est = round($activity->eet,1);
                }
            }
            $activities[$i]->eet = round($activities[$i]->est + $activities[$i]->duration, 1);
        }
        return $activities;
    }
    public static function executeSql($order, $sqlStatement)
    {
        $duration = 0;
        $pattern = "/({{)([^{}]*)(}})/";
        $matches = null;
        $keys = [];
        $keyValues = [];
        preg_match_all($pattern, $sqlStatement, $matches);
        if (count($matches) > 0)
        {
            $fields = $matches[2];
            foreach($fields as $field)
            {
                $field = trim($field);
                if ($field)
                    $keys[] = trim($field);
                else
                    throw new Exception('Invalid parameter found in sql statement');
            }
        }
        foreach($keys as $key)
        {
            $keyValues[] = $order->{$key};
        }
        $sqlStatement = preg_replace($pattern, "?", $sqlStatement);
        $rets = DB::select(DB::raw($sqlStatement), $keyValues);
        if (count($rets) > 0)
        {
            $duration = intval($rets[0]->DURATION);
        }
        return $duration;
    }
    public static function getDuration($order, $activity)
    {
        // check whether has sql if yes execute it to get duration
        // if not using static value
        $sql = $activity->sql;
        if ($sql)
        {
            // execute sql
            $duration = self::executeSql($order, $sql);
        }
        else
        {
            $duration =  $activity->duration;
        }
        return $duration;

    }
    public static function calculateEstAndEet($order, $activities)
    {
        $na = count($activities);
        // normally first activity won't have zero duration
        $durationOfStartActivity = round(self::getDuration($order, $activities[0]), 1);
        $activities[0]->eet = round($activities[0]->est + $durationOfStartActivity, 1);
        for ($i = 1; $i < $na; $i++)
        {
            // get duration according to sql or static value
            // then check whether the duration is zero if yes don't need this activity
            $duration = round(self::getDuration($order, $activities[$i]), 1);
            if (doubleval($duration) == doubleval(0))
            {
                // continue;
                $activities[$i]->isNeeded = false;
                continue;
            }
            foreach($activities[$i]->predecessors as $activity)
            {
                if ($activities[$i]->est < $activity->eet)
                {
                    // select the biggest one in its predecessors
                    $activities[$i]->est = round($activity->eet,1);
                }
            }
            $activities[$i]->eet = round($activities[$i]->est + $duration, 1);
        }
        return $activities;
    }
    public static function calculateLstAndLet($order, $activities)
    {
        $na = count($activities);
        // normally last activity won't have zero duration
        $lastActivity = $activities[$na - 1];
        $durationOfLastActivity = round(self::getDuration($order, $lastActivity), 1);
        $lastActivity->let = round($lastActivity->eet,1);
        $lastActivity->lst = round($lastActivity->let - $durationOfLastActivity, 1);

        for($i = $na - 2; $i >= 0; $i--)
        {
            // get duration according to sql or static value
            // then check whether the duration is zero if yes don't need this activity
            $duration = round(self::getDuration($order, $activities[$i]), 1);
            if (doubleval($duration) == doubleval(0))
            {
                // continue;
                $activities[$i]->isNeeded = false;
                continue;
            }

            foreach($activities[$i]->successors as $activity)
            {
                if ($activities[$i]->let == 0)
                    $activities[$i]->let = round($activity->lst,1);
                else if ($activities[$i]->let > $activity->lst)
                    $activities[$i]->let = round($activity->lst,1);
            }
            $activities[$i]->lst = round($activities[$i]->let - $duration, 1);
        }
        return $activities;
    }
    /**
     * Performs the walk aback inside the array of activities calculating for each
     * activity its latest start time and latest end time.
     *
     * @param $activities Array storing the activities already entered.
     * @return mixed
     */
    private function WalkListAback($activities)
    {
        $activities[$this->na - 1]->let = round($activities[$this->na - 1]->eet,1);
        $activities[$this->na - 1]->lst = round($activities[$this->na - 1]->let - $activities[$this->na - 1]->duration, 1);

        for($i = $this->na - 2; $i >= 0; $i--)
        {
            foreach($activities[$i]->successors as $activity)
            {
                if ($activities[$i]->let == 0)
                    $activities[$i]->let = round($activity->lst,1);
                else if ($activities[$i]->let > $activity->lst)
                    $activities[$i]->let = round($activity->lst,1);
            }
            $activities[$i]->lst = round($activities[$i]->let - $activities[$i]->duration,1);
        }
        return $activities;
    }
    /**
     * Calculates the critical path by verifying if each activity's earliest end time
     * minus the latest end time and earliest start time minus the latest start
     * time are equal zero. If so, then prints out the activity id that match the
     * criteria.
     * @param $activities
     */
    private function CriticalPath($activities)
    {
        foreach($activities as $activity)
        {
            if (($activity->eet - $activity->let == 0) && ($activity->est - $activity->lst == 0))
            {
                $activity->isCritical = true;
                $this->cpm[] = $activity;
                $this->totalDays = ($this->totalDays > $activity->eet) ? $this->totalDays: $activity->eet;
                $this->numOfCpmActivity++;
            }
            else
                $activity->isCritical = false;
            $activity->float = round($activity->lst - $activity->est, 1);
            $this->allActivities[] = $activity;
        }
    }
//    private function getDuration($activity, $revisedActivities)
//    {
//        $duration = $activity->duration;
//        foreach($revisedActivities as $revisedActivity)
//        {
//            if ($revisedActivity['activity_id'] == $activity->activity_id)
//            {
//                $duration = $revisedActivity['duration'];
//                break;
//            }
//        }
//        return $duration;
//    }
    private function getRevisedActivity($activity, $revisedActivities)
    {
        $ret = null;
        foreach($revisedActivities as $revisedActivity)
        {
            if ($activity instanceof CPM_Order)
            {
                if ($revisedActivity['activity_id'] == $activity->activity_id)
                {
                    $ret = $revisedActivity;
                    break;
                }
            }
            else
            {
                if ($revisedActivity['activity_id'] == $activity->id)
                {
                    $ret = $revisedActivity;
                    break;
                }
            }

        }
        return $ret;
    }
    public function __toString()
    {
        return get_class($this);
    }
}