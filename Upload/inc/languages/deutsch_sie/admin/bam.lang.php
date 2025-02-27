<?php
		/*    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    Übersetzung für BAM+/2.0 Announcements Manager -Darth Apple <http://www.makestation.net/>:
    Deutsch_sie von Tc4me <htpps://autism4all.at/>
--------------------------------------------------------------------------------*/

$l['bam_title'] = "Board-Ankündigungs-Manager";
$l['bam_title_acronym'] = "Board-Ankündigungs-Manager";
$l['bam_desc'] = "Erlaubt das erstellen von Ankündigungen unter dem Forum Header.";
$l['bam_announcements_menu'] = "Board-Ankündigungs-Manager";
$l['bam_enabled'] = "Ankündigungs-Manager aktivieren/deaktivieren?";
$l['bam_enabled_desc'] = "Hier kann der Bam-Ankündigungsmanager eingeschaltet/ausgeschaltet werden ohne das Plugin zu deinstallieren.";
$l['bam_random'] = "Zufallsmodus";
$l['bam_random_desc'] = "Wenn diese Einstellung aktiv ist, wird BAM alle verfügbaren Ankündigungen in zufälliger Reihenfolge anzeigen. Diese Einstellung ist standardmäßig deaktiviert.";
$l['bam_random_max'] = "Zufallsergebnisse die erzeugt werden";
$l['bam_random_max_desc'] = "Maximale Anzahl der Ankündigungen die zufällig angezeigt werden sollen. Diese Einstellung bewirkt nichts wenn der Zufallsmodus deaktiviert ist.";
$l['bam_random_group'] = "Benutzergruppeneinstellung für den Zufallsmodus";
$l['bam_random_group_desc'] = "Konfurigation des Zufallsmodus für Benutzergruppen. Diese Einstellung kann, wenn bevorzugt auch beim erstellen einer Ankündigung vorgenommen werden.";
$l['bam_global'] = "Ankündigungen global anzeigen?";
$l['bam_global_desc'] = "Diese Einstellung ermöglicht es, die Sichtbarkeit der Ankündigung festzulegen. Standardmäßig werden Ankündigungen nur auf der Indexseite angezeigt.";
$l['bam_global_disable'] = "Deaktivieren";
$l['bam_global_pinned'] = "Nur angepinnte Ankündigungen";
$l['bam_global_all'] = "Alle Ankündigungen";
$l['bam_index_page'] = "Benutzerdefinierte Index Seite (für fortgeschrittene Benutzer)";
$l['bam_index_page_desc'] = "Benutzerfefinierte Seite angeben die als die \"Index-Seite\" für nicht globale Bekanntmachungen genutzt wird. Standardmäßig sollte index.php eingestellt sein. Änderungen bitte nur bei umbenannter index.php vornehmen. Oder wenn Ankündigungen z.B. nur auf der Portal Seite angezeigt werden sollen.";
$l['bam_custom_css'] = "Benutzerdefiniertes CSS";
$l['bam_custom_css_desc'] = "Benutzerdefiniertes CSS hier einfügen.";
$l['bam_welcome'] = "[b]BAM wurde erfolgreich installiert![/b] Ankündigungen können nun im ACP verwaltet werden.";
$l['bam_date_enable'] = "Veröffentlichungsdatum anzeigen?";
$l['bam_date_enable_desc'] = "Wenn eingeschaltet wird das Veröffentlichungsdatum in der Ankündigung angezeigt.";
$l['bam_settings_link'] = "Klicken Sie hier, um die allgemeinen Plugin-Einstellungen von BAM anzuzeigen";
$l['bam_slidedown_enable'] = "Slidedown-Effekt aktivieren?";
$l['bam_slidedown_enable_desc'] = "Wenn diese Option aktiviert ist, werden Ankündigungen beim Laden einer neuen Seite nach unten verschoben. Beachten Sie, dass diese Einstellung dem Browser Zeit gibt, gelöschte Ankündigungen zu entfernen, bevor sie angezeigt werden. Wenn diese Einstellung deaktiviert ist, blinken diese Ansagen möglicherweise kurz auf dem Bildschirm.";
// 2.0

$l['bam_round_announcements'] = "Verwenden Sie weiche Ränder für Ankündigungen?";
$l['bam_round_announcements_desc'] = "Weiche Ränder verleihen der Ankündigung ein abgerundetes Erscheinungsbild. Ist es deaktiviert, verwendet die BAM den selben Stil wie im MyBB-Supportforum. ";

