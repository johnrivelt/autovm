<?php

namespace app\extensions;

class Api
{
	const ACTION_INSTALL = '/install';
	const ACTION_START = '/start';
	const ACTION_STOP = '/stop';
	const ACTION_DELETE = '/delete';
	const ACTION_RESTART = '/restart';
	const ACTION_STATUS = '/status';
	const ACTION_BANDWIDTH = '/bandwidth';
	const ACTION_MONITOR = '/monitor';
	
	protected $url,
			  $data;
			  
	
	public function setUrl($url)
	{
		$this->url = $url;
	}
	
	public function getUrl()
	{
		return $this->url;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function request($action)
	{
file_put_contents(dirname(__FILE__) . '/a.txt', json_encode($this->data));
#return true;
		$c = curl_init();
		
		curl_setopt($c, CURLOPT_URL, $this->getUrl() . $action);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		
		if ($this->data) {
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, $this->buildParams($this->data));
		}
		
		$result = curl_exec($c);
				  curl_close($c);
	file_put_contents(dirname(__FILE__) . '/log.log', $result, FILE_APPEND);
		$result = @json_decode($result);
		
		if (!is_object($result) || $result->ok != 'true') {
			return false;
		}
		
		return $result;
	}
	
	public function buildParams($params)
	{
		return http_build_query($params);
	}
}
