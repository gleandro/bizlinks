<?php

ini_set('memory_limit', '100M');
ini_set('max_execution_time', '3600');

//@session_start();

class Excel_model extends CI_Model 
{

    var $excel = NULL;
    function __construct() 
	{
        parent::__construct();
        $this->load->library('excel');
        //$this->load->library('PHPExcel/iofactory');--se comento por que parecia que no funciona svr
        $this->load->library('iofactory');
        $this->excel = new PHPExcel();

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '100MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    }

    function CampoCelda($coordenada, $valor, $tipodato = 'C') 
	{
        if (!empty($valor)) 
		{
            if ($tipodato == 'N') 
			{	
				/*
				if ($valor=='0')
				{	
					$valor=2;
                	$this->excel->getActiveSheet()->getStyle($coordenada)->getNumberFormat()->setFormatCode(
						PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}
				else
				{
                	$this->excel->getActiveSheet()->getStyle($coordenada)->getNumberFormat()->setFormatCode(
						PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}
				*/
				$this->excel->getActiveSheet()->getStyle($coordenada)->getNumberFormat()->setFormatCode(
						PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $this->excel->getActiveSheet()->setCellValue($coordenada, $valor);
            } 
			else 
			{
                $this->excel->getActiveSheet()->getStyle($coordenada)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $this->excel->getActiveSheet()->setCellValue($coordenada, $valor . ' ');
            }
        }
    }

    function leer_excel($file,$index,$column) 
	{
        $inputFileName = $file;
		//Read your Excel workbook
        try 
		{
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 
		catch (Exception $e) 
		{
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
		
		//  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet($index);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
		
		//  Loop through each row of the worksheet in turn
        $rowData=NULL; 

        for ($row = 1; $row <= $highestRow; $row++) 
		{
            //  Read a row of data into an array
            $rowData[] = $sheet->rangeToArray($column . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            //  Insert row data array into your database of choice here
        }
        return $rowData;		
		
    }

    function Total_columnas_usar($num_fila) 
	{
        $inicio = 'A';
        $array = NULL;
        $total = 0;
        while ($total <= $num_fila) {
            $array[$total] = $inicio;
            $inicio++;
            $total++;
        }
        return $array;
    }

    function BloquearCelda() 
	{
        $this->excel->getActiveSheet()->getProtection()->setPassword('PHPExcel');
        $this->excel->getActiveSheet()->getProtection()->setSheet(true);
        $this->excel->getActiveSheet()->getProtection()->setSort(true);
        $this->excel->getActiveSheet()->getProtection()->setInsertRows(true);
        $this->excel->getActiveSheet()->getProtection()->setFormatCells(true);
        /* $this->excel->getActiveSheet()->getStyle('A')->getProtection()->setLocked(
          PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
          ); */
    }

    function CeldaEstilo($coordenada = 'A1', $color = '', $bold = false, $size = 10, $aling = 'L', $color_letra = '000000') 
	{
        $this->excel->getActiveSheet()->getStyle($coordenada)->applyFromArray
		(
                array(
                    'font' => array
					(
                        'bold' => $bold,
                        'size' => $size,
                        'color' => array(
                            'rgb' => $color_letra
                        )
                    ),
                    'borders' => array
					(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN//DOTTED
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN//DOTTED
                        ),
                        'left' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN//DOTTED
                        ),
                        'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN//DOTTED
                        )
                    )
                )
        );

        if (trim($color) != "") 
		{
            $this->excel->getActiveSheet()->getStyle($coordenada)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $this->excel->getActiveSheet()->getStyle($coordenada)->getFill()->getStartColor()->setARGB($color);
        }
        if ($aling == 'C')
		{
            $this->excel->getActiveSheet()->getStyle($coordenada)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        } 
		else if ($aling == 'R')
		{
            $this->excel->getActiveSheet()->getStyle($coordenada)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        } else 
		{
            $this->excel->getActiveSheet()->getStyle($coordenada)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        }

        //$this->excel->getActiveSheet()->getStyle($coordenada)->getFont()->getColor()->setARGB($color_letra);
    }

    function combinarcelda($coordenadaini, $coordenadafin, $valor, $color_fondo, $bold = false, $size = 10, 
				$aling = 'L', $color_letra = '000000') 
	{
        $objRichText = new PHPExcel_RichText();
        $objPayable = $objRichText->createTextRun($valor);
        $this->excel->getActiveSheet()->getCell($coordenadaini)->setValue($objRichText);
        $this->excel->getActiveSheet()->mergeCells($coordenadaini . ':' . $coordenadafin);

        $this->excel->getActiveSheet()->getStyle($coordenadaini . ':' . $coordenadafin)->applyFromArray(
                array(
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN//DOTTED
                        ),
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        ),
                        'left' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        ),
                        'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
        );

        if (trim($color_fondo) != "") {
            $this->excel->getActiveSheet()->getStyle($coordenadaini . ':' . $coordenadafin)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $this->excel->getActiveSheet()->getStyle($coordenadaini . ':' . $coordenadafin)->getFill()->getStartColor()->setARGB($color_fondo);
        }
        if ($aling == 'C') {
            $this->excel->getActiveSheet()->getStyle($coordenadaini)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        } else if ($aling == 'R') {
            $this->excel->getActiveSheet()->getStyle($coordenadaini)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        } else {
            $this->excel->getActiveSheet()->getStyle($coordenadaini)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        }

        $this->excel->getActiveSheet()->getStyle($coordenadafin)->applyFromArray(
                array(
                    'font' => array(
                        'bold' => $bold,
                        'size' => $size,
                        'color' => array(
                            'rgb' => $color_letra
                        )
                    )
                )
        );
    }
	
	function celdadetalle($celda,$bold = false) 
	{
		$this->excel->getActiveSheet()->getStyle($celda)->applyFromArray(
                array
				(
                    'font' => array
					(
                        'bold' => $bold,
                        //'size' => $size,
                        /*
						'color' => array
						(
                            'rgb' => $color_letra
                        )*/
                    )
                )
        );
	}

    function Descargar($tipo = 'Excel2007', $nombrearchivo = 'reporte')
	{
        $objWriter = IOFactory::createWriter($this->excel, $tipo);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombrearchivo . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
    
	function Guardar($tipo = 'Excel2007', $nombrearchivo = 'reporte') 
	{
        $objWriter = IOFactory::createWriter($this->excel, $tipo);//IOFactory
        $objWriter->save($nombrearchivo . '.xlsx');
    }

    public function InsertarImagen($path, $coordenada = 'A1') 
	{
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setPath($path);
        $objDrawing->setCoordinates($coordenada);
        $objDrawing->setWorksheet($this->excel->getActiveSheet());
    }

}

?>