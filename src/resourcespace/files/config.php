<?php
###############################
## ResourceSpace
## Local Configuration Script
###############################

# All custom settings should be entered in this file.
# Options may be copied from config.default.php and configured here.

# Base URL of the installation
$baseurl = 'http://127.0.0.1'; # Fallback for cron job execution
# When running cli php scripts, HTTP_HOST is not set
if (isset($_SERVER['HTTP_HOST'])) {
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') { # Used with Nginx server
    #if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { # Used with Apache server
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['HTTPS'] = 'true';
        $baseurl = 'https://' . $_SERVER['HTTP_HOST'];
    } else {
        $baseurl = 'http://' . $_SERVER['HTTP_HOST'];
    }
}
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}

# Paths
$imagemagick_path = '/usr/bin';
$ghostscript_path = '/usr/bin';
$ffmpeg_path = '/usr/bin';
$exiftool_path = '/usr/bin';
$php_path = '/usr/bin/php';
$homeanim_folder = 'filestore/system/slideshow_a383ab9e2f595db';
$CSRF_whitelist = [$baseurl]; # Whitelist URLs to be exempt from CSRF (Cross-Site Request Forgery) checks

# Logging and debugging
$log_resource_access = false;
$log_search_performance = false;
$log_php_errors = true;
$log_all_php_errors = false; # Including E_NOTICE and E_WARNING level errors, recommended for debugging only
$debug_log = false; # General debugging log
$debug_log_location = '/var/www/resourcespace/logs/debug.txt';
$show_detailed_errors = false;

# Antivirus and security
$antivirus_enabled = true; # Enable antivirus scanning of uploaded files
$antivirus_path = '/usr/bin/clamscan';
$antivirus_silent_options = '--suppress-ok-results -o --no-summary';

# File uploads
$api_upload_urls = []; # Whitelist for restricting API uploads by URL
$upload_then_edit = true;
$enable_related_resources = true;
$related_search_show_self = true; # Include the current resource in the related resources set
$relate_on_upload = true;
$relate_on_upload_default = true;
$file_checksums = false;

# Static sync and offline jobs
$syncdir = '/var/www/resourcespace/filestore/static_sync';
$staticsync_ingest = 'yes';
$staticsync_max_files = 200;
$staticsync_file_minimum_age = 120;
$staticsync_filepath_to_field = 148;
$autorotate_ingest = true;
$offline_job_queue = true; # Use offline job queue to generate previews in the background for improved performance
$resource_type_extension_mapping = array (
    #27 => array('pdf', 'doc', 'docx', 'epub', 'ppt', 'pptx', 'odt', 'ods', 'tpl', 'ott', 'rtf', 'txt', 'xml'), # RST Map in PROD
    #3 => array('mov', '33gp', 'avi', 'mpg', 'mp4', 'flv', 'wmv', 'webm'), # Video in PROD
    #99 => array('flac', 'mp3', '3ga', 'cda', 'rec', 'aa', 'au', 'mp4a', 'wav', 'aac', 'ogg', 'weba'), # Audio in PROD
    #26 => array('webp') # RST Recreation Resource in PROD
    
    #24 => array('pdf', 'doc', 'docx', 'epub', 'ppt', 'pptx', 'odt', 'ods', 'tpl', 'ott', 'rtf', 'txt', 'xml'), # RST Map in TEST
    #23 => array('webp') # RST Photo in TEST
);
#unset($resource_type_extension_mapping[2]); # This default resource type was removed
#unset($resource_type_extension_mapping[4]); # This default resource type was removed


# Collections, downloads, and comments
$collection_download = true;
$collection_download_tar_size = 0;  # 0 disables TAR download, forces ZIP
$use_zip_extension = true;
$comments_resource_enable = true;
$collection_commenting = true;

# Filestore and temporary files
$purge_temp_folder_age = 90;
$filestore_evenspread = true; # Enable/disable even spread mode
$filestore_migrate = true; # Allows existing, non-even spread file structure to be read
$originals_separate_storage = false; # Separate storage of originals from previews

# Image processing and preview settings
$imagemagick_colorspace = 'sRGB';
$camera_autorotation = true; # Auto-rotate images based on EXIF orientation
$ffmpeg_preview_force = true;
$ffmpeg_preview_extension = 'mp4';
$ffmpeg_preview_options = '-f mp4 -b:v 1200k -b:a 64k -ac 1 -c:v libx264 -pix_fmt yuv420p -profile:v baseline -level 3 -c:a aac -strict -2';
$imagemagick_preserve_profiles = true;
$preview_generate_max_file_size = 3; # Immediately generate previews if the file size is <=3MB

# Slideshows and thumbnails/previews
$slideshow_big = true; # Use the large slideshow layout by default
$home_slideshow_width = 1920;
$home_slideshow_height = 1080;
$xlthumbs = true; # Generate and display extra-large thumbnails
$image_preview_zoom = true; # Enable the image preview zoom feature, which uses OpenSeadragon
$preview_tiles = true; # Generate extra preview tiles for use with image preview zoom

# Themes and user interface
$themes_simple_view = true;
$themes_show_background_image = true;
$contact_link = false;
$use_native_input_for_date_field = true; # Use the browser's native HTML5 date input control instead of a custom date picker

# User management and notifications
$case_insensitive_username = true;
$user_pref_user_management_notifications = true; # Send notifications to users regarding events such as account changes or other administrative updates that affect user management
$check_upgrade_available = false; # Check for available ResourceSpace upgrades

# Search
$stemming = true; # Reduce words in search queries and indexed content to their basic root form. For example, 'running', 'runs', and 'ran' might all be reduced to 'run'
$daterange_search = true; # Enable searching by a range of dates
$search_filter_nodes = true; # Enable hierarchical search using category trees

# TUS protocol parameters for uploads
$tus_enabled = true; # Enable TUS protocol
$tus_chunk_size = 5000000; # Chunk size in bytes
$tus_max_upload_size = 100000000; # Maximum upload size in bytes

# MySQL database settings
$mysql_charset = 'utf8mb4';
