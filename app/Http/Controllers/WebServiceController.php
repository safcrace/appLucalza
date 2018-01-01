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
        $combo = '<select class="form-control" id="codigoProveedorSap" name="codigoProveedorSap", disable=false>';
        $combo .='<option value="0">Seleccione una opción</option>';
        $combo .='<option value="V00000">V00000 - Sin Código</option>';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['code'].' - '.$b['name'] . '</option>';
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
        $combo .='<option value="0">Seleccione una opción</option>';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] .'" '. ($b['postable'] == 'N' ? ' style="font-weight:bold; background-color:eee;"' : '') . ($b['postable'] == 'Y' ? ' data-postable="' . $b['postable'] . '"' : '') . '>' . str_replace(' ', '&nbsp;', $b['name']) . '</option>';
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
        $combo = '<select class="form-control" id="cuentaContableAfecta" name="cuentaContableAfecta">';
        $combo .='<option value="0">Seleccione una opción</option>';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] .'" '. ($b['postable'] == 'N' ? ' style="font-weight:bold; background-color:eee;"' : '') . ($b['postable'] == 'Y' ? ' data-postable="' . $b['postable'] . '"' : '') . '>' . str_replace(' ', '&nbsp;', $b['name']) . '</option>';
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
        $combo = '<select class="form-control" id="cuentaContableRemanente" name="cuentaContableRemanente">';
        $combo .='<option value="0">Seleccione una opción</option>';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] .'" '. ($b['postable'] == 'N' ? ' style="font-weight:bold; background-color:eee;"' : '') . ($b['postable'] == 'Y' ? ' data-postable="' . $b['postable'] . '"' : '') . '>' . str_replace(' ', '&nbsp;', $b['name']) . '</option>';
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
        $combo = '<select class="form-control" id="codigoImpuestoExento" name="codigoImpuestoExento">';
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
        $combo = '<select class="form-control" id="codigoImpuestoAfecto" name="codigoImpuestoAfecto">';
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
        $combo = '<select class="form-control" id="codigoImpuestoRemanente" name="codigoImpuestoRemanente">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoUno($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $code = $param[1];

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
                'filter' => '',
                'dimcode' => $code,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select class="form-control" id="CENTROCOSTO1" name="CENTROCOSTO1">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoDos($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $code = $param[1];

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
                'filter' => '',
                'dimcode' => $code,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {
            $combo = '<select class="form-control" id="CENTROCOSTO5" name="CENTROCOSTO5">';            
                $combo .=  '<option value="00">SIN RESULTADOS</option>';            
            $combo .= '</select>';    
            return $combo;
        }
        
        $combo = '<select class="form-control" id="CENTROCOSTO2" name="CENTROCOSTO2">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoTres($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $code = $param[1];

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
                'filter' => '',
                'dimcode' => $code,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select class="form-control" id="CENTROCOSTO3" name="CENTROCOSTO3">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoCuatro($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $code = $param[1];

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
                'filter' => '',
                'dimcode' => $code,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        $combo = '<select class="form-control" id="CENTROCOSTO4" name="CENTROCOSTO4">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getCodigoCentroCostoCinco($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $code = $param[1];

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
                'filter' => '',
                'dimcode' => $code,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];

        if($filas == 0) {
            $combo = '<select class="form-control" id="CENTROCOSTO5" name="CENTROCOSTO5">';            
                $combo .=  '<option value="00">SIN RESULTADOS</option>';            
            $combo .= '</select>';    
            return $combo;
        }

        $combo = '<select class="form-control" id="CENTROCOSTO5" name="CENTROCOSTO5">';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function getTasaCambio($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => 1,
                'requestType' => 2,
                'rateDate' => $id,
                'currency' => 'USD'                
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        
        foreach ($data['data'] as $a => $b) {
            $valor = $b['rate'];
        }
        

        return round($valor, 2);

    }
}
