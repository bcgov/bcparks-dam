<?php


$lang["youtube_publish_title"]='Veröffentlichung auf YouTube';
$lang["youtube_publish_linktext"]='Veröffentlichen auf YouTube';
$lang["youtube_publish_configuration"]='Veröffentlichen auf YouTube - Einrichtung';
$lang["youtube_publish_notconfigured"]='"Das YouTube-Upload-Plugin ist nicht konfiguriert. Bitte fragen Sie Ihren Administrator, um das Plugin zu konfigurieren."';
$lang["youtube_publish_legal_warning"]='Durch Klicken auf "OK" bestätigen Sie, dass Sie alle Rechte an dem Inhalt besitzen oder dass Sie vom Eigentümer autorisiert sind, den Inhalt öffentlich auf YouTube verfügbar zu machen, und dass er ansonsten den YouTube-Nutzungsbedingungen entspricht, die unter http://www.youtube.com/t/terms zu finden sind.';
$lang["youtube_publish_resource_types_to_include"]='Wählen Sie gültige YouTube-Ressourcentypen aus';
$lang["youtube_publish_mappings_title"]='ResourceSpace - YouTube Feldzuordnungen';
$lang["youtube_publish_title_field"]='Titelfeld';
$lang["youtube_publish_descriptionfields"]='Beschreibungsfelder';
$lang["youtube_publish_keywords_fields"]='Tag-Felder';
$lang["youtube_publish_url_field"]='Metadatenfeld zur Speicherung der YouTube-URL';
$lang["youtube_publish_allow_multiple"]='Mehrere Uploads derselben Ressource zulassen?';
$lang["youtube_publish_log_share"]='Geteilt auf YouTube';
$lang["youtube_publish_unpublished"]='unveröffentlicht';
$lang["youtube_publishloggedinas"]='Sie werden auf das YouTube-Konto veröffentlichen: %youtube_username%';
$lang["youtube_publish_change_login"]='Verwenden Sie ein anderes YouTube-Konto';
$lang["youtube_publish_accessdenied"]='Sie haben keine Berechtigung, diese Ressource zu veröffentlichen';
$lang["youtube_publish_alreadypublished"]='Diese Ressource wurde bereits auf YouTube veröffentlicht.';
$lang["youtube_access_failed"]='Konnte nicht auf die YouTube Upload-Service-Schnittstelle zugreifen. Bitte kontaktieren Sie Ihren Administrator oder überprüfen Sie Ihre Konfiguration.';
$lang["youtube_publish_video_title"]='Videotitel';
$lang["youtube_publish_video_description"]='Videobeschreibung';
$lang["youtube_publish_video_tags"]='Video-Tags';
$lang["youtube_publish_access"]='Zugriff festlegen';
$lang["youtube_public"]='Öffentlich';
$lang["youtube_private"]='privat';
$lang["youtube_publish_public"]='Öffentlich';
$lang["youtube_publish_private"]='Privat';
$lang["youtube_publish_unlisted"]='Nicht aufgelistet';
$lang["youtube_publish_button_text"]='Veröffentlichen';
$lang["youtube_publish_authentication"]='Authentifizierung';
$lang["youtube_publish_use_oauth2"]='Möchten Sie OAuth 2.0 verwenden?';
$lang["youtube_publish_oauth2_advice"]='YouTube OAuth 2.0 Anweisungen';
$lang["youtube_publish_oauth2_advice_desc"]='<p>Um dieses Plugin einzurichten, müssen Sie OAuth 2.0 einrichten, da alle anderen Authentifizierungsmethoden offiziell veraltet sind. Dazu müssen Sie Ihre ResourceSpace-Website als Projekt bei Google registrieren und eine OAuth-Client-ID und ein Geheimnis erhalten. Es entstehen keine Kosten.</p><ul><li>Melden Sie sich bei Google an und gehen Sie zu Ihrem Dashboard: <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>.</li><li>Erstellen Sie ein neues Projekt (Name und ID sind nicht wichtig, sie dienen nur Ihrer Referenz).</li><li>Klicken Sie auf \'APIs und Dienste aktivieren\' und scrollen Sie zur Option \'YouTube Data API\'.</li><li>Klicken Sie auf \'Aktivieren\'.</li><li>Auf der linken Seite wählen Sie \'Anmeldedaten\' aus.</li><li>Klicken Sie dann auf \'Anmeldedaten erstellen\' und wählen Sie im Dropdown-Menü \'OAuth-Client-ID\' aus.</li><li>Sie werden dann auf die Seite \'OAuth-Client-ID erstellen\' weitergeleitet.</li><li>Um fortzufahren, müssen wir zuerst auf die blaue Schaltfläche \'Zustimmungsbildschirm konfigurieren\' klicken.</li><li>Füllen Sie die relevanten Informationen aus und speichern Sie sie.</li><li>Sie werden dann zur Seite \'OAuth-Client-ID erstellen\' zurückgeleitet.</li><li>Wählen Sie unter \'Anwendungstyp\' \'Webanwendung\' aus und füllen Sie unter \'Autorisierte JavaScript-URIs\' Ihre System-Basis-URL und unter \'Weiterleitungs-URI\' die Callback-URL ein, die oben auf dieser Seite angegeben ist, und klicken Sie auf \'Erstellen\'.</li><li>Sie werden dann mit einem Bildschirm präsentiert, der Ihre neu erstellte \'Client-ID\' und \'Client-Geheimnis\' anzeigt.</li><li>Notieren Sie sich die Client-ID und das Geheimnis und geben Sie diese Details unten ein.</li></ul>';
$lang["youtube_publish_developer_key"]='Entwickler-Schlüssel';
$lang["youtube_publish_oauth2_clientid"]='Kunden-ID';
$lang["youtube_publish_oauth2_clientsecret"]='Mandanten-Geheimnis';
$lang["youtube_publish_base"]='Basis-URL';
$lang["youtube_publish_callback_url"]='Rückruf-URL';
$lang["youtube_publish_username"]='YouTube Benutzername';
$lang["youtube_publish_password"]='YouTube-Passwort';
$lang["youtube_publish_existingurl"]='Vorhandene YouTube-URL:';
$lang["youtube_publish_notuploaded"]='Nicht hochgeladen';
$lang["youtube_publish_failedupload_error"]='Fehler beim Hochladen (Upload-Fehler)';
$lang["youtube_publish_success"]='Video erfolgreich veröffentlicht!';
$lang["youtube_publish_renewing_token"]='Erneuern des Zugriffstokens';
$lang["youtube_publish_category"]='Kategorie';
$lang["youtube_publish_category_error"]='Fehler beim Abrufen von YouTube-Kategorien: -';
$lang["youtube_chunk_size"]='Größe der Datenblöcke, die beim Hochladen auf YouTube verwendet werden sollen (MB)';
$lang["youtube_publish_add_anchor"]='Sollen Anker-Tags zur URL hinzugefügt werden, wenn sie im Feld "YouTube-URL-Metadaten" gespeichert wird?';
$lang["plugin-youtube_publish-title"]='YouTube Veröffentlichen';
$lang["plugin-youtube_publish-desc"]='Veröffentlicht Videoressource auf dem konfigurierten YouTube-Konto.';