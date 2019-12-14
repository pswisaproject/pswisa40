<?php

namespace App\Models;

class ClinicsAppointmentSlots extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('clinics_appointment_slots');

    }

    protected $id;
    protected $clinics_id;
    protected $start_date;
    protected $end_date;
    protected $type;
    protected $doctor_id;
    protected $clinics_prices_id;
    protected $operating_room_id;
    protected $status;
    protected $created_at;
    protected $updated_at;

    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findFirst($parameters = null)
    {
        return parent::findFirst($clinics_prices_idparameters);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getClinicsId()
    {
        return $this->clinics_id;
    }

    public function setClinicsId($clinics_id)
    {
        $this->clinics_id = $clinics_id;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getDoctorId()
    {
        return $this->doctor_id;
    }

    public function setDoctorId($doctor_id)
    {
        $this->doctor_id = $doctor_id;
    }

    public function getClinicsPricesId()
    {
        return $this->clinics_prices_id;
    }

    public function setClinicsPricesId($clinics_prices_id)
    {
        $this->clinics_prices_id = $clinics_prices_id;
    }

    public function getOperatingRoomId()
    {
        return $this->operating_room_id;
    }

    public function setOperatingRoomId($operating_room_id)
    {
        $this->operating_room_id = $operating_room_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdateAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