$l['bam_enable_dismissal'] = "Ankündigung gelesen: ";
$l['bam_enable_dismissal_desc'] = "Legen Sie fest, wie BAM mit gelesenen Ankündigungen umgehen soll. Wenn \"Nur schließen\" eingestellt ist, werden Ankündigungen immer wieder angezeigt. \"Schließen und beenden\" verhindert, dass die Ansage erneut angezeigt wird. ";
$l['bam_dismissal_days'] = "Nach wieviel Tagen läuft Ankündigung ab ?: ";
$l['bam_dismissal_days_desc'] = "Legt fest, wie viele Tage eine BAM-Ankündigung angezeigt werden soll. Der Standardwert ist 30 Tage.";
$l['bam_random_dismissal'] = "Aktivieren, dass Ankündigungen im Zufallsmodus geschlossen werden?";
$l['bam_random_dismissal_desc'] =  "Im Zufallsmodus werden Ankündigungsbeendigung anders behandelt als im Standardmodus. Wenn eine Ankündigung beendet wird, wird sie einfach von der Seite entfernt und eine neue Ankündigung wird auf der nächsten Seite geladen. Diese Einstellung definiert, ob dieses Verhalten aktiviert werden soll oder ob beim Ablaufen die Ankündigung vollständig deaktiviert werden sollen. <b> Diese Einstellung wirkt sich nur auf Ankündigungen aus, die im Zufallsmodus erstellt werden. Standardankündigungen folgen den normalen Einstellungen. </b>";
$l['bam_manage_popupmenu'] = "Verwalten";
$l['bam_cookie_id_prefix'] = "Ankündigungs-ID-Präfix für Cookies / Zurückweisen: ";
$l['bam_cookie_id_prefix_desc'] = "Ändern Sie diesen Wert (in einen beliebigen numerischen Wert), wenn Sie alle beendeten Ankündigungen löschen / zurücksetzen müssen. Dieser Wert wird automatisch erstellt, wenn BAM installiert wird.";
$l['bam_dismissal_disable'] = "Deaktivieren Sie Ankündigung löschen";
$l['bam_dismissal_closeonly'] = "Ankündigungen nur schließen";
$l['bam_dismissal_savecookie'] = "Schließen und nicht mehr anzeigen ";
$l['bam_dismissal_savecookie_useronly'] = "Schließen und nicht mehr anzeigen, gilt nur für registrierte Benutzer ";
$l['bam_add_announcement'] = "Ankündigung hinzufügen";
$l['bam_edit_announcement'] = "Ankündigung bearbeiten";
$l['bam_manage_announcements'] = "Ankündigung verwalten";

$l['bam_manage'] = "Verwalten";
$l['bam_manage_random'] = "Zufallsmodus";
$l['bam_manage_random_desc'] = "<b>Die BAM wählt zufällig eine Ankündigung aus dieser Liste aus, die im Forenindex angezeigt werden soll. </ B> Diese können hier verwaltet werden! <br /><br />";
$l['bam_manage_random_desc'] .= " - Um dieser Liste Ankündigungen hinzuzufügen, wählen Sie beim Hinzufügen einer neuen Ankündigung \"Zufällige Ankündigung\". <br />";
$l['bam_manage_random_desc'] .= " - Die BAM hat einige spezielle Konfigurationseinstellungen. Diese Optionen finden Sie in den Plugin-Einstellungen der BAM.<br />";
$l['bam_manage_random_desc'] .= " - Diese Ankündigungen werden unterhalb der Standardankündigung angezeigt. <br /> ";
$l['bam_manage_random_desc'] .= " - Verwenden Sie diese Funktion, wenn Ankündigungen bei jedem Seitenbesuch automatisch aktualisiert werden sollen.";
$l['bam_manage_random_desc'] .= "<br /><br /><i>Ankündigung wird nur im Forenindex anzeigt.</i>";

