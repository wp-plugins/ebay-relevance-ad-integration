<?php
/*
eBay Relevance Plugin
Copyright (C) 2007  Daniel Kemper <daniel.kemper@mediabeam.com>
This program is free software; you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by the Free Software Foundation;
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program;
if not, see <http://www.gnu.org/licenses/>.

Plugin Name: eBay Relevance Ad Integration
Version: 1.0.0.1
Plugin URI: http://www.mediabeam.com
Description: Mit diesem Plugin haben Sie einfach die Möglichkeit Ihre eBay Relevance Ads in Wordpress zu integrieren.
Author: Daniel Kemper
Author URI: http://www.mediabeam.com
*/
define('EBAY_RELEVANCE_AD_VERSION',"1.0.0.1");

/**
* Function: wp_ebay_relevancead_init()
* display the main template
* @since 0.2 BETA
* @param none;
* @return void;
*/
function wp_ebay_relevancead_init() {

	if ( isset ( $_POST['era_template_id']) && is_numeric($_POST['era_template_id']) && isset($_POST['era_action']) && $_POST['era_action'] == 'delete' ) {
		global $wpdb;
		$table_name = $wpdb->prefix."era_content";
		$sql = $wpdb->query("DELETE FROM {$table_name} WHERE id = ".mysql_real_escape_string($_POST['era_template_id']));
		die();
	}

	if ( isset ( $_POST['era_template_id']) && is_numeric($_POST['era_template_id']) && isset($_POST['era_action']) && $_POST['era_action'] == 'change_status' ) {
		global $wpdb;
		$table_name = $wpdb->prefix."era_content";
		$wpdb->query("UPDATE `{$table_name}` SET active = ".mysql_real_escape_string($_POST['era_template_status'])." WHERE id = ".mysql_real_escape_string($_POST['era_template_id']));
		die();
	}

	//add_submenu_page('plugins.php', 'eBay Relevance Ad', 8, __FILE__, 'era_toplevel_options_page');
}

/**
* Function: wp_ebay_relevance_add_pages()
* add the eBay RelevanceAd Tab to the adminstration menu
* @since 0.1 BETA
* @return void;
*/
function wp_ebay_relevancead_add_pages()
{
	add_management_page('eBay Relevance Ad', 'eBay Relevance Ad', 8, 'ebayrelevancead', 'wp_ebay_relevancead_plugin');
}

/**
* Function: era_plugin()
* checks the request values and display the correct page
* @since 0.1 BETA
* @param none;
* @return void;
*/
function wp_ebay_relevancead_plugin()
{
	if ( isset($_GET['eraaction']) && $_GET['eraaction'] == 'add_template' ) {
		wp_ebay_relevancead_show_add_template();
		return;
	}

	if ( isset($_GET['eraaction']) && $_GET['eraaction'] == 'edit_template') {
		wp_ebay_relevancead_show_edit_template((int)$_REQUEST['tid']);
		return;
	}

	if ( isset($_GET['preview']) && $_GET['preview'] == 'true') {
		wp_ebay_relevancead_show_preview_template($_REQUEST['pid']);
		return;
	}

	wp_ebay_relevancead_show_main_template();
}

