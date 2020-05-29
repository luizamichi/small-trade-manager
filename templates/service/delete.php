	<!-- MODAL DE REMOÇÃO -->
	<div class="fade modal" id="remove-modal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Confirmação de remoção</h3>
					<button class="close" data-dismiss="modal" type="button">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h5>Tem certeza que deseja remover o serviço?</h5>
					<p>Todos os orçamentos e vendas vinculados ao serviço serão removidos.</p>
					<small><em>* A operação é irreversível.</em></small>
				</div>
				<div class="modal-footer">
					<a class="btn btn-danger text-white" id="confirm-remove" href="<?=BASE_URL . 'action/' . PAGE_NAME?>/delete/?id=<?=$tuple->id ?? 0?>">Sim</a>
					<button class="btn btn-dark" data-dismiss="modal" type="button">Não</button>
				</div>
			</div>
		</div>
	</div>
	<!--/ MODAL DE REMOÇÃO -->
