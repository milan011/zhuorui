<?php 
	namespace App\Exceptions; 

	class ExcelException extends \Exception{ 

		function __construct($msg='', $code=100){ 

			parent::__construct($msg); 
		}
	}