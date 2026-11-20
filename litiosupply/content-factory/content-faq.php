<div class="section">
    <div class="r-container">
        <div class="row row-cols-xl-2 row-cols-1 d-flex align-items-center1">

            <!-- Левая колонка — четные -->
            <div class="col mb-4">
                <div class="accordion d-flex flex-column gap-4" id="faqAccordionLeft">
                    <?php $i = 1; foreach ($args['faq_list'] as $faq): ?>
                        <?php if ($i % 2 == 0): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq<?php echo $i; ?>"
                                        aria-expanded="false" aria-controls="faq<?php echo $i; ?>">
                                        <?php echo $faq['question']; ?>
                                    </button>
                                </h2>
                                <div id="faq<?php echo $i; ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#faqAccordionLeft">
                                    <div class="accordion-body">
                                        <?php echo $faq['answer']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $i++; endforeach; ?>
                </div>
            </div>

            <!-- Правая колонка — нечетные -->
            <div class="col mb-4">
                <div class="accordion d-flex flex-column gap-4" id="faqAccordionRight">
                    <?php $i = 1; foreach ($args['faq_list'] as $faq): ?>
                        <?php if ($i % 2 != 0): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq<?php echo $i; ?>"
                                        aria-expanded="false" aria-controls="faq<?php echo $i; ?>">
                                        <?php echo $faq['question']; ?>
                                    </button>
                                </h2>
                                <div id="faq<?php echo $i; ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#faqAccordionRight">
                                    <div class="accordion-body">
                                        <?php echo $faq['answer']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $i++; endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>