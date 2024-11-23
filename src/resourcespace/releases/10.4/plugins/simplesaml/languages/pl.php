<?php


$lang["simplesaml_configuration"]='Konfiguracja SimpleSAML';
$lang["simplesaml_main_options"]='Opcje użytkowania';
$lang["simplesaml_site_block"]='Użyj SAML do zablokowania całkowitego dostępu do witryny. Jeśli ustawione na true, to nikt nie będzie mógł uzyskać dostępu do witryny, nawet anonimowo, bez uwierzytelnienia';
$lang["simplesaml_allow_public_shares"]='Jeśli blokowanie witryny, zezwól na pomijanie uwierzytelniania SAML dla udostępniania publicznego';
$lang["simplesaml_allowedpaths"]='Lista dodatkowych dozwolonych ścieżek, które mogą ominąć wymaganie SAML';
$lang["simplesaml_allow_standard_login"]='Zezwól użytkownikom na logowanie się za pomocą standardowych kont oraz poprzez SAML SSO. UWAGA: Wyłączenie tej opcji może spowodować ryzyko zablokowania dostępu wszystkich użytkowników do systemu, jeśli autentykacja SAML zawiedzie';
$lang["simplesaml_use_sso"]='Użyj SSO do zalogowania się';
$lang["simplesaml_idp_configuration"]='Konfiguracja IdP (Identyfikatora dostawcy tożsamości)';
$lang["simplesaml_idp_configuration_description"]='Użyj poniższego, aby skonfigurować wtyczkę do pracy z Twoim IdP';
$lang["simplesaml_username_attribute"]='Atrybut(y) do użycia jako nazwa użytkownika. Jeśli jest to konkatenacja dwóch atrybutów, proszę oddzielić je przecinkiem';
$lang["simplesaml_username_separator"]='Jeśli łączysz pola dla nazwy użytkownika, użyj tego znaku jako separatora';
$lang["simplesaml_fullname_attribute"]='Atrybut(y) do użycia dla pełnej nazwy. Jeśli jest to konkatenacja dwóch atrybutów, proszę oddzielić je przecinkiem';
$lang["simplesaml_fullname_separator"]='Jeśli łączysz pola dla pełnej nazwy, użyj tego znaku jako separatora';
$lang["simplesaml_email_attribute"]='Atrybut do użycia dla adresu e-mail';
$lang["simplesaml_group_attribute"]='Atrybut do wykorzystania w celu określenia przynależności do grupy';
$lang["simplesaml_username_suffix"]='Przyrostek dodawany do nazw użytkowników utworzonych w celu odróżnienia ich od standardowych kont w ResourceSpace';
$lang["simplesaml_update_group"]='Aktualizuj grupę użytkowników przy każdym logowaniu. Jeśli nie korzystasz z atrybutu grupy SSO do określenia dostępu, ustaw to na false, aby użytkownicy mogli być ręcznie przenoszeni między grupami';
$lang["simplesaml_groupmapping"]='SAML - Mapowanie grup w ResourceSpace';
$lang["simplesaml_fallback_group"]='Domyślna grupa użytkowników, która będzie używana dla nowo utworzonych użytkowników';
$lang["simplesaml_samlgroup"]='Grupa SAML';
$lang["simplesaml_rsgroup"]='Grupa w ResourceSpace';
$lang["simplesaml_priority"]='Priorytet (wyższa liczba będzie miała pierwszeństwo)';
$lang["simplesaml_addrow"]='Dodaj mapowanie';
$lang["simplesaml_service_provider"]='Nazwa lokalnego dostawcy usług (SP)';
$lang["simplesaml_prefer_standard_login"]='Preferuj standardowe logowanie (domyślnie przekieruj do strony logowania)';
$lang["simplesaml_sp_configuration"]='Konfiguracja simplesaml SP musi zostać ukończona, aby użyć tego pluginu. Proszę zapoznać się z artykułem w bazie wiedzy, aby uzyskać więcej informacji';
$lang["simplesaml_custom_attributes"]='Niestandardowe atrybuty do zapisania w rekordzie użytkownika';
$lang["simplesaml_custom_attribute_label"]='Atrybut SSO (Single Sign-On) -';
$lang["simplesaml_usercomment"]='Utworzone przez wtyczkę SimpleSAML';
$lang["origin_simplesaml"]='Wtyczka SimpleSAML';
$lang["simplesaml_lib_path_label"]='Ścieżka biblioteki SAML (proszę podać pełną ścieżkę serwera)';
$lang["simplesaml_login"]='Czy chcesz zalogować się do ResourceSpace przy użyciu poświadczeń SAML? (Opcja ta jest dostępna tylko wtedy, gdy powyższa opcja jest włączona)';
$lang["simplesaml_create_new_match_email"]='Porównanie adresów e-mail: Przed utworzeniem nowych użytkowników, sprawdź, czy adres e-mail użytkownika SAML odpowiada istniejącemu adresowi e-mail konta RS. Jeśli zostanie znalezione dopasowanie, użytkownik SAML "przejmuje" to konto';
$lang["simplesaml_allow_duplicate_email"]='Czy zezwolić na tworzenie nowych kont, jeśli istnieją już konta w ResourceSpace z tym samym adresem e-mail? (to zostanie zignorowane, jeśli powyżej ustawiono dopasowanie adresów e-mail i zostanie znalezione jedno dopasowanie)';
$lang["simplesaml_multiple_email_match_subject"]='ResourceSpace SAML - próba logowania z konfliktującym adresem e-mail';
$lang["simplesaml_multiple_email_match_text"]='Nowy użytkownik SAML uzyskał dostęp do systemu, ale istnieje już więcej niż jedno konto z tym samym adresem e-mail.';
$lang["simplesaml_multiple_email_notify"]='Adres e-mail do powiadomienia w przypadku wykrycia konfliktu e-maili';
$lang["simplesaml_duplicate_email_error"]='Istnieje już konto z tym samym adresem e-mail. Skontaktuj się z administratorem.';
$lang["simplesaml_usermatchcomment"]='Zaktualizowano użytkownika do SAML za pomocą wtyczki SimpleSAML.';
$lang["simplesaml_usercreated"]='Utworzono nowego użytkownika SAML';
$lang["simplesaml_duplicate_email_behaviour"]='Zarządzanie zduplikowanymi kontami';
$lang["simplesaml_duplicate_email_behaviour_description"]='Ta sekcja kontroluje, co się dzieje, gdy nowy użytkownik SAML loguje się i występuje konflikt z istniejącym kontem';
$lang["simplesaml_authorisation_rules_header"]='Reguła autoryzacji';
$lang["simplesaml_authorisation_rules_description"]='Umożliwiaj konfigurację ResourceSpace z dodatkową lokalną autoryzacją użytkowników na podstawie dodatkowego atrybutu (np. twierdzenia/wniosku) w odpowiedzi z IdP. To twierdzenie będzie używane przez wtyczkę do określenia, czy użytkownik ma pozwolenie na zalogowanie się do ResourceSpace, czy nie.';
$lang["simplesaml_authorisation_claim_name_label"]='Nazwa atrybutu (twierdzenie/ roszczenie)';
$lang["simplesaml_authorisation_claim_value_label"]='Wartość atrybutu (twierdzenie/ roszczenie)';
$lang["simplesaml_authorisation_login_error"]='Nie masz dostępu do tej aplikacji! Skontaktuj się z administratorem swojego konta!';
$lang["simplesaml_authorisation_version_error"]='WAŻNE: Konfiguracja SimpleSAML musi zostać zaktualizowana. Prosimy o odwołanie się do sekcji "<a href=\'https://www.resourcespace.com/knowledge-base/plugins/simplesaml#saml_instructions_migrate\' target=\'_blank\'>Migracja SP do konfiguracji ResourceSpace</a>" w bazie wiedzy, aby uzyskać więcej informacji';
$lang["simplesaml_healthcheck_error"]='Błąd wtyczki SimpleSAML';
$lang["simplesaml_rsconfig"]='Użyj standardowych plików konfiguracyjnych ResourceSpace do ustawienia konfiguracji SP i metadanych? Jeśli ustawione na false, konieczne będzie ręczne edytowanie plików';
$lang["simplesaml_sp_generate_config"]='Wygeneruj konfigurację SP';
$lang["simplesaml_sp_config"]='Konfiguracja dostawcy usług (SP)';
$lang["simplesaml_sp_data"]='Informacje o dostawcy usług (SP)';
$lang["simplesaml_idp_section"]='IdP - dostawca tożsamości (Identity Provider)';
$lang["simplesaml_idp_metadata_xml"]='Wklej metadane IdP w formacie XML';
$lang["simplesaml_sp_cert_path"]='Ścieżka do pliku certyfikatu SP (pozostaw puste, aby wygenerować, ale wypełnij szczegóły certyfikatu poniżej)';
$lang["simplesaml_sp_key_path"]='Ścieżka do pliku klucza SP (.pem) (pozostaw puste, aby wygenerować)';
$lang["simplesaml_sp_idp"]='Identyfikator IdP (zostaw puste, jeśli przetwarzasz XML)';
$lang["simplesaml_saml_config_output"]='Wklej to do pliku konfiguracyjnego ResourceSpace';
$lang["simplesaml_sp_cert_info"]='Informacje o certyfikacie (wymagane)';
$lang["simplesaml_sp_cert_countryname"]='Kod kraju (tylko 2 znaki)';
$lang["simplesaml_sp_cert_stateorprovincename"]='Nazwa stanu, hrabstwa lub prowincji';
$lang["simplesaml_sp_cert_localityname"]='Lokalizacja (np. miasto)';
$lang["simplesaml_sp_cert_organizationname"]='Nazwa organizacji';
$lang["simplesaml_sp_cert_organizationalunitname"]='Jednostka organizacyjna / dział';
$lang["simplesaml_sp_cert_commonname"]='Nazwa powszechna (np. sp.acme.org)';
$lang["simplesaml_sp_cert_emailaddress"]='Adres e-mail';
$lang["simplesaml_sp_cert_invalid"]='Nieprawidłowe informacje o certyfikacie';
$lang["simplesaml_sp_cert_gen_error"]='Nie można wygenerować certyfikatu';
$lang["simplesaml_sp_samlphp_link"]='Odwiedź stronę testową SimpleSAMLphp';
$lang["simplesaml_sp_technicalcontact_name"]='Nazwa technicznego kontaktu';
$lang["simplesaml_sp_technicalcontact_email"]='Techniczny adres e-mail kontaktowy';
$lang["simplesaml_sp_auth.adminpassword"]='Hasło administratora strony testowej SP';
$lang["simplesaml_acs_url"]='Adres URL ACS / Adres URL odpowiedzi';
$lang["simplesaml_entity_id"]='ID jednostki/URL metadanych';
$lang["simplesaml_single_logout_url"]='Adres URL pojedynczego wylogowania';
$lang["simplesaml_start_url"]='Rozpocznij/Zaloguj się na adres URL';
$lang["simplesaml_existing_config"]='Postępuj zgodnie z instrukcjami bazy wiedzy, aby przenieść istniejącą konfigurację SAML';
$lang["simplesaml_test_site_url"]='Adres URL strony testowej SimpleSAML';
$lang["plugin-simplesaml-title"]='Prosty SAML';
$lang["plugin-simplesaml-desc"]='[Zaawansowane] Wymagaj uwierzytelniania SAML, aby uzyskać dostęp do ResourceSpace';