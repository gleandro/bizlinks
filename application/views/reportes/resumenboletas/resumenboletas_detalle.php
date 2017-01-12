<?php

	$ruta_formato='FormatoResumenBoletasDetalle.xlsx';
	$ruta_formato='nc_formatos/resumenboletas/'.$ruta_formato;
	
	$inputFileName = $ruta_formato;//NOMBRE DE LA RUTA DEL FORMATO
	
	
	$PHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$PHPExcel->setActiveSheetIndex(0);  //set first sheet as active
	
	//$PHPExcel->getActiveSheet()->setCellValue('A6', ''); //IMAGEN DEL LOGO
	$PHPExcel->getActiveSheet()->setCellValue('C2', $datos_empresa[0]['raz_social']); //NOMBRE EMPRESA
	$PHPExcel->getActiveSheet()->setCellValue('C4', $datos_empresa[0]['direcc_empresa']); //DIRECCION 
	
	$PHPExcel->getActiveSheet()->setCellValue('C6', $param1. ' ');
	$PHPExcel->getActiveSheet()->setCellValue('K7', $param2. ' ');

	$fila=9;
	$contador=1;
	if(!empty($lista_datosdocumento))//SI NO ES NULO O VACIO
	{
		foreach ($lista_datosdocumento as $ind => $val) 
		{	
			$PHPExcel->getActiveSheet()->setCellValue('A'. $fila, $contador); 
			$PHPExcel->getActiveSheet()->setCellValue('B'. $fila, trim($val['nombre_tipodocumento'])); 
			$PHPExcel->getActiveSheet()->setCellValue('C'. $fila, trim($val['seriegrupodocumento'])); 
			$PHPExcel->getActiveSheet()->setCellValue('D'. $fila, trim($val['numerocorrelativoinicio']).' '); 
			$PHPExcel->getActiveSheet()->setCellValue('E'. $fila, trim($val['numerocorrelativofin']).' ');
			
			$PHPExcel->getActiveSheet()->setCellValue('F'. $fila, trim($val['tipomonedadoc']));
			$PHPExcel->getActiveSheet()->setCellValue('G'. $fila, trim($val['totalvalorventaopgravadaconigv']).' ');
			
			$PHPExcel->getActiveSheet()->setCellValue('H'. $fila, trim($val['totaligv']).' ');
			$PHPExcel->getActiveSheet()->setCellValue('I'. $fila, '0.00'.' ');
			$PHPExcel->getActiveSheet()->setCellValue('J'. $fila, trim($val['totalvalorventaopexoneradasigv']).' ');
			$PHPExcel->getActiveSheet()->setCellValue('K'. $fila, trim($val['totalvalorventaopgratuitas']).' ');
			$PHPExcel->getActiveSheet()->setCellValue('L'. $fila, trim($val['totalventa']).' ');
			$PHPExcel->getActiveSheet()->setCellValue('M'. $fila, trim($val['fechaemisioncomprobante']));
			
			$fila++;
			$contador++;
		}
	}
	/*	
	$PHPExcel->getActiveSheet()->setCellValue('A18', strtoupper($moneda_letras .' '.$lista_datosdocumento[0]['nom_moneda']));
	$PHPExcel->getActiveSheet()->setCellValue('I19', $lista_datosdocumento[0]['sub_total']); 
	$PHPExcel->getActiveSheet()->setCellValue('I20', $lista_datosdocumento[0]['igv_doc']);
	$PHPExcel->getActiveSheet()->setCellValue('I21', $lista_datosdocumento[0]['total_doc']); 
	*/
	
	
	//prepare download
	$filename=   'ResumendeBoletasDetalle';//$lista_datosdocumento[0]['nom_tipdoc'].'_'.$lista_datosdocumento[0]['ser_doc'].'-'.$lista_datosdocumento[0]['num_doc']; 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;meta charset="utf-8"');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007'); 
	$objWriter->save('php://output'); 
	
	exit;
	
	//just some random filename   mt_rand(1,100000)

?>



