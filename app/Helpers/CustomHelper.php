<?php

class CustomHelper
{
    public function getStatusDetails($activeStatus)
    {
        switch ($activeStatus) {
            case 'YES':
                return ['Yes', 'badge bg-primary rounded'];
            case 'NO':
                return ['No', 'badge bg-warning rounded'];
            default:
                return ['Unknown', 'badge bg-dark rounded'];
        }
    }

    public function isSelected($active, $value)
    {
        return ($active === $value) ? 'selected' : '';
    }

    public function getMethodDetails($method)
    {
        switch ($method) {
            case 'POST':
                return ['POST', 'badge bg-warning rounded'];
            case 'GET':
                return ['GET', 'badge bg-success rounded'];
            case 'DELETE':
                return ['DELETE', 'badge bg-danger rounded'];
            case 'PUT':
                return ['PUT', 'badge bg-info rounded'];
            default:
                return ['PATCH', 'badge bg-secondary rounded'];
        }
    }

    public function getUserTypeDetails($userType)
    {
        switch ($userType) {
            case 'SUPER_ADMIN':
                return ['Super Admin', 'badge bg-success rounded'];
            case 'ADMIN':
                return ['Admin', 'badge bg-secondary rounded'];
            default:
                return ['Unknown', 'badge bg-dark rounded'];
        }
    }
}
