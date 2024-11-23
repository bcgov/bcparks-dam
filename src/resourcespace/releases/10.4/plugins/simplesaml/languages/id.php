<?php


$lang["simplesaml_configuration"]='Konfigurasi SimpleSAML';
$lang["simplesaml_main_options"]='Opsi Penggunaan';
$lang["simplesaml_site_block"]='Gunakan SAML untuk memblokir akses ke situs secara keseluruhan, jika diatur ke benar maka tidak ada yang dapat mengakses situs, bahkan secara anonim, tanpa otentikasi';
$lang["simplesaml_allow_public_shares"]='Jika menghalangi situs, izinkan berbagi publik untuk melewati otentikasi SAML?';
$lang["simplesaml_allowedpaths"]='Daftar jalur tambahan yang diizinkan yang dapat melewati persyaratan SAML';
$lang["simplesaml_allow_standard_login"]='Izinkan pengguna untuk masuk dengan akun standar serta menggunakan SAML SSO? PERINGATAN: Menonaktifkan ini dapat mengakibatkan risiko mengunci semua pengguna dari sistem jika otentikasi SAML gagal';
$lang["simplesaml_use_sso"]='Gunakan SSO untuk masuk';
$lang["simplesaml_idp_configuration"]='Konfigurasi IdP';
$lang["simplesaml_idp_configuration_description"]='Gunakan yang berikut ini untuk mengkonfigurasi plugin agar dapat bekerja dengan IdP Anda';
$lang["simplesaml_username_attribute"]='Atribut yang digunakan untuk nama pengguna. Jika ini adalah penggabungan dari dua atribut, harap dipisahkan dengan koma';
$lang["simplesaml_username_separator"]='Jika menggabungkan kolom untuk nama pengguna, gunakan karakter ini sebagai pemisah';
$lang["simplesaml_fullname_attribute"]='Atribut yang digunakan untuk nama lengkap. Jika ini adalah penggabungan dari dua atribut, silakan pisahkan dengan koma';
$lang["simplesaml_fullname_separator"]='Jika menggabungkan kolom untuk nama lengkap, gunakan karakter ini sebagai pemisah';
$lang["simplesaml_email_attribute"]='Atribut yang digunakan untuk alamat email';
$lang["simplesaml_group_attribute"]='Atribut yang digunakan untuk menentukan keanggotaan grup';
$lang["simplesaml_username_suffix"]='Akhiran yang ditambahkan ke nama pengguna yang dibuat untuk membedakannya dari akun ResourceSpace standar';
$lang["simplesaml_update_group"]='Memperbarui grup pengguna setiap kali masuk. Jika tidak menggunakan atribut grup SSO untuk menentukan akses, maka atur ini menjadi salah sehingga pengguna dapat dipindahkan antar grup secara manual';
$lang["simplesaml_groupmapping"]='Pemetaan Grup ResourceSpace - SAML';
$lang["simplesaml_fallback_group"]='Grup pengguna default yang akan digunakan untuk pengguna yang baru dibuat';
$lang["simplesaml_samlgroup"]='Grup SAML';
$lang["simplesaml_rsgroup"]='Grup ResourceSpace';
$lang["simplesaml_priority"]='Prioritas (nomor yang lebih tinggi akan diutamakan)';
$lang["simplesaml_service_provider"]='Nama penyedia layanan lokal (SP)';
$lang["simplesaml_prefer_standard_login"]='Lebih suka login standar (dialihkan ke halaman login secara default)';
$lang["simplesaml_sp_configuration"]='Konfigurasi simplesaml SP harus diselesaikan untuk menggunakan plugin ini. Silakan lihat artikel Basis Pengetahuan untuk informasi lebih lanjut';
$lang["simplesaml_custom_attributes"]='Atribut kustom untuk dicatat pada catatan pengguna';
$lang["simplesaml_custom_attribute_label"]='Atribut SSO -';
$lang["simplesaml_usercomment"]='Dibuat oleh plugin SimpleSAML';
$lang["origin_simplesaml"]='Plugin SimpleSAML dalam Bahasa Indonesia dapat diterjemahkan menjadi "Plugin SimpleSAML"';
$lang["simplesaml_lib_path_label"]='Jalur pustaka SAML (harap tentukan jalur server lengkap)';
$lang["simplesaml_login"]='Gunakan kredensial SAML untuk masuk ke ResourceSpace? (Ini hanya relevan jika opsi di atas diaktifkan)';
$lang["simplesaml_create_new_match_email"]='Cocokan Email: Sebelum membuat pengguna baru, periksa apakah email pengguna SAML cocok dengan email akun RS yang sudah ada. Jika ada yang cocok, pengguna SAML akan \'mengambil alih\' akun tersebut';
$lang["simplesaml_allow_duplicate_email"]='Izinkan pembuatan akun baru jika ada akun ResourceSpace yang sudah ada dengan alamat email yang sama? (ini akan digantikan jika email-match diatur di atas dan satu kecocokan ditemukan)';
$lang["simplesaml_multiple_email_match_subject"]='ResourceSpace SAML - upaya login email yang bertentangan';
$lang["simplesaml_multiple_email_match_text"]='Pengguna SAML baru telah mengakses sistem tetapi sudah ada lebih dari satu akun dengan alamat email yang sama.';
$lang["simplesaml_multiple_email_notify"]='Alamat email untuk memberitahu jika terjadi konflik email';
$lang["simplesaml_duplicate_email_error"]='Ada akun yang sudah ada dengan alamat email yang sama. Silakan hubungi administrator Anda.';
$lang["simplesaml_usermatchcomment"]='Diperbarui menjadi pengguna SAML oleh plugin SimpleSAML.';
$lang["simplesaml_usercreated"]='Membuat pengguna SAML baru';
$lang["simplesaml_duplicate_email_behaviour"]='Manajemen akun duplikat';
$lang["simplesaml_duplicate_email_behaviour_description"]='Bagian ini mengontrol apa yang terjadi jika pengguna SAML baru yang masuk login bertentangan dengan akun yang sudah ada';
$lang["simplesaml_authorisation_rules_header"]='Aturan otorisasi';
$lang["simplesaml_authorisation_rules_description"]='Mengaktifkan ResourceSpace agar dapat dikonfigurasi dengan otorisasi lokal tambahan pengguna berdasarkan atribut tambahan (yaitu, asertasi/klaim) dalam respons dari IdP. Asertasi ini akan digunakan oleh plugin untuk menentukan apakah pengguna diizinkan untuk masuk ke ResourceSpace atau tidak.';
$lang["simplesaml_authorisation_claim_name_label"]='Nama atribut (pernyataan/klaim)';
$lang["simplesaml_authorisation_claim_value_label"]='Nilai Atribut (pernyataan/klaim)';
$lang["simplesaml_authorisation_login_error"]='Anda tidak memiliki akses ke aplikasi ini! Silakan hubungi administrator untuk akun Anda!';
$lang["simplesaml_authorisation_version_error"]='PENTING: Konfigurasi SimpleSAML Anda perlu diperbarui. Silakan lihat bagian \'<a href=\'https://www.resourcespace.com/knowledge-base/plugins/simplesaml#saml_instructions_migrate\' target=\'_blank\'>Migrasi SP untuk menggunakan konfigurasi ResourceSpace</a>\' di Basis Pengetahuan untuk informasi lebih lanjut';
$lang["simplesaml_healthcheck_error"]='Kesalahan plugin SimpleSAML';
$lang["simplesaml_rsconfig"]='Gunakan file konfigurasi standar ResourceSpace untuk mengatur konfigurasi SP dan metadata? Jika ini diatur ke false maka pengeditan manual file diperlukan';
$lang["simplesaml_sp_generate_config"]='Membuat konfigurasi SP';
$lang["simplesaml_sp_config"]='Konfigurasi Penyedia Layanan (SP)';
$lang["simplesaml_sp_data"]='Informasi Penyedia Layanan (SP)';
$lang["simplesaml_idp_section"]='IdP stands for "Penyedia Identitas" in Bahasa Indonesia';
$lang["simplesaml_idp_metadata_xml"]='Tempelkan XML Metadata IdP';
$lang["simplesaml_sp_cert_path"]='Jalur ke berkas sertifikat SP (kosongkan untuk menghasilkan tetapi isi detail sertifikat di bawah)';
$lang["simplesaml_sp_key_path"]='Jalur ke berkas kunci SP (.pem) (kosongkan untuk menghasilkan)';
$lang["simplesaml_sp_idp"]='Identifier IdP (kosongkan jika memproses XML)';
$lang["simplesaml_saml_config_output"]='Tempelkan ini ke dalam file konfigurasi ResourceSpace Anda';
$lang["simplesaml_sp_cert_info"]='Informasi sertifikat (wajib)';
$lang["simplesaml_sp_cert_countryname"]='Kode Negara (hanya 2 karakter)';
$lang["simplesaml_sp_cert_stateorprovincename"]='Nama negara bagian, kabupaten atau provinsi';
$lang["simplesaml_sp_cert_localityname"]='Lokalitas (misalnya kota)';
$lang["simplesaml_sp_cert_organizationname"]='Nama organisasi';
$lang["simplesaml_sp_cert_organizationalunitname"]='Unit organisasi / departemen';
$lang["simplesaml_sp_cert_commonname"]='Nama umum (misalnya sp.acme.org)';
$lang["simplesaml_sp_cert_emailaddress"]='Alamat email';
$lang["simplesaml_sp_cert_invalid"]='Informasi sertifikat tidak valid';
$lang["simplesaml_sp_cert_gen_error"]='Tidak dapat menghasilkan sertifikat';
$lang["simplesaml_sp_samlphp_link"]='Kunjungi situs uji coba SimpleSAMLphp';
$lang["simplesaml_sp_technicalcontact_name"]='Nama kontak teknis';
$lang["simplesaml_sp_technicalcontact_email"]='Email kontak teknis';
$lang["simplesaml_sp_auth.adminpassword"]='Kata sandi administrator situs uji SP';
$lang["simplesaml_acs_url"]='URL ACS / URL Balasan';
$lang["simplesaml_entity_id"]='ID Entitas/URL metadata';
$lang["simplesaml_single_logout_url"]='URL logout tunggal';
$lang["simplesaml_start_url"]='Mulai/Tautan Masuk (URL)';
$lang["simplesaml_existing_config"]='Ikuti instruksi Basis Pengetahuan untuk memigrasi konfigurasi SAML yang sudah ada';
$lang["simplesaml_test_site_url"]='URL situs uji coba SimpleSAML';
$lang["simplesaml_addrow"]='Tambahkan pemetaan';
$lang["plugin-simplesaml-title"]='SAML Sederhana';
$lang["plugin-simplesaml-desc"]='[Advanced] Memerlukan autentikasi SAML untuk mengakses ResourceSpace';