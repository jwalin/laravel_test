<?php

namespace App\Http\Controllers\api\v1;

use App\Model\Property;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::all();
        foreach ($properties as $property) {
            $data = $this->_getLatLong($property);
            $property->lat = $data['lat'];
            $property->lng = $data['lng'];
        }
        $response = [
            'success' => true,
            'properties' => $properties
        ];
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $property = new Property;
        $property->fill($data);
        $property->save();

        $data = $this->_getLatLong($property);
        $property->lat = $data['lat'];
        $property->lng = $data['lng'];
        
        $response = [
            'success' => true,
            'property' => $property
        ];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        $data = $this->_getLatLong($property);
        $property->lat = $data['lat'];
        $property->lng = $data['lng'];
        $response = [
            'success' => true,
            'property' => $property
        ];
        return response()->json($response);
    }

    private function _getLatLong($property)
    {
        $address = $property->full_address;
        $address = str_replace(" ", "+", $address);

        $client = new Client();
        $response = $client->get(config('gmap.url').'?address='.$address.'&key='.config('gmap.api_key'))->getBody()->getContents();
        $response = json_decode($response, true);
        return $response['results'][0]['geometry']['location'];
    }
}

