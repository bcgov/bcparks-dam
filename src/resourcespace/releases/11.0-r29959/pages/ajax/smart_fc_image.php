<?php

include_once __DIR__ . "/../../include/boot.php";
include_once __DIR__ . "/../../include/authenticate.php";

// Smart featured collection node to load images for.
$node = getval("node", 0, true);

if ($node <= 0) {
    exit();
}

// Build the minimal featured collection data required by the shared rendering functions.
$fc = [
    "ref" => $node,
    "parent" => 0,
    "thumbnail_selection_method" => $FEATURED_COLLECTION_BG_IMG_SELECTION_OPTIONS["most_popular_image"],
];

// Fetch up to three resources for the smart featured collection tile.
$resources = get_featured_collection_resources(
    $fc,
    [
        "smart" => true,
        "use_thumbnail_selection_method" => false,
        "all_fcs" => [],
        "limit" => 3,
    ]
);

// Reuse the standard featured collection image generation/rendering logic.
$theme_images = generate_featured_collection_image_urls($resources);

echo generate_featured_collection_image_html($theme_images, $fc);
