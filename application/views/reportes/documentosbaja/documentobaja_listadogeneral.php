<?php

	$ruta_formato='FormatoDocumentoBaja.xlsx';
	$ruta_formato='nc_formatos/documentosbaja/'.$ruta_formato;
	
	$inputFileName = $ruta_formato;//NOMBRE DE LA RUTA DEL FORMATO
	
	
	$PHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$PHPExcel->setActiveSheetIndex(0);  //set first sheet as active
	
	$PHPExcel->getActiveSheet()->setCellValue('A6', ''); //IMAGEN DEL LOGO
	$PHPExcel->getActiveSheet()->setCellValue('C2', $datos_empresa[0]['raz_social']); //NOMBRE EMPRESA
	$PHPExcel->getActiveSheet()->setCellValue('C4', $datos_empresa[0]['direcc_empresa']); //DIRECCION 

	$PHPExcel->getActiveSheet()->setCellValue('C6', $param2. ' '); 
	$PHPExcel->getActiveSheet()->setCellValue('C7', $param3. ' '); 
	$PHPExcel->getActiveSheet()->setCellValue('E7', $param4. ' '); 
	
	$PHPExcel->getActiveSheet()->setCellValue('C8', $param6. ' '); 
	$PHPExcel->getActiveSheet()->setCellValue('E8', $param7. ' '); 
	$PHPExcel->getActiveSheet()->setCellValue('G7', $param8. ' '); 
	
	$PHPExcel->getActiveSheet()->setCellValue('C9', $param5. ' ');
	$PHPExcel->getActiveSheet()->setCellValue('A10', $param9. ' ');
	$PHPExcel->getActiveSheet()->setCellValue('G8', $param11. ' ');
	
	$fila=12;
	$contador=1;
	
	if(!empty($lista_datosdocumento))//SI NO ES NULO O VACIO
	{
		if ($param10=='')
		{
			foreach ($lista_datosdocumento as $ind => $val) 
			{	
				$PHPExcel->getActiveSheet()->setCellValue('A'. $fila, $contador); 
				$PHPExcel->getActiveSheet()->setCellValue('B'. $fila, trim($val['resumenid'])); 
				$PHPExcel->getActiveSheet()->setCellValue('C'. $fila, trim($val['fechaemisioncomprobante'])); 
				$PHPExcel->getActiveSheet()->setCellValue('D'. $fila, trim($val['fechageneracionresumen'])); 
				$PHPExcel->getActiveSheet()->setCellValue('E'. $fila, trim($val['estadoregistro']));
				$PHPExcel->getActiveSheet()->setCellValue('F'. $fila, trim($val['nombreestadosunat']));			
				$PHPExcel->getActiveSheet()->setCellValue('G'. $fila, trim($val['bl_fecharespuestasunat']));
				$PHPExcel->getActiveSheet()->setCellValue('H'. $fila, trim($val['obssunat']));
				
				$fila++;
				$contador++;
			}
		}
		else
		{
			foreach ($lista_datosdocumento as $ind => $val) 
			{	
				$posicion = strpos($param10, trim($val['resumenid']));			 
				if($posicion !== FALSE)	
				{
					$PHPExcel->getActiveSheet()->setCellValue('A'. $fila, $contador); 
					$PHPExcel->getActiveSheet()->setCellValue('B'. $fila, trim($val['resumenid'])); 
					$PHPExcel->getActiveSheet()->setCellValue('C'. $fila, trim($val['fechaemisioncomprobante'])); 
					$PHPExcel->getActiveSheet()->setCellValue('D'. $fila, trim($val['fechageneracionresumen'])); 
					$PHPExcel->getActiveSheet()->setCellValue('E'. $fila, trim($val['estadoregistro']));
					$PHPExcel->getActiveSheet()->setCellValue('F'. $fila, trim($val['nombreestadosunat']));			
					$PHPExcel->getActiveSheet()->setCellValue('G'. $fila, trim($val['bl_fecharespuestasunat']));
					$PHPExcel->getActiveSheet()->setCellValue('H'. $fila, trim($val['obssunat']));
					$fila++;
					$contador++;
				}
			}
		}
	}
	/*	
	$PHPExcel->getActiveSheet()->setCellValue('A18', strtoupper($moneda_letras .' '.$lista_datosdocumento[0]['nom_moneda']));
	$PHPExcel->getActiveSheet()->setCellValue('I19', $lista_datosdocumento[0]['sub_total']); 
	$PHPExcel->getActiveSheet()->setCellValue('I20', $lista_datosdocumento[0]['igv_doc']);
	$PHPExcel->getActiveSheet()->setCellValue('I21', $lista_datosdocumento[0]['total_doc']); 
	*/
	
	
	//prepare download
	$filename=   'ListaDocumentosdeBajas';//$lista_datosdocumento[0]['nom_tipdoc'].'_'.$lista_datosdocumento[0]['ser_doc'].'-'.$lista_datosdocumento[0]['num_doc']; 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;meta charset="utf-8"');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007'); 
	$objWriter->save('php://output'); 
	
	exit;
	
	//just some random filename   mt_rand(1,100000)

?>



