<?php
/*
 * this file will create the xml string
 */
$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$xml .= '<LeadImport>';

$xml .= '<SourceUrl>' . $entry['source_url'] . '</SourceUrl>';

foreach(GravityFormCustomCRM::$gftooltips_default as $key=>$value){
	$xml .= '<' . GravityFormCustomCRM::$xml_keys_dfaults[$key] . '>' . $entry[$form['customcrm_'.$key]] . '</' . GravityFormCustomCRM::$xml_keys_dfaults[$key] . '>'; 
}

$xml .= '</LeadImport>';

return;