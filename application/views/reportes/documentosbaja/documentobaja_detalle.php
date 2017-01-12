<?php

	$ruta_formato='FormatoDocumentoBajaDetalle.xlsx';
	$ruta_formato='nc_formatos/documentosbaja/'.$ruta_formato;
	
	$inputFileName = $ruta_formato;//NOMBRE DE LA RUTA DEL FORMATO
	
	
	$PHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$PHPExcel->setActiveSheetIndex(0);  //set first sheet as active
	
	//$PHPExcel->getActiveSheet()->setCellValue('A6', ''); //IMAGEN DEL LOGO
	$PHPExcel->getActiveSheet()->setCellValue('C2', $datos_empresa[0]['raz_social']); //NOMBRE EMPRESA
	$PHPExcel->getActiveSheet()->setCellValue('C4', $datos_empresa[0]['direcc_empresa']); //DIRECCION 
	
	$PHPExcel->getActiveSheet()->setCellValue('C6', $param1. ' ');
	$PHPExcel->getActiveSheet()->setCellValue('E7', $param2. ' ');

	$fila=9;
	$contador=1;
	if(!empty($lista_datosdocumento))//SI NO ES NULO O VACIO
	{
		foreach ($lista_datosdocumento as $ind => $val) 
		{	
			$PHPExcel->getActiveSheet()->setCellValue('A'. $fila, $contador); 
			$PHPExcel->getActiveSheet()->setCellValue('B'. $fila, trim($val['tipodocumento'])); 
			$PHPExcel->getActiveSheet()->setCellValue('C'. $fila, trim($val['seriedocumentobaja']).'-'.trim($val['numerodocumentobaja'])); 
			$PHPExcel->getActiveSheet()->setCellValue('D'. $fila, trim($val['fechaemisioncomprobante'])); 
			$PHPExcel->getActiveSheet()->setCellValue('E'. $fila, trim($val['motivobaja']));
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
	$filename=   'DocumentosdeBajasDetalle';//$lista_datosdocumento[0]['nom_tipdoc'].'_'.$lista_datosdocumento[0]['ser_doc'].'-'.$lista_datosdocumento[0]['num_doc']; 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;meta charset="utf-8"');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007'); 
	$objWriter->save('php://output'); 
	
	exit;
	
	//just some random filename   mt_rand(1,100000)

?>