$l['bam_manage_desc_norandom'] = "Hier können Sie Ihre Ankündigungen verwalten, bearbeiten, löschen und neu anordnen. Es verwaltet nur Standardansagen. Wenn Ankündigungen in Ihrem Forum-Index zufällig ausgewählt werden sollen, aktivieren Sie den <i> Zufallsmodus </ i> in den Plugin-Einstellungen von BAM.";
$l['bam_manage_desc'] = "Hier können Sie Ihre Ankündigungen verwalten, bearbeiten, löschen und neu anordnen. Diese Anzeigen sind statische Standardankündigungen. Sie werden immer angezeigt. Siehe <i> Zufallsmodus </ i> für die automatische Aktualisierung von Ankündigungen.";
$l['bam_edit'] = "Bearbeiten";
$l['bam_edit_desc'] = "Hier können Sie eine vorhandene Ankündigung bearbeiten. ";
$l['bam_add_announcement_desc'] = "Fügen Sie hier neue Ankündigungen hinzu. Sie können Ihren Ankündigungen HTML hinzufügen. ";
$l['bam_add_announcement_noadvance_desc'] = "Fügen Sie hier neue Ankündigungen hinzu. Sie können MyCode in Ihren Ankündigungen verwenden.";
$l['bam_order_success'] = "Ankündigung erfolgreich aktualisiert. ";
$l['bam_no_announcement'] = "Fehler: Keine Ankündigung zum Aktualisieren. ";
$l['bam_pin_success'] = "Setze die Ankündigung als angepinnt. Diese kann vom Benutzer nicht beendet werden. ";
$l['bam_unpin_success'] = "Setze die Ankündigung als nicht angepinnt. Diese kann vom Benutzer beendet werden. ";
$l['bam_error'] = "Fehler. ";
$l['bam_form_announcement'] = "Ankündigung: ";
$l['bam_form_announcement_desc'] = "Sie können MyCode in Ihren Ankündigungen verwenden. Bis zu 1024 Zeichen sind zulässig.";
$l['bam_form_announcement_advanced_desc'] = "Sie können MyCode und HTML in Ihren Ankündigungen verwenden. Bis zu 1024 Zeichen sind zulässig. ";
$l['bam_form_tags_link'] = "Verfügbare Tags anzeigen.";
$l['bam_make_standard'] = "Statisch eingestellt";
$l['bam_make_standard_confirm'] = "Stellen Sie Ihre Ankündigung als: Standardankündigung ein?";
$l['bam_make_random_confirm'] = "Stellen Sie Ihre Ankündigung als: Ankündigung im Zufallsmodus ein?";
$l['bam_make_random'] = "Als Ankündigung im Zufallsmodus erstellen";
$l['bam_make_random_success'] = "Ihre Ankündigung wurde erfolgreich zu einer Ankündigung im <i> Zufallsmodus </ i> gemacht. Bitte stellen Sie sicher, dass alle Einstellungen korrekt aussehen. ";
$l['bam_make_standard_success'] = "Setzen Sie Ihre Ankündigung erfolgreich auf den Standardmodus zurück. Bitte bearbeiten Sie alle zusätzlichen Einstellungen und stellen Sie sicher, dass sie korrekt aussehen!";
$l['bam_make_standard_header'] = "Verwalten";
$l['bam_add_new_random'] = "Erstellen Sie eine zufällige Ankündigung";

$l['bam_activate_success'] = "Ankündigung erfolgreich aktiviert. ";
$l['bam_deactivate_success'] = "Ankündigung erfolgreich deaktiviert. ";
$l['bam_set_activated'] = "Aktiviert";
$l['bam_set_deactivated'] = "Deaktiviert";
$l['bam_announcement_activated'] = "Aktiviert?";
$l['bam_deactivated_announcements'] = "Deaktivierte Ankündigungen: ";
$l['bam_activated_announcements'] = "Aktivierte Ankündigungen: ";

$l['bam_announcement_is_global'] = "Diese Ankündigung ist global (auf allen Seiten angezeigt).";
$l['bam_announcement_is_index'] = "Diese Ankündigung wird auf der Standardindex angezeigt.";
$l['bam_announcement_is_sticky'] = "Diese Ankündigung ist gepinnt(kann nicht deaktiviert werden).";
$l['bam_announcement_is_forums'] = "Diese Ankündigung wird in bestimmten Foren angezeigt.";
$l['bam_announcement_is_random'] = "Diese Ankündigung ist im Zufallsmodus.";
$l['bam_announcement_has_directives'] = "Diese Ankündigung enthält spezielle Richtlinien. ";
$l['bam_announcement_is_custom_class'] = "Diese Ankündigung verwendet einen benutzerdefinierten Stil.";

