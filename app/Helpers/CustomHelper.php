<?php

namespace App\Helpers;

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

    // Format as "Dec 25, 2023 • 03:30 PM"
    public function formatDateTime($dateTimeString)
    {
        $dateTimeString = trim((string)$dateTimeString);
        if ($dateTimeString === '' || strtolower($dateTimeString) === 'null') {
            return 'Invalid date';
        }

        $formats = [
            'Y-m-d H:i:s.u',
            'Y-m-d H:i:s',
            'Y-m-d\TH:i:s.uP',
            'Y-m-d\TH:i:sP',
            'Y-m-d',
        ];

        foreach ($formats as $fmt) {
            $date = \DateTime::createFromFormat($fmt, $dateTimeString);
            if ($date !== false) {
                return $date->format('M j, Y • h:i A');
            }
        }

        if (preg_match('/^-?\d+$/', $dateTimeString)) {
            try {
                $date = (new \DateTime())->setTimestamp((int)$dateTimeString);
                return $date->format('M j, Y • h:i A');
            } catch (\Exception $e) {
            }
        }

        try {
            $date = new \DateTime($dateTimeString);
            return $date->format('M j, Y • h:i A');
        } catch (\Exception $e) {
            return 'Invalid date';
        }
    }

    public function getProductStock($productStock)
    {
        switch ($productStock) {
            case 'IN_STOCK':
                return ['In Stock', 'badge bg-info rounded'];
            case 'OUT_OF_STOCK':
                return ['Out of Stock', 'badge bg-danger rounded'];
            default:
                return ['Unknown', 'badge bg-dark rounded'];
        }
    }
}
