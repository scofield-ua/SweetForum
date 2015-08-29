<?php
require_once "SimpleDomParser.php";
class TopicText {
    public static $options = array();    
    private static $load_iframe_action_url = "topics/load_iframe_data";
    
    /**
     *  What link you will if self::$options['not_load_iframes'] is TRUE
     *  @param $url string Resource url
     *  @param $type string Resource content type (ex. video, audio...)
     *  @return string
     */
    public function getIframeNotLoadLink($url, $type) {
        return "<a href='{$url}' class='iframe-to-load' data-content-type='{$type}' data-url='".self::$load_iframe_action_url."' target='_blank'>{$url}</a>";
    }
    
    /*
    *   Get video data by url
    *   @return array|string
    */
    public static function getVideo($url) {
        $options = self::$options;
        
        // default options
        $options['not_load_iframes'] = array_key_exists('not_load_iframes', $options) ? $options['not_load_iframes'] : false;
        
        // if this parametr is TRUE then just return formatted fallback 
        if($options['not_load_iframes']) return array("html" => self::getIframeNotLoadLink($url, "video"));
        
        $allowed_schemes = array('https', 'http');
        $allowed_exts = array('mp4', 'webm', 'ogg'); // allowed video extensions
        
        // parse url
        $parts = parse_url($url);
        $domain = $parts['host'];
        if(is_int(strpos($domain, 'www.'))) $domain = substr($domain, 4);
        
        if(in_array($parts['scheme'], $allowed_schemes)) {
            $c_n = 'video_data_'.md5($url);
            $c_d = 'sf_default';
            
            if(!$results = Cache::read($c_n, $c_d)) {            
                App::uses('HttpSocket', 'Network/Http');
    
                $HttpSocket = new HttpSocket();
                switch($domain) {
                    case "youtube.com" :                    
                        $results = $HttpSocket->get('http://www.youtube.com/oembed', array('url' => $url, 'width' => 640, 'format' => 'json'));
                        $results = json_decode($results->body, true);
                    break;
                    case "vimeo.com" :
                        $results = $HttpSocket->get('http://vimeo.com/api/oembed.json', array('url' => $url, 'width' => 640));
                        $results = json_decode($results->body, true);
                    break;
                    case "rutube.ru" :                    
                        $results = $HttpSocket->get('http://rutube.ru/api/oembed/', array('url' => $url, 'width' => 640, 'format' => 'json'));
                        $results = json_decode($results->body, true);
                    break;
                    case "coub.com" :
                        $results = $HttpSocket->get('http://coub.com/api/oembed.json', array('url' => $url));
                        $results = json_decode($results->body, true);
                    break;
                    case "vine.co" :
                        $results['html'] = '<iframe class="vine-embed" src="'.$url.'/embed/simple" width="600" height="600" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>';
                    break;
                    case "instagram.com" :
                    case "instagr.am"  :
                        $results = $HttpSocket->get('http://api.instagram.com/oembed', array('url' => $url));
                        $results = json_decode($results->body, true);                        
                        $ext = strtolower(pathinfo($results['url'], PATHINFO_EXTENSION));
                        if(in_array($ext, $allowed_exts)) {
                            $url = rtrim($url, '/\\'); 
                            $results['html'] = '<iframe src="'.$url.'/embed/" width="640" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
                        }
                    break;
                    case "twitch.tv" :
                        $results['html'] = '<iframe frameborder="0" scrolling="no" src="'.$url.'" height="480" width="640" class="twitch"></iframe>';
                    break;
                    default :                        
                        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));                        
                        if(in_array($ext, $allowed_exts)) {
                            $results['html'] = '
                                <video width="640" height="480" controls="">
                                    <source src="'.$url.'" type="video/'.$ext.'">                                    
                                    <p class="not-supported">'.__d("sweet_forum", "Your browser does not support the video tag").'</p>
                                </video>
                            ';
                        } else {
                            return $url;
                        }
                    break;
                }
                
                Cache::write($c_n, $results, $c_d);
            }
            
