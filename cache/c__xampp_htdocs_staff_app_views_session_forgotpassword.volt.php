<?= $this->getContent() ?>

<div class="form__wrapper">
	<form action="<?= $this->url->get(['for' => 'session-forgot-password']) ?>" method="POST">
        <div align="left">
            <h2>Forgot Password?</h2>
        </div>

        <div class="form-group">
		    <?= $form->render('email') ?>
		    <?= $form->messages('email') ?>
		</div>

		<div class="form-group">
		    <?= $form->render('send') ?>
		</div>
	</form>
</div>