<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('form');
echo form_open('sistema/usuario/salvar_senha');?>
    <div class="row">
        <div class="small-12 column">
            <?php echo validation_errors('<div class="alert callout">','</div>');?>
            <div data-abide-error class="alert callout" style="display: none;">
                <p><i class="fi-alert"></i> Existem alguns erros no seu formulário.</p>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="small-12 medium-6 large-8 medium-push-6 large-push-4 column">
			<div data-abide-error class="primary callout">
				<h1 class="text-center"><i class="fi-info"></i></h1>
                <p>Por questões de segurança o sistema exige que você altere sua senha para manter restrito o acesso aos dados usados neste ambiente, somente pelos usuários autorizados pelo administrador.</p>
            </div>
		</div>
		<div class="small-12 medium-6 large-4 medium-pull-6 large-pull-8 column">
			<div class="row">
				<div class="small-12 column">
					<label>
						Senha Atual
						<input type="password" name="senha_atual" placeholder="Digite a senha atual" required="" autofocus="">
						<span class="form-error">A sua senha atual é necessária.</span>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 column">
					<label>
						Nova Senha
						<input type="password" name="senha_nova" placeholder="Digite uma nova senha" required="">
						<span class="form-error">Crie uma senha nova e única.</span>
					</label>
				</div>
			</div>
			<div class="row">
				<div class="small-12 column">
					<label>
						Confirmar Senha
						<input type="password" name="senha_confirma" placeholder="Confirme sua senha nova" required="">
						<span class="form-error">Você precisa digitar sua senha nova neste campo também.</span>
					</label>
				</div>
			</div>
			<div class="row">
				<input name="salvar" value="Salvar" id="salvar" data-icone="fi-save" class="is-button-bar-menu button" style="display: none;" type="submit">
				<?php if($this->config->item('pular-resenha')){?>
					<a href="./sistema/usuario/adiar_resenha" class="is-button-bar-menu button" style="" data-icone="">Alterar depois</a>
				<?php } ?>
			</div>
		</div>
	</div>
<?php
echo form_close();
