<?php

if (!defined('e107_INIT'))
{
	exit;
}

// Load language file for the contact plugin
e107::lan('contact', 'lan_contact');

// Load parameters from Menu Manager

// Assign caption and subtitle, using the current language if available, or fallback to default
$caption = $parm['caption'][e_LANGUAGE] ?? $parm['caption'] ?? null;
$subtitle = $parm['subtitle'][e_LANGUAGE] ?? $parm['subtitle'] ?? null;

// Assign template key, with 'default' as a fallback
$template_key = $parm['template'] ?? 'default';

// Prepare contact form URL and form elements
$contact_url = e107::url('contact', 'index');
$form = e107::getForm();
$head = $form->open('contact-menu', 'POST', $contact_url);
$foot = $form->close();

// Get the appropriate template and shortcodes
$template = e107::getTemplate('contact', 'contact_menu', $template_key);
$contact_shortcodes = e107::getScBatch('form', 'contact', false);
$contact_shortcodes->wrapper('contact_menu/menu');

// Parse the template
$text = e107::getParser()->parseTemplate($head . $template['form'] . $foot, true, $contact_shortcodes);

// Handle tablestyle with fallback
$default_tablestyle = $template['tablestyle'] ?? 'contact-menu';
$tablestyle = $parm['tablestyle'] ?? $default_tablestyle;

// Handle caption rendering if template caption is provided
if (isset($template['caption']) && $caption)
{
	$var = [
		'MENU_TITLE'    => $caption,
		'MENU_SUBTITLE' => $subtitle,
	];
	$caption = e107::getParser()->simpleParse($template['caption'], $var);
}

// Render the menu using e107's tablerender method
e107::getRender()->tablerender($caption, $text, $tablestyle);
