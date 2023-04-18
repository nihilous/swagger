<?php

namespace App\Http\Helper;

class StaticFunction
{
    public static function formatGarages($garages)
    {
        $formattedGarages = [];

        foreach ($garages as $garage) {
            preg_match('/POINT\(([0-9\.]+) ([0-9\.]+)\)/', $garage->point, $matches);
            $formattedGarages[] = [
                'garage_id' => $garage->garage_id,
                'name' => $garage->name,
                'hourly_price' => $garage->hourly_price,
                'currency' => $garage->currency,
                'contact_email' => $garage->contact_email,
                'point' => $matches[1].' '.$matches[2],
                'country' => $garage->country,
                'owner_id' => $garage->owner_id,
                'garage_owner' => $garage->garage_owner,
            ];
        }

        return $formattedGarages;
    }
}
