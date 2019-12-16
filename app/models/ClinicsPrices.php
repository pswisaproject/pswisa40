<?php

namespace App\Models;

class ClinicsPrices extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('clinics_prices');
    }

    protected $id;
    protected $clinics_id;
    protected $checkup_price;
    protected $operation_price;
    protected $description;
    protected $created_at;
    protected $updated_at;

    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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

    public function getCheckupPrice()
    {
        return $this->checkup_price;
    }

    public function setCheckupPrice($checkup_price)
    {
        $this->checkup_price = $checkup_price;
    }

    public function getOperationPrice()
    {
        return $this->operation_price;
    }

    public function setOperationPrice($operation_price)
    {
        $this->operation_price = $operation_price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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
