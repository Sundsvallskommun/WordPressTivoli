<?php
/**
 *
 *
 * @author Johan Linder <johan@flatmate.se>
 * @since 1.0.0
 */

if(!function_exists('sk_log')) {

	/**
	 * @author Johan Linder <johan@flatmate.se>
	 * 
	 * @param string|array $message Message or info to log.
	 * @param string|array optional $data Data associated with log entry
	 * @param string $type optional type, defaults to 'error'.
	 */
	function sk_log($message, $data, $type = 'error') {

		$bt = debug_backtrace();
		$caller = array_shift($bt);
		$caller_file = $caller['file'];
		$caller_line = $caller['line'];

	/*
	 * Från utveckling.sundsvall.se (15/1-2016):
	 *
	 * Vid utveckling skall alla händelser, lyckade och misslyckade skrivas till en
	 * separat loggfil för den specifika WordPress-installationen.
	 *
	 * Felmeddelanden ska skrivas till loggen med hänvisningar till exakt:
	 *
	 * - Vad som går fel
	 * - Vad som går rätt
	 * - När
	 * - Vem/vilken process som försöker göra vad
	 *
	 * */

		$log_path = get_template_directory().'/logs/';

		if(defined('SK_LOG_PATH')) {
			$log_path = SK_LOG_PATH;
		}

		if( !is_writable($log_path) ) {
			if ( true === WP_DEBUG ) {
				error_log('log_path defined for sk_log() is not writable: '.$log_path);
				return;
			}
		}

		$site = str_replace('http://', '', site_url());
		$site = str_replace('https://', '', $site);

		$date = date('Y-m-d');

		$ext  = '.log';

		$log_file = $log_path.$site.'-'.$date.$ext;

		$output  = '['.date('c'). ']';
		$output  .= '['.$type. '] ';

		if( isset( $message ) && is_string( $message )) {
			$output .= $message;
		}

		if( !empty($data) && is_array( $data ) || is_object( $data )) {
			$output  .= " | ";
			$output .= print_r( $data, true );
		} else if( !empty($data) ){
			$output  .= " | ";
			$output .= $data;
		}


    $output .= ", called in " . $caller_file . " on line " . $caller_line;

    $output  .= "\n";

		error_log( $output, 3, $log_file );
	}

}
