		<?php if(isset($setting)): ?>
		<!-- CONFIGURAÇÕES DO ESTABELECIMENTO -->
		<section id="setting" style="display: none;">
			<h5 class="text-center" id="setting-header"><?=$setting->fantasy_name?></h5>
			<p class="text-center" id="setting-text"><?=$setting->address?>, <?=$setting->number?> - <?=$setting->city?> (<?=$setting->state?>) - <?=$setting->district?></p>
		</section>
		<p id="setting-website" style="display: none;"><?=$setting->website?></p>
		<!--/ CONFIGURAÇÕES DO ESTABELECIMENTO -->
		<?php endif; ?>
