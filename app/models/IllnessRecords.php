<?php

namespace App\Models;

class IllnessRecords extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('illness_records');

    }

    protected $id;
    protected $patient_id;
    protected $diagnosis_id;
    protected $clinics_appointment_slots_id;
    protected $created_at;
    protected $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPatientId()
    {
        return $this->patient_id;
    }

    public function setPatientId($patient_id)
    {
        $this->patient_id = $patient_id;
    }

    public function getDiagnosisId()
    {
        return $this->diagnosis_id;
    }

    public function setDiagnosisId($diagnosis_id)
    {
        $this->diagnosis_id = $diagnosis_id;
    }
    
    public function getClinicsAppointmentSlotsId()
    {
        return $this->clinics_appointment_slots_id;
    }

    public function setClinicsAppointmentSlotsId($clinics_appointment_slots_id)
    {
        $this->clinics_appointment_slots_id = $clinics_appointment_slots_id;
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
