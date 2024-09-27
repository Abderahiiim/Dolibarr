<?php
require_once DOL_DOCUMENT_ROOT ."/core/class/commondocgenerator.class.php";




abstract class ModeleNumRefDemandestock{

	public $error = '';

	public function isEnabled(){
		return true;
	}

	public function info(){
		global $langs;
		$langs->load('demandestock@demandestock');
		return $langs->trans('NoDescription');

	}

	public function canBeActivated(){
		return true;
	}

	public function getNextValue($demandeStock){
		global $langs;
		return $langs->trans('NotAvailable');
	}

}
