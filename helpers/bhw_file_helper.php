<?php

if (!function_exists('bh_upload_file')) {
	function bh_upload_file($file_name, $config)
	{

		$up = $config['upload_path'];
		$config['upload_path'] = "uploads/sym/" . $config['upload_path'];

		$CI = &get_instance();
		$CI->load->library('upload', $config);

		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, true);
			try {
				$indexFile = fopen($config['upload_path'] . "/index.html", "w");
				$txt = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
				fwrite($indexFile, $txt);
				fclose($indexFile);
			} catch (Exception $error) {
			}
		}

		$file_ext = pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
		$_FILES[$file_name]['name'] = base64_encode($_FILES[$file_name]['name'] . ':' . date('Y-m-d H:i:s')) . '.' . $file_ext;

		if (!$CI->upload->do_upload($file_name)) {
			$error = array('error' => $CI->upload->error_msg);
			return $error;
		} else {
			$data = $CI->upload->data();
			$file_name = $data['file_name'];
			return $up . '/' . $file_name;
		}
	}
}


if (!function_exists('bh_upload_file_ym')) {
	function bh_upload_file_ym($file_name, $config)
	{
		$year = date("Y");
		$month = date("m");
		$config['upload_path'] = $config['upload_path'] . "/$year/$month";

		return bh_upload_file($file_name, $config);
	}
}
