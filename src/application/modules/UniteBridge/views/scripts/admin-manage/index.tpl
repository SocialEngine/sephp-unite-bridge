<h2>Unite Bridge</h2>

<div class="clear">
    <?php if ((empty($this->unite['url']) || empty($this->unite['apiKey'])) || $this->reset): ?>
    <div class="settings">
        <?php echo $this->error; ?>
        <?php echo $this->form->render($this); ?>
    </div>
    <?php else: ?>
        Connected to SocialEngine Unite.
    <?php endif; ?>
</div>

