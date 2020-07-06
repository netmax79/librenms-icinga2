<?php

use LibreNMS\Config;

class Icinga2 {
    
    function curl_request ($username="",$password="",$url, $postfields="",$headers=array()) {
            $parsed_url = parse_url($url);

            $port = isset($parsed_url['port']) ? $parsed_url['port'] : "80";
            $tuCurl = curl_init($url);
            if ($username!="") {
                    curl_setopt($tuCurl, CURLOPT_USERPWD, $username . ":" . $password);
            }
            if ( preg_match('/^https:/i',$url) ) {
                    curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl, CURLOPT_PORT, 443); 
            } else {
                    curl_setopt($tuCurl, CURLOPT_PORT, $port);
            }
            curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
            curl_setopt($tuCurl, CURLOPT_HEADER, 0);
            curl_setopt($tuCurl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($tuCurl, CURLOPT_POST, 0);
            curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER,0);

            if ( is_array($postfields) ) {
                    foreach($postfields as $key=>$value) {
                            $fields_string .= $key.'='.$value.'&';
                    }
                    rtrim($fields_string, '&');

                    curl_setopt($tuCurl,CURLOPT_POST, count($postfields));
                    curl_setopt($tuCurl,CURLOPT_POSTFIELDS, $fields_string);
            } else {
                    curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER,1);
            }
            $tuData = curl_exec($tuCurl);

            if (!curl_errno($tuCurl)) {
                    return $tuData;
            } else {
                    echo 'Curl error: ' . curl_error($tuCurl);
            }
            curl_close($tuCurl);
    }
    public function ic2_state_color($state) {
        switch ($state) {
            case 0:
                $color = "#BAFFAD";
                break;
            case 1:
                $color = "#FFFC9E";
                break;
            case 2:
                $color = "#FFADAD";
                break;
            case 3:
                $color = "#FFE2AD";
                break;
        }
        return $color;
    }
    public static function ic2_get_status($host) {
        global $config;
        $ic2api_user = $config['icinga2']['api']['username'];
        $ic2api_password = $config['icinga2']['api']['password'];
        $ic2api_url = $config['icinga2']['api']['url'];
           
        if ($host != "all" ) {
            $jsonout = Icinga2::curl_request($ic2api_user,$ic2api_password, $ic2api_url . "objects/services?filter=match(%22" . $host . "%22,host.name)&attrs=name&attrs=state&attrs=last_check_result");
            
        } else {
            $jsonout = "NOT IMPL";
        }
        $ic2data = json_decode($jsonout,true);
        $outdata = '<table class="table table-hover table-condensed table-striped">';
        foreach ($ic2data['results'] as $attr ) {
            $name = $attr['attrs']['name'];
            $state = $attr['attrs']['state'];
	    $check_result = $attr['attrs']['last_check_result']['output'];
            $color = Icinga2::ic2_state_color($state);
            $outdata .= '<tr><td bgcolor="'.$color.'">'.$name.'</td><td>'.$check_result.'</td></tr>';
        }
        $outdata .= '</table>';
        return print_r($outdata,true);
    }

    public static function menu() {
        echo('<li><a href="plugin/p='.get_class().'">'.get_class().'</a></li>');
    }
  
    public function device_overview_container($device) {
    
        echo('<div class="row"> <div class="col-md-12"> <div class="panel panel-default panel-condensed"> <div class="panel-heading"><strong>'.get_class().' Plugin </strong> </div>');
        echo Icinga2::ic2_get_status($device['hostname']);
        echo('</div></div></div>');
    }

    public function port_container($device, $port) {
        
    }

    public function fetch_devicelist() {
	global $config;
	$auth_header = array(
			"X-Auth-Token:" .  $config['icinga2']['librenms']['apikey']
			);
	$jsonout = Icinga2::curl_request("","",$config['base_url'] . "api/v0/devices?order=hostname","",$auth_header); 
	return $jsonout;
    }
}

?>
