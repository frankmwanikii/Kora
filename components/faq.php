<?php

declare(strict_types=1);

$faqs = [
    [
        'q' => 'How quickly will I receive a quote?',
        'a' => 'A quote is shared within 24 hours of receiving your brief. Share as much detail as you can — event type, quantity, materials, and timeline — and we will get back to you the same day or next business day.',
    ],
    [
        'q' => 'How long does production take?',
        'a' => 'Production typically takes 3–7 business days. This may vary depending on the complexity of the design and the quantities ordered. Share your event date when you request a quote so we can confirm a delivery timeline that works for you.',
    ],
    [
        'q' => 'How do you handle shipping?',
        'a' => 'We use trusted courier services like G4S and Wells Fargo to ensure all items are delivered safely and efficiently anywhere in the country. Collection from our Nanyuki workshop is also available if you prefer.',
    ],
    [
        'q' => 'Do I need finished artwork or a logo file?',
        'a' => 'No. A rough idea, a photo for inspiration, or just your logo is enough to start. Our design team prepares the layout and shares a proof for your approval before anything is cut or engraved.',
    ],
    [
        'q' => 'Is there a minimum order quantity?',
        'a' => 'We handle everything from a single commemorative trophy to thousands of marathon medals. Quantity affects unit pricing, so include your best estimate and we will quote accordingly.',
    ],
    [
        'q' => 'What materials can I choose from?',
        'a' => 'We work in premium MDF, solid wood, plywood, and acrylic — single or multi-layered, and cut into any shape. Your quotation will recommend materials that suit your design, budget, and event feel.',
    ],
    [
        'q' => 'Can I see a proof before production starts?',
        'a' => 'Yes. Every order includes a design proof for your review. Production only begins once you approve the layout, wording, and finishing details.',
    ],
    [
        'q' => 'Where is KORA based?',
        'a' => 'Our workshop is in Nanyuki, Laikipia. Everything is designed and produced in-house, so you deal directly with the makers — not a middleman.',
    ],
];
?>
<section class="home-faq section" id="faqs" aria-labelledby="faq-title">
    <div class="container home-faq__layout">
        <header class="home-faq__intro reveal">
            <p class="home-faq__eyebrow">Common questions</p>
            <h2 id="faq-title" class="section-label">Frequently Asked Questions</h2>
            <p class="lead-italic home-faq__lead">Clear answers on timing, production, and delivery — so you know what to expect before you request a quote.</p>
            <a class="home-faq__cta" href="#contact">
                Still have a question?
                <span aria-hidden="true">→</span>
            </a>
        </header>

        <div class="home-faq__list reveal">
            <?php foreach ($faqs as $index => $faq): ?>
                <details class="home-faq__item"<?= $index === 0 ? ' open' : '' ?>>
                    <summary class="home-faq__question">
                        <span class="home-faq__number" aria-hidden="true"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="home-faq__question-text"><?= htmlspecialchars($faq['q']) ?></span>
                        <span class="home-faq__toggle" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round">
                                <path class="home-faq__toggle-h" d="M5 12h14"/>
                                <path class="home-faq__toggle-v" d="M12 5v14"/>
                            </svg>
                        </span>
                    </summary>
                    <div class="home-faq__answer">
                        <p><?= htmlspecialchars($faq['a']) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
