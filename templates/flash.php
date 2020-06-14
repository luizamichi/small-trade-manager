		<!-- MENSAGEM DE OPERAÇÃO -->
		<div class="alert <?=!(isset($message) && !empty($message)) ?: 'alert-warning'?> text-center" id="flash-message" style="<?=(isset($message) && !empty($message)) ?: 'display: none;'?>">
			<?=$message ?? ''?>
			<a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
		</div>
		<!--/ MENSAGEM DE OPERAÇÃO -->
