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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
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
                $combo = '<select class="form-control" id="CENTROCOSTO1" name="CENTROCOSTO1">';
                foreach ($data['data'] as $a => $b) {
                    $combo .=  '<option value="' . $b['code'] . '">' . $b['name'] . '</option>';
                }
                $combo .= '</select>';               

                echo $combo;                
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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
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
        ]);

        $data = json_decode($response->getBody(), true);


        $filas = $data['nrows'];
        if($filas == 0) {
            $combo = '<select class="form-control" id="CENTROCOSTO2" name="CENTROCOSTO2">';            
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
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
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
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
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
        $empresaId = $param[0];
        $listId = $param[1];
        $codeId = $param[2];

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
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

    public function getMonedasEmpresa($id)
    {
        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $id,
                'requestType' => 1,
                'listId' => 7,
                'filter' => ''                
            ]
        ]);

        
        $data = json_decode($response->getBody(), true);
        
       /*  if ($data['message'] == "Invalid SAP database reference") {
            dd('Bien Safi');
        } */
        
        $filas = $data['nrows'];

        $codigos = [];
        
        foreach ($data['data'] as $a => $b) {
            $codigos[] = $b['local_currency'];
            $codigos[] = $b['sys_currency'];
        }        

        Empresa::where('ID', $id)
          ->update(['MONEDA_LOCAL' => $codigos[0], 'MONEDA_SYS' => $codigos[1]]);      
        

        return redirect::to('empresas');

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


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $empresaId,
                'requestType' => 1,
                'listId' => 6,
                'filter' => ''//*'Carlos Pérez'*/$filtro                
            ]
        ]);

        $data = json_decode($response->getBody(), true);


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
        $usuario_id = Auth::user()->id;  
        $notaCredito = Empresa::where('ID', '=', $empresa_id)->pluck('FILAS_NOTA_CREDITO');

        $codigoUsuarioSap = UsuarioEmpresa::select('USERSAP_ID')->where('USER_ID', '=', $usuario_id)->where('EMPRESA_ID', '=', $empresa_id)->first();
        $codigoUsuarioSap = $codigoUsuarioSap->USERSAP_ID;

        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'users.nombre as USUARIO', 
                                           'cat_usuarioempresa.CODIGO_PROVEEDOR_SAP', 'cat_ruta.DESCRIPCION as RUTA', 'liq_liquidacion.FECHA_FINAL')
                                        ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                        ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.ID')
                                        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                        ->where('liq_liquidacion.id', '=', $id)
                                        ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                                        ->first();

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.ID as PROVEEDORID', 'cat_proveedor.NOMBRE', 'cat_proveedor.IDENTIFICADOR_TRIBUTARIO', 'liq_factura.SERIE as SERIE',
                                    'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL', 'liq_factura.FECHA_FACTURA', 'liq_factura.MONTO_IVA',
                                    'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'cat_tipogasto.GRUPOTIPOGASTO_ID', 'liq_factura.APROBACION_PAGO', 'cat_tipodocumento.DESCRIPCION as DOCUMENTO',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'cat_proveedor.TIPOPROVEEDOR_ID', 'liq_factura.MONTO_AFECTO',
                                    'MONTO_EXENTO', 'liq_factura.MONTO_REMANENTE', 'pre_detpresupuesto.CENTROCOSTO1', 'pre_detpresupuesto.CENTROCOSTO2',
                                    'pre_detpresupuesto.CENTROCOSTO3', 'pre_detpresupuesto.CENTROCOSTO4', 'pre_detpresupuesto.CENTROCOSTO5', 'cat_tipogasto.CUENTA_CONTABLE_EXENTO', 'cat_tipogasto.CODIGO_IMPUESTO_EXENTO',
                                    'cat_tipogasto.CUENTA_CONTABLE_AFECTO', 'cat_tipogasto.CODIGO_IMPUESTO_AFECTO', 'cat_tipogasto.CUENTA_CONTABLE_REMANENTE', 'cat_tipogasto.CODIGO_IMPUESTO_REMANENTE')
                                        ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                        ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                        ->join('liq_liquidacion', 'liq_liquidacion.ID', '=', 'liq_factura.LIQUIDACION_ID')
                                        ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('cat_tipodocumento', 'cat_tipodocumento.ID', '=', 'liq_factura.TIPODOCUMENTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.ID', '=', 'liq_factura.DETPRESUPUESTO_ID')
                                        ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                        //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                        ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                        ->where('liq_factura.ANULADO', '=', 0) 
                                        ->get();  
            
            // Construcción de Arreglo de Documentos Enviados a SAP

            $DocNum = 0;    

            //dd($facturas);
            $noteCredit = array();
            foreach ($facturas as $factura) {                      
                $DocNum += 1;     
                $factura->DocNum = $DocNum ;
                $factura->DocType = 'dDocument_Service';
                $factura->DocDate = $factura->FECHA_FACTURA->format('Y-m-d');
                $factura->DocDueDate = $factura->FECHA_FACTURA->format('Y-m-d');
                $factura->DocTaxDate = $factura->FECHA_FACTURA->format('Y-m-d');
                $factura->CardCode = $liquidacion->CODIGO_PROVEEDOR_SAP;
                $factura->NumAtCard = $factura->SERIE . ' - ' . $factura->NUMERO;
                $factura->DocCurrency = 'QTZ';
                $factura->SalesPersonCode = $codigoUsuarioSap;
                $factura->U_FacFecha = $factura->FECHA_FACTURA->format('Y-m-d');
                $factura->U_FacSerie = $factura->SERIE;
                $factura->U_FacNum = $factura->NUMERO;
                $factura->U_FacNum = $factura->NUMERO;
                $factura->U_FacNit = $factura->IDENTIFICADOR_TRIBUTARIO;
                $factura->U_FacNom = $factura->NOMBRE;
                $factura->U_Clase_Libro = $factura->GRUPOTIPOGASTO_ID;
                $factura->U_Tipo_Documento = $factura->DOCUMENTO;  
                if ($factura->TIPOGASTO == 'Alimentación') {
                    if ($notaCredito == 0) {
                        if ($factura->MONTO_REMANENTE == null) {
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => 0,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->TOTAL,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                )
                            );                
                        } 
                    } else { 
                        if ($factura->MONTO_REMANENTE == null) {
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => 0,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->TOTAL,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                )
                            );
                        } else {
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_REMANENTE,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_REMANENTE,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );
                        }                   
                    }
                }          
                
                if ($factura->TIPOGASTO == 'Combustible') {
                    if ($notaCredito == 0) {
                        if ($factura->MONTO_REMANENTE == null) {
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );
                        }
                    } else {
                        if ($factura->MONTO_REMANENTE == null) {                        
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );                        
                        } else {
                            $linea = 0;
                            $factura->Detalle = array(
                                array(   
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                ),
                                array(
                                    'ParentKey_3' => $factura->$DocNum,
                                    'LineNum_3' => $linea + 3,
                                    'AccountCode_3' => $factura->CUENTA_CONTABLE_REMANENTE,
                                    'ItemDescription_3' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_3' => $factura->MONTO_REMANENTE,
                                    'TaxCode_3' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                    'ProjectCode_3' => 'OOFISCAL',
                                    'CostingCode_3' => $factura->CENTROCOSTO1,
                                    'CostingCode2_3' => $factura->CENTROCOSTO2,
                                    'CostingCode3_3' => $factura->CENTROCOSTO3,
                                    'CostingCode4_3' => $factura->CENTROCOSTO4,
                                    'CostingCode5_3' => $factura->CENTROCOSTO5
                                )
                            );
                        }
                    }
                }
                //Hospedaje
                if ($factura->TIPOGASTO == 'Hospedaje') {
                    if ($notaCredito == 0) {
                        if ($factura->MONTO_EXENTO == null) {
                            if ($factura->MONTO_REMANENTE == null) {
                                $factura->Detalle = array(
                                    array(
                                        'ParentKey' => $factura->$DocNum,
                                        'LineNum' => 0,
                                        'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                        'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT' => $factura->TOTAL,
                                        'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                        'ProjectCode' => 'OOFISCAL',
                                        'CostingCode' => $factura->CENTROCOSTO1,
                                        'CostingCode2' => $factura->CENTROCOSTO2,
                                        'CostingCode3' => $factura->CENTROCOSTO3,
                                        'CostingCode4' => $factura->CENTROCOSTO4,
                                        'CostingCode5' => $factura->CENTROCOSTO5
                                    )
                                );    
                            }                     
                        
                        } else {                        
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );                     

                        }
                    } else {
                        if ($factura->MONTO_EXENTO == null) {
                            if ($factura->MONTO_REMANENTE == null) {
                                $factura->Detalle = array(
                                    array(
                                        'ParentKey' => $factura->$DocNum,
                                        'LineNum' => 0,
                                        'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                        'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT' => $factura->TOTAL,
                                        'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                        'ProjectCode' => 'OOFISCAL',
                                        'CostingCode' => $factura->CENTROCOSTO1,
                                        'CostingCode2' => $factura->CENTROCOSTO2,
                                        'CostingCode3' => $factura->CENTROCOSTO3,
                                        'CostingCode4' => $factura->CENTROCOSTO4,
                                        'CostingCode5' => $factura->CENTROCOSTO5
                                    )
                                );    
                            } else {
                                $linea = 0;
                                $factura->Detalle = array(
                                    array(
                                        'ParentKey' => $factura->$DocNum,
                                        'LineNum' => $linea + 1,
                                        'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                        'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                        'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                        'ProjectCode' => 'OOFISCAL',
                                        'CostingCode' => $factura->CENTROCOSTO1,
                                        'CostingCode2' => $factura->CENTROCOSTO2,
                                        'CostingCode3' => $factura->CENTROCOSTO3,
                                        'CostingCode4' => $factura->CENTROCOSTO4,
                                        'CostingCode5' => $factura->CENTROCOSTO5,
                                    ),
                                    array(
                                        'ParentKey_2' => $factura->$DocNum,
                                        'LineNum_2' => $linea + 2,
                                        'AccountCode_2' => $factura->CUENTA_CONTABLE_REMANENTE,
                                        'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT_2' => $factura->MONTO_REMANENTE,
                                        'TaxCode_2' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                        'ProjectCode_2' => 'OOFISCAL',
                                        'CostingCode_2' => $factura->CENTROCOSTO1,
                                        'CostingCode2_2' => $factura->CENTROCOSTO2,
                                        'CostingCode3_2' => $factura->CENTROCOSTO3,
                                        'CostingCode4_2' => $factura->CENTROCOSTO4,
                                        'CostingCode5_2' => $factura->CENTROCOSTO5
                                    )
                                );
                            }  
                        } else {
                            if ($factura->MONTO_REMANENTE == null) {                        
                                $linea = 0;
                                $factura->Detalle = array(
                                    array(
                                        'ParentKey' => $factura->$DocNum,
                                        'LineNum' => $linea + 1,
                                        'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                        'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                        'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                        'ProjectCode' => 'OOFISCAL',
                                        'CostingCode' => $factura->CENTROCOSTO1,
                                        'CostingCode2' => $factura->CENTROCOSTO2,
                                        'CostingCode3' => $factura->CENTROCOSTO3,
                                        'CostingCode4' => $factura->CENTROCOSTO4,
                                        'CostingCode5' => $factura->CENTROCOSTO5,
                                    ),
                                    array(
                                        'ParentKey_2' => $factura->$DocNum,
                                        'LineNum_2' => $linea + 2,
                                        'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                        'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                        'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                        'ProjectCode_2' => 'OOFISCAL',
                                        'CostingCode_2' => $factura->CENTROCOSTO1,
                                        'CostingCode2_2' => $factura->CENTROCOSTO2,
                                        'CostingCode3_2' => $factura->CENTROCOSTO3,
                                        'CostingCode4_2' => $factura->CENTROCOSTO4,
                                        'CostingCode5_2' => $factura->CENTROCOSTO5
                                    )
                                );                        
                            } else {
                                $linea = 0;
                                $factura->Detalle = array(
                                    array(   
                                        'ParentKey' => $factura->$DocNum,
                                        'LineNum' => $linea + 1,
                                        'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                        'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                        'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                        'ProjectCode' => 'OOFISCAL',
                                        'CostingCode' => $factura->CENTROCOSTO1,
                                        'CostingCode2' => $factura->CENTROCOSTO2,
                                        'CostingCode3' => $factura->CENTROCOSTO3,
                                        'CostingCode4' => $factura->CENTROCOSTO4,
                                        'CostingCode5' => $factura->CENTROCOSTO5
                                    ),
                                    array(
                                        'ParentKey_2' => $factura->$DocNum,
                                        'LineNum_2' => $linea + 2,
                                        'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                        'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                        'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                        'ProjectCode_2' => 'OOFISCAL',
                                        'CostingCode_2' => $factura->CENTROCOSTO1,
                                        'CostingCode2_2' => $factura->CENTROCOSTO2,
                                        'CostingCode3_2' => $factura->CENTROCOSTO3,
                                        'CostingCode4_2' => $factura->CENTROCOSTO4,
                                        'CostingCode5_2' => $factura->CENTROCOSTO5
                                    ),
                                    array(
                                        'ParentKey_3' => $factura->$DocNum,
                                        'LineNum_3' => $linea + 3,
                                        'AccountCode_3' => $factura->CUENTA_CONTABLE_REMANENTE,
                                        'ItemDescription_3' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                        'PriceAfterVAT_3' => $factura->MONTO_REMANENTE,
                                        'TaxCode_3' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                        'ProjectCode_3' => 'OOFISCAL',
                                        'CostingCode_3' => $factura->CENTROCOSTO1,
                                        'CostingCode2_3' => $factura->CENTROCOSTO2,
                                        'CostingCode3_3' => $factura->CENTROCOSTO3,
                                        'CostingCode4_3' => $factura->CENTROCOSTO4,
                                        'CostingCode5_3' => $factura->CENTROCOSTO5
                                    )
                                );
                            }

                        }                 

                    }
                }
                $noteCredit['DocNum'] = $DocNum + 1;
                $noteCredit['DocType'] = 'dDocument_Service';
                $noteCredit['DocDate'] = $factura->FECHA_FACTURA->format('Y-m-d');
                $noteCredit['DocDueDate'] = $factura->FECHA_FACTURA->format('Y-m-d');
                $noteCredit['DocTaxDate'] = $factura->FECHA_FACTURA->format('Y-m-d');
                $noteCredit['CardCode'] = $liquidacion->CODIGO_PROVEEDOR_SAP;
                $noteCredit['NumAtCard'] = $factura->SERIE . ' - ' . $factura->NUMERO;
                $noteCredit['DocCurrency'] = 'QTZ';
                $noteCredit['SalesPersonCode'] = $codigoUsuarioSap;
                $noteCredit['U_FacFecha'] = $factura->FECHA_FACTURA->format('Y-m-d');
                $noteCredit['U_FacSerie'] = $factura->SERIE;
                $noteCredit['U_FacNum'] = $factura->NUMERO;
                $noteCredit['U_FacNum'] = $factura->NUMERO;
                $noteCredit['U_FacNit'] = $factura->IDENTIFICADOR_TRIBUTARIO;
                $noteCredit['U_FacNom'] = $factura->NOMBRE;
                $noteCredit['U_Clase_Libro'] = $factura->GRUPOTIPOGASTO_ID;
                $noteCredit['U_Tipo_Documento'] = $factura->DOCUMENTO;
            }
            
            dd($noteCredit);
            $facturas = $facturas->toArray();
            

            $facturas = array_map(function($fac) {
                return array_except($fac, ['ID', 'PROVEEDORID', 'NOMBRE', 'IDENTIFICADOR_TRIBUTARIO', 'SERIE', 'NUMERO',
                                        'TOTAL', 'FECHA_FACTURA', 'MONTO_IVA', 'TIPOGASTO', 'GRUPOTIPOGASTO_ID',
                                        'APROBACION_PAGO', 'DOCUMENTO', 'EMAIL', 'FOTO', 'TIPOPROVEEDOR_ID', 'MONTO_AFECTO',
                                        'MONTO_EXENTO', 'MONTO_REMANENTE', 'CENTROCOSTO1', 'CENTROCOSTO2', 'CENTROCOSTO3',
                                        'CENTROCOSTO4', 'CENTROCOSTO5', 'CUENTA_CONTABLE_EXENTO', 'CODIGO_IMPUESTO_EXENTO',
                                        'CUENTA_CONTABLE_AFECTO', 'CODIGO_IMPUESTO_AFECTO','CUENTA_CONTABLE_REMANENTE',
                                        'CODIGO_IMPUESTO_REMANENTE']);
            }, $facturas);

            //dd($id);
        

        $client = new Client([
            'headers' => ['content-type' => 'application-json', 'Accept' => 'application-jsoon'],
        ]);


        $response = $client->request('POST', 'http://pcidmsserver.cloudapp.net:8080/lucalza/ws/', [
            'json' => [
                'key' => 1502934063,
                'token' => '0a2fd04f2aebaf869aea5e4a3482e427',
                'companyId' => $empresa_id,
                'requestType' => 3,
                'liquidacionId' => $id,
                'items' => $facturas//*'Carlos Pérez'*/$filtro                
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        dd($data);

    }

}


