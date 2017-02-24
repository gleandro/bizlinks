<?php

	$uploadedfile_size=$_FILES['uploadedfile']['size'];

	if ($_FILES['uploadedfile']['size']>500000)
	{
		echo json_encode(3);
	}else
	{
		if (!($_FILES['uploadedfile']['type'] =="image/png"))
		{
			echo json_encode(2);	
		}else
		{
			$file_name=$_FILES['uploadedfile']['name'];
			//$add="uploads/$file_name";//C:\PortalBizlinks\nginx\html\bizlinks_v2\application\helpers\image\logos
			$add=$_SERVER["DOCUMENT_ROOT"];
			$add=$add."\application\helpers\image\logos\logo_Empresa.png";
			if(move_uploaded_file ($_FILES['uploadedfile']['tmp_name'], $add))
			{
				echo json_encode(1);
			}else
			{
				echo json_encode(0);
			}
		}
	}
	
	exit;
?>
