<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonInterval;

/**
 * @property $ig_no              int auto_increment comment 'info_garage primary key' primary key,
 * @property $ig_hourly_price    int                                 not null comment 'info garage standard price for an hour',
 * @property $ig_currency_type   tinyint                             not null comment 'info garage local currency type 0:Euro, 1:Dollar, 2:Swedish Krona, 3:Norwegian Krone, 4:Danish Krone, 5:Pound, 6:French Franc, 7:Korean Won, 8:Japanese Yen, 9 Chinese Yuan',
 * @property $ig_coordinate      point                               not null comment 'info garage spatial data 6numbers under point to match google API',
 * @property $ig_owner_id        int                                 not null comment 'info garage join key to info_owner_client',
 * @property $ig_garage_name     varchar(50)                         not null comment 'info garage name of parking space',
 * @property $ig_reserved_until  datetime                            null comment 'info garage reserved period by ig_rental_id, if null or if ig_reserved_until < now() then available',
 * @property $ig_rental_id       int                                 null comment 'info garage join key to info_rental_client',
 * @property $ig_created_at      timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP comment 'info garage record created time stamp',
 * @property $ig_updated_at      timestamp                           null on update CURRENT_TIMESTAMP comment 'info garage record updated time stamp',
 * @property $ig_editor_employee int                                 null comment 'info join key to info_employee'
 */

class InfoGarage extends Model
{
    protected $table = 'info_garage';

    protected $primaryKey = 'ig_no';

    public $timestamps = false;

    protected $fillable = [
        'ig_hourly_price',
        'ig_currency_type',
        'ig_coordinate',
        'ig_owner_id',
        'ig_garage_name',
        'ig_reserved_until',
        'ig_rental_id',
        'ig_created_at',
        'ig_updated_at',
        'ig_editor_employee',
    ];

    protected $dates = [
        'ig_reserved_until',
        'ig_created_at',
        'ig_updated_at',
    ];

    protected $casts = [
        'ig_hourly_price' => 'integer',
        'ig_currency_type' => 'integer',
        'ig_owner_id' => 'integer',
        'ig_rental_id' => 'integer',
        'ig_editor_employee' => 'integer',
    ];


    public function findGarageWithOwnerId($ig_owner_id) {
        $result = DB::table('info_garage')
            ->select('ig_no as garage_id', 'ig_garage_name as name', 'ig_hourly_price as hourly_price', 'imc_currency_char as currency', 'ioc_email as contact_email', 'igv_ig_coordinate as point', 'ic_name as country', 'ioc_id as owner_id', 'ioc_owner as garage_owner')
            ->join('info_owner_client', 'ig_owner_id', '=', 'ioc_id')
            ->join('info_country as ic', 'info_owner_client.ioc_country', '=', 'ic.ic_no')
            ->join('info_garage_view_ig_coordinate', 'ig_no', '=', 'igv_ig_no')
            ->join('info_money_currency', 'ig_currency_type', '=', 'imc_no')
            ->where('ig_owner_id', '=', $ig_owner_id)
            ->get();

        if (count($result) != 0) {
            Log::channel('single')->debug('result set: ' . json_encode($result));
            return $result;
        } else {
            Log::channel('single')->debug('empty result set: ' . json_encode($result));
            return false;
        }
    }

    public function findGarageWithOwnerCountry($ioc_couontry) {
        $result = DB::table('info_garage')
            ->select('ig_no as garage_id', 'ig_garage_name as name', 'ig_hourly_price as hourly_price', 'imc_currency_char as currency', 'ioc_email as contact_email', 'igv_ig_coordinate as point', 'ic_name as country', 'ioc_id as owner_id', 'ioc_owner as garage_owner')
            ->join('info_owner_client', 'ig_owner_id', '=', 'ioc_id')
            ->join('info_country as ic', 'info_owner_client.ioc_country', '=', 'ic.ic_no')
            ->join('info_garage_view_ig_coordinate', 'ig_no', '=', 'igv_ig_no')
            ->join('info_money_currency', 'ig_currency_type', '=', 'imc_no')
            ->where('ioc_country', '=', $ioc_couontry)
            ->get();

        if (count($result) != 0) {
            Log::channel('single')->debug('result set: ' . json_encode($result));
            return $result;
        } else {
            Log::channel('single')->debug('empty result set: ' . json_encode($result));
            return false;
        }
    }

