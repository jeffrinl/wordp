<?php

function _lean_time_ago( $type = 'post' ) {
	$d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
 
 
	$date = $d('G'); //get_post_time('G', true, $post);
 
	/**
	 * Where you see 'lean' below, you'd
	 * want to replace those with whatever term
	 * you're using in your theme to provide
	 * support for localization.
	 */ 
 
	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year', 'lean' ), __( 'years', 'lean' ) ),
		array( 60 * 60 * 24 * 30 , __( 'month', 'lean' ), __( 'months', 'lean' ) ),
		array( 60 * 60 * 24 * 7, __( 'week', 'lean' ), __( 'weeks', 'lean' ) ),
		array( 60 * 60 * 24 , __( 'day', 'lean' ), __( 'days', 'lean' ) ),
		array( 60 * 60 , __( 'hour', 'lean' ), __( 'hours', 'lean' ) ),
		array( 60 , __( 'minute', 'lean' ), __( 'minutes', 'lean' ) ),
		array( 1, __( 'second', 'lean' ), __( 'seconds', 'lean' ) )
	);
 
	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}
 
	$current_time = current_time( 'mysql', $gmt = 0 );
	$newer_date = strtotime( $current_time );
 
	// Difference in seconds
	$since = $newer_date - $date;
 
	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since )
		return __( 'sometime', 'lean' );
 
	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */
 
	//Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
 
		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 )
			break;
	}
 
	// Set output var
	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
 
 
	if ( !(int)trim($output) ){
		$output = '0 ' . __( 'seconds', 'lean' );
	}
 
	$output .= __(' ago', 'lean');
 
	return $output;
}
?>