$l['bam_green'] = "Grün";
$l['bam_blue'] = "Blau";
$l['bam_yellow'] = "Gelb";
$l['bam_orange'] = "Orange";
$l['bam_red'] = "Rot";
$l['bam_silver'] = "Silver";
$l['bam_magenta'] = "Magenta";
$l['bam_custom'] = "Benutzerdefiniert (Nachfolgend definieren)";

$l['bam_form_style'] = "Style/Class:";
$l['bam_form_style_desc'] = "color/class Style für Ankündigung auswählen.";
$l['bam_form_class_custom'] = "Benutzerdefiniertes CSS:";
$l['bam_form_class_custom_desc'] = "Geben Sie benutzerdefinierte CSS-Klassen ein, die anstelle der in BAM integrierten Stile verwendet werden sollen (Sie können mehrere Werte durch ein Leerzeichen trennen). <i> Sie müssen diese Klassen entweder im CSS Ihres Themas oder in den Plugin-Einstellungen der BAM definieren. </ i> Einzelheiten finden Sie in der Dokumentation.";

//Dies wird in Javascript eingegeben. Stellen Sie daher sicher, dass die Zeichenfolge, die generiert wird, keine neuen Zeilen enthält, wenn Sie dies übersetzen.
$l['bam_remove_custom_class'] = "<i><font color=\'red\'>Sie müssen benutzerdefinierte Klassen entfernen, bevor Sie einen vordefinierten Farbstil festlegen. ";
$l['bam_remove_custom_class'] .= "Wenn Sie zusätzlich zu einer benutzerdefinierten Klasse einen vordefinierten Farbwert verwenden müssen, können Sie beide in die unten stehende benutzerdefinierte Klasseneinstellung aufnehmen. </font>";
$l['bam_remove_custom_class'] .= "Anweisungen zum Hinzufügen benutzerdefinierter Klassen finden Sie in der Dokumentation. </i>";
$l['bam_remove_custom_class'] .= "<br /><br />Example: \"blue my_custom_class\" &nbsp;&nbsp; (separate multiple classes by spaces)<br />";
$l['bam_remove_custom_class'] .= "Built in classes: red, blue, yellow, green, magenta, orange, silver <br/><br />";

$l['bam_remove_additional_page'] = "<i><font color = \'red\'>Sie müssen die Ankündigungen entfernen, bevor Sie die standart Einstellungen von BAM verwenden können. ";
$l['bam_remove_additional_page'] .= "</font>Wenn Ihre Ankündigung auf der index.php-Seite oder auf bestimmten Boards angezeigt werden soll, können Sie diese Felder zusammen mit weiteren Seiten unten hinzufügen.</i><br /><br />";
$l['bam_remove_additional_page'] .= "<b>Zum Beispiel: index.php, forumdisplay.php?fid=2, forumdisplay.php?fid=3</b> &nbsp;&nbsp; (Anzeige in der Index, board ID 2, board ID 3)<br /><br />";
$l['bam_form_order'] = "Anzeige Reihenfolge:";
$l['bam_form_order_desc'] = "Anzeigenreihenfolge für diese Ankündigung festlegen. Dieses Feld kann leer gelassen werden.";
$l['bam_form_groups'] = "Benutzergruppeneinstellung:";
$l['bam_form_groups_desc'] = "Hier angeben für welche Benutzergruppen die Ankündigung sichtbar sein soll. Zum Auswählen einzelner Gruppen STRG gedrückt halten.";
$l['bam_form_url'] = "Ankündigung URL (Optional):";
$l['bam_form_url_desc'] = "Wenn eine URL festgelegt wird, kann die Ankündigung als Verlinkung verwendet werden. Diese Einstellung kann leer gelassen werden.";
$l['bam_form_pinned'] = "Diese Ankündigung anpinnen?";
$l['bam_form_pinned_desc'] = "Soll diese Ankündigung standardmäßig angepinnt werden?";
$l['bam_form_add_submit'] = "Ankündigung hinzufügen";
$l['bam_form_add'] = "Neue Ankündigung hinzufügen";
$l['bam_form_edit_submit'] = "Ankündigung bearbeiten";
// 2.0
$l['bam_announcement_type'] = "Ankündigungs Type: ";
$l['bam_announcement_type_desc'] = "Wählen Sie aus, ob diese Ankündigung im Zufallsmodus oder im Standardmodus hinzugefügt werden soll. ";
$l['bam_display_mode'] = "In welchen Seiten wir die Ankündigung angezeigt"; // no longer used
$l['bam_display_mode_desc'] = "Wählen Sie aus, wo diese Ankündigung angezeigt werden soll. Standardmäßig wird es nur auf der Indexseite angezeigt.";
$l['bam_list_display_global'] = "Global (auf allen Seiten)";
$l['bam_list_display_index'] = "Nur auf der Forum index ";
$l['bam_list_display_forums'] = "Anzeige in bestimmten Kategorien / Foren";
$l['bam_list_display_special'] = "Andere (erweitert - unten bitte definieren)";
$l['bam_make_global'] = "Diese Ankündigung global machen?";
$l['bam_make_global_desc'] = "Wählen Sie aus, ob diese Ankündigung global erfolgen soll. Globale Ankündigungen werden auf allen Seiten angezeigt, unabhängig von anderen Einstellungen oder Einschränkungen. ";
$l['bam_additional_pages'] = "Benutzerdefinierte Seiten und Parameter (erweitert): ";
$l['bam_has_additional_pages'] = "Diese Ankündigung verfügt über benutzerdefinierte Anzeigeeinstellungen.";
$l['bam_additional_pages_desc'] = "<b>Fügen Sie Links zu bestimmten Seiten ein, um diese Ankündigung anzuzeigen. </b> <i>Sie können vollständige oder teilweise Links (wie unten gezeigt) oder SEO-freundliche Links einfügen, die mit der in MyBB  .htaccess-Datei kompatibel sind, inkludieren. </i> Trennen Sie mehrere Seiten durch ein Komma.<br /><br />

