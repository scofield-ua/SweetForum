<?php
App::uses('Model', 'Model');
class SweetForumAppModel extends AppModel {
    public $recursive = -1;
    public $actsAs = array('Containable');
    public $validationDomain = 'validation';    
	public $useDbConfig = 'sweet_forum';

    protected function _readDataSource($type, $query) {
        $cache_it = array_key_exists('cache_options', $query);

        if($cache_it) {
            $name = array_key_exists('name', $query['cache_options']) ? $query['cache_options']['name'] : md5(json_encode($query));
            $duration = array_key_exists('duration', $query['cache_options']) ? $query['cache_options']['duration'] : '5_min';

            $cache = Cache::read($name, 'default');
            if($cache) return $cache;
        }

        $results = parent::_readDataSource($type, $query);
        if($cache_it) Cache::write($name, $results, $duration);

        return $results;
    }
    
    /**
     *  Get MySQL LIMIT string
     *  @param int $count Count of records to paginate
     *  @param int $per_page Records per page
     *  @param int $current_page Current page
     *  @return string
     */
    function getLimitString($count, $per_page, $current_page) {
        if($count > $per_page) {
            $min = $count - ($per_page * $current_page);
            if($min < 0) $min = 0; 
            
            $limit = "{$min}, {$count}";
        } else {
            $limit = $count;
        }
        
        return $limit;
    }
}
