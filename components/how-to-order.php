<?php

declare(strict_types=1);

$order = cms_section('how_to_order');
$order_title = (string) ($order['title'] ?? 'How to Order');
$order_lead = (string) ($order['lead'] ?? 'A straightforward process, with enough care at every step to make the final object feel right.');
$order_steps = cms_list('how_to_order', 'steps', [
    ['number' => '01', 'title' => 'Tell us', 'description' => 'The event & product'],
    ['number' => '02', 'title' => 'Send Details', 'description' => 'Logo, names, dates'],
    ['number' => '03', 'title' => 'Get a Quote', 'description' => 'Design + price'],
    ['number' => '04', 'title' => 'Approve', 'description' => 'Confirm & deposit'],
    ['number' => '05', 'title' => 'Collection or Delivery', 'description' => 'Pieces ready'],
]);

$order_icons = [
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4-.8L3 20l1.8-4A8.96 8.96 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h5"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 6 9 17l-5-5"/></svg>',
    '<svg class="order-step__icon-svg--truck" viewBox="0 0 24 24" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" aria-hidden="true"><path d="M3.25 5.5h9.75c.69 0 1.25.56 1.25 1.25v6.75H3.25V5.5zm10.75 1.35h4.65l2.35 2.05v4.35H14V6.85zM3 13.35h17.75v1.15c0 .58-.47 1.05-1.05 1.05H3.3c-.58 0-1.05-.47-1.05-1.05v-1.15zM7 14.35a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zm10 0a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zM15.15 8.15h3.35v3.05h-3.35V8.15zM7 15.55a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5zm10 0a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z"/></svg>',
];
?>
<section class="how-to-order section" id="how-it-works" aria-labelledby="how-title">
    <div class="container">
        <div class="how-to-order__header reveal">
            <h2 id="how-title" class="section-label"><?= htmlspecialchars($order_title) ?></h2>
            <p class="lead-italic"><?= htmlspecialchars($order_lead) ?></p>
        </div>
        <ol class="order-steps reveal">
            <?php foreach ($order_steps as $index => $step): ?>
                <?php
                if (!is_array($step)) {
                    continue;
                }
                $number = (string) ($step['number'] ?? str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT));
                $title = (string) ($step['title'] ?? '');
                $desc = (string) ($step['description'] ?? '');
                $icon = $order_icons[$index] ?? $order_icons[0];
                $isLast = $index === count($order_steps) - 1;
                ?>
                <li class="order-step">
                    <span class="order-step__number" aria-hidden="true"><?= htmlspecialchars($number) ?></span>
                    <div class="order-step__icon" aria-hidden="true"><?= $icon ?></div>
                    <h3 class="order-step__title"><?= htmlspecialchars($title) ?></h3>
                    <p class="order-step__desc"><?= htmlspecialchars($desc) ?></p>
                    <?php if (!$isLast): ?>
                        <span class="order-step__arrow" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
