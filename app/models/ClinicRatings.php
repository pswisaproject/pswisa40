<?php

namespace App\Models;

class ClinicRatings extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('clinic_ratings');
    }

    protected $id;
    protected $clinic_id;
    protected $rating;
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

    public function getClinicId()
    {
        return $this->clinic_id;
    }

    public function setClinicId($clinic_id)
    {
        $this->clinic_id = $clinic_id;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
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
