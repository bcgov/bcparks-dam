<?php

include_once __DIR__ . "/../../include/boot.php";
include_once __DIR__ . "/../../include/authenticate.php";

// Featured collection node to load images for.
$node = getval("node", 0, true);
$is_smart = (int) getval("smart", 0, true) === 1;

if ($node <= 0) {
    exit();
}

// Build the minimal featured collection data required by the shared rendering functions.
if ($is_smart) {
    $fc = [
        "ref" => $node,
        "parent" => 0,
        "thumbnail_selection_method" => $FEATURED_COLLECTION_BG_IMG_SELECTION_OPTIONS["most_popular_image"],
    ];
} else {
    $fc = get_collection($node);

    if ($fc === false) {
        exit();
    }
    $fc["has_resources"] = (bool) ps_value(
        "SELECT EXISTS(
            SELECT 1
            FROM collection_resource
            WHERE collection = ?
        ) AS value",
        ["i", $node],
        0
    );
}

// Fetch resources for the featured collection tile.
$resource_context = [
    "smart" => $is_smart,
    "use_thumbnail_selection_method" => !$is_smart,
    "all_fcs" => [],
];

if ($is_smart) {
    $resource_context["limit"] = 3;
}

$resources = get_featured_collection_resources($fc, $resource_context);

// Reuse the standard featured collection image generation/rendering logic.
$theme_images = generate_featured_collection_image_urls($resources);

echo generate_featured_collection_image_html($theme_images, $fc);
