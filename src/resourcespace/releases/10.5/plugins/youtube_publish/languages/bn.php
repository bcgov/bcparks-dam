<?php


$lang["youtube_publish_title"]='ইউটিউব প্রকাশনা';
$lang["youtube_publish_linktext"]='ইউটিউবে প্রকাশ করুন';
$lang["youtube_publish_configuration"]='ইউটিউবে প্রকাশ করুন - সেটআপ';
$lang["youtube_publish_notconfigured"]='ইউটিউব আপলোড প্লাগইন কনফিগার করা হয়নি। অনুগ্রহ করে আপনার প্রশাসককে প্লাগইনটি কনফিগার করতে বলুন';
$lang["youtube_publish_legal_warning"]='\'ঠিক আছে\' ক্লিক করে আপনি নিশ্চিত করছেন যে আপনি কন্টেন্টের সমস্ত অধিকার রাখেন বা আপনি মালিকের দ্বারা অনুমোদিত হয়েছেন কন্টেন্টটি YouTube-এ সর্বসাধারণের জন্য উপলব্ধ করতে, এবং এটি অন্যথায় YouTube পরিষেবার শর্তাবলীর সাথে সঙ্গতিপূর্ণ যা http://www.youtube.com/t/terms এ অবস্থিত।';
$lang["youtube_publish_resource_types_to_include"]='বৈধ ইউটিউব রিসোর্স প্রকার নির্বাচন করুন';
$lang["youtube_publish_mappings_title"]='ResourceSpace - YouTube ক্ষেত্রের ম্যাপিংসমূহ';
$lang["youtube_publish_title_field"]='শিরোনাম ক্ষেত্র';
$lang["youtube_publish_descriptionfields"]='বর্ণনা ক্ষেত্রসমূহ';
$lang["youtube_publish_keywords_fields"]='ট্যাগ ক্ষেত্রসমূহ';
$lang["youtube_publish_url_field"]='ইউটিউব URL সংরক্ষণের জন্য মেটাডেটা ক্ষেত্র';
$lang["youtube_publish_allow_multiple"]='একই রিসোর্সের একাধিক আপলোডের অনুমতি দিন?';
$lang["youtube_publish_log_share"]='ইউটিউবে শেয়ার করা হয়েছে';
$lang["youtube_publish_unpublished"]='অপ্রকাশিত';
$lang["youtube_publishloggedinas"]='আপনি YouTube অ্যাকাউন্টে প্রকাশ করবেন: %youtube_username%';
$lang["youtube_publish_change_login"]='একটি ভিন্ন ইউটিউব অ্যাকাউন্ট ব্যবহার করুন';
$lang["youtube_publish_accessdenied"]='আপনার এই রিসোর্সটি প্রকাশ করার অনুমতি নেই';
$lang["youtube_publish_alreadypublished"]='এই রিসোর্সটি ইতিমধ্যে ইউটিউবে প্রকাশিত হয়েছে।';
$lang["youtube_access_failed"]='YouTube আপলোড সার্ভিস ইন্টারফেসে প্রবেশ করতে ব্যর্থ হয়েছে। অনুগ্রহ করে আপনার প্রশাসকের সাথে যোগাযোগ করুন বা আপনার কনফিগারেশন পরীক্ষা করুন।';
$lang["youtube_publish_video_title"]='ভিডিও শিরোনাম';
$lang["youtube_publish_video_description"]='ভিডিও বিবরণ';
$lang["youtube_publish_video_tags"]='ভিডিও ট্যাগসমূহ';
$lang["youtube_publish_access"]='অ্যাক্সেস নির্ধারণ করুন';
$lang["youtube_public"]='পাবলিক';
$lang["youtube_private"]='ব্যক্তিগত';
$lang["youtube_publish_public"]='পাবলিক';
$lang["youtube_publish_private"]='ব্যক্তিগত';
$lang["youtube_publish_unlisted"]='অতালিকাভুক্ত';
$lang["youtube_publish_button_text"]='প্রকাশ করুন';
$lang["youtube_publish_authentication"]='প্রমাণীকরণ';
$lang["youtube_publish_use_oauth2"]='OAuth 2.0 ব্যবহার করবেন?';
$lang["youtube_publish_oauth2_advice"]='YouTube OAuth 2.0 নির্দেশাবলী';
$lang["youtube_publish_oauth2_advice_desc"]='<p>এই প্লাগইন সেটআপ করতে আপনাকে OAuth 2.0 সেটআপ করতে হবে কারণ অন্যান্য সমস্ত প্রমাণীকরণ পদ্ধতি আনুষ্ঠানিকভাবে অপ্রচলিত। এর জন্য আপনাকে আপনার ResourceSpace সাইটকে Google এর সাথে একটি প্রকল্প হিসেবে নিবন্ধন করতে হবে এবং একটি OAuth ক্লায়েন্ট আইডি এবং সিক্রেট পেতে হবে। এর জন্য কোনো খরচ নেই।</p><ul><li>Google এ লগইন করুন এবং আপনার ড্যাশবোর্ডে যান: <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>।</li><li>একটি নতুন প্রকল্প তৈরি করুন (নাম এবং আইডি গুরুত্বপূর্ণ নয়, এগুলি আপনার রেফারেন্সের জন্য)।</li><li>\'ENABLE API\'S AND SERVICES\' এ ক্লিক করুন এবং ‘YouTube Data API\' অপশনে স্ক্রোল করুন।</li><li>\'Enable\' এ ক্লিক করুন।</li><li>বাম দিকে \'Credentials\' নির্বাচন করুন।</li><li>তারপর \'CREATE CREDENTIALS\' এ ক্লিক করুন এবং ড্রপ ডাউন মেনুতে \'Oauth client ID\' নির্বাচন করুন।</li><li>এরপর আপনাকে \'Create OAuth client ID\' পৃষ্ঠায় নিয়ে যাওয়া হবে।</li><li>চালিয়ে যেতে আমাদের প্রথমে নীল বোতাম \'Configure consent screen\' এ ক্লিক করতে হবে।</li><li>প্রাসঙ্গিক তথ্য পূরণ করুন এবং সংরক্ষণ করুন।</li><li>এরপর আপনাকে \'Create OAuth client ID\' পৃষ্ঠায় পুনঃনির্দেশিত করা হবে।</li><li>\'Application type\' এর অধীনে \'Web application\' নির্বাচন করুন এবং \'Authorized Javascript origins\' এ আপনার সিস্টেমের বেস URL এবং রিডাইরেক্ট URL এ এই পৃষ্ঠার শীর্ষে নির্দিষ্ট কলব্যাক URL পূরণ করুন এবং \'Create\' এ ক্লিক করুন।</li><li>এরপর আপনাকে আপনার সদ্য তৈরি \'client ID\' এবং \'client secret\' দেখানো হবে।</li><li>ক্লায়েন্ট আইডি এবং সিক্রেট নোট করুন তারপর নিচে এই বিবরণগুলি প্রবেশ করান।</li></ul>';
$lang["youtube_publish_developer_key"]='ডেভেলপার কী';
$lang["youtube_publish_oauth2_clientid"]='ক্লায়েন্ট আইডি';
$lang["youtube_publish_oauth2_clientsecret"]='ক্লায়েন্ট সিক্রেট';
$lang["youtube_publish_base"]='বেস URL';
$lang["youtube_publish_callback_url"]='কলব্যাক URL';
$lang["youtube_publish_username"]='ইউটিউব ব্যবহারকারীর নাম';
$lang["youtube_publish_password"]='ইউটিউব পাসওয়ার্ড';
$lang["youtube_publish_existingurl"]='বিদ্যমান ইউটিউব URL :-';
$lang["youtube_publish_notuploaded"]='আপলোড করা হয়নি';
$lang["youtube_publish_failedupload_error"]='আপলোড ত্রুটি';
$lang["youtube_publish_success"]='ভিডিও সফলভাবে প্রকাশিত হয়েছে!';
$lang["youtube_publish_renewing_token"]='অ্যাক্সেস টোকেন নবায়ন করা হচ্ছে';
$lang["youtube_publish_category"]='বিভাগ';
$lang["youtube_publish_category_error"]='ইউটিউব বিভাগগুলি পুনরুদ্ধারে ত্রুটি: -';
$lang["youtube_chunk_size"]='ইউটিউবে আপলোড করার সময় ব্যবহৃত চাঙ্ক সাইজ (এমবি)';
$lang["youtube_publish_add_anchor"]='ইউটিউব URL মেটাডেটা ফিল্ডে সংরক্ষণ করার সময় URL-এ অ্যাঙ্কর ট্যাগ যোগ করবেন?';