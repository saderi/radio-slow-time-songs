
<?php 

class Spotify 
{
    protected $clientID;
    protected $clientSecret;

    function __construct($clientID,$clientSecret) {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
    }


    function getAccessToken()
    {
        $authorization = base64_encode($this->clientID . ':' . $this->clientSecret);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://accounts.spotify.com/api/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic $authorization",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return true;
        } else {
            $obj = json_decode($response);
            return $obj->access_token;
        }
    }
    


    function getSpotifyURL($accessToken, $song, $artist)
    {
        
        $songForURL = rawurlencode($song . ' ' . $artist);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.spotify.com/v1/search?q=$songForURL&type=track%2Cartist",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Bearer $accessToken",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $obj = json_decode($response);
          foreach ($obj->tracks->items as $key => $value) {
            
                if (strtolower($song) == strtolower($value->name)) {
                    return str_replace("spotify:track:","",$value->uri);
                }
            
          }
        }
    }

}

