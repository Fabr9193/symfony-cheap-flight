<?php 
namespace App\Entity;

class Research
{
    protected $research;
    protected $flyFrom;
    protected $budget;
    protected $nbTravelers;
    protected $submit;

    /**
     * @return mixed
     */
    public function getResearch()
    {
        return $this->research;
    }

    /**
     * @param mixed $research
     */
    public function setResearch($research): void
    {
        $this->research = $research;
    }

    /**
     * @return mixed
     */
    public function getFlyFrom()
    {
        return $this->flyFrom;
    }

    /**
     * @param mixed $flyFrom
     */
    public function setFlyFrom($flyFrom): void
    {
        $this->flyFrom = $flyFrom;
    }

    /**
     * @return mixed
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param mixed $budget
     */
    public function setBudget($budget): void
    {
        $this->budget = $budget;
    }

    /**
     * @return mixed
     */
    public function getNbTravelers()
    {
        return $this->nbTravelers;
    }

    /**
     * @param mixed $nbTravelers
     */
    public function setNbTravelers($nbTravelers): void
    {
        $this->nbTravelers = $nbTravelers;
    }

    /**
     * @return mixed
     */
    public function getSubmit()
    {
        return $this->submit;
    }

    /**
     * @param mixed $submit
     */
    public function setSubmit($submit): void
    {
        $this->submit = $submit;
    }


}