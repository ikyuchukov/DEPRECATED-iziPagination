## iziPagination

iziPagination is a light and simple to use PHP pagination library.

## Installation

To install the iziPagination library just include the src/Pagination.php file to your PHP project, either via an autoloader or by a require_once, require, include, ect function.
 
## Usage

After including the src/Pagination.php file to your PHP project you are ready to start using the library. You can set the namespace with the following :

use IKychukov\iziPagination\Pagination; 

after that create a new pagination object:

$bar = new Pagination;

You will need to initialise the library with a config array, for base usage you need to supply only the base url for your application where the pagination will be done and the number of rows for the pagination, for example the base url can be http://domain.com/catalog.php/ (the library can work with htaccess redirects, so you can use your "pretty url") and the let's say you are paginating products which are 105. The sample config file and initializing of the library will be:

$foo['myconfig'] = array(
	'base_url' => 'http://domain.com/catalog.php/',
	'total_rows' => '105', 
);

$bar->initialise($foo['myconfig']);

That's it, the pagination is now complete, you can print it via the following:

echo $bar->returnPagination();

A sample of base usage can be found in the index.php file.

## Advanced Usage

The library uses some default settings which you can change via the configuration array which was used earlier, also while the library can detect the current URL, it's best if you supply it in the configuration array, as it is not escaped as this is not something that the library should do.

Below are the configuration options:

currentl_url = the currently loaded URL, for better security it is recommended that you provide it, instead of leaving the auto function.

per_page = the amount of results you wil show per page, by default set to 10.

links_shown = the amount of page links the library should show before and after the current page, by default set to 5.

pages_url = the part that is added to the URL for the pagination links, by default set to "page", for example the third page on http://domain.com/catalog.php/ will be at http://domain.com/catalog.php/page/3 .

start_tag = the tag used before the page links, currently set to the html element for non breaking space.

end_tag = the tag used after the page links, currently set to the html element for non breaking space.

anchor_class = here you can add a class or another html element to the <a> links.

current_page_tags = this is an array with elements 'first' and 'second' used for setting the current page link to have special tags, by default sets html bold tags.

first_last = this is an array with elements 'show', 'first' and 'second' used for setting the symbols used for the first and last page links. Show takes either 1 or 0 depending if you wish to have them visible, set to 1 (on) by default. 'first' and 'second' are just the symbols used, by default set to Â and Â» .

prev_next = this is an array with elemts 'show', 'prev' and 'next' as above, however the links are for the next and previous pages, by default set to be shown and the symbols are < and > .

 
## License

BSD 3.0