    public function findGaragesWithinDistance($standard_of_distance, $distance_range, $latitude, $longitude)
    {
        // convert distance range to meters if it's in km
        if ($standard_of_distance == 'km') {
            $distance_range *= 1000;
        }

        // query for garages within the specified distance range
        $result = DB::table('info_garage')
            ->select('ig_no as garage_id', 'ig_garage_name as name', 'ig_hourly_price as hourly_price', 'imc_currency_char as currency', 'ioc_email as contact_email', DB::raw('ST_AsText(ST_SetSRID(`ig_coordinate`, 4326)) as point'), 'ic_name as country', 'ioc_id as owner_id', 'ioc_owner as garage_owner')
            ->join('info_owner_client', 'ig_owner_id', '=', 'ioc_id')
            ->join('info_country as ic', 'info_owner_client.ioc_country', '=', 'ic.ic_no')
            ->join('info_garage_view_ig_coordinate', 'ig_no', '=', 'igv_ig_no')
            ->join('info_money_currency', 'ig_currency_type', '=', 'imc_no')
            ->whereRaw("ST_Distance_Sphere(ST_GeomFromText('POINT($latitude $longitude)', 4326), ST_SetSRID(`ig_coordinate`, 4326)) <= $distance_range")
            ->get();

        if (count($result) != 0) {
            Log::channel('single')->debug('result set: ' . json_encode($result));
            return $result;
        } else {
            Log::channel('single')->debug('empty result set: ' . json_encode($result));
            return false;
        }
    }



    public function findLowerThan($hourly_price, $currency_type) {
            $result = DB::table('info_garage')
                ->join('info_owner_client', 'info_garage.ig_owner_id', '=', 'info_owner_client.ioc_id')
                ->select('info_garage.ig_garage_name', 'info_garage.ig_hourly_price')
                ->where('info_garage.ig_hourly_price', '<=', $hourly_price)
                ->where('info_owner_client.ioc_is_use', '=', 1)
                ->where('info_garage.ig_currency_type', '=', $currency_type)
                ->where('info_garage.ig_reserved_until', '<', now())
                ->get();

            if (count($result) != 0) {
                Log::channel('single')->debug('empty False ' .json_encode($result));
                return $result;
            } else {
                Log::channel('single')->debug('empty True ' .json_encode($result));
                return false;
            }
    }

    public function getByIgNo($ig_no){
        $result = DB::table('info_garage')->where('ig_no', $ig_no)->first();

        if ($result) {
            // Convert any non-UTF8 characters to UTF-8
            array_walk_recursive($result, function(&$value, $key) {
                if (!mb_check_encoding($value, 'UTF-8') && !is_numeric($value)) {
                    $value = utf8_encode($value);
                }
            });

            Log::channel('single')->debug('Record found ' . json_encode($result));
            return $result;
        } else {
            Log::channel('single')->debug('Record not found');
            return false;
        }

    }

    public function reserveGarage($ig_no, $ig_rental_id, $timestandard, $number)
    {
        $garage = self::find($ig_no);

        if (!$garage) {
            Log::debug("Garage not found for ID $ig_no");
            return false;
        }

        if ($garage->ig_reserved_until != null && $garage->ig_reserved_until > Carbon::now()) {
            Log::debug("Garage with ID $ig_no is already reserved");
            return false;
        }

        $duration = 0;

        switch ($timestandard) {
            case 'm':
                $duration = CarbonInterval::minutes($number);
                break;
            case 'h':
                $duration = CarbonInterval::hours($number);
                break;
            case 'd':
                $duration = CarbonInterval::days($number);
                break;
        }

        Log::debug("Reserving garage with ID $ig_no for $number $timestandard");

        $garage->ig_reserved_until = Carbon::now()->add($duration);
        $garage->ig_rental_id = $ig_rental_id;
        $garage->save();

        array_walk_recursive($garage, function(&$value, $key) {
            if (!mb_check_encoding($value, 'UTF-8') && !is_numeric($value)) {
                $value = utf8_encode($value);
            }
        });

        return $garage;
    }
}
