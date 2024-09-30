
<?php

class Demandestock extends CommonObject{

    public $element = 'demandestock';
    public $table_element = 'demandestock';
    public $lines = array();
    public $fk_project ;
    public $object_demande ;
    public $date_demande ;
    public $date_souhaite ;
    public $fk_warehouse ;
    public $fk_user_create;
    public $fk_user_modif;
    public $fk_user_valid;
    public $fk_statut ;
	public $type_demande;
	public $picto = "demandestock@demandestock" ;


    const STATUS_DRAFT = 0 ;
    const STATUS_VALIDATED = 1 ;
    const STATUS_SENDED = 2 ;
    const STATUS_EN_COURS = 3 ;
    const STATUS_REFUSED =  4 ;
    const STATUS_CLOSED =  5 ;


    public function __construct($db){
        $this->db = $db;

    }

	////////////////////////////////decalre the fields in here for the list not in the list card
	public $fields = array(
		'rowid'=> array('type'=> 'integer', 'label'=>'technicalID', 'enabled' => 1, 'visible' => '-1', "position" => 0),
		"ref"=> array("type"=> "varchar(30)", "label"=> "Ref",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"object_demande"=> array("type"=> "varchar(30)", "label"=> "ObjectDemande",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"fk_project"=> array("checked"=> 1 , "type"=> "integer:Project:projet/class/project.class.php", "label"=> "Prject Ref.",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"fk_warehouse"=> array("type"=> "integer:Entrepot:product/stock/class/entrepot.class.php", "label"=> "Warehouse",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"type_demande"=> array("type"=> "integer", "label"=> "TypeDemande",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"desired_date"=> array("type"=> "date", "label"=> "DateSouhaite",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"date_creation"=> array("type"=> "date", "label"=> "dateCreation",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"fk_user_author"=> array("type"=> "integer:User:user/class/user.class.php", "label"=> "Fkuserauthor",'enabled' => 1, 'visible' => '1', "position" => 0 ),
		"fk_statut"=> array("type"=> "integer", "label"=> "status",'enabled' => 1, 'visible' => '1', "position" => 0 ),
	);


    public function fetch($id, $ref = ""){

        // $this->db->begin();

        $sql = "SELECT ";
        $sql .= "d.rowid,";
        $sql .= "d.ref,";
        $sql .= "d.fk_project,";
        $sql .= "d.object_demande,";
		$sql .= "d.type_demande,"; //
        $sql .= "d.desired_date,";
        $sql .= "d.date_demande, ";
        $sql .= "d.date_creation,";
        $sql .= "d.fk_warehouse, ";
        $sql .= "d.fk_user_author,";
        $sql .= "d.fk_user_modif,";
        $sql .= "d.fk_user_valid,";
        $sql .= "d.date_modif,";
        $sql .= "d.date_valid,";
        $sql .= "d.fk_statut,";
        $sql .= "d.note_private,";
        $sql .= "d.note_public";

        $sql .= " from ".MAIN_DB_PREFIX."demandestock as d";
        $sql .= " where rowid = ".intval($id);


        $resql = $this->db->query( $sql);
        if ($resql) {
            $num = $this->db->num_rows( $resql);
            if ($num > 0) {
                # code ..
                $obj = $this->db->fetch_object($resql);

                $this->id = $obj->rowid;
                $this->ref = $obj->ref;
                $this->object_demande = $obj->object_demande;
                $this->fk_project = $obj->fk_project;
                $this->fk_warehouse = $obj->fk_warehouse;
                $this->fk_user_create = $obj->fk_user_author;
                $this->fk_user_modif = $obj->fk_user_modif;
                $this->fk_user_valid = $obj->fk_user_valid;
                $this->date_demande = $obj->date_demande;
				$this->date_souhaite = $obj->desired_date;
				$this->type_demande = $obj->type_demande; //
                $this->date_creation = $this->db->jdate(string: $obj->date_creation);
                $this->date_modification = $this->db->jdate(string: $obj->date_modif);
                $this->date_validation = $this->db->jdate(string: $obj->date_valid);
                $this->status = $obj->fk_statut;
                $this->note_private = $obj->note_private;
                $this->note_public = $obj->note_public;
            }
            $this->db->free($resql);
            if($num){
                return 1;
            }
            else{
                return 0;
            }


        }else{
            $this->error = "error :".$this->db->lasterror();
            return -1 ;

        }



    }

    public function create($user, $notrigger = 0){


        global $config , $hookamnger ;
        $error = 0;
        $now = dol_now();

        $sql = "INSERT INTO ".MAIN_DB_PREFIX."demandestock(";
        $sql .= "ref";
        $sql .= ", fk_project";
        $sql .= ", object_demande";
		$sql .= ", type_demande"; //
        $sql .= ", date_demande";
        $sql .= ", desired_date";
        $sql .= ", fk_warehouse";
        $sql .= ", fk_user_author";
        $sql .= ", fk_statut";
        $sql .= ", date_creation";
        $sql .= ") VALUES (";
        $sql.="'(PROV)'";
        $sql.=", ".($this->fk_project ? $this->fk_project: 'NULL');
        $sql.=",'".$this->object_demande."'";
        $sql.=",'".$this->type_demande."'"; //
        $sql.=",'".$this->db->idate( $this->date_demande)."'";
        $sql.=",'".$this->db->idate ( $this->date_souhaite)."'";

        $sql.=",".($this->fk_warehouse ? $this->fk_warehouse: 'NULL');
        $sql.=",".$user->id;
        $sql.=", ".self:: STATUS_DRAFT;
        $sql.=",'".$this->db->idate( $now)."'";
        $sql.=")";
        $resql = $this->db->query( $sql);

        // var_dump($sql);
        // die;

        if(!$resql){
            $error ++;
            $this->error[] = $this->db->lasterror();

        }
        if(!$error){
            $this->id = $this->db->last_insert_id($this->db->prefix().$this->table_element);
            $this->ref = "(PROV".$this->id.")";

            $sql = "UPDATE ".MAIN_DB_PREFIX. "demandestock SET ref = '" . $this->db->escape(stringtoencode: $this->ref) . "' WHERE rowid = " . $this->id ;
            $resqlupd = $this->db->query(query: $sql);
            if (!$resqlupd) {
                $error ++;
                $this->errors[] = $this->db->lasterror();
            }else{
                $this->ref = '(PROV' . $this->id . ')';
            }
        }

        // commit or rollback
        if($error){
            foreach($this->errors as $errormessage){
                $this->error .= ($this->error ? '<br> ':'').$errormessage;
            }
            $this->db->rollback();
            return -1;


        }else {
            $this->db->commit();
            return 1;
        }

	}
	////////////////////////////////////////////////////////////////////// FUCNTION DELETE FOR THE DEMANDESTOCK
	public function delete() {
		global $conf, $hookmanager;
		$error = 0;
		$this->db->begin();
		$sql = 'DELETE FROM '.MAIN_DB_PREFIX.'demandestock' ;
		$sql .= ' WHERE rowid = '.((int) $this->id) ;
		$resql = $this->db->query($sql);

		if(!$resql) {
			$error ++;
			$this->errors[] = $this->db->lasterror();
		}

		if (!$error) {
			$this->db->commit();
			return 1;
		}
		else{
			$this->db->rollback();
			return -1;
		}


	}

	////////////////////////////////////////////////////////////////////// FUCNTION VALIDATE FOR THE DEMANDESTOCK

	public function validate($user)
	{
		require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
		global $conf;

		if($this->status == self::STATUS_VALIDATED) return 0;

		$now = dol_now();

		$error = 0;
		dol_syslog(get_class($this).'::validate user='.$user->id);


		$this->db->begin();

		// Define new ref
		if (!$error && (preg_match('/^[\(]?PROV/i', $this->ref) || empty($this->ref))) { // empty should not happened, but when it occurs, the test save life
			$num = $this->getNextNumRef();
		}


		$this->newref = dol_sanitizeFileName($num);

		if ($num) {
			$sql = "UPDATE ".MAIN_DB_PREFIX."demandestock SET ref = '".$this->db->escape($num)."', fk_statut = 1";
			$sql.= ", fk_user_valid = ".$user->id.", date_valid = '".$this->db->idate($now)."'";
			$sql .= " WHERE rowid = ".((int) $this->id)." AND fk_statut = 0";

			dol_syslog(get_class($this)."::validate", LOG_DEBUG);
			$resql = $this->db->query($sql);
			if (!$resql) {
				dol_print_error($this->db);
				$error++;
				$this->error = $this->db->lasterror();
			}


			// if (!$error) {

			// 	$this->oldref = $this->ref;

			// 	// Rename directory if dir was a temporary ref
			// 	if (preg_match('/^[\(]?PROV/i', $this->ref)) {
			// 		// Now we rename also files into index
			// 		$sql = 'UPDATE '.MAIN_DB_PREFIX."ecm_files set filename = CONCAT('".$this->db->escape($this->newref)."', SUBSTR(filename, ".(strlen($this->ref) + 1).")), filepath = 'contract/".$this->db->escape($this->newref)."'";
			// 		$sql .= " WHERE filename LIKE '".$this->db->escape($this->ref)."%' AND filepath = 'contract/".$this->db->escape($this->ref)."' and entity = ".$conf->entity;
			// 		$resql = $this->db->query($sql);
			// 		if (!$resql) {
			// 			$error++;
			// 			$this->error = $this->db->lasterror();
			// 		}
			// 		$sql = 'UPDATE '.MAIN_DB_PREFIX."ecm_files set filepath = 'contract/".$this->db->escape($this->newref)."'";
			// 		$sql .= " WHERE filepath = 'contract/".$this->db->escape($this->ref)."' and entity = ".$conf->entity;
			// 		$resql = $this->db->query($sql);
			// 		if (!$resql) {
			// 			$error++;
			// 			$this->error = $this->db->lasterror();
			// 		}

			// 		// We rename directory ($this->ref = old ref, $num = new ref) in order not to lose the attachments
			// 		$oldref = dol_sanitizeFileName($this->ref);
			// 		$newref = dol_sanitizeFileName($num);
			// 		$dirsource = $conf->contract->dir_output.'/'.$oldref;
			// 		$dirdest = $conf->contract->dir_output.'/'.$newref;
			// 		if (!$error && file_exists($dirsource)) {
			// 			dol_syslog(get_class($this)."::validate rename dir ".$dirsource." into ".$dirdest);

			// 			if (@rename($dirsource, $dirdest)) {
			// 				dol_syslog("Rename ok");
			// 				// Rename docs starting with $oldref with $newref
			// 				$listoffiles = dol_dir_list($conf->contract->dir_output.'/'.$newref, 'files', 1, '^'.preg_quote($oldref, '/'));
			// 				foreach ($listoffiles as $fileentry) {
			// 					$dirsource = $fileentry['name'];
			// 					$dirdest = preg_replace('/^'.preg_quote($oldref, '/').'/', $newref, $dirsource);
			// 					$dirsource = $fileentry['path'].'/'.$dirsource;
			// 					$dirdest = $fileentry['path'].'/'.$dirdest;
			// 					@rename($dirsource, $dirdest);
			// 				}
			// 			}
			// 		}
			// 	}
			// }

			// Set new ref and define current statut
			if (!$error) {


				$this->ref = $num;
				$this->status = self::STATUS_VALIDATED;
				$this->status = self::STATUS_VALIDATED;
				$this->date_validation = $now;
			}
		} else {
			$error++;
		}

		if (!$error) {
			$this->db->commit();
			return 1;
		} else {
			$this->db->rollback();
			return -1;
		}
	}


	 /////////////////////////////////// get name of the ref for the list.php
	/**
	 *  Return a link to the object card (with optionaly the picto)
	 *
	 *  @param  int     $withpicto                  Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
	 *  @param  string  $option                     On what the link point to ('nolink', ...)
	 *  @param  int     $notooltip                  1=Disable tooltip
	 *  @param  string  $morecss                    Add more css on link
	 *  @param  int     $save_lastsearch_value      -1=Auto, 0=No save of lastsearch_values when clicking, 1=Save lastsearch_values whenclicking
	 *  @return	string                              String with URL
	 */
	public function getNomUrl($withpicto = 0, $option = '', $notooltip = 0, $morecss = '', $save_lastsearch_value = -1)
	{
		global $conf, $langs, $hookmanager;

		if (!empty($conf->dol_no_mouse_hover)) {
			$notooltip = 1; // Force disable tooltips
		}

		$result = '';
		$params = [
			'id' => $this->id,
			'objecttype' => $this->element.($this->module ? '@'.$this->module : ''),
			'option' => $option,
		];
		$classfortooltip = 'classfortooltip';
		$dataparams = '';
		if (getDolGlobalInt('MAIN_ENABLE_AJAX_TOOLTIP')) {
			$classfortooltip = 'classforajaxtooltip';
			$dataparams = ' data-params="'.dol_escape_htmltag(json_encode($params)).'"';
			$label = '';
		} else {
			$label = implode($this->getTooltipContentArray($params));
		}

		$url = dol_buildpath('/demandestock/card.php', 1).'?id='.$this->id;

		if ($option !== 'nolink') {
			// Add param to save lastsearch_values or not
			$add_save_lastsearch_values = ($save_lastsearch_value == 1 ? 1 : 0);
			if ($save_lastsearch_value == -1 && isset($_SERVER["PHP_SELF"]) && preg_match('/list\.php/', $_SERVER["PHP_SELF"])) {
				$add_save_lastsearch_values = 1;
			}
			if ($url && $add_save_lastsearch_values) {
				$url .= '&save_lastsearch_values=1';
			}
		}

		$linkclose = '';
		if (empty($notooltip)) {
			if (getDolGlobalInt('MAIN_OPTIMIZEFORTEXTBROWSER')) {
				$label = $langs->trans("ShowDemandestock");
				$linkclose .= ' alt="'.dol_escape_htmltag($label, 1).'"';
			}
			$linkclose .= ($label ? ' title="'.dol_escape_htmltag($label, 1).'"' : ' title="tocomplete"');
			$linkclose .= $dataparams.' class="'.$classfortooltip.($morecss ? ' '.$morecss : '').'"';
		} else {
			$linkclose = ($morecss ? ' class="'.$morecss.'"' : '');
		}

		if ($option == 'nolink' || empty($url)) {
			$linkstart = '<span';
		} else {
			$linkstart = '<a href="'.$url.'"';
		}
		$linkstart .= $linkclose.'>';
		if ($option == 'nolink' || empty($url)) {
			$linkend = '</span>';
		} else {
			$linkend = '</a>';
		}

		$result .= $linkstart;

		if (empty($this->showphoto_on_popup)) {
			if ($withpicto) {
				$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), (($withpicto != 2) ? 'class="paddingright"' : ''), 0, 0, $notooltip ? 0 : 1);
			}
		} else {
			if ($withpicto) {
				require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

				list($class, $module) = explode('@', $this->picto);
				$upload_dir = $conf->$module->multidir_output[$conf->entity]."/$class/".dol_sanitizeFileName($this->ref);
				$filearray = dol_dir_list($upload_dir, "files");
				$filename = $filearray[0]['name'];
				if (!empty($filename)) {
					$pospoint = strpos($filearray[0]['name'], '.');

					$pathtophoto = $class.'/'.$this->ref.'/thumbs/'.substr($filename, 0, $pospoint).'_mini'.substr($filename, $pospoint);
					if (!getDolGlobalString(strtoupper($module.'_'.$class).'_FORMATLISTPHOTOSASUSERS')) {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><div class="photoref"><img class="photo'.$module.'" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div></div>';
					} else {
						$result .= '<div class="floatleft inline-block valignmiddle divphotoref"><img class="photouserphoto userphoto" alt="No photo" border="0" src="'.DOL_URL_ROOT.'/viewimage.php?modulepart='.$module.'&entity='.$conf->entity.'&file='.urlencode($pathtophoto).'"></div>';
					}

					$result .= '</div>';
				} else {
					$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'"'), 0, 0, $notooltip ? 0 : 1);
				}
			}
		}

		if ($withpicto != 2) {
			$result .= $this->ref;
		}

		$result .= $linkend;
		//if ($withpicto != 2) $result.=(($addlabel && $this->label) ? $sep . dol_trunc($this->label, ($addlabel > 1 ? $addlabel : 0)) : '');

		global $action, $hookmanager;
		$hookmanager->initHooks(array($this->element.'dao'));
		$parameters = array('id' => $this->id, 'getnomurl' => &$result);
		$reshook = $hookmanager->executeHooks('getNomUrl', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks
		if ($reshook > 0) {
			$result = $hookmanager->resPrint;
		} else {
			$result .= $hookmanager->resPrint;
		}

		return $result;
	}


	/////////////method to get the ref for demende when validated
	public function getNextNumRef()
	{
		global $langs, $conf;
		$langs->load("demandestock@demandestock"); //changed the domain to demandestock@demandestock

		if (!getDolGlobalString('DEMANDESTOCK_ADDON')) { // changed DEMANDESTOCK_ADDON
			$conf->global->DEMANDESTOCK_ADDON = 'mod_demandestock_standard'; // changed DEMANDESTOCK_ADDON
		}

		if (getDolGlobalString('DEMANDESTOCK_ADDON')) { // changed DEMANDESTOCK_ADDON
			$mybool = false;

			$file = getDolGlobalString('DEMANDESTOCK_ADDON').".php"; // changed DEMANDESTOCK_ADDON
			$classname = getDolGlobalString('DEMANDESTOCK_ADDON'); // changed DEMANDESTOCK_ADDON

			// Include file with class
			$dirmodels = array_merge(array('/'), (array) $conf->modules_parts['models']);
			foreach ($dirmodels as $reldir) {
				$dir = dol_buildpath($reldir."/custom/demandestock/core/modules/demandestock/"); //changed this link



				// Load file with numbering class (if found)
				$mybool |= @include_once $dir.$file;
			}

			if ($mybool === false) {
				dol_print_error('', "Failed to include file ".$file);
				return '';
			}

			if (class_exists($classname)) {
				$obj = new $classname();
				$numref = $obj->getNextValue($this);

				if ($numref != '' && $numref != '-1') {
					return $numref;
				} else {
					$this->error = $obj->error;
					//dol_print_error($this->db,get_class($this)."::getNextNumRef ".$obj->error);
					return "";
				}
			} else {
				print $langs->trans("Error")." ".$langs->trans("ClassNotFound").' '.$classname;
				return "";
			}
		} else {
			print $langs->trans("ErrorNumberingModuleNotSetup", $this->element);
			return "";
		}
	}



	public function getLibStatut($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}

	// phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 *    	Return label of a status (draft, validated, ...)
	 *
	 *    	@param      int			$status		Id status
	 *    	@param      int			$mode      	0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
	 *    	@return     string		Label
	 */
	public function LibStatut($status, $mode = 0)
	{

		// Init/load array of translation of status
		if (empty($this->labelStatus) || empty($this->labelStatusShort)) {
			global $langs;
			$this->labelStatus[self::STATUS_DRAFT] = $langs->transnoentitiesnoconv("Draft");
			$this->labelStatus[1] = $langs->transnoentitiesnoconv("Validated");
			$this->labelStatus[2] = $langs->transnoentitiesnoconv("Disabled");

		}

		if ($status == self::STATUS_DRAFT) {
			$statusType = 'status0';
		} elseif ($status == self::STATUS_VALIDATED) {
			$statusType = 'status1';
		} elseif ($status == self::STATUS_EN_COURS) {
			$statusType = 'status4';
		} elseif ($status == self::STATUS_REFUSED) {
			$statusType = 'status9';
		} elseif ($status == self::STATUS_CLOSED) {
			$statusType = 'status6';
		}


		return dolGetStatus($this->labelStatus[$status], $this->labelStatusShort[$status], '', $statusType, $mode);
	}
}

