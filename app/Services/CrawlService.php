<?php 

namespace App\Services;

use App\Models\Site;
use MongoDB\Client;


class CrawlService {
    // Your business logic methods here

    protected $site;
    protected $update;
    protected $depth;
    protected $curl;


    public function crawl($url, $update, $depth) {

        $this->update = $update;
        $this->depth = $depth;
        $this->site = Site::where("url",$url)->first();


        if($this->site && !$this->update) {
            $attributes = $this->site->getAttributes();
            $urls =[];
            if (array_key_exists('inner_urls', $attributes)) {
                $urls = $attributes['inner_urls'];
            }

            return ['message' => "The results for - $url - (were fetch from DB!)", "urls" => $urls];
        }

        else {

            $this->curl = curl_init();
            $site_error = $this->getCrawler($url, 0, true);
            curl_close($this->curl);

            $urls =[];

            if (!$site_error) {
                $attributes = $this->site->getAttributes();
                if (array_key_exists('inner_urls', $attributes)) {
                    $urls = $attributes['inner_urls'];
                }
    
                return ['message' => "The results for $url", "urls" => $urls];

            }
            else {
                return ['error' => "Error with crawling the site - $site_error error", "urls" => $urls];
            }


        }
    }

    public function getCrawler($url, $x, $is_first = false) {
   
        if ($x <= $this->depth) { 

            curl_setopt_array($this->curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($this->curl);
            $http_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    
            if($http_code == 200) {

                if ($is_first) { 

                    if ($this->update) {
                        $this->site->delete();
                    } 
                    
                    $this->site = new Site([
                        'url' => $url,
                        'content' => $response,
                        'inner_urls' => [],
                        'inner_sites' => [],
                    ]);
                    $this->site->save();
                   
                }

                else {

                    $this->site->push("inner_urls", [
                        'url' => $url
                    ]);
                    $this->site->push("inner_sites",[
                        'url' => $url,
                        'content' => $response
                    ]);
                }
               
                preg_match_all('/href="http.+?"/', $response, $matches1);
                preg_match_all("/href='http.+?'/", $response, $matches2);
                $matches = array_merge($matches1[0], $matches2[0]);

            
                foreach($matches as $str) {
                    $str = strtolower($str);
                    $url = str_replace(['href=', '"', "'"],'', $str);
                    if ( preg_match("/(\.png|\.jpg|\.jpeg|\.gif|\.css|\.js)$/", $url) ) continue;
                    $this->getCrawler($url, $x + 1);
                }
            }
            
            else {
                return $http_code ? $http_code : '404';
            }
            
        }
     
    }

}