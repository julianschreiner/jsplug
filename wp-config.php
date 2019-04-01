<?php
/**
 * Grundeinstellungen für WordPress
 *
 * Zu diesen Einstellungen gehören:
 *
 * * MySQL-Zugangsdaten,
 * * Tabellenpräfix,
 * * Sicherheitsschlüssel
 * * und ABSPATH.
 *
 * Mehr Informationen zur wp-config.php gibt es auf der
 * {@link https://codex.wordpress.org/Editing_wp-config.php wp-config.php editieren}
 * Seite im Codex. Die Zugangsdaten für die MySQL-Datenbank
 * bekommst du von deinem Webhoster.
 *
 * Diese Datei wird zur Erstellung der wp-config.php verwendet.
 * Du musst aber dafür nicht das Installationsskript verwenden.
 * Stattdessen kannst du auch diese Datei als wp-config.php mit
 * deinen Zugangsdaten für die Datenbank abspeichern.
 *
 * @package WordPress
 */
if (
    isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && 
    $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https"
) {
    $_SERVER["HTTPS"] = "on";
}

define('WP_DEBUG', true);
define('WP_MEMORY_LIMIT', '256M');
define('WP_HOME','https://system.drinktesla.com');
define('WP_SITEURL','https://system.drinktesla.com');


// ** MySQL-Einstellungen ** //
/**   Diese Zugangsdaten bekommst du von deinem Webhoster. **/

/**
 * Ersetze datenbankname_hier_einfuegen
 * mit dem Namen der Datenbank, die du verwenden möchtest.
 */
define( 'DB_NAME', 'db12504610-shop' );

/**
 * Ersetze benutzername_hier_einfuegen
 * mit deinem MySQL-Datenbank-Benutzernamen.
 */
define( 'DB_USER', 'db12504610-js' );

/**
 * Ersetze passwort_hier_einfuegen mit deinem MySQL-Passwort.
 */
define( 'DB_PASSWORD', 'gegenwart4You!' );

/**
 * Ersetze localhost mit der MySQL-Serveradresse.
 */
define( 'DB_HOST', 'localhost' );

/**
 * Der Datenbankzeichensatz, der beim Erstellen der
 * Datenbanktabellen verwendet werden soll
 */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Der Collate-Type sollte nicht geändert werden.
 */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden untenstehenden Platzhaltertext in eine beliebige,
 * möglichst einmalig genutzte Zeichenkette.
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * kannst du dir alle Schlüssel generieren lassen.
 * Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten
 * Benutzer müssen sich danach erneut anmelden.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']7~Muq~*JGjZsWqI,a{F8X-V[(u@$16|`?!bzLO9_`WdH.s!)A}<YBUL%vHxoCPU' );
define( 'SECURE_AUTH_KEY',  '=iY4bjBr K0hPlNp^tlry{EsJPRwL=@P#%x,PSJ%0b#0v%g?4$>V zQe7RTK8gKK' );
define( 'LOGGED_IN_KEY',    'D@+rT+`XeT08Y./$TD>7w$7 4.S1hLeGRH}_@w/}F+kdXSE&_E^;P@FRbS},E5-]' );
define( 'NONCE_KEY',        ' K+Yu*t_K#?&%a@}(;5`hJ*0=-pT, V#G%VF(GfANy8GodweHjpL#*l9_>b%`ME|' );
define( 'AUTH_SALT',        'Ttj[?}l3UN%B>`GiHuF~l5wdnM?ZU+.S#_.V+7*HL~@lNB;#oxH3vC%fzu1O!,Y6' );
define( 'SECURE_AUTH_SALT', 'WqR2+|ih?k=qZaTwl41wd2wigz<ODqsa<C&Z6$F{@<KEH#?g_ ]K()i1FNgpd/HF' );
define( 'LOGGED_IN_SALT',   ';&_7$=65|rf/Mh<|^Fv:B)vW(H0tnuW#?z_uOIdscXxgL>7GY#fXX2!7m{%CP=qO' );
define( 'NONCE_SALT',       'D=yZJh:$MmA_XbXma]$Z79,NnfO|LJNCW1M&LkBqfQs,CZ[R^wiK=9jkhq/XrZH-' );

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 * Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 * verschiedene WordPress-Installationen betreiben.
 * Bitte verwende nur Zahlen, Buchstaben und Unterstriche!
 */
$table_prefix = 'wp_';

/**
 * Für Entwickler: Der WordPress-Debug-Modus.
 *
 * Setze den Wert auf „true“, um bei der Entwicklung Warnungen und Fehler-Meldungen angezeigt zu bekommen.
 * Plugin- und Theme-Entwicklern wird nachdrücklich empfohlen, WP_DEBUG
 * in ihrer Entwicklungsumgebung zu verwenden.
 *
 * Besuche den Codex, um mehr Informationen über andere Konstanten zu finden,
 * die zum Debuggen genutzt werden können.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
/* Das war’s, Schluss mit dem Bearbeiten! Viel Spaß. */
/* That's all, stop editing! Happy publishing. */

/** Der absolute Pfad zum WordPress-Verzeichnis. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Definiert WordPress-Variablen und fügt Dateien ein.  */
require_once( ABSPATH . 'wp-settings.php' );