<html>
<body>
<?php
//This is just a test file to show a simple use of the pagination library, just basic usage with no fancy styling
require 'src/Pagination.php';
use IKyuchukov\iziPagination\Pagination;

//we make a basic config array 
//amount of rows, base url we will use ect (see README.md)
$foo['pagination'] = array(
	//change to your application's url where the pagination is needed;
	'base_url' => 'http://page.areli.ga/index.php/',
	//you will probably set the rows to a row count of a database table or whatever you are using the pagination for 
	'total_rows' => '105',

);

$bar = new Pagination;
$bar->initialise($foo['pagination']);
echo $bar->returnPagination();
?>
</body>
</html>
