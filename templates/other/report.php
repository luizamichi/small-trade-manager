	<?php require_once HEADER; ?>

	<div class="container mb-5">
		<!-- CABEÇALHO -->
		<div class="my-3 text-center">
			<img alt="Relatório" class="img-fluid mx-3" height="75" src="<?=BASE_URL?>img/report-color.svg" width="75"/>
			<h2><?=PAGE_TITLE?></h2>
			<hr/>
		</div>
		<!--/ CABEÇALHO -->

		<?php require_once FLASH; ?>

		<?php if($purchases): ?>
		<!-- GRÁFICOS DE COMPRAS -->
		<canvas height="450" id="bar-chart-purchases" width="800"></canvas>
		<p class="mb-0 text-center"><strong>Total de compras:</strong> <?=$purchases->total?></p>
		<p class="pb-3 text-center"><small><strong>Fornecedores:</strong> <?=$purchases->providers?>.</small></p>
		<!--/ GRÁFICOS DE COMPRAS -->
		<?php endif; ?>

		<?php if($sales): ?>
		<!-- GRÁFICOS DE VENDAS -->
		<canvas height="450" id="bar-chart-sales" width="800"></canvas>
		<p class="mb-0 text-center"><strong>Total de vendas:</strong> <?=$sales->total?></p>
		<p class="pb-3 text-center"><small><strong>Clientes:</strong> <?=$sales->clients?>.</small></p>
		<!--/ GRÁFICOS DE VENDAS -->
		<?php endif; ?>
	</div>

	<?php require_once FOOTER; ?>

	<!-- GERADOR DE GRÁFICOS -->
	<script src="<?=BASE_URL?>js/chart.js"></script>
	<script>
		<?php if($purchases): ?>
		new Chart(document.getElementById("bar-chart-purchases"), {
			type: "bar",
			data: {
				labels: [<?=$purchases->days?>],
				datasets: [
					{
						label: "Compra",
						backgroundColor: "rgba(146, 9, 226, 0.5)",
						borderColor: "#8e5ea2",
						borderWidth: 1,
						data: [<?=$purchases->values?>]
					},
				]
			},
			options: {
				plugins: {
					title: {
						display: true,
						text: "Compras dos últimos 30 dias",
						font: {
							size: 18
						}
					},
					legend: {
						position: "top"
					},
					subtitle: {
						display: true,
						text: "<?=date('d/m/Y', strtotime(date('Y-m-d') . ' - 1 month')) . ' até ' . date('d/m/Y')?>",
						padding: {
							bottom: 10
						}
					}
				},
				responsive: true
			}
		});
		<?php endif; ?>

		<?php if($sales): ?>
		new Chart(document.getElementById("bar-chart-sales"), {
			type: "bar",
			data: {
				labels: [<?=$sales->days?>],
				datasets: [{
					label: "Venda",
					backgroundColor: "rgba(62, 149, 205, 0.5)",
					borderColor: "#3e95cd",
					borderWidth: 1,
					data: [<?=$sales->values?>]
				}]
			},
			options: {
				plugins: {
					title: {
						display: true,
						text: "Vendas dos últimos 30 dias",
						font: {
							size: 18
						}
					},
					legend: {
						position: "top"
					},
					subtitle: {
						display: true,
						text: "<?=date('d/m/Y', strtotime(date('Y-m-d') . ' - 1 month')) . ' até ' . date('d/m/Y')?>",
						padding: {
							bottom: 10
						}
					}
				},
				responsive: true
			}
		});
		<?php endif; ?>
	</script>
	<!--/ GERADOR DE GRÁFICOS -->

</body>

</html>
