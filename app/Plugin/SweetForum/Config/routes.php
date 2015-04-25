<?php
Router::connect(SWEET_FORUM_BASE_URL, array('controller' => 'pages', 'action' => 'homepage', 'plugin' => 'sweet_forum'));
Router::connect(SWEET_FORUM_BASE_URL.'topic/**', array('controller' => 'topics', 'action' => 'view', 'plugin' => 'sweet_forum'));
Router::connect(SWEET_FORUM_BASE_URL.'search', array('controller' => 'searchs', 'action' => 'index', 'plugin' => 'sweet_forum'));
Router::connect(SWEET_FORUM_BASE_URL.'u/**', array('controller' => 'users', 'action' => 'view', 'plugin' => 'sweet_forum'));
Router::connect(SWEET_FORUM_BASE_URL.'blog/**', array('controller' => 'blogs', 'action' => 'view', 'plugin' => 'sweet_forum'));
// minifier & combine
Router::connect(SWEET_FORUM_BASE_URL.'mc/**', array('controller' => 'css_js', 'action' => 'minify_combine', 'plugin' => 'sweet_forum'));

Router::connect(SWEET_FORUM_BASE_URL.'admin/:controller/:action/**', array('controller' => ':controller', 'action' => ':action', 'plugin' => 'sweet_forum_admin'));
Router::connect(SWEET_FORUM_BASE_URL.':controller/:action/**', array('controller' => ':controller', 'action' => ':action', 'plugin' => 'sweet_forum'));
