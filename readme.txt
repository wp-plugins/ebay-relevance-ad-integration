=== eBay Relevance Ad Integration ===
Contributors: mawl
Donate link: http://www.mediabeam.com
Tags: eBay, Relevance Ad, Advertising
Requires at least: 2.2
Tested up to: 2.5.1
Stable tag: 1.0

Das eBay Relevance Ad Plugin integriert eBay Relevance Ads in Ihren Wordpress Blog

== Description ==

Das eBay Relevance Ad Plugin integriert eBay Relevance Ads in Ihr Blogsytem.
Es besteht die  Möglichkeit, Templates zu definieren, die Ihre Werbebanner hinter jedem Beitrag
oder am Ende einer Seite anzeigen.
Das Plugin verfügt ausserdem über ein Sitebar Widget, das Sie bei der Werbemitteleinbindung
in Ihrer Sitebar unterstützt. Mit diesem Plugin stellt dies nun keine Herausforderung mehr dar.


== Installation ==

1. Kopieren Sie die Datei wp-ebay-relevancead.php in das Verzeichnis /wp-content/plugins/
2. Loggen Sie sich mit Ihrem Benutzer in das Wordpress Admininterface ein. Der Pfad ist 
   standardmäßig /wp-admin/
3. Klicken Sie auf Plugins und aktivieren Sie das Plugin.
4. Nun können Sie unter "Verwalten / eBay Relevance Ad"  das Plugin nutzen.


== Info ==

Es wird während des Installationsprozesses folgende Tabelle angelegt. 
Falls Sie Probleme mit der Datenbank haben, führen Sie folgendes Statement auf Ihrer 
Datenbank(des gewünschten Blogs) aus, um die benötigte Tabelle zu erzeugen.

`CREATE TABLE `wp_era_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `options` text NOT NULL,
  `active` tinyint(3) unsigned NOT NULL default '1',
  `standard` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1`


== Einfügen von Templates ==

1. Loggen Sie sich bei affilinet.de ein und erstellen Sie ein neues eBay Relevance Ad 2.0.
	 Dazu müssen Sie Mitglied des eBay Partnerprogrammes sein.
2. Erstellen Sie ein neues Template mit dem affilinet - eBay Relevance Ad Generator
3. Kopieren Sie im Schlussschritt das Code Template, welches Sie in Ihre Website einbauen würden, 
   in Ihre Zwischenablage.
4. Loggen Sie sich in die Wordpress Administration ein und wählen Sie "Verwalten" > "eBay Relevance Ad"
5. Erstellen Sie ein neues Template. 
6. Wählen Sie einen Templatenamen z.B "Leaderboard"
7. Erstellen Sie eine Beschreibung. Dies hilft Ihnen später, die Templates voneinander 
   zu unterscheiden.
8. Kopieren Sie das Template, welches Sie von affilinet.de kopiert haben in das "Affilinet Code" Feld.
9. Klicken Sie auf "Template hinzufügen" um das Template zu speichern.


== Einbindung - Ohne Sidebar Widget Plugin ==

1. Schreiben oder wählen Sie einen Beitrag, indem Sie das eBay Relevance Ad hinzufügen wollen.
2. Gehen Sie in die Code-Ansicht und fügen folgendes Beispiel ein:
		<!-- eBayRelevanceAd@Leaderboard -->
		Dabei entspricht "Leaderboard" in diesem Fall dem Name des Templates, dass Sie einbinden wollen.
3. Speichern Sie den Beitrag ab. 
4. Schauen Sie sich den Beitrag an. Sie sollten nun das eBay Relevance Ad sehen, welches Sie soeben 
   bei affilinet.de erstellt haben.
	 

== Einbindung mit Sidebar Widget ==

Achtung:
Um diese Art der Einbindung nutzen zu können, muss Ihr Wordpress-Theme das Sidebar Widget unterstützen.
Eine Liste von Wordpress-Themes, die das Sidebar Widget unterstützen, finden Sie 
unter http://themes.wordpress.net/ 
( Tipp: Wählen Sie unter den Sortierfunktionen "widget ready", um alle Themes anzuzeigen, die das Sidebar Plugin unterstützen)
Außerdem müssen Sie das Plugin von (http://wordpress.org/extend/plugins/widgets/) herunterladen und aktiviert haben.

1. Wählen Sie den Punkt "Presentation"  > "Sidebar Widgets"
2. Wählen Sie aus der Liste der verfügbaren Widgets das eBay Relevance Ad Widget aus und ziehen Sie es in die Sidebar.
3. Klicken Sie auf das Symbol neben dem "eBay Relevance Ad"
4. Wählen Sie unter "Widget Titel" einen Titel aus, unter dessen Menupunkt das Widget in Ihrer Sidebar angezeigt wird.
5. Wählen Sie durch Klick auf den Namen das Templates das gewünschte aus, das in der SideBar angezeigt werden soll
6. Bestätigen Sie mit der Enter-Taste
7. Klicken Sie auf den Button "Speichern", um die Sidebar zu speichern.
8. Das eBay Relevance Ad ist nun in Ihrer Seite.

Have Fun!