<b>Zum Beispiel:</b> <br />
  - \"portal.php, index.php\" &nbsp;&nbsp;&nbsp;&nbsp; -- <i>Nur auf index.php und portal.php anzeigen</i><br />
  - \"portal.php?fid=2\" &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -- <i>Zeigen Sie diese Ankündigung auf portal.php an, aber nur, wenn die fid 2 ist.</i><br /><br />
  <b>Unterstützte Seiten: </b>Jede .php-Seite im Verzeichnis Ihres Forums. <br />
  <b>URL-Parameter werden unterstützt: </b> aktion, uid, tid, fid, gid, aid. Alle anderen URL-Parameter werden ignoriert. Einzelheiten finden Sie in der Dokumentation.<br />";

$l['bam_random_select'] = "Ankündigung im Zufallsmodus";
$l['bam_standard_select'] = "Statische Ankündigung (immer anzeigen)";
$l['bam_advanced_mode'] = "HTML in Ankündigungen zulassen?";
$l['bam_advanced_mode_desc'] = "Standardmäßig MyCode in Ankündigungen. Wenn Sie vollständigen HTML-Code benötigen, aktivieren Sie diese Einstellung.";
$l['bam_manage_random_form_container'] = "Verwalten von Ankündigungen im Zufallsmodus";
$l['bam_forum_select'] = "Boards zur Anzeige der Ankündigung auf: ";
$l['bam_forum_select_desc'] = "Wählen Sie aus, auf welchen Boards / Foren die BAM diese Ankündigung anzeigen soll. Halten Sie die STRG-Taste gedrückt, um mehrere Foren auszuwählen.";
$l['bam_undefined'] = "Undefiniert";
$l['bam_edit_success'] = "Ankündigung erfolgreich bearbeitet.";
$l['bam_add_success'] = "Ankündigung erfolgreich hinzugefügt.";
$l['bam_delete_success'] = "Ankündigung erfolgreich gelöscht.";
$l['bam_delete_error'] = "Fehler beim löschen: Ankündigung nicht gefunden.";
$l['bam_manage_announcement'] = "Ankündigung";
$l['bam_manage_class'] = "Style / Class ";
$l['bam_manage_order'] = "Anzeige Reihenfolge";
$l['bam_manage_actions'] = "Aktionen";
$l['bam_manage_edit'] = "Bearbeiten";
$l['bam_manage_delete'] = "Löschen";
$l['bam_manage_pin'] = "Anpinnen";
$l['bam_manage_unpin'] = 'Abpinnen';
$l['bam_manage_delete_confirm'] = 'Diese Ankündigung wirklich löschen?';
$l['bam_manage_actions'] = "Aktionen";
$l['bam_manage_null'] = 'Keine Ankündigungen gefunden.';
$l['bam_manage_order_submit'] = 'Anzeigenreihenfolge aktualisieren';
$l['bam_invalid_post_code'] = 'Ungültige Post ID erkannt. Bitte erneut versuchen.';
$l['bam_announcement_tags_alert'] = "<b>BAM unterstützt zusätzliche Tags und Anweisungen in Ankündigungen. </b> Diese werden analysiert, wenn Ihre Ankündigung angezeigt wird. </b><br /><br />";
$l['bam_announcement_tags_alert'] .= "<b>Variablen: </b><br />";
$l['bam_announcement_tags_alert'] .= "&nbsp;&nbsp;&nbsp;&nbsp; {username} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Analysiert den Benutzer\Namen (oder den Gast, wenn er nicht angemeldet ist).</i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; {newestmember} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Analysiert den Benutzernamen des neuesten registrierten Mitglieds.</i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; {newestmember_link} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Erstellt einen Link zum Profil des neuesten registrierten Mitglieds.</i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; {threadreplies} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Nur Thread anzeigen. Analysiert die Anzahl der Antworten im aktuellen Thread.</i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; {countingthread} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Experimental. Analysiert die aktuelle Anzahl in Forenspielen / Zählthreads. Versuche, die tatsächliche Anzahl aufzulösen und zu bestimmen, wenn ein Benutzer die falsche Anzahl veröffentlicht. </i>";
$l['bam_announcement_tags_alert'] .= "<br /><br /><b>Ankündigungsrichtlinien: </b><br />";
$l['bam_announcement_tags_alert'] .= "&nbsp;&nbsp;&nbsp;&nbsp;Diese Richtlinien geben BAM zusätzliche Informationen darüber, wo Ankündigungen angezeigt werden sollen. Diese sind experimentell. Beispiele finden Sie in der Readme-Datei. <br />";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; [@themes:1,2] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Ankündigung nur auf den Themen-IDs 1 und 2 anzeigen. </i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; [@languages:espanol] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Anzeige nur in Deutsch (oder einer von Ihnen gewählten Sprache) anzeigen. </i>";
$l['bam_announcement_tags_alert'] .= "<br />&nbsp;&nbsp;&nbsp;&nbsp; [@template:custom] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Verwenden Sie für diese Ankündigung eine alternative globale Vorlage (Erweitert - verwenden Sie diese Option, wenn Sie Javascript benötigen).</i>";
$l['bam_announcement_tags_alert'] .= "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp; [marquee]Laufschrift- Text[/marquee]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Ankündigung mit Lauftext anzeigen. </i>";
$l['bam_announcement_tags_alert'] .= "<br /><br />Diese Richtlinien können an einer beliebigen Stelle im Text Ihrer Ankündigung platziert werden und werden entfernt, bevor Ihre Ankündigung in Ihrem Forum angezeigt wird. Diese Merkmale gelten als experimentell. Bitte fordern Sie Support an oder besuchen Sie die Dokumentation bei Fragen!<br /><br />";


