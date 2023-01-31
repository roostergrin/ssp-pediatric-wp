<section class="overlapping-bubbles<?= !empty($classes) ? ' ' . implode(' ', $classes) : '';?>">
    <div class="content">
        <div class="inner-content">
            <div class="cols-container">
                <div class="col col-left">
                    <div class="bubble big-bubble">
                        <div class="copy-container">
                            <div class="heading h2">
                                <?= !empty($bubbles['bb_heading']) ? $bubbles['bb_heading'] : '';?>
                            </div>
                            <div class="copy h4">
                                <?= !empty($bubbles['bb_copy']) ? $bubbles['bb_copy'] : '';?>
                            </div>
                        </div>
                    </div>
                    <div class="bubble small-bubble copy-center">
                        <div class="copy-container">
                            <div class="heading h4">
                                <?= !empty($bubbles['sb_heading']) ? $bubbles['sb_heading'] : '';?>
                            </div>
                            <div class="copy">
                                <?= !empty($bubbles['sb_copy']) ? $bubbles['sb_copy'] : '';?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-right">
                    <div class="heading h5">
                        <?= $right_heading; ?>
                    </div>
                    <div class="copy">
                        <?= $right_copy; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>