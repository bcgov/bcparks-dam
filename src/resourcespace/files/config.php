<?php
###############################
## ResourceSpace
## Local Configuration Script
###############################

# All custom settings should be entered in this file.
# Options may be copied from config.default.php and configured here.

$show_detailed_errors = true;

# Base URL of the installation
$baseurl = 'http://127.0.0.1'; # Fallback for cron job execution
# When running cli php scripts, HTTP_HOST is not set
if (isset($_SERVER['HTTP_HOST'])) {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
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
$homeanim_folder = 'filestore/system/slideshow_a383ab9e2f595db';
$php_path = '/opt/bitnami/php/bin';

$log_resource_access = false; # Log resource access
$log_search_performance = false; # Log search performance information
$log_php_errors = false; # Log php errors
$log_all_php_errors = false; # Including E_NOTICE and E_WARNING level errors, recommended for debugging only
$debug_log = false; # General debugging log
$debug_log_location = '/opt/bitnami/resourcespace/logs/debug.txt'; # Specify the log file path
$show_detailed_errors = false;

/*
New Installation Defaults
-------------------------
The following configuration options are set for new installations only.
This provides a mechanism for enabling new features for new installations without affecting existing installations (as would occur with changes to config.default.php)
*/

# Set imagemagick default for new installs to expect the newer version with the sRGB bug fixed.
$imagemagick_colorspace = 'sRGB';

$contact_link = false;

$slideshow_big = true;
$home_slideshow_width = 1920;
$home_slideshow_height = 1080;

$themes_simple_view = true;
$xlthumbs = true;
$image_preview_zoom = true;

$stemming = true;
$case_insensitive_username = true;
$user_pref_user_management_notifications = true;
$themes_show_background_image = true;

$use_zip_extension = true;
$collection_download = true;
$collection_download_tar_size = 0; # Set to 0 to disable TAR download and to force to use ZIP every time

$ffmpeg_preview_force = true;
$ffmpeg_preview_extension = 'mp4';
$ffmpeg_preview_options = '-f mp4 -b:v 1200k -b:a 64k -ac 1 -c:v libx264 -pix_fmt yuv420p -profile:v baseline -level 3 -c:a aac -strict -2';

$daterange_search = true;
$search_filter_nodes = true;

$upload_then_edit = true;
$enable_related_resources = true;
$relate_on_upload = true;
$relate_on_upload_default = true;
$offline_job_queue = true; # Use offline job queue to generate previews in the background for improved performance
$preview_generate_max_file_size = 5; # Immediately generate previews if the file size is <=5MB

$purge_temp_folder_age = 90;
$filestore_evenspread = true; # enable/disable even spread mode
$filestore_migrate = true; # allows existing, non-even spread file structure to be read
$originals_separate_storage = false; # separate storage of originals from previews

$comments_resource_enable = true;
$collection_commenting = true;

$check_upgrade_available = false;

$api_upload_urls = [];

$use_native_input_for_date_field = true;