/**
* Function: wp_ebay_relevancead_show_main_template()
* display the main site
* @param none;
* @since 0.1 BETA
* @return void;
*/
function wp_ebay_relevancead_show_main_template()
{
	?>
	<div class='wrap'>
		<h2>eBay Relevance Ad</h2>
			<div id='message'></div>
				<div style='padding-left: 20px; padding-bottom: 20px;'>
					<p>eBay Relevance Ad - Wordpress Intergration hilft Ihnen beim einfachen Einbau von eBay Relevance Ads in Ihre Website.</p>
				</div>
				<div style='padding-left: 20px; padding-bottom: 30px;'>
					<fieldset class='options'>
						<legend id='era_list'><b>Aktuelle eBay Relevance Ads</b></legend>
							<table class='widefat'>
								<thead>
									<tr>
										<th scope='col' width='20%'>Templatename</th>
										<th scope='col' width='*'>Beschreibung</th>
										<th scope='col' width='5%'>Aktiv</th>
										<th scope='col' width='220' colspan='3'></th>
									</tr>
								</thead>
								<tbody id='the_list'>
	<?php
	$url = $_SERVER['SCRIPT_NAME'];
	$templates = wp_ebay_relevancead_fetch_template('all');
	if ( count($templates) > 0 ) {
		foreach ( $templates as $key => $template) {
			if ( $key%2 == 0 ) {
			?>
				<tr class="alternate" id="template_<?php echo $template['id']; ?>">
			<?php
			} else  {
			?>
				<tr class='' id="template_<?php echo $template['id'] ?>">
			<?php } ?>

			<td><?php echo $template['name'] ?></td>
			<td><?php echo $template['description'] ?></td>
			<!-- geht besser -->
			<?php if ( $template['active'] == 1 ) : ?>
				<td><input type="checkbox" value="active" checked="checked" id="active_<?php echo $template['id']; ?>" onclick="changeStatus(<?php echo $template['id']; ?>)" /></td>
			<?php else: ?>
				<td><input type="checkbox" value="active"' id="active_<?php echo $template['id']; ?>" onclick="changeStatus(<?php echo $template['id']; ?>)" /></td>
			<?php endif; ?>
			<td><a href="<?php echo $url ?>?page=ebayrelevancead&preview=true&pid=<?php echo $template['id'] ?>">Anzeigen</a>
			<td><a href="?page=ebayrelevancead&eraaction=edit_template&tid=<?php echo $template['id']; ?>">Bearbeiten</a>
			<td><a onclick="if(confirm('Wollen Sie das eBay RelevanceAd mit dem Namen  <?php echo $template['name']; ?> wirklich löschen?') == true){delete_era_template('<?php echo $template['id']; ?>')};">Löschen</a>
			</td>
			</tr>
	<?php
		}
	}
	?>
	</tbody>
	</table>
		<input class="button" style="text-align:center;padding:5px" value="Neues Template" onclick="location.href='<?php echo $_SERVER['REQUEST_URI']; ?>&eraaction=add_template'">
	</fieldset>
	</div>
		Tipp: Hinweise zu der Einbindung finden Sie unter dem Punkt <i>Anzeigen</i> beim jeweiligen Template.
	</div>
<?php
}

/**
 * Bereitet die Einträge auf
 *
 * @param string $options
 * @example 'all' => returns all entries of the database
 * 					 1 => return the entry with the id 1
 * 					'right side' => returns the entry with the name right_side;
 * @return array $templates;
 */
function wp_ebay_relevancead_fetch_template($options = "",$only_active = true)
{
	global $wpdb;
	$table_name = $wpdb->prefix."era_content";
	if ( empty($options) )  {
			$templates = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE active =1 ORDER BY id DESC ",ARRAY_A);
			//$wpdb->hide_errors();
			return $templates;
	}

	if ( $options == "all") {
		// Es werden alle Templates geholt
		//$wpdb->hide_errors();
		$templates = $wpdb->get_results("SELECT * FROM `{$table_name}` ORDER BY id DESC ",ARRAY_A);
		//print_r($templates);
		return $templates;
	}

	if ( is_numeric($options)) {
		if ( $only_active == true ) {
			$templates = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE id = ".mysql_real_escape_string($options).' AND active=1',ARRAY_A);
		} else {
			$templates = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE id = ".mysql_real_escape_string($options),ARRAY_A);
		}
		return $templates;
	}

	if ( is_string($options) ) {
		$templates = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE name = '".mysql_real_escape_string($options)."' AND active=1 ",ARRAY_A);
		return $templates;
	}

}

/**
 * Function: show_add_era_tempalte()
 * print the template to add templates
 * @since 0.1 BETA
 * @param none;
 * @return Output the HTML Content
 */