$l['bam_announcement_too_long'] = "FEHLER! Ihre Ankündigung darf nicht länger als 1023 Zeichen sein. ";
$l['bam_announcement_link_too_long'] = "FEHLER! Die URL Ihrer Ankündigung darf nicht länger als 159 Zeichen sein. ";
$l['bam_class_too_long'] = "FEHLER! Ihre benutzerdefinierten Klassen dürfen nicht mehr als 39 Zeichen enthalten. ";
$l['bam_additional_pages_too_long'] = "FEHLER! Das Feld für zusätzliche Seiten darf nicht länger als 511 Zeichen sein. ";
// Lang strings for BAM's built in "upgrade advisor"

$l['bam_upgrade_required'] = "<b> Sie haben BAM 2.0 erfolgreich auf den Server hochgeladen! </ B> Um die neue Version verwenden zu können, müssen Sie das Upgrade-Skript von BAM ausführen, um Datenbankfelder und -vorlagen zu aktualisieren. Dadurch werden alle Ihre vorhandenen Ankündigungen von BAM 1.0 auf BAM + 2.0 migriert. Stellen Sie sicher, dass Sie die Einstellungen der BAM nach dem Ausführen Ihres Upgrades aufrufen und sicherstellen, dass alles noch korrekt aussieht.";
$l['bam_upgrade_link_text'] = "Klicken Sie hier, um das Upgrade auszuführen!";
$l['bam_upgrade_link_text_plugins_panel'] = "BAM ist bereit zum Update. Klicken Sie hier, um das Upgrade auf BAM 2.0 abzuschließen!<br />";
$l['bam_upgrade_success'] = "Sie haben BAM erfolgreich auf BAM + 2.0 aktualisiert! Stellen Sie sicher, dass alle Einstellungen korrekt sind, da diese aktualisiert und auf die Standardeinstellungen des Upgrades zurückgesetzt wurden. Wenn Sie weitere Probleme haben, deinstallieren Sie BAM und installieren Sie es erneut von der Plugins-Seite. Dadurch werden alle verbleibenden Probleme behoben!";
$l['bam_info_alternative_upgrade'] = "<br /><br /><i>Alternativ können Sie das Plugin deinstallieren und auf dieser Seite eine vollständige Neuinstallation durchführen! Beachten Sie, dass dadurch Ihre Ansagen gelöscht werden.</i>";

