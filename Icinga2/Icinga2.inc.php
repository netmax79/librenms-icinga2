<?php

use Icinga2;

$devarr = json_decode(Icinga2::fetch_devicelist(),true);
foreach ( $devarr['devices'] as $devaridx => $device ) {

	if ( $device['ignore'] == 0 ) {
		$link_url=$config['base_url'] . "device/device=" . $device['device_id'];
		echo('<div class="row"> <div class="col-md-12"> <div class="panel panel-default panel-condensed"> <div class="panel-heading"><strong><a href='.$link_url.'>'.$device['hostname'].'</a></strong> </div>');                                                                                                                     
		echo Icinga2::ic2_get_status( $device['hostname'] );
		echo '</div</div></div>';
	}
}
