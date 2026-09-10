<?php

declare(strict_types=1);

function kora_sections_catalog(): array
{
    return [
        ['slug' => 'settings', 'title' => 'Site Settings', 'description' => 'Contact details, social links, and global site metadata.', 'group' => 'General'],
        ['slug' => 'header', 'title' => 'Header & Navigation', 'description' => 'Top navigation links and Request a Quotation button.', 'group' => 'General'],
        ['slug' => 'hero', 'title' => 'Hero', 'description' => 'Homepage hero slider and headline copy.', 'group' => 'Homepage'],
        ['slug' => 'about', 'title' => 'About', 'description' => 'About section copy and background image.', 'group' => 'Homepage'],
        ['slug' => 'what_we_make', 'title' => 'What We Make', 'description' => 'Homepage product showcase cards and materials.', 'group' => 'Homepage'],
        ['slug' => 'how_to_order', 'title' => 'How to Order', 'description' => 'Homepage order steps strip.', 'group' => 'Homepage'],
        ['slug' => 'workshop', 'title' => 'Workshop Banner', 'description' => 'Full-width workshop image on the homepage.', 'group' => 'Homepage'],
        ['slug' => 'faq', 'title' => 'FAQ', 'description' => 'Homepage frequently asked questions.', 'group' => 'Homepage'],
        ['slug' => 'contact', 'title' => 'Contact / Quote Form', 'description' => 'Homepage contact and quotation form section.', 'group' => 'Homepage'],
        ['slug' => 'products', 'title' => 'Products Page', 'description' => 'Products page hero, categories, and materials.', 'group' => 'Pages'],
        ['slug' => 'work_samples', 'title' => 'Work Samples', 'description' => 'Studio samples page galleries.', 'group' => 'Pages'],
        ['slug' => 'how_it_works', 'title' => 'How It Works', 'description' => 'Detailed ordering process page.', 'group' => 'Pages'],
        ['slug' => 'request_quote', 'title' => 'Request Quote', 'description' => 'Dedicated quotation page content.', 'group' => 'Pages'],
        ['slug' => 'contact_us', 'title' => 'Contact Us', 'description' => 'Dedicated contact page — channels, copy, and form labels.', 'group' => 'Pages'],
        ['slug' => 'footer', 'title' => 'Footer', 'description' => 'Footer newsletter, navigation, and tagline.', 'group' => 'General'],
        ['slug' => 'privacy', 'title' => 'Privacy Policy', 'description' => 'Privacy policy page copy.', 'group' => 'Pages'],
    ];
}

function kora_default_settings(): array
{
    return [
        'site_name' => 'KORA',
        'site_tagline' => 'Custom Awards & Trophies',
        'site_location' => 'Made in-house in Laikipia',
        'site_address' => 'Laikipia, Nanyuki, Kenya',
        'site_phone' => '0790355707',
        'site_phone_link' => '+254790355707',
        'site_email' => 'info@kora.fraittech.co.ke',
        'site_url' => 'https://kora.fraittech.co.ke',
        'site_year' => '2026',
        'site_instagram' => 'https://www.instagram.com/koralasercraft',
        'site_tiktok' => 'https://www.tiktok.com/@koralasercraft',
        'site_facebook' => 'https://www.facebook.com/',
        'site_youtube' => 'https://www.youtube.com/',
        'site_tagline_footer' => 'Recognition Made Personal',
        'business_hours' => 'Mon–Sat, 8am–6pm',
        'whatsapp_url' => 'https://wa.me/254790355707',
    ];
}

