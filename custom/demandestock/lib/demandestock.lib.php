<?php
/* Copyright (C) 2024 SuperAdmin
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    demandestock/lib/demandestock.lib.php
 * \ingroup demandestock
 * \brief   Library files with common functions for DemandeStock
 */

function demandestock_prepare_head(Demandestock $obj) {
	global $langs, $conf;

	$langs->load("demandestock@demandestock");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/demandestock/card.php", 2)."?id=".$obj->id;
	$head[$h][1] = $langs->trans("Demandestock");
	$head[$h][2] = 'card';
	$h++;

	$head[$h][0] = dol_buildpath("/demandestock/note.php", 2)."?id=".$obj->id;
	$head[$h][1] = $langs->trans("Notes");
	$head[$h][2] = 'note';
	$h++;

	return $head;
}

/**
 * Prepare admin pages header
 *
 * @return array
 */
function demandestockAdminPrepareHead()
{
	global $langs, $conf;

	// global $db;
	// $extrafields = new ExtraFields($db);
	// $extrafields->fetch_name_optionals_label('myobject');

	$langs->load("demandestock@demandestock");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/demandestock/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;

	/*
	$head[$h][0] = dol_buildpath("/demandestock/admin/myobject_extrafields.php", 1);
	$head[$h][1] = $langs->trans("ExtraFields");
	$nbExtrafields = is_countable($extrafields->attributes['myobject']['label']) ? count($extrafields->attributes['myobject']['label']) : 0;
	if ($nbExtrafields > 0) {
		$head[$h][1] .= ' <span class="badge">' . $nbExtrafields . '</span>';
	}
	$head[$h][2] = 'myobject_extrafields';
	$h++;
	*/

	$head[$h][0] = dol_buildpath("/demandestock/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@demandestock:/demandestock/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@demandestock:/demandestock/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, null, $head, $h, 'demandestock@demandestock');

	complete_head_from_modules($conf, $langs, null, $head, $h, 'demandestock@demandestock', 'remove');

	return $head;
}


function get_ds_type(){
	global $db, $conf, $langs;

	$ds_type = array();

	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."c_demandestock_type ";
	$sql .= "WHERE active = 1";

	// echo $sql ;
	// die;
	$resql = $db->query($sql);
	if ($resql){
		$num_rows = $db->num_rows($resql);
		while ($obj = $db->fetch_object($resql)){
			$ds_type[$obj->rowid] = $obj->label;
		}
		if ($num_rows > 0){
			return $ds_type;
		} else {
			return 0;
		}
	}else{
		setEventMessage("error".$db->lasterror(),"errors");
		return -1;
	}
}



function selectType($fieldname = '', $selected){

	$html =

	'<select id="'.dol_escape_htmltag($fieldname).'" name="'.dol_escape_htmltag($fieldname).'" class="flat miniwith200" >';
	$html .= '<option value ="-1"></option>';

	$ds_type  = get_ds_type();

	if (!empty($ds_type) && is_array($ds_type)){
		foreach ($ds_type as $key => $value) {
			$select = $key == $selected ?'selected':'';
			$html .= '<option '.$select.' value="'.dol_escape_htmltag($key).'">'.dol_escape_htmltag($value).'</option>';
		}
	}
	$html.='</select>';
	include_once DOL_DOCUMENT_ROOT.'/core/lib.php';
	$html .= ajax_combobox($fieldname);

	return $html;
}
