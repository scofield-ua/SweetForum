<?php
class CssJsController extends SweetForumAppController {
    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('minify_combine'));
    }

    /**
     *  Simple CSS compressor
     *  @param string $code Your css styles
     */
    private function compressCSS($code = '') {
        $code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
        $code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $code);
        $code = str_replace('{ ', '{', $code);
        $code = str_replace(' }', '}', $code);
        $code = str_replace('; ', ';', $code);

        return $code;
    }

    /**
     *  Function for combining and minifing files
     *  You can set files using ?files[] parametr
     *  You can cache result using ?cache=1 parametr
     *  @param string $type Type of file (its can be "css" or "js")
     */
    function minify_combine($type) {
        $this->autoRender = false;

        switch($type) {
            case 'css' :
                $this->response->type('css');
            break;
            case 'js' :
                $this->response->type('application/javascript');
            break;
            default :
                throw new NotFoundException();
            break;
        }

        // files
        $files = array();
        if(array_key_exists('files', $this->request->query)) $files = $this->request->query['files'];
        if(empty($files)) {
            $this->response->body("");
            return $this->response;
        }

        // cache
        $cache = false;
        if(array_key_exists('cache', $this->request->query)) $cache = (int) $this->request->query['cache'];

        // root
        $root = array_key_exists('root', $this->request->query) ? $this->request->query['root']  : "/sweet_forum/";


        $c_n = $type."_".md5(json_encode($files).$root);
        $c_d = "sf_default";

        if($cache) {
            $output = Cache::read($c_n, $c_d);

            if($output) {
                $this->response->body($output);
                return $this->response;
            }
        }

        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));

        $output = "";

        $base = Router::url('/', true);

        foreach($files as $url) {
            $url_c_n = "css_js_file_".md5($base.$url);
            $url_c_d = "sf_default";

            if(!$result = Cache::read($url_c_n, $url_c_d)) $result = (array) $HttpSocket->get($base.$url);

            if($result['code'] == 200) {
                Cache::write($url_c_n, $result, $url_c_d);

                $current_content = $result['body'];

                if($type == 'css') {
                    // rewrite "url()" to correct
                    if(preg_match('/url\(([^)]+)\)/i', $current_content, $regs)) {
                        $pos = strrpos($regs[1], '../');
                        if(is_int($pos)) {
                            $current_content = preg_replace_callback("/url\(([^)]+)\)/i", function($match) use ($root, $pos) {
                                $match = substr($match[1], $pos + strlen('../'));
                                return "url({$root}{$match})";
                            }, $current_content);
                        } else {
                            $trail_slash = strrpos($url, '/');
                            $css_file_root = '/'.substr($url, 0, $trail_slash + 1);
                            $current_content = preg_replace("/url\(([^)]+)\)/i", "url({$css_file_root}$1)", $current_content);
                        }
                    }
                }

                $output .= $current_content;
            }
        }

        if(!empty($output)) {
            switch($type) {
                case 'css' :
                    $output = $this->compressCSS($output);
                break;
                case 'js' :
                    App::uses('JSMin', 'SweetForum.Lib');
                    $output = JSMin::minify($output);
                break;
            }
        }

        // save if cache is ON
        if($cache) Cache::write($c_n, $output, $c_d);

        $this->response->body($output);

        return $this->response;
    }
}