function wp_ebay_relevancead_show_add_template()
{
	?>
	<div class='wrap'>
		<form method='post' action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<h2>eBay Relevance Ad - Neues Template hinzufügen </h2>
	<?php
	if ( isset($_POST['add_page_submit'])) {
		$res = wp_ebay_relevancead_validate_page($_POST);
		if ( is_array($res) ) {
			echo "<ul>";
			foreach ( $res as $key => $error ) {
				echo "<li><font color='red'>{$error}</font></li>";
			}
			echo "</ul>";
		} else {
			wp_ebay_relevancead_add_template($_POST);
			echo "<div id='message' class='updated fade'><p>Erfolgreich <b>hinzugefügt</b>&nbsp;&nbsp;<a href='?page=ebayrelevancead'>(zurück zur Übersicht)</a></p></div>";
			unset($_POST);
		}
	}
	if ( is_array($_POST) ) {
		extract($_POST); // Convert keys into variables
	}
	$code_snipplet = stripslashes($code_snipplet);
	?>
	<div style='padding-left: 20px; padding-bottom: 30px;'>
		<fieldset class='options'>
			<table class='editform' width='100%' cellspacing='2' cellpadding='5'>
				<tr>
					<td width='33%' scope='row' valign='top'>Templatename</td>
					<td width='67%'><input type='text' name='name' size='50' value='<?php echo $name ?>'></td>
				</tr>
				<tr>
					<td width='33%' scope='row' valign='top'>Beschreibung</td>
					<td width='67%'><textarea name='description' cols='48' rows='5'><?php echo $description; ?></textarea></td>
				</tr>
				<tr>
					<td width='33%' scope='row' valign='top'>Affilinet Code Snippet</td>
					<td width='67%'><textarea name='code_snipplet' cols='70' rows='10'><?php echo $code_snipplet; ?></textarea></td>
				</tr>
				<tr>
					<td colspan='2'>
						<input type='submit' value='Neues Template einfügen' class='button' name='add_page_submit' style='padding: 5px'>
					</td>
				</tr>
			</table>
		</fieldset>
		</form>
	</div>

	</div>
	<?php
}

/**
* Function: wp_ebay_relevancead_show_preview_template()
* Preview a template in the Wordpress Admin Interface
* @param int $id - Id of the Template;
* @since 0.1 BETA
* @return void;
*/
function wp_ebay_relevancead_show_preview_template($id)
{
	?>
	<div class='wrap'>
		<h2>eBay Relevance Ad - Vorschau</h2>
			<div style='padding-left:20px;padding-bottom:30px;text-align:center;'>
			<?php
			$template = wp_ebay_relevancead_fetch_template($id,false);
			$era = wp_ebay_relevancead_get_affilinet_template($template,true);
			echo $era;
			?>
			<p>Fügen Sie bitte folgendes Code-Snippet an der Stelle in Ihrer Seite ein, an der Sie das eBay Relevance Ad anzeigen möchten.</p>

			Innerhalb von Beitr&auml;gen und Seiten verwenden Sie bitte folgendes Code Snippet:
			<code><br/><br/>
			<?php
			echo htmlentities("<!-- eBayRelevanceAd@{$template[0]['name']}-->");
			?>
			</code>
			<br/><br/>
			Für die direkte Einbindung in Themes verwenden Sie bitte folgendes Code Snippet:
			<code><br/><br/>
			<?php
			echo htmlentities("<?php if( function_exists('wp_ebay_relevancead_show')) { wp_ebay_relevancead_show('{$template[0]['name']}'); } ?>");
			?>
			</code>
		</p>
		</div>
	</div>
	<?php
}

