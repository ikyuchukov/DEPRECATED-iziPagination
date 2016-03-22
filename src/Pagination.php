<?php

namespace IKyuchukov\iziPagination;	

class Pagination{

	private $config=array(

			//the current page URL being loaded
			//if not supplied will be taken
			'current_page' => Null,
			//the base url for the pagination, without a page selected
			//example: http://domain.com/catalog/
			'base_url' => '',
			//the count of the thing we are using the pagination for
			'total_rows' => 0,
			//set how many results should be shown per page
			'per_page' => 10,
			//links to show after and before the current page
			'links_shown' => 5,
			//the name of the pages in the url
			'pages_url' => 'page',
			//the tag to be used before the page number
			'start_tag' => '&nbsp;',
			//the tag to be used after the page number
			'end_tag' => '&nbsp',
			//here you can set the class of the <a> link
			//note: applies for the first_last as well
			'anchor_class' => '',
			//tags for the current pagel link
			'current_page_tags' => array(
					'first' => '<b>',
					'second' => '</b>',
					),
			//links to go to the first and last page
			//set show to 0 to turn it off
			//you can change first and last to other signs
			'first_last' => array(
					'show' => 1,
					'first' => '&laquo;',
					'last' => '&raquo;',
					),
			//links for previous and next page
			//works as above 
			'prev_next' => array(
					'show' => 1,
					'prev' => '&lt;',
					'next' => '&gt;',
					),
			);

	//this holds the actual output
	private $pagination='';
	//number of pages
	private $pages;
	//the current page
	private $current_page;
	private $link = array();

	function initialise($config = null)
	{
		//add custom provided config settings
		if ($config != null){
			$this->config = array_replace_recursive($this->config, $config);
		}
		$this->pages = $this->calcPages($this->config['total_rows'], $this->config['per_page']);
		//we take the current page
		//either from the supplied $config or from the built in function
		$this->currentPage();

		//complete the pagination building 
		$this->complete();	
	}

	function complete()
	{

		$link = $this->link;
		$cur_page = $this->current_page;
		$links = $this->config['links_shown'];
		$pages = $this->pages;
		//we check if previous links are needed
		if ($links >= $cur_page && $cur_page > 1)
		{
			$this->toPrevious();	
			for ($i = 1; $i < $cur_page; $i++)
			{
				$this->pagination .= $this->buildLink ($i, $i);
			}
			$this->buildCurrentLink();
			//if the current page is more than the links we add the "go to first" link 
		} else if ($cur_page > 1){
			$this->toFirst();
			$this->toPrevious();	
			for ($i = $cur_page - $links; $i < $cur_page; $i++)
			{
				$this->pagination .= $this->buildLink ($i, $i);  
			}
			$this->buildCurrentLink();
		} else {

			$this->buildCurrentLink();
		}
		if ($cur_page + $links > $pages){
			for ($i = $cur_page + 1; $i <= $pages; $i++){
				$this->pagination .=  $this->buildLink ($i, $i);
			}
			if ($cur_page < $pages){
				$this->toNext();
			}
		} else {
			for ($i = $cur_page + 1; $i <= $cur_page + $links; $i++){
				$this->pagination .=  $this->buildLink ($i, $i); 

			}
			$this->toNext();
			//we add the "To last" page link
			$this->toLast();
		} 
	}	
	//return the finished pagination
	function returnPagination()
	{
		return $this->pagination;
	}
	//adds "previous" link
	function toPrevious()
	{	
		if ($this->config['prev_next']['show'] == 1){
			$this->pagination .= $this->buildLink ($this->current_page - 1, $this->config['prev_next']['prev']);
		}
	}
	//adds "next" link
	function toNext()
	{
		if ($this->config['prev_next']['show'] == 1){
			$this->pagination .= $this->buildLink ($this->current_page + 1, $this->config['prev_next']['next']);
		}
	}
	//adds the "To first" link to $this->pagination
	function toFirst()
	{
		if ($this->config['first_last']['show'] == 1){ 
			$this->pagination .= $this->buildLink (1, $this->config['first_last']['first']);
		}
	}
	function toLast()
	{
		if ($this->config['first_last']['show'] == 1) {
			$this->pagination .= $this->buildLink ($this->pages, $this->config['first_last']['last']);
		}	
	}
	//we get the current page
	function currentPage()
	{
		if ($this->config['current_page'] == Null){
			$current_url = $this->currentUrl();
			//we remove the part of the URL not containing the page number
			$current_url = str_replace($this->config['base_url'] . $this->config['pages_url'], "", $current_url);
			//we sanitize the last URL part containing the page number
			$current_url = abs((int)filter_var($current_url, FILTER_SANITIZE_NUMBER_INT));

			//if no current page is found or url does not contain the page, we default to 1
			if ($current_url == Null)
			{
				$current_url = 1;
			}
			$this->current_page = $current_url;
		}
	}
	function buildLink($page, $sign)
	{
		$link = array();
		//start of the link
		$link['1'] = $this->config['start_tag'] . '<a class="' . $this->config['anchor_class'] . '" href="' . $this->config['base_url'] . $this->config['pages_url'] . '/';
		$link['2'] = '/" >';
		$link['3'] = '</a>' . $this->config['end_tag'];

		return $link['1'] . $page . $link['2'] . $sign . $link['3'];
	}
	//we build the link for the current page, with styling taken from $this->config
	function buildCurrentLink(){
		$current_page_link = $this->config['current_page_tags']['first'] . $this->current_page . $this->config['current_page_tags']['second'];
		$this->pagination .=  $this->buildLink ($this->current_page, $current_page_link);

	}	
	function calcPages($total_rows, $per_page)
	{
		$pages = $total_rows / $per_page;
		//we return the number of pages
		//we round the fractions up 
		return ceil($pages);
	}
	//we get the current URL
	function currentUrl()
	{
		//we check if HTTP or HTTPS is used
		if ($this->httpsCheck()) {
			$this->current_url = 'https://';
		} else {
			$this->current_url = 'http://';
		}
		$this->current_url = $this->current_url. $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		return $this->current_url;
	}
	function httpsCheck()
	{
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ){
				//https is used
				return true;
				} else {
				//http is used
				return false;
	}
}
}
