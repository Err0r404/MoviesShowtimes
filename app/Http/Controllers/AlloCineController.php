<?php

namespace App\Http\Controllers;

class AlloCineController extends Controller {
    private $PARTNER_KEY = "100043982026";
    private $SECRET_KEY = "29d185d98c984a359e6e6f26a0474269";
    private $API_URI = "http://api.allocine.fr/rest/v3";
    private $USER_AGENT = "Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)";
    private $RADIUS = "10";
    private $FORMAT = "json";

    public function __construct() {
    }
    
    /**
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    private function _doRequest($method = "", $params = []){
        // Build endpoint uri
        $queryUri = $this->API_URI.'/'.$method;
        
        // Add required partner
        $params['partner'] = $this->PARTNER_KEY;
        
        // Encode query
        $sed = date('Ymd');
        $sig =
            urlencode(
                base64_encode(
                    sha1($this->SECRET_KEY.http_build_query($params).'&sed='.$sed, true)
                )
            );
        $queryUri .= '?'.http_build_query($params).'&sed='.$sed.'&sig='.$sig;
        
        // Do request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->USER_AGENT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    
    /**
     * @param string $zip
     *
     * @return mixed
     */
    public function getTheaterListByZip($zip = ""){
        $params = array(
            'zip'      => (trim($zip) != '') ? $zip : '',
            'lat'      => '',
            'long'     => '',
            'radius'   => $this->RADIUS,
            'theater'  => '',
            'location' => '',
            'format'   => $this->FORMAT,
        );
        
        return $this->_doRequest('theaterlist', $params);
    }
    
    /**
     * @param string $lat
     * @param string $long
     *
     * @return mixed
     */
    public function getTheaterListByPosition($lat = "", $long = ""){
        $params = array(
            'zip'      => '',
            'lat'      => (trim($lat) != '') ? $lat : '',
            'long'     => (trim($long) != '') ? $long : '',
            'radius'   => $this->RADIUS,
            'theater'  => '',
            'location' => '',
            'format'   => $this->FORMAT,
        );
        
        return $this->_doRequest('theaterlist', $params);
    }
    
    /**
     * @param string $code
     *
     * @return mixed
     */
    public function getShowtimeListByCinema($code = ""){
        $params = array(
            'zip'      => '',
            'lat'      => '',
            'long'     => '',
            'radius'   => $this->RADIUS,
            'theaters' => $code, // P0702 = Multiplexe
            'location' => '',
            'movie'    => '',
            'date'     => '', // YYYY-MM-DD
            'format'   => $this->FORMAT,
        );
        
        return $this->_doRequest('showtimelist', $params);
    }
    
    /**
     * @param string $cinemaCode
     * @param string $movieCode
     *
     * @return mixed
     */
    public function getShowtimeListByMovie($cinemaCode = "", $movieCode = ""){
        $params = array(
            'zip'      => '',
            'lat'      => '',
            'long'     => '',
            'radius'   => $this->RADIUS,
            'theaters' => $cinemaCode, // P0702 = Multiplexe
            'location' => '',
            'movie'    => $movieCode,
            'date'     => '', // YYYY-MM-DD
            'format'   => $this->FORMAT,
        );
        
        return $this->_doRequest('showtimelist', $params);
    }
    
    /**
     * @param $code
     *
     * @return mixed
     */
    public function getMovie($code){
        $params = array(
            'code'      => $code,
            'profile'   => 'large',
            'filter'    => 'movie',
            'striptags' => 'synopsis,synopsisshort',
            'format'    => $this->FORMAT,
        );
    
        return $this->_doRequest('movie', $params);
    }
}