/**
* Function: install_wp_ebay_relevancead_plugin()
* install the database for the eBay RelavanceAd Plugin
* @param none;
* @return none;
*/
function wp_ebay_relevancead_install()
{
	global $wpdb;
	$table_name = $wpdb->prefix."era_content";
	if ( $wpdb->get_var("show tables like '{$table_name}'") != $table_name ) {
		// create the database table
		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` ".
					" ( ".
					" `id` int(10) unsigned NOT NULL auto_increment, ".
					" `name` varchar(100) NOT NULL default '', ".
					" `description` varchar(255) NOT NULL default '', ".
					" `options` text NOT NULL, ".
					" `active` tinyint(3) unsigned NOT NULL default '1', ".
					" `standard` tinyint(1) default '0', ".
					" PRIMARY KEY  (`id`));";
		add_option('ebay_relevance_ad_version',EBAY_RELEVANCE_AD_VERSION);
		$wpdb->hide_errors(); // Keine Fehler ausgeben
		$result = $wpdb->query( $sql );
		$wpdb->flush(); // Den Cache leeren

		// Keine Möglichkeit, auf Fehler sauber zu prüfen, also prüfe ob die Datenbank vorhanden ist
		if ( $wpdb->get_var("show tables like '{$table_name}'") != $table_name ) {
			print "Konnte die Tabelle nicht anlegen";

			die();
		}


	}
}

/**
 * Function: add_page_check_values()
 * @param array $data;
 * @since 0.1 BETA
 * @return bool true / or array with erros
 */
function wp_ebay_relevancead_validate_page($data)
{
	$errors = array();
	// check for correct name
	if ( !isset ( $data['name']) || empty($data['name']) ) {
		$errors[] = "Name ist nicht definiert";
	}


	// check for description
	if ( !isset ( $data['description']) || empty($data['description'])) {
  	$errors[]  = "Beschreibung ist nicht  korrekt";
	}

	// check for code snipplet
	if ( isset($data['code_snipplet']) && !empty($data['code_snipplet'])) {
		$result = wp_ebay_relevancead_parse_affilinet(stripslashes($data['code_snipplet']));
		if ( is_array($result) && count($result) >  0 ) {
			if ( !array_key_exists('era_width',$result) ) {
				$errors[] = "Es wurde keine Breitenangabe in Ihrem Template gefunden.";
			}
			if ( !array_key_exists('era_height',$result) ) {
				$errors[] = "Es wurde keine Höhenangabe in Ihrem Template gefunden.";
			}
			if ( !array_key_exists('era_publisher',$result) ) {
				$errors[] = "Es wurde Ihre Affilinet - Id nicht im Template gefunden";
			}
			if ( !array_key_exists('era_layout',$result) && !array_key_exists('era_flash',$result) && !array_key_exists('era_banner',$result) ) {
				$errors[] = "Es wurde kein Layout in Ihrem Template gefunden";
			}
		} else {
			$errors[] = "Kein korrektes Code Snippet";
		}
	} else {
		$errors[] = "Affilinet Code Snippet wurde nicht definiert";
	}

	if ( count($errors) == 0 ) {
		return true;
	} else {
		return $errors;
	}

}

/**
* Function: wp_ebay_relevancead_add_template()
* @param array $data - Array with data
* @param string $type
* @return void;
*/
function wp_ebay_relevancead_add_template($data,$type = "add")
{
	global $wpdb;

	$code_details = wp_ebay_relevancead_parse_affilinet(stripslashes($data['code_snipplet']));

	$details = serialize($code_details);
	$table_name = $wpdb->prefix."era_content";
	$name = $data['name'];
	if ( $type == 'add') {

		$wpdb->query(sprintf("INSERT INTO `{$table_name}` (name,description,options) VALUES ('%s','%s','%s')",
												mysql_real_escape_string($name),
												mysql_real_escape_string($data['description']),
												mysql_real_escape_string($details)
								  			));
	} else {
		$wpdb->query(sprintf("UPDATE `{$table_name}` SET name = '%s', description = '%s', options = '%s' WHERE id = %d",
												mysql_real_escape_string($name),
												mysql_real_escape_string($data['description']),
												mysql_real_escape_string($details),
												mysql_real_escape_string($data['id'])
												));
	}
}

/**
* Function: add_era_ajax_script()
* Include the functions to make a ajax request an display it
* @param none;
* @return void;
*/
function wp_ebay_relevancead_add_ajax_script()
{
	$url = get_bloginfo('wpurl')."/wp-admin/admin.php?ebayRelevanceAd.php";
	?>
	<script type='text/javascript'>
			function delete_era_template(id) {
				var mycall = new sack("<?php print $url; ?>");
				mycall.method = 'POST';
				mycall.setVar('era_action','delete');
				mycall.setVar('era_template_id',id);
				mycall.onError = function() { alert('AJAX error in voting' )};
				mycall.runAJAX();
				document.getElementById('template_'+id).style.backgroundColor  = '#ff0000';
				window.setTimeout('wait('+id+')',2000);
			}

			function wait(id) {
				document.getElementById('template_'+id).style.display = 'none';
				document.getElementById('message').className = 'updated fade';
				document.getElementById('message').innerHTML = "<p>Erfolgreich <b>gelöscht!<\/b><\/p>";
				Fat.fade_all();
			}

			function changeStatus(id) {
				var active_status = document.getElementById('active_'+id).checked;
				if ( active_status == false ) {
					check_user = confirm("Möchten Sie dieses Template wirklich deaktivieren?");
					if ( check_user == false ) {
						document.getElementById('active_'+id).checked = true;
					} else {
						updateStatus(id,0);
					}
				} else {
					check_user = confirm("Möchten Sie dieses Template wirklich aktivieren?");
					if ( check_user == false ) {
							document.getElementById('active_'+id).checked = false;
						} else {
							updateStatus(id,1);
						}
					}
				}

				function updateStatus(id,status) {
					var mycall = new sack("<?php print $url; ?>");
					mycall.method = 'POST';
					mycall.setVar('era_action','change_status');
					mycall.setVar('era_template_id',id);
					mycall.setVar('era_template_status',status);
					mycall.onError = function() { alert('AJAX error in voting' )};
					mycall.runAJAX();
					if (status == 1 ) {
						document.getElementById('message').innerHTML  = '<p>Template <b>aktiviert!<\/b><\/p>';
						document.getElementById('message').className  = '';
						document.getElementById('message').className  = 'updated fade';
						Fat.fade_all();
					} else {
						document.getElementById('message').innerHTML  = '<p>Template <b>deaktiviert!<\/b><\/p>';
						document.getElementById('message').className  = '';
						document.getElementById('message').className  = 'updated fade';
						Fat.fade_all();
						}
				}
				</script>
				<?php
}

/**
 * Function: wp_ebay_relevancead_parse_affilinet()
 * parses the affilinet Template an get the options
 * @param string $string
 * @return array $res;
 */
function wp_ebay_relevancead_parse_affilinet($string)
{
	$regex = @"/(era_[\w]+)([\s'=]+)([0-9(\"\w\s|.,&\\äÄöÖüÜ*$§%\/=#_\-!;:\?)]+)(?=';)/";
	preg_match_all($regex,$string,$result,PREG_PATTERN_ORDER);
	$res = array();
	foreach ( $result[1] as $num => $name )
	{
		$res[$name] = $result[3][$num];

	}
	return $res;
}

/**
* Function: wp_ebay_relevancead_show_edit_template()
* display the form to edit a eBay Relevance Ad Template
* @since 0.1 BETA
* @param integer $id
* @return void;
*/
function wp_ebay_relevancead_show_edit_template($id)
{
	?>
		<div class='wrap'>
		<form method='post'>
			<h2>eBay Relevance Ad - Template bearbeiten</h2>
	<?php
	if ( isset($_POST['edit_page_submit'])) {
		$res = wp_ebay_relevancead_validate_page($_POST);
		if ( is_array($res) ) {
			echo "<ul>";
			foreach ( $res as $key => $error )
			{
				echo "<li><font color='red'>{$error}</font></li>";
			}
			echo "</ul>";
		} else {
			wp_ebay_relevancead_add_template($_POST,'edit');
			echo "<div id='message' class='updated fade'><p><b>Erfolgreich <b>editiert</b>! <a href='?page=ebayrelevancead'>(zurück zur Übersicht)</a></b></p></div>";
		}
	}
	$template = wp_ebay_relevancead_fetch_template($id,false);
	$string = wp_ebay_relevancead_get_affilinet_template($template,'true');
	extract($template[0]);
	//$code_details = unserialize($options);
	//print_r($code_details);
	?>
	<div style="padding-left: 20px; padding-bottom: 30px;">
	<fieldset class='options'>
		<legend id='era_list'><b>Template '<?php echo $name; ?>' bearbeiten</b></legend>
			<table class='editform' width='100%' cellspacing='2' cellpadding='5'>
				<tr>
					<td width='33%' scope='row' valign='top'>Templatename</td>
					<td width='67%'><input type='text' name='name' size='72' value="<?php echo $name; ?>" /></td>
				</tr>
				<tr>
					<td width='33%' scope='row' valign='top'>Beschreibung</td>
					<td width='67%'><textarea name='description' cols='70' rows='5'><?php echo $description; ?></textarea></td>
				</tr>
				<tr>
					<td width='33%' scope='row' valign='top'>Affilinet Code Snippet<br/><small>Achtung: ( Änderungen können im schlimmsten Fall dazu führen, dass Ihre Klicks nicht gezählt werden oder das Banner nicht angezeigt wird)</small></td>
					<td width='67%'><textarea name='code_snipplet' cols='70' rows='15'><?php echo htmlspecialchars($string); ?></textarea></td>
				</tr>
				<tr>
					<td colspan='2'><input type='hidden' name='id' value="<?php echo $id; ?>" /><input type='submit' value='Template speichern' class='button' name='edit_page_submit' /></td>
				</tr>
			</table>
		</fieldset>
		</div>
	<?php
}

/**
* Function: wp_ebay_relevancead_show()
* Display the eBay RelevanceAd in your Template
* @since 0.1 BETA
* @param string $id;
* @para, string $return - Return the string if not exists
* @return string $output;
*/
function wp_ebay_relevancead_show($id = "", $return = false)
{
	$templates = wp_ebay_relevancead_fetch_template($id);
	if ( $return == true ) {
		return wp_ebay_relevancead_get_affilinet_template($templates,$return);
	} else {
		echo wp_ebay_relevancead_get_affilinet_template($templates,false);
	}
}

function wp_ebay_relevancead_get_affilinet_template($templates,$return = true)
{
	if ( is_array($templates)) {
		// Bestimmte die Werte aus der Datenbank
		if ( count ($templates) > 1 ) {
			// get a random entry of the ads
			$rand = rand(0,count($templates)-1);
			$template = $templates[$rand];
		} else {
			// just a singe entry
			$template = $templates[0];
		}
		// Standard gebe alles wichtige aus
		$era = "<!-- eBay RelevanceAd --> \n";
		$era .= "<script language='JavaScript' type='text/javascript'> \n".
				"//<-- DO NOT CHANGE --> \n".
				"// <!--<[CDATA[ \n";
		$options = unserialize($template['options']);
		foreach ( $options as $name => $value )
		{
			$era .= $name." = '".$value."';\n";
		}
		$era .= "// ]]> -->".
				"</script>";

		$era .= "<script language='JavaScript' type='text/javascript' src='http://ebayrelevancead.webmasterplan.com/js/show_ads.js'></script>\n";
		$era .= "<!-- /eBay RelevanceAd -->\n";
		if ( !$return) {
			print $era;
		} else {
			return $era;
		}
	} else {
		print "<!-- Kein eBay RelevanceAd gefunden -->";
	}
}

function wp_ebay_relevancead_replace_placeholders($content)
{
	$regex = @"/<!--([\s]+)?(ebayrelevancead)\@(.*)([\s]+)?-->/i";
	preg_match_all($regex,$content,$matches,PREG_SET_ORDER);
	foreach ( $matches as $match ) {
		$content = str_replace($match[0],wp_ebay_relevancead_show($match[3],true),$content);
	}
	return $content;
}


/**
* Function: add_wp_era_scripts()
* Includes all necessary javascript files
* @param none;
* @since 0.1 BETA
* @return void;
*/
function wp_ebay_relevancead_add_javascript()
{
	wp_print_scripts( array( 'sack' ));
}

function widget_wp_ebay_relevancead_init()
{
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) {
        return; // ...and if not, exit gracefully from the script.
  }

function era_widget($args) {

	// $args is an array of strings which help your widget
	// conform to the active theme: before_widget, before_title,
	// after_widget, and after_title are the array keys.
	extract($args);

  // Collect our widget's options, or define their defaults.
  $options     = get_option('widget_eBayRelevanceAd');
  $title       = empty($options['title']) ? 'eBay Relevance Ad' : $options['title'];
  $banner_name = empty($options['banner_name']) ? 'eBay Relevance Ad' : $options['banner_name'];
  $text        = wp_ebay_relevancead_show($banner_name, true);	
	
	// It's important to use the $before_widget, $before_title,
	// $after_title and $after_widget variables in your output.
	echo $before_widget;
	echo $before_title . $title . $after_title;
	echo $text;
	echo $after_widget;
	}

	function widget_era_control() {

			// Collect our widget's options.
			$options = get_option('widget_eBayRelevanceAd');
			// This is for handing the control form submission.
			
			if ( $_POST['widget_eBayRelevanceAd-submit'] )
			{
				// Clean up control form submission options
				$newoptions['title']       = strip_tags(stripslashes($_POST['widget_eBayRelevanceAd-title']));
				$newoptions['banner_name'] = strip_tags(stripslashes($_POST['widget_eBayRelevanceAd-banner-name']));
				
				$options = $newoptions;
			}

			// If original widget options do not match control form
			// submission options, update them.
      if ($options === false OR !is_array($options))
      {
        add_option('widget_eBayRelevanceAd', $options, '', 'yes');
      }
      else
      {
        update_option('widget_eBayRelevanceAd', $options);
      }

			// Format options as valid HTML. Hey, why not.
      $title       = htmlspecialchars($options['title'], ENT_QUOTES);
      $banner_name = htmlspecialchars($options['banner_name'], ENT_QUOTES); 
	?>
		<div>
			<?php
				$arr = wp_ebay_relevancead_fetch_template();
				if ( count($arr) == 0 ) {
					echo "<font color='red'>Bitte legen Sie zuerst einen Banner an, damit ein Werbebanner angezeigt werden kann.</font>";
				}
			?>
			<label for="widget_eBayRelevanceAd-title" style="line-height:35px;display:block;">Widget title: <input type="text" id="widget_eBayRelevanceAd-title" name="widget_eBayRelevanceAd-title" value="<?php echo $title; ?>" /></label>
			<label for="widget_eBayRelevanceAd-banner-name" style="line-height:35px;display:block;">Template: <input type="text" id="widget_eBayRelevanceAd-banner-name" name="widget_eBayRelevanceAd-banner-name" value="<?php echo $banner_name; ?>" /></label>
			<ul>
				<?php
				if ( count($arr) > 0 ) {
					foreach ( $arr as $item ) {
					?>
						<li><a href='#' onClick="document.getElementById('widget_eBayRelevanceAd-banner-name').value = '<?php echo $item['name']; ?>'"><?php echo $item['name']; ?></a></li>
					<?php
					}
				}
				?>
				</ul>
				<input type="hidden" name="widget_eBayRelevanceAd-submit" id="widget_eBayRelevanceAd-submit" value="1" />
			</div>
		<?php
	}

		// Registr the Sidebar Widget
		register_sidebar_widget('eBay Relevance Ad', 'era_widget');

    // We do this to set a description
    global $wp_registered_widgets;

    if (isset($wp_registered_widgets['ebay-relevancead']))
      $wp_registered_widgets['ebay-relevancead']['description'] = 'Mit diesem Widget können Sie eBay Relevance Ads in Ihrer Sidebar anzeigen lassen.';  
  		
		
		// register the sidebar widget control
		register_widget_control('eBay RelevanceAd', 'widget_era_control');
}



if (function_exists('register_activation_hook'))
{
  register_activation_hook(__FILE__, 'wp_ebay_relevancead_install');
}
else 
{
  add_action('activate_ebay_relevance_ad.php', 'wp_ebay_relevancead_install');
}

add_action('admin_head','wp_ebay_relevancead_add_ajax_script');
add_action('init','wp_ebay_relevancead_init');
add_action('admin_menu', 'wp_ebay_relevancead_add_pages');
add_action('admin_footer','wp_ebay_relevancead_add_javascript');
add_action('plugins_loaded', 'widget_wp_ebay_relevancead_init');
add_filter('the_content','wp_ebay_relevancead_replace_placeholders');

?>