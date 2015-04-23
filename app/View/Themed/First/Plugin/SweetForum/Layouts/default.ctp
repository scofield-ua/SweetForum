<?php
if(!isset($page_title)) $page_title = "SweetForum";
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?= $page_title; ?></title>
	<?php
		echo $this->Html->meta('icon');

		if(isset($meta_k)) echo $this->Html->meta('keywords', h($meta_k));
		if(isset($meta_d)) echo $this->Html->meta('description', h($meta_d));

		# css
		echo $this->element('parts/mc',
			array(
				'type' => 'css',
				'cache' => true,
				'files' => array('sweet_forum/First/css/bootstrap.min.css', 'sweet_forum/First/css/t.css', 'sweet_forum/First/css/style.css'),
				'root' => '/sweet_forum/First/'
			)
		);
		
		# some special not minified css
		echo $this->element('parts/special-css');
	?>
	<meta property="og:title" content="<?= h($page_title); ?>" />
    <meta property="og:description" content="<?php if(isset($meta_d)) echo h($meta_d); ?>" />
    <meta property="og:url" content="<?= $current_url; ?>" />
	<script type="text/javascript">
		function SweetForum() {
			this.baseUrl = "<?= $sweet_forum_base_url; ?>";
		}
		var sweetForum = new SweetForum();
		
		var timerStart = Date.now();
	</script>	
</head>
<body>	
	<?php
		flush();
		echo $this->element('parts/header');
	?>
	<div class='container'>		
		<?= $this->fetch('content'); ?>		
		<div class='footer text-center'>
			<?= $this->element('parts/footer'); ?>
		</div>
	</div>
	
	<?= $this->element('parts/end-of-body'); ?>
	
	<script type="text/javascript">
		$(document).ready(function() {
			console.log("Time until DOMready: ", Date.now()-timerStart);
		});
		$(window).load(function() {
			console.log("Time until everything loaded: ", Date.now()-timerStart);
		});
   </script>
	
	<?php #echo $this->element('sql_dump'); ?>	
</body>
</html>