function kora_section_defaults(string $slug): array
{
    $catalog = [];
    foreach (kora_sections_catalog() as $section) {
        $catalog[$section['slug']] = $section['title'];
    }

    $title = $catalog[$slug] ?? ucfirst(str_replace('_', ' ', $slug));

    return match ($slug) {
        'settings' => [
            'slug' => 'settings',
            'title' => $title,
            'content' => kora_default_settings(),
        ],
        'header' => [
            'slug' => 'header',
            'title' => $title,
            'content' => [
                'nav' => [
                    ['label' => 'Home', 'href' => '/', 'page' => 'home'],
                    ['label' => 'Our work', 'href' => '/work-samples', 'page' => 'work-samples'],
                    ['label' => 'Products', 'href' => '/products', 'page' => 'products'],
                    ['label' => 'How it works', 'href' => '/how-it-works', 'page' => 'how-it-works'],
                    ['label' => 'Contact Us', 'href' => '/contact-us', 'page' => 'contact-us'],
                ],
                'cta' => [
                    'label' => 'Request a Quotation',
                    'href' => '/request-quote',
                ],
                'default_description' => 'KORA creates custom awards, medals, plaques, and souvenirs in Nanyuki, Laikipia — laser-cut and hand-finished. Recognition made personal for organisations, NGOs, corporates, and sports teams.',
            ],
        ],
        'hero' => [
            'slug' => 'hero',
            'title' => $title,
            'content' => [
                'kicker' => 'Welcome to Kora Laser Craft',
                'title_accent' => 'Recognition,',
                'title_rest' => 'Made Personal',
                'subtitle' => 'Crafting Custom awards, medals and souvenirs.',
                'slides' => [
                    ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Collection of custom KORA awards and trophies', 'width' => 4558, 'height' => 3376],
                    ['file' => 'medals/hero.jpeg', 'alt' => 'Custom KORA medals laid out on wood', 'width' => 2752, 'height' => 1536],
                    ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Custom KORA souvenirs and keepsakes', 'width' => 2752, 'height' => 1536],
                    ['file' => 'workshop_hero.jpeg', 'alt' => 'Laser engraving detail on wood in the KORA workshop', 'width' => 1200, 'height' => 800],
                    ['file' => 'laser_1.jpg', 'alt' => 'Close-up of the laser engraving a custom design into wood', 'width' => 6000, 'height' => 3376],
                ],
                'actions' => [
                    ['label' => 'View Our Work', 'href' => '/work-samples.php', 'style' => 'solid'],
                    ['label' => 'Request a Quotation', 'href' => '/request-quote.php', 'style' => 'light'],
                ],
            ],
        ],
        'about' => [
            'slug' => 'about',
            'title' => $title,
            'content' => [
                'title' => 'Where laser meets craft',
                'background_image' => 'about image.jpg',
                'paragraphs' => [
                    'KORA is a custom awards studio based in Nanyuki, Laikipia, specialising in the design and creation of premium awards, medals, plaques, and souvenirs.',
                    'We blend advanced laser-cutting technology with meticulous hand-assembly and finishing to craft every piece.',
                    'Committed to supporting our community, we partner closely with local timber providers to source our high-quality solid wood, plywood, and MDF, pairing them with premium acrylics.',
                    'KORA works alongside organisations, NGOs, corporates, and sports teams to honour employees, participants, sponsors, and partners at moments worth remembering.',
                ],
            ],
        ],
        'what_we_make' => [
            'slug' => 'what_we_make',
            'title' => $title,
            'content' => [
                'title' => 'What We Make',
                'cards' => [
                    [
                        'id' => 'awards',
                        'label' => 'Awards & Trophies',
                        'image' => ['file' => 'awards/WOODEN AWARD.png', 'alt' => 'Custom layered wooden cycling award by KORA', 'width' => 1207, 'height' => 1303],
                    ],
                    [
                        'id' => 'medals',
                        'label' => 'Medals',
                        'image' => ['file' => 'medals/marathon.jpg', 'alt' => 'Custom layered wooden marathon medal', 'width' => 3376, 'height' => 4199],
                    ],
                    [
                        'id' => 'souvenirs',
                        'label' => 'Souvenirs & Keepsakes',
                        'image' => ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Branded travel souvenir gift set with tumbler and keepsakes', 'width' => 2752, 'height' => 1536],
                    ],
                ],
                'actions' => [
                    ['label' => 'All Products', 'href' => '/products.php'],
                    ['label' => 'Studio Samples', 'href' => '/work-samples.php'],
                    ['label' => 'Request a Quote', 'href' => '/request-quote.php'],
                ],
                'process_images' => [
                    ['file' => 'laser_1.jpg', 'alt' => 'Close-up of the laser engraving a custom design into wood', 'width' => 6000, 'height' => 3376, 'role' => 'back'],
                    ['file' => 'awards/Aw1.jpg', 'alt' => 'Close-up of laser engraving a custom design onto wood', 'width' => 3376, 'height' => 3593, 'role' => 'front'],
                ],
                'materials' => [
                    ['name' => 'Wood', 'image' => 'WOOD.jpg'],
                    ['name' => 'MDF', 'image' => 'MDF.jpg'],
                    ['name' => 'Acrylic', 'image' => 'ACRYLIC.png'],
                ],
            ],
        ],
        'how_to_order' => [
            'slug' => 'how_to_order',
            'title' => $title,
            'content' => [
                'title' => 'How to Order',
                'lead' => 'A straightforward process, with enough care at every step to make the final object feel right.',
                'steps' => [
                    ['number' => '01', 'title' => 'Tell us', 'description' => 'The event & product'],
                    ['number' => '02', 'title' => 'Send Details', 'description' => 'Logo, names, dates'],
                    ['number' => '03', 'title' => 'Get a Quote', 'description' => 'Design + price'],
                    ['number' => '04', 'title' => 'Approve', 'description' => 'Confirm & deposit'],
                    ['number' => '05', 'title' => 'Collection or Delivery', 'description' => 'Pieces ready'],
                ],
            ],
        ],
        'workshop' => [
            'slug' => 'workshop',
            'title' => $title,
            'content' => [
                'image' => [
                    'file' => 'medals/workshop_hero.jpeg',
                    'alt' => 'Custom KORA awards, medals, and souvenirs displayed in the workshop',
                    'width' => 2752,
                    'height' => 1536,
                ],
            ],
        ],
        'faq' => [
            'slug' => 'faq',
            'title' => $title,
            'content' => [
                'eyebrow' => 'Common questions',
                'title' => 'Frequently Asked Questions',
                'lead' => 'Clear answers on timing, production, and delivery — so you know what to expect before you request a quote.',
                'cta_label' => 'Still have a question?',
                'cta_href' => '#contact',
                'items' => [
                    ['q' => 'How quickly will I receive a quote?', 'a' => 'A quote is shared within 24 hours of receiving your brief. Share as much detail as you can — event type, quantity, materials, and timeline — and we will get back to you the same day or next business day.'],
                    ['q' => 'How long does production take?', 'a' => 'Production typically takes 3–7 business days. This may vary depending on the complexity of the design and the quantities ordered. Share your event date when you request a quote so we can confirm a delivery timeline that works for you.'],
                    ['q' => 'How do you handle shipping?', 'a' => 'We use trusted courier services like G4S and Wells Fargo to ensure all items are delivered safely and efficiently anywhere in the country. Collection from our Nanyuki workshop is also available if you prefer.'],
                    ['q' => 'Do I need finished artwork or a logo file?', 'a' => 'No. A rough idea, a photo for inspiration, or just your logo is enough to start. Our design team prepares the layout and shares a proof for your approval before anything is cut or engraved.'],
                    ['q' => 'Is there a minimum order quantity?', 'a' => 'We handle everything from a single commemorative trophy to thousands of marathon medals. Quantity affects unit pricing, so include your best estimate and we will quote accordingly.'],
                    ['q' => 'What materials can I choose from?', 'a' => 'We work in premium MDF, solid wood, plywood, and acrylic — single or multi-layered, and cut into any shape. Your quotation will recommend materials that suit your design, budget, and event feel.'],
                    ['q' => 'Can I see a proof before production starts?', 'a' => 'Yes. Every order includes a design proof for your review. Production only begins once you approve the layout, wording, and finishing details.'],
                    ['q' => 'Where is KORA based?', 'a' => 'Our workshop is in Nanyuki, Laikipia. Everything is designed and produced in-house, so you deal directly with the makers — not a middleman.'],
                ],
            ],
        ],
        'products' => [
            'slug' => 'products',
            'title' => $title,
            'content' => [
                'hero' => [
                    'title' => 'Our Products',
                    'image' => ['file' => 'awards/Hero Section.jpg', 'alt' => 'Collection of custom KORA awards and trophies', 'width' => 4558, 'height' => 3376],
                ],
                'intro' => [
                    'title' => 'What we make',
                    'text' => 'KORA creates recognition products by hand for organisations, NGOs, corporates, schools, and sports teams. Every item starts with your brief — event, brand, quantity, and budget — and is shaped in our Nanyuki workshop using premium materials, precise engraving, and finishes built to last.',
                    'nav' => [
                        ['label' => 'Medals', 'href' => '#medals'],
                        ['label' => 'Awards', 'href' => '#awards'],
                        ['label' => 'Souvenirs', 'href' => '#souvenirs'],
                    ],
                ],
                'categories' => [
                    [
                        'id' => 'medals',
                        'tag' => 'Event recognition',
                        'title' => 'Medals',
                        'lead' => 'Custom medals that give every participant something tangible to keep long after the finish line.',
                        'body' => 'We design and produce medals for marathons, school sports days, corporate wellness challenges, and community events. Each medal can be cut into almost any shape, layered for depth, and finished with engraving, colour fill, ribbon attachment, or presentation packaging.',
                        'features' => [
                            'Single or multi-layered construction for a premium feel',
                            'Custom shapes, logos, dates, and event branding',
                            'Ribbon colours and lengths matched to your brief',
                            'Options for wood, MDF, plywood, and acrylic',
                            'Bulk production for large participant numbers',
                        ],
                        'materials' => 'Premium MDF, solid wood, plywood, and acrylic',
                        'ideal_for' => 'Marathons, fun runs, school sports, corporate challenges, and charity walks',
                        'hero' => ['file' => 'medals/hero.jpeg', 'alt' => 'Custom KORA medals laid out on wood', 'width' => 2752, 'height' => 1536],
                        'images' => [
                            ['file' => 'medals/marathon.jpg', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
                            ['file' => 'medals/bike.jpg', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
                            ['file' => 'medals/football.jpg', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
                        ],
                        'reverse' => false,
                    ],
                    [
                        'id' => 'awards',
                        'tag' => 'Winner recognition',
                        'title' => 'Awards & Trophies',
                        'lead' => 'Physical awards that turn achievement into something worth displaying.',
                        'body' => 'From elegant wooden trophies to layered acrylic awards, we build pieces that feel substantial and personal. Awards can be shaped to reflect your brand, event, or institution, with engraving, inset logos, painted accents, and hand-finished details.',
                        'features' => [
                            'Standing trophies, desktop awards, and sculpted forms',
                            'Engraved text, logos, and sponsor recognition',
                            'Layered acrylic, wood, and mixed-material builds',
                            'Custom heights, bases, and display profiles',
                            'Suitable for ceremonies, galas, and internal recognition',
                        ],
                        'materials' => 'Solid wood, MDF, plywood, acrylic, and mixed finishes',
                        'ideal_for' => 'Corporate awards, sports championships, school prizegivings, and NGO recognition',
                        'hero' => ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Handcrafted business award by KORA', 'width' => 2752, 'height' => 1536],
                        'images' => [
                            ['file' => 'awards/Aw5.jpg', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
                            ['file' => 'awards/Padel Award.jpg', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
                            ['file' => 'awards/Golf_award.jpg', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
                        ],
                        'reverse' => true,
                    ],
                    [
                        'id' => 'souvenirs',
                        'tag' => 'Everyday brand presence',
                        'title' => 'Souvenirs & Keepsakes',
                        'lead' => 'Branded giveaways that keep your organisation present in everyday life.',
                        'body' => 'Beyond awards, we produce souvenirs and keepsakes that extend the reach of your event or brand. From tumblers and fridge magnets to badge pins and custom gift items, each piece is made to feel considered rather than generic.',
                        'features' => [
                            'Tumblers, magnets, badge pins, and branded gift items',
                            'Logo application, colour matching, and custom packaging',
                            'Practical items people keep and use after an event',
                            'Flexible quantities for conferences, launches, and campaigns',
                            'Great for delegates, volunteers, sponsors, and guests',
                        ],
                        'materials' => 'Mixed materials depending on product — wood accents, acrylic, and branded merchandise bases',
                        'ideal_for' => 'Conferences, corporate events, tourism campaigns, and branded giveaways',
                        'hero' => ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Branded travel souvenir gift set by KORA', 'width' => 2752, 'height' => 1536],
                        'images' => [
                            ['file' => 'souvenir/travel.png', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
                            ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Open souvenir gift set with engraved tumbler', 'width' => 2752, 'height' => 1536],
                            ['file' => 'medals/workshop_hero.jpeg', 'alt' => 'Souvenir gift set displayed with awards and medals', 'width' => 2752, 'height' => 1536],
                        ],
                        'reverse' => false,
                    ],
                ],
                'materials_section' => [
                    'title' => 'Materials we work with',
                    'text' => 'We select materials based on the look, weight, and durability your product needs. Whether you want the warmth of natural wood, the precision of layered acrylic, or the versatility of MDF and plywood, we advise on the best combination for your event or brand.',
                    'items' => [
                        ['name' => 'Wood', 'image' => 'WOOD.jpg', 'alt' => 'Solid wood material sample', 'description' => 'Rich, natural finishes ideal for trophies and premium medals.'],
                        ['name' => 'MDF & Plywood', 'image' => 'MDF.jpg', 'alt' => 'MDF material sample', 'description' => 'Reliable bases for shaped medals, layered builds, and detailed engraving.'],
                        ['name' => 'Acrylic', 'image' => 'ACRYLIC.png', 'alt' => 'Acrylic material sample', 'description' => 'Clean, modern awards with colour, depth, and sharp branded detail.'],
                    ],
                ],
                'cta' => [
                    'title' => 'Ready to brief your order?',
                    'text' => 'Share your event, quantities, and design ideas — we will guide you from concept to finished pieces.',
                    'primary_href' => '/request-quote.php',
                    'primary_label' => 'Request a Quotation',
                    'secondary_href' => '/work-samples.php',
                    'secondary_label' => 'Browse our work',
                ],
            ],
        ],
        'work_samples' => [
            'slug' => 'work_samples',
            'title' => $title,
            'content' => [
                'hero' => [
                    'title' => 'Our Studio Samples',
                    'image' => ['file' => 'workshop_hero.jpeg', 'alt' => 'Custom KORA awards, medals, and souvenirs displayed in the workshop', 'width' => 2752, 'height' => 1536],
                ],
                'galleries' => [
                    [
                        'id' => 'medals',
                        'title' => 'Medals',
                        'text' => 'Made in premium MDF, solid wood, plywood, or acrylic, single or multi layered, and cut into any shape. Medals give every participant something to keep, building a sense of belonging and making your event more memorable.',
                        'reverse' => false,
                        'images' => [
                            ['file' => 'medals/marathon.jpg', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
                            ['file' => 'medals/bike.jpg', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
                            ['file' => 'medals/football.jpg', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
                        ],
                    ],
                    [
                        'id' => 'awards',
                        'title' => 'Awards & Trophies',
                        'text' => 'Made in the same range of materials, single or multi layered, and cut into any shape. Awards give recognition a physical form, motivating winners and encouraging others to perform better next time.',
                        'reverse' => true,
                        'images' => [
                            ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Business award trophy on a wooden base', 'width' => 2752, 'height' => 1536],
                            ['file' => 'awards/Aw5.jpg', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
                            ['file' => 'awards/Golf_award.jpg', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
                            ['file' => 'awards/Padel Award.jpg', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
                            ['file' => 'awards/Graduation.jpg', 'alt' => 'Custom graduation award', 'width' => 3682, 'height' => 3376],
                            ['file' => 'awards/Football.jpg', 'alt' => 'Custom football tournament award', 'width' => 3376, 'height' => 5221],
                            ['file' => 'awards/teacher.jpg', 'alt' => 'Custom teacher appreciation award', 'width' => 3203, 'height' => 3680],
                            ['file' => 'awards/retire.jpg', 'alt' => 'Custom retirement plaque', 'width' => 3448, 'height' => 3376],
                            ['file' => 'awards/WOODEN AWARD.png', 'alt' => 'Custom layered wooden cycling award', 'width' => 1207, 'height' => 1303],
                            ['file' => 'awards/Aw4.jpg', 'alt' => 'Multi-layer award sample', 'width' => 3185, 'height' => 3376],
                            ['file' => 'awards/appreciate.png', 'alt' => 'Appreciation award sample', 'width' => 1254, 'height' => 1254],
                            ['file' => 'awards/Golf_Award.jpg', 'alt' => 'Custom golf award sample', 'width' => 1254, 'height' => 1254],
                        ],
                    ],
                    [
                        'id' => 'souvenirs',
                        'title' => 'Souvenirs & Keepsakes',
                        'text' => 'From fridge magnets and tumblers to badge pins and other branded giveaways. Souvenirs keep your organisation present in people\'s everyday lives, extending your event\'s reach and building lasting goodwill.',
                        'reverse' => false,
                        'images' => [
                            ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Branded travel souvenir gift set', 'width' => 2752, 'height' => 1536],
                            ['file' => 'souvenir/travel.png', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
                        ],
                    ],
                ],
            ],
        ],
        'how_it_works' => [
            'slug' => 'how_it_works',
            'title' => $title,
            'content' => [
                'hero' => [
                    'title' => 'How It Works',
                    'image' => ['file' => 'laser_1.jpg', 'alt' => 'Laser engraving a custom design in the KORA workshop', 'width' => 6000, 'height' => 3376],
                ],
                'intro' => [
                    'title' => 'Simple process, careful craft',
                    'text' => 'Ordering from KORA is straightforward. You share your event and design needs, we shape the product with you, and our workshop handles production locally in Nanyuki. The result is recognition that feels personal, durable, and ready for the moment it matters.',
                ],
                'steps_summary' => [
                    ['number' => '01', 'title' => 'Tell us', 'description' => 'The event & product'],
                    ['number' => '02', 'title' => 'Send Details', 'description' => 'Logo, names, dates'],
                    ['number' => '03', 'title' => 'Get a Quote', 'description' => 'Design + price'],
                    ['number' => '04', 'title' => 'Approve', 'description' => 'Confirm & deposit'],
                    ['number' => '05', 'title' => 'Collection or Delivery', 'description' => 'Pieces ready'],
                ],
                'step_details' => [
                    [
                        'id' => 'step-tell-us',
                        'number' => '01',
                        'title' => 'Tell us about your order',
                        'summary' => 'Start with the event, the product, and the outcome you want people to feel.',
                        'body' => 'Reach out with the basics: what you are celebrating, what you need made, how many pieces you require, and when you need them ready.',
                        'tips' => ['Share your event date and ideal delivery window', 'Mention product type — medals, awards, souvenirs, or a mix', 'Include approximate quantities so we can plan production'],
                        'image' => ['file' => 'medals/hero.jpeg', 'alt' => 'Custom KORA medals for events and challenges', 'width' => 2752, 'height' => 1536],
                        'reverse' => false,
                    ],
                    [
                        'id' => 'step-send-details',
                        'number' => '02',
                        'title' => 'Send your design details',
                        'summary' => 'Give us the artwork, names, and specifications we need to shape your pieces.',
                        'body' => 'Once we know the direction, send your logo files, wording, dates, colour preferences, ribbon choices, and any reference images.',
                        'tips' => ['Logo files in PDF, PNG, or AI work best', 'List names, titles, or categories if they vary per piece', 'Note ribbon colours, sizes, or packaging preferences'],
                        'image' => ['file' => 'awards/Aw1.jpg', 'alt' => 'Laser engraving a custom design in the KORA workshop', 'width' => 3376, 'height' => 3593],
                        'reverse' => true,
                    ],
                    [
                        'id' => 'step-get-quote',
                        'number' => '03',
                        'title' => 'Receive your quote',
                        'summary' => 'We respond with a clear design direction and pricing based on your brief.',
                        'body' => 'Our team reviews your requirements and prepares a quotation covering design approach, materials, quantities, and timeline.',
                        'tips' => ['Quotes reflect material choice, complexity, and volume', 'We can suggest alternatives to match your budget', 'Ask questions — we are happy to explain each line item'],
                        'image' => ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Finished award sample prepared for client review', 'width' => 2752, 'height' => 1536],
                        'reverse' => false,
                    ],
                    [
                        'id' => 'step-approve',
                        'number' => '04',
                        'title' => 'Approve and confirm',
                        'summary' => 'Review the direction, confirm details, and secure your production slot.',
                        'body' => 'When you are happy with the quote and design approach, approve the order and confirm with a deposit where required.',
                        'tips' => ['Double-check spelling, dates, and logo placement', 'Confirm quantities before production starts', 'Approval triggers scheduling in the KORA workshop'],
                        'image' => ['file' => 'laser_1.jpg', 'alt' => 'Close-up of the laser engraving a custom design into wood', 'width' => 6000, 'height' => 3376],
                        'reverse' => true,
                    ],
                    [
                        'id' => 'step-deliver',
                        'number' => '05',
                        'title' => 'Collect or receive delivery',
                        'summary' => 'Your finished pieces are prepared, checked, and ready for the moment.',
                        'body' => 'We produce your order in-house, inspect each piece, and prepare everything for handover.',
                        'tips' => ['Lead times depend on quantity and complexity', 'Collection available from our Laikipia workshop', 'Delivery options can be discussed when you confirm'],
                        'image' => ['file' => 'medals/workshop_hero.jpeg', 'alt' => 'Finished KORA awards, medals, and souvenirs ready for handover', 'width' => 2752, 'height' => 1536],
                        'reverse' => false,
                    ],
                ],
                'prepare' => [
                    'title' => 'What to prepare',
                    'text' => 'Having these details ready helps us respond faster with an accurate quote and design direction.',
                    'items' => [
                        ['title' => 'Event details', 'text' => 'Date, venue, and when you need the order ready'],
                        ['title' => 'Product type', 'text' => 'Medals, trophies, souvenirs, or a combination'],
                        ['title' => 'Quantities', 'text' => 'How many pieces you need for each category'],
                        ['title' => 'Branding assets', 'text' => 'Logo files, colours, and any reference artwork'],
                        ['title' => 'Copy & names', 'text' => 'Wording, recipient names, or category labels'],
                        ['title' => 'Budget range', 'text' => 'Helps us recommend the best material and finish'],
                    ],
                ],
                'notes' => [
                    'title' => 'Good to know',
                    'items' => [
                        ['title' => 'Made in-house', 'text' => 'Every piece is produced in our Nanyuki workshop — not outsourced — so quality and timelines stay in our hands.'],
                        ['title' => 'Lead times vary', 'text' => 'Timing depends on quantity, material, and detail. Share your event date early so we can schedule production properly.'],
                        ['title' => 'We guide the design', 'text' => 'No finished artwork? Share your logo and brief — we will help shape a layout that works for your product and budget.'],
                    ],
                ],
                'cta' => [
                    'title' => 'Ready to start your order?',
                    'text' => 'Tell us about your event and we will take it from there.',
                    'primary_href' => '/request-quote.php',
                    'primary_label' => 'Request a Quotation',
                    'secondary_href' => '/products.php',
                    'secondary_label' => 'View our products',
                ],
            ],
        ],
        'contact' => [
            'slug' => 'contact',
            'title' => $title,
            'content' => [
                'title' => 'Request a Custom Quotation',
                'lead' => 'Tell us what you have in mind. We\'ll review your brief and recommend suitable options based on your event, quantity, materials, and budget.',
                'submit_label' => 'Request a Quotation',
                'form_action' => '/process-quote.php',
                'channels' => [
                    ['label' => 'Whatsapp KORA', 'href' => 'https://wa.me/254790355707', 'external' => true],
                    ['label' => 'Email KORA', 'href' => 'mailto:koradesignprint@gmail.com', 'external' => false],
                ],
            ],
        ],
        'request_quote' => [
            'slug' => 'request_quote',
            'title' => $title,
            'content' => [
                'hero' => [
                    'title' => 'Request a Quotation',
                    'image' => ['file' => 'acrylic_material.jpeg', 'alt' => 'Acrylic material used for custom KORA awards', 'width' => 2752, 'height' => 1536],
                ],
                'trust' => [
                    ['label' => '24-hour response', 'text' => 'Quotations prepared within one business day.'],
                    ['label' => 'Made in-house', 'text' => 'Designed and produced in our Nanyuki workshop.'],
                    ['label' => 'Free design guidance', 'text' => 'We refine your layout and artwork before production.'],
                    ['label' => 'Delivery or collection', 'text' => 'Collect in Nanyuki or arrange delivery countrywide.'],
                ],
                'form' => [
                    'title' => 'Tell us about your order',
                    'note' => 'Fields marked * are required. The more detail you share, the more accurate your quotation will be.',
                    'action' => '/process-quote.php',
                ],
                'aside' => [
                    'talk_title' => 'Prefer to talk first?',
                    'talk_text' => 'We are happy to discuss your idea before you fill anything in.',
                    'checklist_title' => 'Have these ready',
                    'checklist' => [
                        'Event date and delivery deadline',
                        'Product type and estimated quantity',
                        'Logo files or artwork, if available',
                        'Wording, names, or engraving text',
                        'Preferred material and rough budget',
                    ],
                    'steps_title' => 'What happens next',
                    'steps' => [
                        ['title' => 'We review your brief', 'text' => 'Same day, with follow-up questions if needed.'],
                        ['title' => 'You receive your quotation', 'text' => 'Pricing plus a suggested design direction within 24 hours.'],
                        ['title' => 'Approve the design', 'text' => 'We share a proof; production starts on your approval.'],
                        ['title' => 'Collect or receive delivery', 'text' => 'Finished pieces ready ahead of your event date.'],
                    ],
                ],
                'faq' => [
                    ['q' => 'How quickly will I receive a quote?', 'a' => 'A quote is shared within 24 hours of receiving your brief.'],
                    ['q' => 'How long does production take?', 'a' => 'Production typically takes 3–7 business days depending on complexity and quantities.'],
                    ['q' => 'How do you handle shipping?', 'a' => 'We use trusted courier services like G4S and Wells Fargo. Collection from our Nanyuki workshop is also available.'],
                    ['q' => 'Do I need finished artwork or a logo file?', 'a' => 'No. A rough idea, a photo for inspiration, or just your logo is enough to start.'],
                    ['q' => 'Is there a minimum order quantity?', 'a' => 'We handle everything from a single commemorative trophy to thousands of marathon medals.'],
                    ['q' => 'How is pricing calculated?', 'a' => 'Pricing depends on material, size, layers, engraving detail, finishing, and quantity.'],
                ],
            ],
        ],
        'footer' => [
            'slug' => 'footer',
            'title' => $title,
            'content' => [
                'newsletter' => [
                    'title' => 'Subscribe to our newsletter to get updates on our latest collections',
                    'text' => 'Be first to see new awards, medals, souvenirs plus seasonal offers from the KORA workshop.',
                    'image' => ['file' => 'awards/Golf_award.jpg', 'alt' => 'Custom KORA golf award', 'width' => 3376, 'height' => 4667],
                    'privacy_href' => '/privacy.php',
                ],
                'blurb' => 'Custom awards, medals and souvenirs designed and made for organisations, schools, corporates, and sports teams.',
                'explore_nav' => [
                    ['label' => 'Home', 'href' => '/'],
                    ['label' => 'Our work', 'href' => '/work-samples.php'],
                    ['label' => 'Products', 'href' => '/products.php'],
                    ['label' => 'How it works', 'href' => '/how-it-works.php'],
                    ['label' => 'Contact Us', 'href' => '/contact-us'],
                ],
                'products_nav' => [
                    ['label' => 'Medals', 'href' => '/products.php#medals'],
                    ['label' => 'Awards & Trophies', 'href' => '/products.php#awards'],
                    ['label' => 'Souvenirs', 'href' => '/products.php#souvenirs'],
                ],
                'credit' => ['label' => 'Designed by Fraittech', 'href' => 'https://fraittech.co.ke'],
            ],
        ],
        'contact_us' => [
            'slug' => 'contact_us',
            'title' => $title,
            'content' => [
                'hero' => [
                    'title' => 'Contact Us',
                    'lead' => 'Speak with the KORA studio — we are here to help with awards, medals, plaques, and custom keepsakes.',
                    'image' => [
                        'file' => 'workshop_hero.jpeg',
                        'alt' => 'KORA workshop in Nanyuki',
                        'width' => 1200,
                        'height' => 800,
                    ],
                ],
                'intro_title' => 'Reach the workshop',
                'intro_text' => 'Whether you have a quick question or want to talk through an idea before requesting a quotation, send us a message or use the channels below. We reply within one business day.',
                'channels' => [
                    [
                        'label' => 'WhatsApp',
                        'value' => 'Chat with KORA',
                        'href' => 'https://wa.me/254790355707',
                        'external' => true,
                        'icon' => 'whatsapp',
                    ],
                    [
                        'label' => 'Phone',
                        'value' => '0790355707',
                        'href' => 'tel:+254790355707',
                        'external' => false,
                        'icon' => 'phone',
                    ],
                    [
                        'label' => 'Email',
                        'value' => 'info@kora.fraittech.co.ke',
                        'href' => 'mailto:info@kora.fraittech.co.ke',
                        'external' => false,
                        'icon' => 'email',
                    ],
                    [
                        'label' => 'Visit',
                        'value' => 'Laikipia, Nanyuki, Kenya',
                        'href' => '',
                        'external' => false,
                        'icon' => 'location',
                    ],
                    [
                        'label' => 'Hours',
                        'value' => 'Mon–Sat, 8am–6pm',
                        'href' => '',
                        'external' => false,
                        'icon' => 'hours',
                    ],
                ],
                'form' => [
                    'title' => 'Send a message',
                    'note' => 'Fields marked * are required. We will get back to you by email or phone.',
                    'action' => '/process-contact.php',
                    'submit_label' => 'Send message',
                ],
                'aside' => [
                    'title' => 'Need a formal quotation?',
                    'text' => 'Share your event details, quantity, and materials for a priced quote within 24 hours.',
                    'cta_label' => 'Request a Quotation',
                    'cta_href' => '/request-quote',
                ],
            ],
        ],
        'privacy' => [
            'slug' => 'privacy',
            'title' => $title,
            'content' => [
                'title' => 'Privacy Policy',
                'updated' => '10 September 2026',
                'intro' => 'This Privacy Policy explains how KORA (“we”, “us”, or “our”) collects, uses, stores, and protects personal information when you visit our website, request a quotation, contact our studio, subscribe to our newsletter, or otherwise communicate with us.',
                'sections' => [
                    [
                        'heading' => '1. Who we are',
                        'paragraphs' => [
                            'KORA is a custom awards studio based in Nanyuki, Laikipia, Kenya. We design and produce medals, awards, trophies, plaques, souvenirs, and related keepsakes for organisations, NGOs, corporates, schools, and sports teams.',
                            'This website is operated by KORA. For privacy questions, please contact us using the details in the “How to contact us” section below.',
                        ],
                    ],
                    [
                        'heading' => '2. Scope of this policy',
                        'paragraphs' => [
                            'This policy applies to personal information collected through our website (including quotation and contact forms, newsletter signup, and WhatsApp links), by phone or email, and through files or design briefs you send us in connection with an enquiry or order.',
                            'It does not cover third-party websites or apps that we may link to (for example Instagram, TikTok, Facebook, YouTube, or WhatsApp). Those services have their own privacy practices.',
                        ],
                    ],
                    [
                        'heading' => '3. Information we collect',
                        'paragraphs' => [
                            'We collect information you choose to provide, and limited technical information needed to operate the website securely.',
                        ],
                    ],
                    [
                        'heading' => '3.1 Information you provide',
                        'paragraphs' => [
                            'Quotation requests may include your name, organisation, phone or WhatsApp number, email address, event type, product interest, quantity, event date, project brief or message, and optional inspiration files (such as PDF, PNG, WebP, or JPG).',
                            'Contact messages may include your name, organisation, email address, phone or WhatsApp number, subject, and message content.',
                            'Newsletter signup collects your email address so we can send updates about collections, offers, and workshop news.',
                            'If you message us on WhatsApp, phone, email, or social media, we process the details and conversation content you share so we can respond and manage your enquiry or order.',
                            'For orders, we may also hold delivery or collection details, artwork, logos, engraving text, branding guidelines, and payment or invoicing references needed to fulfil your project.',
                        ],
                    ],
                    [
                        'heading' => '3.2 Information collected automatically',
                        'paragraphs' => [
                            'Like most websites, our hosting environment may record standard server logs such as IP address, browser type, device information, pages requested, referring URL, and date/time of access. We use this information for security, troubleshooting, and basic service operation.',
                            'We do not currently use advertising cookies or third-party analytics trackers on the public website. If that changes, we will update this policy and, where required, provide appropriate notice or choices.',
                        ],
                    ],
                    [
                        'heading' => '4. How we use your information',
                        'paragraphs' => [
                            'We use personal information to:',
                            'Respond to enquiries, prepare quotations, and discuss design options, materials, quantities, timelines, and pricing.',
                            'Produce and deliver your medals, awards, souvenirs, or other ordered items, including using logos, names, and artwork you supply.',
                            'Communicate with you about your brief, proofs, production status, delivery or collection, invoices, and after-sales support.',
                            'Send newsletter updates if you have subscribed, and process unsubscribe requests.',
                            'Protect our website, studio, and customers against spam, fraud, and misuse (including honeypot and basic validation checks on forms).',
                            'Keep business records required for accounting, tax, warranty, dispute resolution, and legal compliance.',
                            'Improve our products, service quality, and website experience based on the types of enquiries we receive.',
                        ],
                    ],
                    [
                        'heading' => '5. Legal bases and legitimate purposes',
                        'paragraphs' => [
                            'We process personal information where it is necessary to take steps at your request before entering a contract, to perform a contract with you, to pursue our legitimate business interests (such as responding to enquiries and operating a secure website), to send marketing you have opted into, or where we must comply with a legal obligation.',
                            'You may choose not to provide certain information, but that may limit our ability to quote accurately or complete your order.',
                        ],
                    ],
                    [
                        'heading' => '6. How we share information',
                        'paragraphs' => [
                            'We do not sell your personal information.',
                            'We may share information with trusted service providers who help us operate our business, such as website hosting, email delivery, domain or IT support, couriers, and payment or accounting tools — only as needed to provide those services and under appropriate confidentiality expectations.',
                            'If you contact us through WhatsApp or social platforms, your message is also processed under those platforms’ terms and privacy policies.',
                            'We may disclose information where required by law, regulation, court order, or to protect the rights, safety, or property of KORA, our customers, or others.',
                            'If our studio is involved in a business transfer, reorganisation, or similar event, personal information may be transferred as part of that process, subject to continued privacy protections where reasonably possible.',
                        ],
                    ],
                    [
                        'heading' => '7. File uploads and creative materials',
                        'paragraphs' => [
                            'Inspiration files, logos, brand assets, and artwork you upload or send are used only to understand your brief, prepare designs or quotations, and produce your order.',
                            'Please ensure you have the right to share any logos, images, names, or brand materials you provide. You are responsible for obtaining any permissions needed for us to use those materials in your project.',
                            'We may retain production files and proofs as part of your project record so we can fulfil reorders, resolve quality queries, and keep an accurate workshop history.',
                        ],
                    ],
                    [
                        'heading' => '8. Storage, retention, and security',
                        'paragraphs' => [
                            'Enquiry and order information may be stored in our email systems, workshop records, and secure server storage used to operate this website (for example quotation and contact submissions kept so we can follow up even if email delivery is delayed).',
                            'We retain personal information only for as long as needed for the purposes described in this policy, including responding to enquiries, completing orders, supporting reorders, and meeting legal, accounting, or dispute-related requirements. Newsletter addresses are kept until you unsubscribe or ask us to remove them.',
                            'We take reasonable technical and organisational measures to protect personal information against unauthorised access, loss, misuse, or alteration. No method of transmission or storage is completely secure, so we cannot guarantee absolute security.',
                        ],
                    ],
                    [
                        'heading' => '9. International processing',
                        'paragraphs' => [
                            'KORA is based in Kenya. Some service providers (for example hosting or email infrastructure) may process data on servers located in other countries. Where that happens, we take reasonable steps to work with reputable providers and protect information appropriately.',
                        ],
                    ],
                    [
                        'heading' => '10. Your choices and rights',
                        'paragraphs' => [
                            'Depending on applicable law, you may request access to the personal information we hold about you, ask us to correct inaccurate details, request deletion where we no longer need the information, object to or restrict certain processing, or withdraw consent for newsletter marketing.',
                            'To unsubscribe from the newsletter, use the unsubscribe method provided in our emails or contact us directly.',
                            'To exercise a privacy request, email or call us using the contact details below. We may need to verify your identity before completing certain requests.',
                            'If you believe your privacy rights have been infringed, you may also raise the matter with the relevant data protection authority in Kenya or in your place of residence where applicable.',
                        ],
                    ],
                    [
                        'heading' => '11. Children',
                        'paragraphs' => [
                            'Our website and services are directed to organisations and adults arranging awards, events, and branded items. We do not knowingly collect personal information from children for marketing purposes. If you believe a child has provided personal information to us inappropriately, please contact us and we will take reasonable steps to delete it.',
                        ],
                    ],
                    [
                        'heading' => '12. Third-party links',
                        'paragraphs' => [
                            'Our website may include links to social media profiles, messaging apps, or other external sites. We are not responsible for the privacy practices or content of those third parties. We encourage you to review their policies before sharing personal information with them.',
                        ],
                    ],
                    [
                        'heading' => '13. Changes to this policy',
                        'paragraphs' => [
                            'We may update this Privacy Policy from time to time to reflect changes in our practices, services, or legal requirements. The “Last updated” date at the top of this page will be revised when changes are published. Continued use of our website or services after an update means you should review the revised policy.',
                        ],
                    ],
                    [
                        'heading' => '14. How to contact us',
                        'paragraphs' => [
                            'If you have questions about this Privacy Policy or how KORA handles personal information, contact us at info@kora.fraittech.co.ke or call 0790355707. You can also message us on WhatsApp via the contact options on our website. Our workshop is based in Laikipia, Nanyuki, Kenya.',
                        ],
                    ],
                ],
            ],
        ],
        default => [
            'slug' => $slug,
            'title' => $title,
            'content' => [],
        ],
    };
}

function kora_load_settings(PDO $pdo): array
{
    $defaults = kora_default_settings();
    $stmt = $pdo->query('SELECT key, value FROM settings');
    $settings = $defaults;

    while ($row = $stmt->fetch()) {
        $settings[(string) $row['key']] = (string) $row['value'];
    }

    return $settings;
}

function kora_save_settings(PDO $pdo, array $settings): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO settings (key, value) VALUES (:key, :value)
         ON CONFLICT(key) DO UPDATE SET value = excluded.value'
    );

    foreach ($settings as $key => $value) {
        $stmt->execute([
            'key' => (string) $key,
            'value' => (string) $value,
        ]);
    }
}

function kora_is_assoc_array(array $array): bool
{
    if ($array === []) {
        return false;
    }

    return array_keys($array) !== range(0, count($array) - 1);
}

/**
 * Fill missing keys from defaults without overwriting saved values.
 *
 * @param array<string, mixed> $defaults
 * @param array<string, mixed> $content
 * @return array<string, mixed>
 */
function kora_merge_content_defaults(array $defaults, array $content): array
{
    foreach ($defaults as $key => $defaultValue) {
        if (!array_key_exists($key, $content)) {
            $content[$key] = $defaultValue;
            continue;
        }

        if (
            is_array($defaultValue)
            && is_array($content[$key])
            && kora_is_assoc_array($defaultValue)
            && kora_is_assoc_array($content[$key])
        ) {
            $content[$key] = kora_merge_content_defaults($defaultValue, $content[$key]);
        }
    }

    return $content;
}

function kora_load_section(PDO $pdo, string $slug): array
{
    if ($slug === 'settings') {
        return [
            'slug' => 'settings',
            'title' => 'Site Settings',
            'content' => kora_load_settings($pdo),
        ];
    }

    $defaults = kora_section_defaults($slug);
    $defaultContent = is_array($defaults['content'] ?? null) ? $defaults['content'] : [];

    $stmt = $pdo->prepare('SELECT slug, title, content_json FROM cms_sections WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    if ($row) {
        $content = json_decode((string) $row['content_json'], true);
        $content = is_array($content) ? $content : [];

        return [
            'slug' => (string) $row['slug'],
            'title' => (string) $row['title'],
            'content' => kora_merge_content_defaults($defaultContent, $content),
        ];
    }

    return $defaults;
}

function kora_save_section(PDO $pdo, string $slug, string $title, array $content): void
{
    if ($slug === 'settings') {
        kora_save_settings($pdo, $content);
        kora_export_site($pdo);

        return;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO cms_sections (slug, title, content_json, updated_at)
         VALUES (:slug, :title, :content_json, :updated_at)
         ON CONFLICT(slug) DO UPDATE SET
            title = excluded.title,
            content_json = excluded.content_json,
            updated_at = excluded.updated_at'
    );
    $stmt->execute([
        'slug' => $slug,
        'title' => $title,
        'content_json' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'updated_at' => now(),
    ]);

    kora_export_site($pdo);
}

function kora_export_site(PDO $pdo): void
{
    $sections = [];
    $stmt = $pdo->query('SELECT slug, title, content_json FROM cms_sections ORDER BY slug');

    while ($row = $stmt->fetch()) {
        $content = json_decode((string) $row['content_json'], true);
        $sections[(string) $row['slug']] = [
            'title' => (string) $row['title'],
            'content' => is_array($content) ? $content : [],
        ];
    }

    foreach (kora_sections_catalog() as $meta) {
        $slug = $meta['slug'];

        if ($slug === 'settings') {
            continue;
        }

        $default = kora_section_defaults($slug);
        $defaultContent = is_array($default['content'] ?? null) ? $default['content'] : [];

        if (!isset($sections[$slug])) {
            $sections[$slug] = [
                'title' => (string) $default['title'],
                'content' => $defaultContent,
            ];
            continue;
        }

        $existing = is_array($sections[$slug]['content'] ?? null) ? $sections[$slug]['content'] : [];
        $sections[$slug]['content'] = kora_merge_content_defaults($defaultContent, $existing);
    }

    $payload = [
        'version' => 1,
        'updated_at' => now(),
        'settings' => kora_load_settings($pdo),
        'sections' => $sections,
    ];

    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        throw new RuntimeException('Unable to encode site content for export.');
    }

    $paths = [
        site_root() . '/data/cms/site.json',
        dirname(__DIR__) . '/data/content/site.json',
    ];

    $livePath = $paths[0];
    $liveOk = false;
    $errors = [];

    foreach ($paths as $path) {
        try {
            kora_write_export_file($path, $json);
            if ($path === $livePath) {
                $liveOk = true;
            }
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$liveOk) {
        throw new RuntimeException($errors[0] ?? 'Unable to write live site content export.');
    }
}

function kora_write_export_file(string $path, string $json): void
{
    $dir = dirname($path);

    if (!is_dir($dir) && !@mkdir($dir, 0777, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create content export directory: ' . $path);
    }

    @chmod($dir, 0777);

    if (!is_writable($dir)) {
        throw new RuntimeException('Export directory is not writable: ' . $dir);
    }

    // Replace a root-owned unwritable file when the directory allows it.
    if (is_file($path) && !is_writable($path)) {
        if (!@unlink($path)) {
            throw new RuntimeException('Unable to replace locked export file: ' . $path);
        }
    }

    $tmp = $dir . '/.site-export-' . bin2hex(random_bytes(4)) . '.tmp';

    if (@file_put_contents($tmp, $json) === false) {
        @unlink($tmp);
        throw new RuntimeException('Unable to write site content export: ' . $path);
    }

    @chmod($tmp, 0666);

    if (is_file($path) && !@unlink($path)) {
        // Fall back to in-place overwrite.
        $wrote = @file_put_contents($path, $json);
        @unlink($tmp);

        if ($wrote === false) {
            throw new RuntimeException('Unable to write site content export: ' . $path);
        }

        @chmod($path, 0666);

        return;
    }

    if (!@rename($tmp, $path)) {
        $wrote = @file_put_contents($path, $json);
        @unlink($tmp);

        if ($wrote === false) {
            throw new RuntimeException('Unable to write site content export: ' . $path);
        }
    }

    @chmod($path, 0666);
}

function kora_seed_all(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM cms_sections')->fetchColumn();

    if ($count === 0) {
        foreach (kora_sections_catalog() as $meta) {
            if ($meta['slug'] === 'settings') {
                continue;
            }

            $default = kora_section_defaults($meta['slug']);
            kora_save_section($pdo, $meta['slug'], (string) $default['title'], $default['content']);
        }
    }

    $settingsCount = (int) $pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn();

    if ($settingsCount === 0) {
        kora_save_settings($pdo, kora_default_settings());
    }

    kora_export_site($pdo);
}

function kora_get_site_json(): array
{
    $path = site_root() . '/data/cms/site.json';

    if (!is_file($path)) {
        $fallback = dirname(__DIR__) . '/data/content/site.json';

        if (is_file($fallback)) {
            $path = $fallback;
        } else {
            return [
                'version' => 1,
                'updated_at' => now(),
                'settings' => kora_default_settings(),
                'sections' => [],
            ];
        }
    }

    $raw = file_get_contents($path);

    if ($raw === false) {
        return [
            'version' => 1,
            'updated_at' => now(),
            'settings' => kora_default_settings(),
            'sections' => [],
        ];
    }

    $data = json_decode($raw, true);

    return is_array($data) ? $data : [
        'version' => 1,
        'updated_at' => now(),
        'settings' => kora_default_settings(),
        'sections' => [],
    ];
}
