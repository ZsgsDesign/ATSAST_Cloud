<?php
   include("FormatFile.php");

	// Settings
   // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
	$targetDir = "../../../".$_GET['folder'];
	$cleanupTargetDir = false; // 删除旧文件
	$maxFileAge = 60 * 60; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Uncomment this one to fake upload time
	// usleep(5000);

	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
	$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

	// 出于安全原因，清理文件名
	$fileName = tosyscode(preg_replace('/[^\w\._]+/u', '', $fileName));

	// 确保文件名是唯一的
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);

		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
			$count++;

		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}

	// 创建目标目录
	if (!file_exists($targetDir))
		@mkdir($targetDir);

	// 删除旧的临时文件
	if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// 删除临时文件，如果他们的最后修改时间比旧
			if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
				@unlink($filePath);// 删除临时文件
		}

		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "无法打开temp目录."}, "id" : "id"}');

	// 寻找内容类型头
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];

	// 处理非多部分上传旧的Web工具包版本不支持HTML5
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// 打开临时文件
			$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// 读取二进制输入流，并追加到临时文件
				$in = fopen($_FILES['file']['tmp_name'], "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "无法打开输入流."}, "id" : "id"}');
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);//删除临时文件
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "无法打开输入流."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "无法移动上传文件."}, "id" : "id"}');
	} else {
		// 打开临时文件
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// 读取二进制输入流，并追加到临时文件
			$in = fopen("php://input", "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "无法打开输入流"}, "id" : "id"}');

			fclose($in);
			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "无法打开输入流."}, "id" : "id"}');
	}

	// 返回的JSON-RPC回应
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
?>