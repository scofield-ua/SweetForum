<?php
if(!isset($page_title)) $page_title = __("SweetForum");
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?= $page_title; ?></title>
	<?php
		echo $this->Html->meta('icon');

        # css
        echo $this->Html->css(array('SweetForumAdmin.bootstrap.min', 'SweetForumAdmin.style'));
		echo $this->element('parts/special-css');

		echo $this->Html->script(array('SweetForumAdmin.jq', 'SweetForumAdmin.bootstrap.min'));
	?>
	<script type="text/javascript">
		function SweetForum() {
			this.baseUrl = "<?= $sweet_forum_base_url; ?>";
		}
		var sweetForum = new SweetForum();
	</script>
</head>
<body>
	<?= $this->element('parts/header'); ?>
	<div class='container-fluid'>
        <div class='row'>
            <div class='col-md-3'>
                <?= $this->element('parts/nav'); ?>
            </div>
            <div class='col-md-9'>
                <?= $this->fetch('content'); ?>
            </div>
        </div>
	</div>
	
	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="small-message-modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class='modal-body text-center'></div>
			</div>
		</div>
	</div>

	<?php #echo $this->element('sql_dump'); ?>
</body>
</html>
