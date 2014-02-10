<?php
	function get_lock(&$lock)
	{
		$lock = fopen("/var/log/projet.log", "cb") or die("cannot open/create log file /var/log/projet.log");
		return flock($lock, LOCK_EX);
	}
	function release_lock(&$lock)
	{
		flock($lock, LOCK_UN);
	}
?>