<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPExcelIOFactory
{
	public function My_PHPExcelIOFactory()
	{
		require_once('PHPExcel/Classes/PHPExcel/IOFactory.php');
	}
}
?>