$l['bam_info_upgrade'] = "<font color='red'><b>Sie haben BAM 2.0 auf den Server hochgeladen, aber ein Upgrade ist erforderlich, um Ihre Ankündigungen auf die neue Version zu migrieren. </font>
Stellen Sie sicher, dass die BAM aktiviert ist, bevor Sie den integrierten Migrator ausführen. Sobald die BAM aktiviert wurde,
Ein Upgrade-Link wird unten angezeigt. </b><br /><br />
<i>(Der Upgrader der BAM wurde speziell für den Betrieb vor Ort entwickelt und läuft, während das Plugin aktiviert ist. Die Ankündigungen für Ihr Forum werden nicht unterbrochen.)</i>";

$l['bam_info_upgrade_ready'] = "<font color='red'><b>Sie haben BAM 2.0 auf den Server hochgeladen, aber ein Upgrade ist erforderlich, um Ihre Ankündigungen auf die neue Version upgraden. </b>Sie können den Upgrader unten starten.</font>
<br /><br />
<i>(Der Upgrader der BAM wurde speziell für den Betrieb vor Ort entwickelt und läuft, während das Plugin aktiviert ist. Die Ankündigungen für Ihr Forum werden nicht unterbrochen.)</i><br />";

$l['bam_compatibility_mode'] = "Kompatibilitätsmodus (erweitert): ";
$l['bam_compatibility_mode_desc'] = "<b>Belassen Sie diese Einstellung auf der Standardeinstellung, wenn Sie sich nicht sicher sind.</b>Die Standardvorlagenvariablen und Plugin-Hooks der BAM sollen dies sicherstellen
die bestmögliche Kompatibilität mit den meisten MyBB-Foren. Gelegentlich treten Kompatibilitätsprobleme mit auf
verschiedene Plugins, ungetestete Versionen von PHP oder stark modifizierte Themes. Wenn diese Einstellung aktiviert ist,Die BAM versucht, Ankündigungen mit dem Hook \"pre_output_page\" anstelle des Standard-Hooks global.php zu rendern.Es wird empfohlen, diese Einstellung auf der Standardeinstellung zu belassen, sofern keine Probleme auftreten. Wenn Kompatibilitätsprobleme weiterhin bestehen, fordern Sie im Kompatibilitätsmodus Unterstützung in den MyBB-Community-Foren an.";

$l['bam_compatibility_mode_desc'] = "<b><u>Belassen Sie diese Standardeinstellung, wenn Sie sich nicht sicher sind.</u></b> Wenn Einstellung aktiviert ist, zwingt
BAM das Rendern von Ansagen bei der Ausgabe der Seite und nicht während der Generierung von Forum-Headern. Dies kann gelegentlich behoben werden
Bestimmte Kompatibilitätsprobleme, die bei nicht getesteten PHP-Versionen auftreten können, konflickte mit Plugins oder stark modifizierte Themes. Diese Funktion wird als experimentell angesehen. Es wird derzeit empfohlen, diese Einstellung zu deaktivieren, es sei denn, Sie haben Probleme.";

$l['bam_admin_permissions'] = "Kann BAM-Ankündigungen verwalten?";