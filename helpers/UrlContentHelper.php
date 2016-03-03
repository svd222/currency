<?php
namespace app\helpers;

use yii\base\Component;

/**
 * UrlContentHelper contains only 1 method, which helps to get content from remote host.
 */
class UrlContentHelper extends Component {
    
    /**
     * @param type $url Url of source
     * @return mixed returns string on success or false otherwise
     */
    public static function getContent($url) {
        $content = '';
        if (function_exists('curl_exec')) { 
            $conn = curl_init($url);
            $urlInfo = parse_url($url);
            if($urlInfo['scheme'] == 'https') {
                if(!ini_get('curl.cainfo')) {
                    /**
                     * @todo Setup the valid .cert 
                     * @see http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
                     */
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//assume that we trust the source
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                } else {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
                }
            }
            curl_setopt($conn, CURLOPT_URL, $url);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($conn);
            curl_close($conn);
        } elseif (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
            $content = file_get_contents($url);
        } elseif (function_exists('fopen') && function_exists('stream_get_contents') && ini_get('allow_url_fopen')) {
            $handle = fopen($url, "r");
            $content = stream_get_contents($handle);
        } else {
            $content = false;
        }
        return $content;
    } 
}
