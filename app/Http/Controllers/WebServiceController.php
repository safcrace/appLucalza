<?php

namespace App\Http\Controllers;

use App\CuentasContables;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class WebServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCodeProveedorSap($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="codigoProveedorSap" name="codigoProveedorSap">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCuentaContableExenta($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="cuentaContableExenta" class="form-control" name="cuentaContableExenta">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
            //CuentasContables::insert(['code' => $b['code'], 'name' => $b['name'] ]);
        }
        $combo .= '</select>';

        //$combo = CuentasContables::lists('name', 'code')->toArray();
        //dd($combo);
        return $combo;

    }

    public function getCuentaContableAfecta($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="cuentaContableAfecta" name="cuentaContableAfecta">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCuentaContableRemanente($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="cuentaContableRemanente" name="cuentaContableAfecta">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoImpuestoExento($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="codigoImpuestoExento" name="codigoImpuestoExento">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoImpuestoAfecto($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="codigoImpuestoAfecto" name="codigoImpuestoAfecto">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoImpuestoRemanente($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="codigoImpuestoRemanente" name="codigoImpuestoRemanente">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoUno($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="CENTROCOSTO1" name="CENTROCOSTO1">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoDos($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="CENTROCOSTO2" name="CENTROCOSTO2">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoTres($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="CENTROCOSTO3" name="CENTROCOSTO3">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoCuatro($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="CENTROCOSTO4" name="CENTROCOSTO4">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoCinco($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 1,
                'listId' => $id,
                'filter' => ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select id="CENTROCOSTO5" name="CENTROCOSTO5">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }
}
