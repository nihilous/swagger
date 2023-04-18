<?php

namespace App\Http\Controllers\API;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation",
 *      description="API documentation for the application",
 *      @OA\Contact(
 *          email="dkfg123@gmail.com"
 *      ),
 *      @OA\License(
 *          name="nginx",
 *          url="http://local-host setup whatever.co.kr/"
 *      )
 * )
 */

use App\Http\Helper\StaticFunction;
use App\Models\InfoGarage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GarageInfoController
{


    /**
     * @OA\Get(
     *     path="/api/garageinfo/get/byownerid/{ig_owner_id}",
     *     operationId="getInfoByIgOwnerId",
     *     tags={"get garage info by ig owner id"},
     *     summary="Find garages with a specific owner ID",
     *     description="Returns a list of garages with the specified owner ID",
     *     @OA\Parameter(
     *         name="ig_owner_id",
     *         in="path",
     *         required=true,
     *         description="Owner ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="garages",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="garage_id",
     *                          type="integer",
     *                          example=50786421
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Garage 1"
     *                      ),
     *                      @OA\Property(
     *                          property="hourly_price",
     *                          type="number",
     *                          example=2
     *                      ),
     *                      @OA\Property(
     *                          property="currency",
     *                          type="string",
     *                          example="€"
     *                      ),
     *                      @OA\Property(
     *                          property="contact_email",
     *                          type="string",
     *                          example="testemail@testautopark.fi"
     *                      ),
     *                      @OA\Property(
     *                          property="point",
     *                          type="string",
     *                          example="60.168607847624095 24.932371066131623"
     *                      ),
     *                      @OA\Property(
     *                          property="country",
     *                          type="string",
     *                          example="Finland"
     *                      ),
     *                      @OA\Property(
     *                          property="owner_id",
     *                          type="integer",
     *                          example=29190
     *                      ),
     *                      @OA\Property(
     *                          property="garage_owner",
     *                          type="string",
     *                          example="AutoPark"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="status_code",
     *                  type="integer",
     *                  example=200
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="OK"
     *              ),
     *              @OA\Property(
     *                  property="api_endpoint",
     *                  type="string",
     *                  example="/api/garageinfo/get/byownerid/29190"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="errors", type="object"),
     *          )
     *     ),
     * )
     */

    public function getInfoByIgOwnerId($ig_owner_id)
    {
        $infoGarage = new InfoGarage();
        $result = $infoGarage->findGarageWithOwnerId($ig_owner_id);

        if (!$result) {

            return response()->json([
                'status_code' => 204,
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byownerid/' . $ig_owner_id
            ]);

        } else {

            $garages = StaticFunction::formatGarages($result);

            return response()->json([
                'garages' => $garages,
                'status_code' => http_response_code(),
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byownerid/' . $ig_owner_id
            ]);

        }
    }

    /**
     * @OA\Get(
     *     path="/api/garageinfo/get/byownercountry/{ioc_country}",
     *     operationId="getInfoByIgOwnerCountry",
     *     tags={"get garage info by ig owner country"},
     *     summary="Find garages with a specific owner country code",
     *     description="Returns a list of garages with the specified owner ID",
     *     @OA\Parameter(
     *         name="ioc_country",
     *         in="path",
     *         required=true,
     *         description="Owner Country (0: Finland, 1: Sweden, 2: Norway, 3: Denmark, 4: UK, 5: France, 6: USA, 7: Korea, 8: Japan, 9: China)",
     *         @OA\Schema(type="integer", enum={0, 1, 2, 3, 4, 5, 6, 7, 8, 9})
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="garages",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="garage_id",
     *                          type="integer",
     *                          example=50786421
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Garage 1"
     *                      ),
     *                      @OA\Property(
     *                          property="hourly_price",
     *                          type="number",
     *                          example=2
     *                      ),
     *                      @OA\Property(
     *                          property="currency",
     *                          type="string",
     *                          example="€"
     *                      ),
     *                      @OA\Property(
     *                          property="contact_email",
     *                          type="string",
     *                          example="testemail@testautopark.fi"
     *                      ),
     *                      @OA\Property(
     *                          property="point",
     *                          type="string",
     *                          example="60.168607847624095 24.932371066131623"
     *                      ),
     *                      @OA\Property(
     *                          property="country",
     *                          type="string",
     *                          example="Finland"
     *                      ),
     *                      @OA\Property(
     *                          property="owner_id",
     *                          type="integer",
     *                          example=29190
     *                      ),
     *                      @OA\Property(
     *                          property="garage_owner",
     *                          type="string",
     *                          example="AutoPark"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="status_code",
     *                  type="integer",
     *                  example=200
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="OK"
     *              ),
     *              @OA\Property(
     *                  property="api_endpoint",
     *                  type="string",
     *                  example="/api/garageinfo/get/byownerid/29190"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="errors", type="object"),
     *          )
     *     ),
     * )
     */

    public function getInfoByIgOwnerCountry($ioc_country)
    {
        $infoGarage = new InfoGarage();
        $result = $infoGarage->findGarageWithOwnerCountry($ioc_country);

        if (!$result) {

            return response()->json([
                'status_code' => 204,
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byownercountry/' . $ioc_country
            ]);

        } else {

            $garages = StaticFunction::formatGarages($result);

            return response()->json([
                'garages' => $garages,
                'status_code' => http_response_code(),
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byownercountry/' . $ioc_country
            ]);

        }
    }


    /**
     * @OA\Get(
     *     path="/api/garageinfo/get/byclientlocation/{standard_of_distance}/{distance_range}",
     *     operationId="getInfoByClientLocation",
     *     tags={"get garage info by client location"},
     *     summary="Find garages within a specified distance range of a client's location",
     *     description="Returns a list of garages that are within the specified distance range of the client's location",
     *     @OA\Parameter(
     *         name="standard_of_distance",
     *         in="path",
     *         required=true,
     *         description="Standard of distance ('m' for meter or 'km' for kilometer)",
     *         @OA\Schema(
     *             type="string",
     *             enum={"m", "km"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="distance_range",
     *         in="path",
     *         required=true,
     *         description="Distance range",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Client latitude",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Client longitude",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="garages",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="garage_id",
     *                          type="integer",
     *                          example=50786421
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Garage 1"
     *                      ),
     *                      @OA\Property(
     *                          property="hourly_price",
     *                          type="number",
     *                          example=2
     *                      ),
     *                      @OA\Property(
     *                          property="currency",
     *                          type="string",
     *                          example="€"
     *                      ),
     *                      @OA\Property(
     *                          property="contact_email",
     *                          type="string",
     *                          example="testemail@testautopark.fi"
     *                      ),
     *                      @OA\Property(
     *                          property="point",
     *                          type="string",
     *                          example="60.168607847624095 24.932371066131623"
     *                      ),
     *                      @OA\Property(
     *                          property="country",
     *                          type="string",
     *                          example="Finland"
     *                      ),
     *                      @OA\Property(
     *                          property="owner_id",
     *                          type="integer",
     *                          example=29190
     *                      ),
     *                      @OA\Property(
     *                          property="garage_owner",
     *                          type="string",
     *                          example="AutoPark"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="status_code",
     *                  type="integer",
     *                  example=200
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="OK"
     *              ),
     *              @OA\Property(
     *                  property="api_endpoint",
     *                  type="string",
     *                  example="/api/garageinfo/get/byclientlocation/m || km/150?latitude=15.123456&longitude=15.123421"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="errors", type="object"),
     *          )
     *     ),
     * )
     */

    public function getGarageInfoByClientLocation(Request $request, $standard_of_distance, $distance_range)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');

        // validate input parameters
        $validator = Validator::make(
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
            [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }


        $infoGarage = new InfoGarage();
        $result = $infoGarage->findGaragesWithinDistance($standard_of_distance, $distance_range, $latitude, $longitude);

        if (!$result) {

            return response()->json([
                'status_code' => 204,
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byclientlocation/'.$standard_of_distance.'/'.$distance_range.'/?latitude="'.$latitude.'"&longitude="'.$longitude.'"'
            ]);

        } else {

            $garages = StaticFunction::formatGarages($result);

            return response()->json([
                'garages' => $garages,
                'status_code' => http_response_code(),
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/byclientlocation/'.$standard_of_distance.'/'.$distance_range.'/?latitude="'.$latitude.'"&longitude="'.$longitude.'"'
            ]);

        }


    }


    /**
     * @OA\Get(
     *     path="/api/garageinfo/get/lowerthan/{country}/{cost}",
     *     operationId="findlowerthan",
     *     tags={"findlowerthan"},
     *     summary="find parking spot has lower cost than {cost} within {country}",
     *     description="{country} code and {cost}",
     *     @OA\Parameter(name="country", in="path", required=true,
     *         description="Currency Type (0: Euro, 1: Dollar, 2: Swedish Krona, 3: Norwegian Krone, 4: Danish Krone, 5: Pound, 6: French Franc, 7: Korean Won, 8: Japanese Yen, 9: Chinese Yuan)",
     *         @OA\Schema(type="integer", enum={0, 1, 2, 3, 4, 5, 6, 7, 8, 9})
     *     ),
     *     @OA\Parameter(name="cost", in="path", required=true, description="Maximum Cost Limit", @OA\Schema(type="integer")),
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(type="object",
     *                      @OA\Property(type="string", property="ig_garage_name"),
     *                      @OA\Property(type="number", property="ig_hourly_price"),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="errors", type="object"),
     *          )
     *     ),
     * )
     */

    public function findCheapest(Request $request,$country,$cost): JsonResponse
    {
        $request->merge([
            'country' => $country,
            'cost' => $cost
        ]);
        $request->validate([
            'country' => 'required',
            'cost' => 'required',
        ]);

        $infoGarage = new InfoGarage();
        $result = $infoGarage->findLowerThan($cost, $country);

        if (!$result) {
            // even though there was no matching record, since API work without an error, code supposed to be 200 I think, just no data included
            return response()->json([
                'status_code' => 204,
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/lowerthan/'.$country.'/'.$cost
            ]);
        }

        return response()->json([
            "data"=>$result,
            "status_code"=>http_response_code(),
            "message"=>\App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
            'api_endpoint' => '/api/garageinfo/get/lowerthan/'.$country.'/'.$cost
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/garageinfo/get/all/{ig_no}",
     *     operationId="getInfoGarage",
     *     tags={"Garage Info"},
     *     summary="Get information about a parking garage",
     *     description="Retrieve information about a parking garage based on its ID",
     *     @OA\Parameter(
     *         name="ig_no",
     *         in="path",
     *         description="ID of the parking garage to retrieve information for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="ig_no",
     *                 type="integer",
     *                 description="ID of the parking garage"
     *             ),
     *             @OA\Property(
     *                 property="ig_hourly_price",
     *                 type="integer",
     *                 description="Standard price for an hour of parking"
     *             ),
     *             @OA\Property(
     *                 property="ig_currency_type",
     *                 type="integer",
     *                 description="Local currency type of the parking garage"
     *             ),
     *             @OA\Property(
     *                 property="ig_coordinate",
     *                 type="object",
     *                 description="Spatial data for the parking garage",
     *                 @OA\Property(
     *                     property="x",
     *                     type="number",
     *                     format="float",
     *                     description="X-coordinate"
     *                 ),
     *                 @OA\Property(
     *                     property="y",
     *                     type="number",
     *                     format="float",
     *                     description="Y-coordinate"
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="ig_owner_id",
     *                 type="integer",
     *                 description="ID of the owner of the parking garage"
     *             ),
     *             @OA\Property(
     *                 property="ig_garage_name",
     *                 type="string",
     *                 description="Name of the parking garage"
     *             ),
     *             @OA\Property(
     *                 property="ig_reserved_until",
     *                 type="string",
     *                 nullable=true,
     *                 format="date-time",
     *                 description="Date and time until the parking garage is reserved (if it is currently reserved)"
     *             ),
     *             @OA\Property(
     *                 property="ig_rental_id",
     *                 type="integer",
     *                 nullable=true,
     *                 description="ID of the rental client that reserved the parking garage (if it is currently reserved)"
     *             ),
     *             @OA\Property(
     *                 property="ig_created_at",
     *                 type="string",
     *                 format="date-time",
     *                 description="Timestamp of when the parking garage was created"
     *             ),
     *             @OA\Property(
     *                 property="ig_updated_at",
     *                 type="string",
     *                 nullable=true,
     *                 format="date-time",
     *                 description="Timestamp of when the parking garage was last updated"
     *             ),
     *             @OA\Property(
     *                 property="ig_editor_employee",
     *                 type="integer",
     *                 nullable=true,
     *                 description="ID of the employee that last edited the parking garage (if it has been edited)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Garage info not found"
     *     )
     * )
     */

    public function getInfoGarage($ig_no)
    {
        $infoGarage = new InfoGarage();
        $result = $infoGarage->getByIgNo($ig_no);

        if (!$result) {
            // even though there was no matching record, since API work without an error, code supposed to be 200 I think, just no data included
            return response()->json([
                'status_code' => http_response_code(),
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
                'api_endpoint' => '/api/garageinfo/get/all/' .$ig_no
            ]);
        }

        return response()->json([
            'data' => $result,
            'status_code' => http_response_code(),
            'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code()),
            'api_endpoint' => '/api/garageinfo/get/all/' .$ig_no
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/garageinfo/update/reserve/{ig_no}/{ig_rental_id}/{timestandard}/{number}",
     *     operationId="reserveGarage",
     *     tags={"Garage Info"},
     *     summary="Reserve a parking garage",
     *     description="Reserve a parking garage by updating ig_reserved_until and ig_rental_id fields of the given garage",
     *     @OA\Parameter(
     *         name="ig_no",
     *         in="path",
     *         description="The id of the parking garage to reserve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ig_rental_id",
     *         in="path",
     *         description="The id of the rental client to reserve the garage for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="timestandard",
     *         in="path",
     *         description="The time standard to use for the reservation (m: minutes, h: hours, d: days)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"m", "h", "d"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="number",
     *         in="path",
     *         description="The number of time units to reserve the garage for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="ig_no",
     *                     type="integer",
     *                     description="The id of the reserved parking garage"
     *                 ),
     *                 @OA\Property(
     *                     property="ig_reserved_until",
     *                     type="string",
     *                     format="date-time",
     *                     description="The date and time until which the parking garage is reserved"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="The error message"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="The error message"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Validation errors"
     *             )
     *         )
     *     )
     * )
     */
    public function reserveGarage(Request $request, $ig_no, $ig_rental_id, $timestandard, $number)
    {
        $request->merge([
            'ig_no' => $ig_no,
            'ig_rental_id' => $ig_rental_id,
            'timestandard' => $timestandard,
            'number' => $number
        ]);

        $request->validate([
            'ig_no' => 'required|integer',
            'ig_rental_id' => 'required|integer',
            'timestandard' => 'required|in:m,h,d',
            'number' => 'required|integer'
        ]);

        $infoGarage = new InfoGarage();
        $result = $infoGarage->reserveGarage($ig_no,$ig_rental_id, $timestandard, $number);

        if (!$result) {
            return response()->json([
                'error' => 'Failed to reserve garage',
                'status_code' => http_response_code(),
                'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code())
            ], 400);
        }

        return response()->json([
            'data' => $result,
            'status_code' => http_response_code(),
            'message' => \App\Http\Helper\AppHelper::instance()->network_code_helper(http_response_code())
        ]);
    }

}
