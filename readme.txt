=== Temporary Login Without Password ===
Contributors: storeapps, niravmehta, malayladu
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPTHCDC382KVA
Tags: admin login, custom login, customer access, customer login, secure login, access, admin, log in, login, login security, protection, user login, user login, wordpress admin login, wordpress login, wp-admin, wp-login, expiration, login, Login Without Password, temporary login, user, WordPress Admin, wp-admin, developer account, developer login, developer account, passwordless login, password less login
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create self-expiring, temporary admin accounts. Easily share direct login links (no need for username / password) with your developers or editors.

== Description ==

Create self-expiring, automatic login links for WordPress. Give them to developers when they ask for admin access to your site. Or an editor for a quick review of work done. Login works just by opening the link, no password needed.

Using "Temporary Login Without Password" plugin you can create a self expiring account for someone and give them a special link with which they can login to your WordPress without needing a username and password.

You can choose when the login expires, as well as the role of the temporary account. 

Really useful when you need to give admin access to a developer for support or for performing routine tasks. 

Use this plugin along with [WP Security Audit Log](https://wordpress.org/plugins/wp-security-audit-log/) plugin to track what the person does after loggin in. 


**Spread The Word**

If you like Temporary Login Without Password, please leave a five star review on WordPress and also spread the word about it. That helps fellow website owners assess Temporary Login Without Password easily and benefit from it!

== Installation ==

1. Unzip downloaded folder and upload it to 'wp-content/plugins/' folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Users > Temporary Login section and create a new temporary login. 

== Screenshots ==

1. Create a new temporary login. 
2. List of all (Active/Expired) temporary logins.

== Changelog ==

= 1.4.2 =

- Bug Fixed: Uncaught Error: Call to undefined function wc_enqueue_js().

= 1.4.1 =

- Added: Now, create a temporary login with custom expiry date.

= 1.4 =

- Added: Support for "Theme My Login" plugin. Now, temporary user will be redirected to page which is defined in Theme My Login plugin.

= 1.3 =

- Bug Fixed: Temporary user is able to login with email address. Now onwards, temporary user is not able to login using username/email and password
- Bug Fixed: Temporary user was able to reset password. Now onwards, they won't be able to reset password.
- Added: Now, role of temporary user is downgrade to "none" on deactivation of plugin and change to default on re activation of plugin

= 1.2 =

- Bug Fixed: Temporary user is able to login with username and password. 

= 1.1 =

- Bug Fixed: Temporary user redirected to login page instead of admin dashboard after successful login. 

= 1.0 =

- Initial Release
