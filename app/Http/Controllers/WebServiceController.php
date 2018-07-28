<?php

namespace App\Http\Controllers;

use App\User;
use App\Empresa;

use App\Factura;
use App\Liquidacion;
use App\Http\Requests;
use GuzzleHttp\Client;
use App\UsuarioEmpresa;
use App\CuentasContables;
//use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\RequestException;

class WebServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCodeProveedorSap($id)
    {
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);
        
        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
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

    public function getCodigoProject($id)
    {
        $param = explode('-', $id);        
        $empresaId = $param[0];
        $listId = $param[1];
        

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                
                
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {                             
            $combo[] = ['ID' => 'SR', 'DESCRIPCION' =>  'SIN RESULTADOS'];
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);       
            return $combo;
        }    
        
        foreach ($data['data'] as $a => $b) {
            $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
        }

        array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

       return $combo;

    }

    public function getCodigoCentroCostoUno($id)
    {
        $param = explode('-', $id);
        //dd($param[0]);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {                             
            $combo[] = ['ID' => '', 'DESCRIPCION' =>  'SIN RESULTADOS'];
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);       
            return $combo;
        }    
        /*$combo = '<select class="form-control" id="CENTROCOSTO1" name="CENTROCOSTO1">';*/
        foreach ($data['data'] as $a => $b) {
            $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
        }
        /*$combo .= '</select>';*/
        array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

       return $combo;

    }

    public function getCodigoCentroCostoUnoAsincrono($id)
    {
        //dd('here');
        $param = explode('-', $id);
        //dd($param[0]);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->requestAsync('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
            ], ['timeout' => 0.5]); 
            $response->wait();
            //dd($response);

        $response->then(
            function (ResponseInterface $res) {                
                //echo $res->getStatusCode() . "\n";
                $data = json_decode($res->getBody(), true); //dd($data);
                $filas = $data['nrows'];
                //$combo = array();
                /* $combo = '<select class="form-control" id="CENTROCOSTO1" name="CENTROCOSTO1">';*/
                foreach ($data['data'] as $a => $b) {
                    $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
                }
                //)
                /*
                $combo .= '</select>';                */

                dd($combo);                
            },
            function (RequestException $e) {  
                dd('entro aqui');              
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            }
        );
    }

    public function getCodigoCentroCostoDos($id)
    {
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {            
            $combo[] = ['ID' => '', 'DESCRIPCION' =>  'SIN RESULTADOS'];       
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);
            return $combo;
        }        
       
       foreach ($data['data'] as $a => $b) {
        $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
       } 
       
       array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

        return $combo;

    }

    public function getCodigoCentroCostoTres($id)
    {
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {            
            $combo[] = ['ID' => '', 'DESCRIPCION' =>  'SIN RESULTADOS'];  
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);     
            return $combo;
        }        
   
        foreach ($data['data'] as $a => $b) {
            $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
        }    

        array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

        return $combo;

    }

    public function getCodigoCentroCostoCuatro($id)
    {
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {            
            $combo[] = ['ID' => '', 'DESCRIPCION' =>  'SIN RESULTADOS'];  
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);     
            return $combo;
        }        
    
        foreach ($data['data'] as $a => $b) {
            $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
        }    

        array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

        return $combo;

    }

    public function getCodigoCentroCostoCinco($id)
    {
        $param = explode('-', $id);
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => $listId,
                'filter' => '',
                'dimcode' => $codeId,
                'active' => 'Y',
            ]
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];

        if($filas == 0) {            
            $combo[] = ['ID' => '', 'DESCRIPCION' =>  'SIN RESULTADOS']; 
            array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);      
            return $combo;
        }        
   
        foreach ($data['data'] as $a => $b) {
            $combo[] = ['ID' => $b['code'], 'DESCRIPCION' =>  $b['name']];
        }    

        array_unshift($combo, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);

        return $combo;

    }

    public function getTasaCambio($id)
    {

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
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

    public function getMonedasEmpresa($id)
    {   
        $param = explode('-', $id);
        $SQL_USER = $param[0];
        $SQL_PASSWORD = $param[1];
        $SQL_DB = $param[2];
        $SQL_SERVER = $param[3];

        
        
        
        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);        

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => 0,
                'requestType' => 4,
                'SQL_USER' => $SQL_USER,
                'SQL_PASSWORD' => $SQL_PASSWORD,
                'SQL_DB' => $SQL_DB,
                'SQL_SERVER' => $SQL_SERVER
                //'filter' => ''                
            ]
        ]);
        
        $data = json_decode($response->getBody(), true);
        //dd($data['result']);
        if ($data['result'] != 0) {
            return 'No se pudo realizar la Conexión, por favor verifique las Credenciales';
        }                
        
        $datos = [];        
        
        $datos[] = $data['company_name'];
        $datos[] = $data['main_currency'];
        $datos[] = $data['sys_currency'];
        
        
        return $datos;        

    }

    public function getCodigoUsuario($id)
    {
        /* $empresa = User::select('cat_empresa.ID as ID')
        ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
        ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
        ->where('users.nombre', '=', $id)
        ->where('users.activo', '=', 1) 
        ->where('cat_usuarioempresa.ANULADO', '=', 0)                               
        ->first();

        if ($empresa) {            
            $empresaId = $empresa->ID;
            $filtro = 'Carlos Pérez';
        } else {
            $combo = '<select class="form-control" id="usersap_id" name="usersap_id">';            
                $combo .=  '<option value="00">Usuario no asociado a Empresa</option>';            
            $combo .= '</select>';    
            return $combo;            
        } */

        $param = explode('-', $id);
        $empresaId = $param[0];
        $filtro = $param[1];

        
        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => 6,
                'filter' => ''//*'Carlos Pérez'*/$filtro                
            ]
        ]);

        $data = json_decode($response->getBody(), true);

              //  dd($data);
        $filas = $data['nrows'];

        if($filas == 0) {
            $combo = '<select class="form-control" id="usersap_id" name="usersap_id">';            
                $combo .=  '<option value="00">SIN RESULTADOS</option>';            
            $combo .= '</select>';    
            return $combo;
        }

        $combo = '<select class="form-control" id="usersap_id" name="usersap_id">';
        $combo .='<option value="0">Seleccione una opción</option>';
        foreach ($data['data'] as $a => $b) {
            $combo .=  '<option value="' . $b['code'] . '">' . $b['code'].' - '.$b['name'] . '</option>';
        }
        $combo .= '</select>';

        return $combo;

    }

    public function envioSap($id)
    {
        //dd('Liquidacion: ' . $id);
        $empresa_id = Session::get('empresa');
      

        $encabezado = collect(DB::select("
                                select                                 
                                DocNum		=	a.ID + 25000, 
                                DocType		=	'dDocument_Service',
                                DocDate		=	a.FECHA_FACTURA, 
                                DocDueDate		=	a.FECHA_FACTURA, 
                                DocTaxDate		=	a.FECHA_FACTURA, 
                                CardCode    =	g.CODIGO_PROVEEDOR_SAP,
                                NumAtCard	=	COALESCE(a.Serie,'') + ' - ' +  a.NUMERO,
                                DocCurrency = RTRIM(a.MONEDA_ID),
                                SalesPersonCode = g.USERSAP_ID,
                                U_FacFecha  = a.FECHA_FACTURA,
                                U_FacSerie = coalesce(a.SERIE, '') ,
                                U_FacNum = a.NUMERO,
                                U_facNit = j.IDENTIFICADOR_TRIBUTARIO ,
                                U_facNom = j.NOMBRE,
                                U_Clase_Libro = c.GRUPOTIPOGASTO_ID,
                                U_tipo_documento = i.descripcion,
                                businessObject = 'oPurchaseInvoices'                                
                                
                            from 
                                liq_factura a inner join
                                cat_tipogasto c on a.TIPOGASTO_ID = c.ID 
                                                    and empresa_id = {$empresa_id}  inner join
                                cat_subcategoria_tipogasto b on a.SUBCATEGORIA_TIPOGASTO_ID = b.ID inner join
                                liq_liquidacion d on a.liquidacion_id = d.id inner join                               
                                cat_usuarioruta e on d.USUARIORUTA_ID = e.ID inner join
                                users f on e.USER_ID = f.id inner join
                                cat_usuarioempresa g on f.id = g.USER_ID
                                                    and g.EMPRESA_ID = c.EMPRESA_ID inner join
                                cat_tipodocumento i on a.TIPODOCUMENTO_ID = i.ID inner join
                                cat_proveedor j on a.PROVEEDOR_ID = j.id
                            where 
                                a.LIQUIDACION_ID = {$id} 
                                and a.ANULARENVIO_SAP <> 1
                                and coalesce(MONTO_afecto,0) > 0
        "));
      
        foreach ($encabezado as $key => $item) {
            $item = (array) $item ;            
            //var_dump ($item);
            //echo('<br>');
        }        

        $detalle = DB::select("
                            select	
                            ParentKey = a.id + 25000,
                            lineNum = 0,
                            ItemDescription =  c.DESCRIPCION + ' - ' + i.DESCRIPCION + ' '+ COALESCE(a.Serie,'') + ' - ' +  a.NUMERO,
                            PriceAfVAT = a.TOTAL - A.MONTO_EXENTO, 
                            AccountCode = c.CUENTA_CONTABLE_AFECTO,
                            TaxCode = RTRIM(c.CODIGO_IMPUESTO_AFECTO),
                            ProjectCode = h.PROYECTO,
                            CostingCode = h.CENTROCOSTO1,
                            CostingCode2 = h.CENTROCOSTO2,
                            CostingCode3 = h.CENTROCOSTO3,
                            CostingCode4 = h.CENTROCOSTO4,
                            CostingCode5 = h.CENTROCOSTO5
                            from 
                                liq_factura a inner join
                                cat_tipogasto c on a.TIPOGASTO_ID = c.ID 
                                                    and empresa_id = {$empresa_id}  inner join                                
                                liq_liquidacion d on a.liquidacion_id = d.id inner join
                                pre_detpresupuesto h on a.DETPRESUPUESTO_ID = h.ID inner join
                                cat_usuarioruta e on d.USUARIORUTA_ID = e.ID inner join                                
                                cat_tipodocumento i on a.TIPODOCUMENTO_ID = i.ID inner join
                                cat_proveedor j on a.PROVEEDOR_ID = j.id
                            where 
                                a.LIQUIDACION_ID = {$id} 
                                and coalesce(MONTO_afecto,0) > 0
                            union all	
                            select 	
                                ParentKey = a.id + 25000,
                                lineNum = 1,
                                Dscription =  c.DESCRIPCION + ' - ' + i.DESCRIPCION + ' '+ COALESCE(a.Serie,'') + ' - ' +  a.NUMERO,
                                PriceAfVAT = A.MONTO_EXENTO, 
                                c.CUENTA_CONTABLE_EXENTO,
                                c.CODIGO_IMPUESTO_EXENTO,
                                h.PROYECTO,
                                h.CENTROCOSTO1,
                                h.CENTROCOSTO2,
                                h.CENTROCOSTO3,
                                h.CENTROCOSTO4,
                                h.CENTROCOSTO5
                            from 
                                liq_factura a inner join
                                cat_tipogasto c on a.TIPOGASTO_ID = c.ID 
                                                    and empresa_id = {$empresa_id} inner join                                
                                liq_liquidacion d on a.liquidacion_id = d.id inner join
                                pre_detpresupuesto h on a.DETPRESUPUESTO_ID = h.ID inner join
                                cat_usuarioruta e on d.USUARIORUTA_ID = e.ID inner join                                
                                cat_tipodocumento i on a.TIPODOCUMENTO_ID = i.ID 
                            where 
                                LIQUIDACION_ID = {$id} 
                                and a.ANULARENVIO_SAP <> 1
                                and coalesce(MONTO_EXENTO,0) > 0
                            order by
                                1
            
        ");
       //dd($encabezado);
       //dd($detalle);
        foreach ($encabezado as $item) {
            $documentoAnterior = 0;
            $nuevoDocumento = true;
            foreach ($detalle as $detail => $itemDetail) {                
                if (($item->DocNum == $itemDetail->ParentKey) && ($nuevoDocumento == true)) {                    
                    $lineaCero = array(                        
                        'ParentKey' => $itemDetail->ParentKey,
                        'LineNum' => $itemDetail->lineNum,
                        'AccountCode' => $itemDetail->AccountCode,
                        'ItemDescription' => $itemDetail->ItemDescription,
                        'PriceAfterVAT' => $itemDetail->PriceAfVAT,
                        'TaxCode' => $itemDetail->TaxCode,
                        'ProjectCode' => $itemDetail->ProjectCode,
                        'CostingCode' => $itemDetail->CostingCode,
                        'CostingCode2' => $itemDetail->CostingCode2,
                        'CostingCode3' => $itemDetail->CostingCode3,
                        'CostingCode4' => $itemDetail->CostingCode4,
                        'CostingCode5' => $itemDetail->CostingCode5,
                        );
                    $item->detail = array($lineaCero);
                    $nuevoDocumento = false;
                }
                if (($item->DocNum == $itemDetail->ParentKey) && ($documentoAnterior == $item->DocNum)) {
                    $lineaUno = array(                        
                        'ParentKey' => $itemDetail->ParentKey,
                        'LineNum' => $itemDetail->lineNum,
                        'AccountCode' => $itemDetail->AccountCode,
                        'ItemDescription' => $itemDetail->ItemDescription,
                        'PriceAfterVAT' => $itemDetail->PriceAfVAT,
                        'TaxCode' => $itemDetail->TaxCode,
                        'ProjectCode' => $itemDetail->ProjectCode,
                        'CostingCode' => $itemDetail->CostingCode,
                        'CostingCode2' => $itemDetail->CostingCode2,
                        'CostingCode3' => $itemDetail->CostingCode3,
                        'CostingCode4' => $itemDetail->CostingCode4,
                        'CostingCode5' => $itemDetail->CostingCode5,
                        );
                    $item->detail = array($lineaCero, $lineaUno);                    
                }
                $documentoAnterior = $itemDetail->ParentKey;                                  
            }
            $fechaUltimaFactura = $item->DocDate;
        }
        
        $notaCredito = collect(DB::select("
                                        Select Distinct
                                            DocNum		=	1 + 25500, 
                                            DocType		=	'dDocument_Service',
                                            DocDate		=		d.FECHA_FINAL, 
                                            DocDueDate		=	d.FECHA_FINAL, 
                                            DocTaxDate		=	d.FECHA_FINAL, 
                                            CardCode    =	g.CODIGO_PROVEEDOR_SAP,
                                            NumAtCard	=	'REMANENTE',
                                            DocCurrency =	RTRIM(k.MONEDA_LOCAL),
                                            SalesPersonCode = g.USERSAP_ID,
                                            U_FacFecha  = d.FECHA_FINAL,
                                            U_FacSerie = '' ,
                                            U_FacNum =  'REMANENTE',
                                            U_FacNit = '12345678-9' ,
                                            U_FacNom = 'Usuario',
                                            U_Clase_Libro = 'Varios',
                                            U_Tipo_Documento = 'Nota Credito',
                                            BusinessObject = 'oPurchaseCreditNotes'
                                        from 
                                            liq_factura a inner join                                           
                                            cat_subcategoria_tipogasto b on a.SUBCATEGORIA_TIPOGASTO_ID = b.ID inner join
                                            liq_liquidacion d on a.liquidacion_id = d.id inner join
                                            pre_detpresupuesto h on a.DETPRESUPUESTO_ID = h.ID inner join
                                            cat_usuarioruta e on d.USUARIORUTA_ID = e.ID inner join
                                            users f on e.USER_ID = f.id  inner join
                                            cat_usuarioempresa g on f.id = g.USER_ID
                                                                and g.EMPRESA_ID = $empresa_id inner join
                                            cat_empresa k on g.EMPRESA_ID = k.ID 
                                                        
                                        where 
                                            a.LIQUIDACION_ID = $id 
                                            and coalesce(a.MONTO_remanente,0) > 0
                                        
                                "));

        foreach ($notaCredito as $key => $item) {
           /*  echo $item->DocNum . '<br>';
            echo $item->DocType . '<br>';
            echo $item->DocDate . '<br>';
            $item = (array) $item ;   */
                             
            $noteCredit['DocNum'] = $item->DocNum;
            $noteCredit['DocType'] = $item->DocType;
            $noteCredit['DocDate'] = $item->DocDate;
            $noteCredit['DocDueDate'] = $item->DocDueDate;
            $noteCredit['DocTaxDate'] = $item->DocTaxDate;
            $noteCredit['CardCode'] = $item->CardCode;
            $noteCredit['NumAtCard'] = $item->NumAtCard;
            $noteCredit['DocCurrency'] = $item->DocCurrency;
            $noteCredit['SalesPersonCode'] = $item->SalesPersonCode;
            $noteCredit['U_FacFecha'] = $item->U_FacFecha;
            $noteCredit['U_FacSerie'] = $item->U_FacSerie;
            $noteCredit['U_FacNum'] = $item->U_FacNum;            
            $noteCredit['U_FacNit'] = $item->U_FacNit;
            $noteCredit['U_FacNom'] = $item->U_FacNom;
            $noteCredit['U_Clase_Libro'] = $item->U_Clase_Libro;
            $noteCredit['U_Tipo_Documento'] = $item->U_Tipo_Documento;
            $noteCredit['businessObject'] = $item->BusinessObject;
        }       

        $detalleNotaCredito = DB::select("
                                            select
                                                ParentKey = 1,
                                                lineNum = 0,
                                                ItemDescription =  c.DESCRIPCION,
                                                PriceAfVAT = sum(a.MONTO_REMANENTE), 
                                                AccountCode =	c.CUENTA_CONTABLE_REMANENTE,
                                                TaxCode =		RTRIM(c.CODIGO_IMPUESTO_REMANENTE),
                                                ProjectCode =	h.PROYECTO,
                                                CostingCode =	h.CENTROCOSTO1,
                                                CostingCode2 =	h.CENTROCOSTO2,
                                                CostingCode3 =	h.CENTROCOSTO3,
                                                CostingCode4 =	h.CENTROCOSTO4,
                                                CostingCode5 =	h.CENTROCOSTO5
                                            from 
                                                liq_factura a inner join
                                                cat_tipogasto c on a.TIPOGASTO_ID = c.ID 
                                                                    and empresa_id = {$empresa_id} inner join                                                
                                                liq_liquidacion d on a.liquidacion_id = d.id inner join
                                                pre_detpresupuesto h on a.DETPRESUPUESTO_ID = h.ID inner join
                                                cat_usuarioruta e on d.USUARIORUTA_ID = e.ID                                               
                                                            
                                            where 
                                                a.LIQUIDACION_ID = {$id }
                                                and coalesce(a.MONTO_remanente,0) > 0
                                            group by                                                
                                                c.GRUPOTIPOGASTO_ID,                                            
                                                --Detalle 
                                                c.DESCRIPCION,
                                                c.CUENTA_CONTABLE_REMANENTE,
                                                RTRIM(c.CODIGO_IMPUESTO_REMANENTE),
                                                h.PROYECTO,
                                                h.CENTROCOSTO1,
                                                h.CENTROCOSTO2,
                                                h.CENTROCOSTO3,
                                                h.CENTROCOSTO4,
                                                h.CENTROCOSTO5
        ");
        //dd($detalleNotaCredito);

        $details = null;  
        $linea = 0;      
        //dd($detalleNotaCredito);
        foreach ($detalleNotaCredito as $nota => $value) {            
            $details[] = [
                'ParentKey' => 1,
                'LineNum' => $linea++,
                'AccountCode' => $value->AccountCode,
                'ItemDescription' => $value->ItemDescription,
                'PriceAfterVAT' => $value->PriceAfVAT,
                'TaxCode' => $value->TaxCode,
                'ProjectCode' => $value->ProjectCode,
                'CostingCode' => $value->CostingCode,
                'CostingCode2' => $value->CostingCode2,
                'CostingCode3' => $value->CostingCode3,
                'CostingCode4' => $value->CostingCode4,
                'CostingCode5' => $value->CostingCode5
            ];      
        }

        $noteCredit['detail'] = $details;
        $encabezado->push($noteCredit);
        //dd($encabezado) ;
        $encabezado = $encabezado->toArray();
        
            $json = [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $empresa_id,
                'requestType' => 3,
                'liquidacionId' => $id,
                'items' => $encabezado               
            ];

          //return $json;

        /* $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->requestAsync('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $empresa_id,
                'requestType' => 3,
                'liquidacionId' => $id,
                'items' => $facturas               
            ]
            ], ['time' => 0.5]);
            $response->wait();

        $response->then(
            function (ResponseInteface $res) {
                dd('Regreso');
                $data = json_decode($response->getBody(), true);
                dd($data);
            },
            function (RequestException $e) {
                dd('entro aqui');
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            }
        ); */

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);

        $key = time();
        $token = md5('tarara' . $key);

        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => $key,
                'token' => $token,
                'companyId' => $empresa_id,
                'requestType' => 3,
                'liquidacionId' => $id,
                'items' => $encabezado
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        
        dd($data);

    }

}


