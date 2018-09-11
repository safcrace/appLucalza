<?php
    
    function encrypt($value) {
        $key = 'encrypter key';
        $method = "AES-128-ECB";
        $iv = null;
        $options = 0;
        return openssl_encrypt ($value, $method, $key); 
    }
   
    function decrypt($value) {
        $key = 'encrypter key';
        $method = "AES-128-ECB";
        $iv = null;
        $options = 0;
        return openssl_decrypt($value, $method, $key, $options, $iv); 
    }
 
    function calculos($factura, $esCombustible, $montoConversion, $valorImpuesto, $saldo, $impuestoAplicar) {
        echo 'Saldo: ' . $saldo . '<br>';        
        if ($impuestoAplicar > 0) { 
            if (isset($esCombustible)) { 
                if ($esCombustible->TIPOASIGNACION_ID == 2) { 
                    /** Se verifica si tiene presupuesto para cubrir el gasto o si es 100% remanente */
                    if ($saldo > 0) { 
                        $saldoFactura = $saldo - $factura->CANTIDAD_PORCENTAJE_CUSTOM;
                        if ($saldoFactura > 0) { 
                            $factura->APROBACION_PAGO = 1;    
                            /** Operaciones de Calculo **/ 
                            $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                        
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/                         
                            $factura->MONTO_REMANENTE = 0;
                        } else {
                            $factura->APROBACION_PAGO = 1;
                            /** Operaciones de Calculo **/ 
                            $saldoParcial = $saldo;                    
                            $remanente = $factura->CANTIDAD_PORCENTAJE_CUSTOM - $saldoParcial; 
                            $precioGalon = round(($montoConversion / $factura->CANTIDAD_PORCENTAJE_CUSTOM), 4);
                            $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                       
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/                       
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4);  /** Calculo de Monto Iva **/                         
                            $factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 4); /** Calculo de Monto Remanente **/                           
                        }
                    } else { 
                        $factura->APROBACION_PAGO = 0;                
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                        
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/                    
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/ 
                        $factura->MONTO_REMANENTE = $montoConversion; /** Calculo de Monto Remanente **/
                        //echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
                    }
                } else if ($esCombustible->TIPOASIGNACION_ID == 1) {  
                    /** Se verifica si tiene presupuesto para cubrir el gasto o si es 100% remanente */
                    if ($saldo > 0) {
                        $saldoFactura = $saldo - $montoConversion;                        
                        if ($saldoFactura > 0) { 
                            $factura->APROBACION_PAGO = 1;    
                            /** Operaciones de Calculo **/ 
                            $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                        
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/                         
                            $factura->MONTO_REMANENTE = 0; /** Calculo de Monto Remanente */
                        } else {
                            $factura->APROBACION_PAGO = 1;
                            /** Operaciones de Calculo **/ 
                            $saldoParcial = $saldo;                                            
                            $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                       
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/                       
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4);  /** Calculo de Monto Iva **/                         
                            $factura->MONTO_REMANENTE = round(($montoConversion - $saldoParcial), 4); /** Calculo de Monto Remanente **/ 
                            echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');                          
                        }
                    } else {
                        $factura->APROBACION_PAGO = 0;                
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_EXENTO = round(($factura->CANTIDAD_PORCENTAJE_CUSTOM * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                        
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/                    
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/ 
                        $factura->MONTO_REMANENTE = $montoConversion; /** Calculo de Monto Remanente **/
                        echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
                    }           
                    
                }
            } else {
                /** Impuesto Hotelero **/
                $impuestoAplicar = round(($impuestoAplicar / 100), 4);                
                if ($saldo > 0) {
                    $saldoFactura = $saldo - $montoConversion;
                    if ($saldoFactura > 0) {
                        $factura->APROBACION_PAGO = 1;    
                        /** Operaciones de Calculo **/ 
                        $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoAplicar)),4); /** Calculo de Monto Afecto **/
                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/  
                        $factura->MONTO_REMANENTE = 0; /** Calculo de Monto Remanente */
                        //echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA .  '<br>');                       
                    } else {
                        $factura->APROBACION_PAGO = 1;
                        /** Operaciones de Calculo **/ 
                        $saldoParcial = $saldo;                                            
                        $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoAplicar)),4); /** Calculo de Monto Afecto **/                       
                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                       
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4);  /** Calculo de Monto Iva **/                         
                        $factura->MONTO_REMANENTE = round(($montoConversion - $saldoParcial), 4); /** Calculo de Monto Remanente **/                           
                        //echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
                    }
                } else {
                    $factura->APROBACION_PAGO = 0;                
                    /** Operaciónes de Calculo **/
                    $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoAplicar)),4); /** Calculo de Monto Afecto **/
                    $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoAplicar), 4); /** Calculo de Monto Exento **/                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/ 
                    $factura->MONTO_REMANENTE = $montoConversion; /** Calculo de Monto Remanente **/
                    echo($factura->MONTO_EXENTO . ' :: ' . $factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
                }
            }
        } else {            
            if ($saldo > 0) {                     
                $saldoFactura = $saldo - $montoConversion;
                if ($saldoFactura > 0) {                        
                    $factura->APROBACION_PAGO = 1;    
                    /** Operaciones de Calculo **/                        
                    echo 'Monto Afecto: ' . $factura->MONTO_AFECTO . ' Monto Conversion: ' . $montoConversion . ' Valor Impuesto: ' . $valorImpuesto . '<br>';
                    //dd('alto'); 
                    $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); /** Calculo de Monto Iva **/  
                    $factura->MONTO_REMANENTE = 0;
                    echo($factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
    
                } else {                        
                    $factura->APROBACION_PAGO = 1;
                    /** Operaciónes de Calculo **/
                    $saldoParcial = $saldo;
                    //echo 'Monto Afecto: ' . $factura->MONTO_AFECTO . ' Monto Conversion: ' . $montoConversion . ' Valor Impuesto: ' . $valorImpuesto . 'Saldo Parcial: ' . $saldoParcial . '<br>';
                    $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); /** Calculo de Monto Afecto **/                      
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); /** Calculo de Monto Iva **/                      
                    $factura->MONTO_REMANENTE = $montoConversion - $saldoParcial; /** Calculo de Monto Remanente **/
                    //echo($factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');                              
                    //dd('alto');
                }
    
            } else {            
                $factura->APROBACION_PAGO = 0;
            
                /** Operaciónes de Calculo **/
                $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto                
                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                $factura->MONTO_REMANENTE = $montoConversion;            
                echo($factura->MONTO_AFECTO . ' :: ' . $factura->MONTO_IVA . ' :: ' . $factura->MONTO_REMANENTE . '<br>');
    
            }
        } 
        
        return $factura;
    }