            return $results;
        } 
        return $url;        
    }
    
    /*
    *   Get audio data by url
    *   @return array|string
    */
    public static function getAudio($url) {
        $options = self::$options;
        
        // default options
        $options['not_load_iframes'] = array_key_exists('not_load_iframes', $options) ? $options['not_load_iframes'] : false;
        
        // if this parametr is TRUE then just return formatted fallback 
        if($options['not_load_iframes']) return array("html" => self::getIframeNotLoadLink($url, "audio"));
        
        $allowed_schemes = array('https', 'http');
        $allowed_exts = array('mp3', 'wav', 'ogg'); // allowed audio extensions
        
        // parse url
        $parts = parse_url($url);
        $domain = $parts['host'];
        if(is_int(strpos($domain, 'www.'))) $domain = substr($domain, 4);
        
        if(in_array($parts['scheme'], $allowed_schemes)) {
            $c_n = 'audio_data_'.md5($url);
            $c_d = 'sf_default';
            
            if(!$results = Cache::read($c_n, $c_d)) {            
                App::uses('HttpSocket', 'Network/Http');
    
                $HttpSocket = new HttpSocket();
                switch($domain) {
                    case "soundcloud.com" :                        
                        $results = $HttpSocket->get('http://soundcloud.com/oembed', array('url' => $url, 'format' => 'json', 'show_comments' => false, 'maxheight' => 200));
                        $results = json_decode($results->body, true);
                    break;                    
                    default :                        
                        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));                        
                        if(in_array($ext, $allowed_exts)) {
                            $results['html'] = '
                                <audio controls="">
                                    <source src="'.$url.'" type="audio/'.$ext.'">                                    
                                    '.__d("sweet_forum", "Your browser does not support the video tag").'
                                </audio>
                            ';
                        } else {
                            return $url;
                        }
                    break;
                }
                
                Cache::write($c_n, $results, $c_d);
            }
            
            return $results;
        } 
        return $url;
    }
    
    /*
    *   Get image data
    *   @param string $url
    *   @return array|string
    */
    public static function getImage($url) {
        // default options
        $options = self::$options;
        
        if(!array_key_exists('gallery', $options)) $options['gallery'] = array();
        
        $allowed_schemes = array('https', 'http');
        $allowed_exts = array('png', 'jpg', 'gif', 'bmp'); // allowed image extensions
        
        // parse url
        $parts = parse_url($url);
        $domain = $parts['host'];
        if(is_int(strpos($domain, 'www.'))) $domain = substr($domain, 4);
        
        if(in_array($parts['scheme'], $allowed_schemes)) {
            $c_n = 'image_data_'.md5($url);
            $c_d = 'sf_default';
            
            if(!$results = Cache::read($c_n, $c_d)) {            
                App::uses('HttpSocket', 'Network/Http');
    
                $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
                
                // gallery string
                $gallery_str = "";
                if(!empty($options['gallery'])) {
                    $gallery_str = "class='gallery-link ".$options['gallery']['class']."'";
                    if(array_key_exists('gallery-id', $options['gallery']))
                        $gallery_str .= " data-gall='".$options['gallery']['gallery-id']."'";
                }                
                
                switch($domain) {
                    case "instagram.com" :
                    case "instagr.am"  :
                        $results = $HttpSocket->get('http://api.instagram.com/oembed', array('url' => $url, 'max-width' => 640, 'format' => 'json'));                        
                        if($results->code != 404) {
                            $results = json_decode($results->body, true);                            
                            $results['html'] = '<img src="'.$results['thumbnail_url'].'" alt="'.$results['title'].'" title="'.$results['title'].'" data-author="'.$results['author_url'].'" />';
                            
                            if(!empty($gallery_str)) {
                                $results['html'] = "<a href='".$results['thumbnail_url']."' {$gallery_str}>".$results['html']."</a>";
                            }
                        } else {
                            return $url;
                        }
                    break;
                    case "flickr.com" :
                    case "flic.kr" :
                        $results = $HttpSocket->get('https://www.flickr.com/services/oembed', array('url' => $url, 'format' => 'json'));
                        if($results->code != 404) {
                            $results = json_decode($results->body, true);
                            $results['html'] = '<img src="'.$results['url'].'" alt="'.$results['title'].'" title="'.$results['title'].'" data-author="'.$results['author_url'].'" />';
                            
                            if(!empty($gallery_str)) {
                                $results['html'] = "<a href='".$results['url']."' {$gallery_str}>".$results['html']."</a>";
                            }
                        } else {
                            return $url;
                        }
                    break;
                    case "dribbble.com" :
                        $id = basename(parse_url($url, PHP_URL_PATH));
                        
                        $results = $HttpSocket->get('http://api.dribbble.com/shots/'.$id);
                         if($results->code != 404) {
                            $results = json_decode($results->body, true);
                            $results['html'] = '<img src="'.$results['image_url'].'" alt="'.strip_tags($results['description']).'" title="'.$results['title'].'" data-author="'.$results['player']['url'].'" />';
                            
                            if(!empty($gallery_str)) {
                                $img = '<img src="'.$results['image_teaser_url'].'" alt="'.strip_tags($results['description']).'" title="'.$results['title'].'" data-author="'.$results['player']['url'].'" />';
                                $results['html'] = "<a href='".$results['image_url']."' {$gallery_str}>{$img}</a>";
                            }
                        } else {
                            return $url;
                        }
                    break;
                    default :
                        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                        if(in_array($ext, $allowed_exts)) {
                            $results['html'] = '<img src="'.$url.'" alt="" />';
                            
                            if(!empty($gallery_str)) {
                                $results['html'] = "<a href='{$url}' {$gallery_str}>".$results['html']."</a>";
                            }            
                        } else {
                            return $url;
                        }
                    break;
                }
                
                Cache::write($c_n, $results, $c_d);
            }
            
            return $results;
        } 
        return $url;
    }
    
    /**
    *   Get social post data (supported: twitter)
    *   @return array|string
    */
    public static function getSocialQuote($url) {
        $options = self::$options;
        
        // default options
        $options['not_load_iframes'] = array_key_exists('not_load_iframes', $options) ? $options['not_load_iframes'] : false;
        
        // if this parametr is TRUE then just return formatted fallback 
        if($options['not_load_iframes']) return array("html" => self::getIframeNotLoadLink($url, "social-quote"));
        
        $allowed_schemes = array('https', 'http');
        $allowed_exts = array('mp3', 'wav', 'ogg'); // allowed video extensions
        
        // parse url
        $parts = parse_url($url);
        $domain = $parts['host'];
        if(is_int(strpos($domain, 'www.'))) $domain = substr($domain, 4);        
        
        if(in_array($parts['scheme'], $allowed_schemes)) {
            $c_n = 'soc_quote_data_'.md5($url);
            $c_d = 'sf_default';
            
            if(!$results = Cache::read($c_n, $c_d)) {            
                App::uses('HttpSocket', 'Network/Http');
    
                $HttpSocket = new HttpSocket();
                switch($domain) {
                    case "twitter.com" :                    
                        $results = $HttpSocket->get('https://api.twitter.com/1/statuses/oembed.json', array('url' => $url, 'width' => 640));
                        $results = json_decode($results->body, true);
                    break;                    
                    default :
                        return $url;                        
                    break;
                }
                
                Cache::write($c_n, $results, $c_d);
            }
            
            return $results;
        } 
        return $url;
    }
    
    public static function processText($text) {
        $options = self::$options;
        
        $is_cache = array_key_exists('cache_options', $options);
        
        if($is_cache) {
            if($result = Cache::read('topic_comment_text_'.md5($text.json_encode($options)), $options['cache_options']['duration'])) {
                return $result;
            }
        }
        
        libxml_use_internal_errors(true);

        $text = preg_replace("/(\r\n){3,}/","\r\n\r\n", trim($text));

        // pre
        $html = str_get_html("<html><body>{$text}</body></html>", true, true, DEFAULT_TARGET_CHARSET, false);

        foreach($html->find('html body code') as $pre) {
            $inner = htmlspecialchars(trim($pre->innertext));
            if(!empty($inner)) {
                $res = "<pre>{$inner}</pre>";
                $pre->innertext = $res;
            }
        }

        $text = $html->save();

        $allowed = array('<strong>', '<em>', '<strike>', '<ul>', '<li>', '<a>', '<image>', '<pre>', '<audio>', '<video>', '<social-quote>');
        $text = strip_tags($text, implode(',', $allowed));

        // remove atributes
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', "UTF-8"));
        $allowed_attributes = array('href', 'src');
        foreach($dom->getElementsByTagName('*') as $node){
            for($i = $node->attributes->length -1; $i >= 0; $i--){
                $attribute = $node->attributes->item($i);
                if(!in_array($attribute->name, $allowed_attributes)) $node->removeAttributeNode($attribute);
            }
            
            if($node->tagName == 'a') {
                $node->setAttribute('rel', 'nofollow');
                $node->setAttribute('target', '_blank');
            }
            
            if($node->tagName == 'img') {
                $node->setAttribute('alt', ' ');
            }
        }
        $text = $dom->saveHtml();
        
        $html = str_get_html($text, true, true, DEFAULT_TARGET_CHARSET, false);
    
        // image
        foreach($html->find('image') as $image) {
            $url = trim(strip_tags($image->innertext));
            if(!empty($url)) {
                $result = TopicText::getImage($url);
                if(is_array($result)) {                
                    $image->outertext = $result['html'];
                } else {
                    $image->outertext = "<a href='{$url}' target='_blank' rel='nofollow'>{$url}</a>";
                }
            }
        }
    
        // video tag
        foreach($html->find('video') as $video) {
            $url = trim($video->innertext);
            if(!empty($url)) {
                $result = TopicText::getVideo($url);
                if(is_array($result)) {                
                    $video->outertext = $result['html'];
                } else {
                    $video->outertext = "<a href='{$url}' target='_blank' rel='nofollow'>{$url}</a>";
                }
            }
        }
        
        // audio tag
        foreach($html->find('audio') as $audio) {
            $url = trim($audio->innertext);
            if(!empty($url)) {
                $result = TopicText::getAudio($url);
                if(is_array($result)) {                
                    $audio->outertext = $result['html'];
                } else {
                    $audio->outertext = "<a href='{$url}' target='_blank' rel='nofollow'>{$url}</a>";
                }
            }
        }
        
        // social-quote tag
        foreach($html->find('social-quote') as $sq) {
            $url = trim($sq->innertext);
            if(!empty($url)) {
                $result = TopicText::getSocialQuote($url);
                if(is_array($result)) {                
                    $sq->outertext = $result['html'];                    
                } else {
                    $sq->outertext = "<a href='{$url}' target='_blank' rel='nofollow'>{$url}</a>";
                }
            }
        }  
            
        // ul
        foreach($html->find('ul') as $ul) {
            $tmp = str_get_html($ul->innertext);
            $res = "";
            foreach($tmp->find('li') as $li) $res .= $li;
            if(!empty($res)) $ul->innertext = $res;
        }

        foreach($html->find('html body pre') as $pre) {
            $inner = strip_tags(trim($pre->innertext));
            if(!empty($inner)) $pre->innertext = $inner;
        }
        
        $dom = new DOMDocument();
        $dom->loadHTML($html);        
        $xpath = new DOMXPath($dom);
        $body = $xpath->query('/html/body');
        $text = $dom->saveXml($body->item(0));            
        
        $text = mb_substr($text, strpos($text, '<body>') + strlen('<body>'), strlen('</body>') * -1);
        
        if($is_cache) {
            Cache::write('topic_comment_text_'.md5($text), $text, $options['cache_options']['duration']);
        }
        
        $text = nl2br($text);
        
        return $text;
    }
}
