<?php
namespace classes;

/*
$this->simpletable->setTable('TABELLE');
$this->simpletable->setPrimaryKey('PRIMARY_KEY_FIELD_NAME');

$fields = array(array('field'=>'FIELDNAME','caption'=>'CAPTION'), 
		);
$this->simpletable->useFields('edit', $fields);
$this->simpletable->useFields('view', $fields);
$this->simpletable->useFields('list', $fields);		

$html = $this->simpletable->manage($QS);

*/
class simpletable {
	public $fw;
	public $id;
	public $params = array();
	public $CONFIG = array();
	public $defaultLink = '*/*';
        protected $filterList = '';
        protected $filterListField = '';
        public $setDeletedDateField = '';
	protected $action = 'list';
	protected $entryId = 0;
	protected $orderBy = '';
	protected $groupBy = '';
	public $disablePrefixCheck = false;
	protected $hideEditLinks = false;

	protected $saveButtonCaption = 'speichern';
	protected $editLinkCallbackObj = '';
        protected $editLinkCallback = '';
        
        protected $extraLinkCallbackObj = '';
        protected $extraLinkCallback = '';
        
        protected $editTemplateFileName = 'tpl.simpletable_edit.php';
        protected $listTemplateFileName = 'tpl.simpletable_list.php';
        protected $viewTemplateFileName = 'tpl.simpletable_view.php';
        protected $extrahidden = "";
        protected $hideSaveButton = "";
        protected $belowInsideForm = "";
        protected $ontopInsideForm = "";
        protected $cancelButtonLink = "";

        protected $addActionIcons = array();
        
        protected $tplModifies = array();
        public $viewDeleted = false;
        
        /**
        * Template-Variablen je View, die mit setVariable ins Template gesetzt werden.
        */
        protected $tplVars = array();
        
        protected $editable = true;
        
	public function __construct($params=array()) {
		// {{{
		$this->params = $params;
		$this->fw = $GLOBALS["FastFW"];
		$this->disablePrefixCheck = false;

                $this->cancelButtonLink = getLink('*/*');
                
                $this->tplModifies = array( 'view' => array(), 'list' => array(), 'edit' => array() );
                
		$this->CONFIG['start'] = (isset($this->fw->QS[2]) ? (int)$this->fw->QS[2] : 0);
		$this->CONFIG['viewPerPage'] = 10;
		$this->CONFIG['where'] = array('1');
		$this->CONFIG['title'] = '';
		$this->action = (isset($this->fw->QS[2]) ? $this->fw->QS[2] : '');
		if($this->action=='') $this->action = 'list';
		$this->entryId = (isset($this->fw->QS[3]) ? (int)$this->fw->QS[3] : 0);
		
                if(isset($_GET['st']['perPage'])) {
                    $this->CONFIG['viewPerPage'] = (int)$_GET['st']['perPage'];
                }

                $this->viewDeleted = false;
		#$this->CONFIG['viewPerPage'] = 10;
		
		// }}}
	}

        public function notEditable() {
            $this->editable = false;
        }
        
        public function addTplModify($part, $methodArray) {
            $this->tplModifies[$part][] = $methodArray;
        }
        
        public function addActionIcons($data) {
            $this->addActionIcons[] = $data;
        }
        
        public function addVar($view, $key, $value) {
        	$this->tplVars[$view][$key] = $value;
        }
        
	public function setTemplate($type, $filename) {
	    if($type=='edit') $this->editTemplateFileName = $filename;
            else if($type=='list') $this->listTemplateFileName = $filename;
            else if($type=='view') $this->viewTemplateFileName = $filename;
	}
	public function setSaveButtonCaption($caption) {
	    $this->saveButtonCaption = $caption;
	}
	public function setCancelButtonLink($link) {
	    $this->cancelButtonLink = $link;
	}
	public function setBelowInsideForm($html) {
	    $this->belowInsideForm = $html;
	}
	public function setOntopInsideForm($html) {
	    $this->ontopInsideForm = $html;
	}
	public function hideSaveButton() {
	    $this->hideSaveButton = "1";
	}
	public function setExtraHidden($hidden) {
	    $this->extrahidden = $hidden;
	}

	public function setEditlinkCallback($obj, $func) {
	    $this->editLinkCallbackObj = $obj;
	    $this->editLinkCallback = $func;
	}

	public function setExtralinkCallback($obj, $func) {
	    $this->extraLinkCallbackObj = $obj;
	    $this->extraLinkCallback = $func;
	}
        
        public function getAction() {
            return $this->action;
        }
        public function setAction($action) {
            $this->action = $action;
        }
        public function getEntryID() {
            return $this->entryId;
        }
        public function setEntryID($id) {
            $this->entryId = $id;
        }
	public function hideEditLinks($hide) {
	    $this->hideEditLinks = $hide;
	}
	function setDefaultLink($link) {
		$this->defaultLink = $link;
		$nrx = explode('/', $this->defaultLink);
		$nr = count($nrx);
		
		$this->CONFIG['start'] = (int)$this->fw->QS[$nr];
		$this->action = $this->fw->QS[$nr];
		if($this->action=='') $this->action = 'list';
		$this->entryId = (int)$this->fw->QS[$nr+1];
		
	}
	function setTitle($title) {
		// {{{
		$this->CONFIG['title'] = $title;
		// }}}
	}
	function setTable($table) {
		// {{{
		$this->CONFIG['table'] = $table;
		// }}}
	}
	function setPrimaryKey($pk) {
		// {{{
		$this->CONFIG['primaryKey'] = $pk;
		// }}}
	}
	function setOrderBy($orderBy) {
		$this->orderBy = $orderBy; 
	}
	function getOrderBy() {
		return $this->orderBy; 
	}
	
	function setViewPerPage($anz=10) {
		$this->CONFIG['viewPerPage'] = $anz;
	}
	function getViewPerPage() {
		return $this->CONFIG['viewPerPage'];
	}
	
	function setGroupBy($groupBy) {
		$this->groupBy = $groupBy; 
	}
	function addWhere($where) {
		// {{{
		$this->CONFIG['where'][] = $where;
		// }}}
	}

        function setFilter($filter, $field) {
            $this->filterList = $filter;
            $this->filterListField = $field;
        }

	function setTableJoin($tables,  $prefixConnect, $joinedFk) {
                $this->addTableJoin($tables,  $prefixConnect, $joinedFk);
	}
	function addTableJoin($tables,  $prefixConnect, $joinedFk) {
		$this->CONFIG['jointables'][] = $tables;
		$this->CONFIG['joinprefix'][] = $prefixConnect;
		$this->CONFIG['joinfk'][] = $joinedFk;

	}

        function setListQuery($Q) {
            $this->CONFIG['listQuery'] = $Q;
        }

	function getList() {
		// {{{

                if(isset($this->CONFIG['listQuery'])) {
                    $Q = $this->CONFIG['listQuery'];
                } else {
                    $Q = "SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->CONFIG['table']." ";
                    if(isset($this->CONFIG['jointables'])) {
                        for($i=0;$i<count($this->CONFIG['jointables']);$i++) {
                            $Q .= " INNER JOIN ".$this->CONFIG['jointables'][$i]." ON ".$this->CONFIG['joinfk'][$i].(stristr($this->CONFIG['joinfk'][$i],'=') ? '' : "=".$this->CONFIG['primaryKey']);
                        }
                    }
                }

                $WHERE = $this->CONFIG['where'];

                if(isset($_GET['filterAlpha']) && $_GET['filterAlpha']!='') {
                    if($_GET['filterAlpha']=='-') $_GET['filterAlpha'] = '';
                    $this->setS('filterAlpha', $_GET['filterAlpha']);
                }
                if($this->getS('filterAlpha') && $this->filterList=='filterAlpha') {
                    if($this->getS('filterAlpha')=='!') {
                        for($i=ord('a');$i<=ord('z');$i++) {
                            $WHERE[] = $this->filterListField." NOT LIKE '".chr($i)."%'";
                        }
                    } else {
                        $W = "(0";
                        $b1 = ord(strtolower(substr($this->getS('filterAlpha'),0,1)));
                        $b2 = ord(strtolower(substr($this->getS('filterAlpha'),1,1)));
                        for($i=$b1;$i<=$b2;$i++) {
                             $W .= ' OR '.$this->filterListField." like '".chr($i)."%'";
                        }
                        $W .= ")";
                        $WHERE[] = $W;
                    }
                }

                if($this->setDeletedDateField!='' && $this->viewDeleted==false) {
                    $WHERE[] = " ".$this->setDeletedDateField."='0000-00-00 00:00:00' ";
                }

		$Q .= " WHERE ".implode($WHERE,' AND ');
                if($this->groupBy!='') $Q .= " GROUP BY ".$this->groupBy." ";
		if($this->orderBy!='') $Q .= " ORDER BY ".$this->orderBy." ";
                
                if(isset($_GET["sq"]) && $_GET["sq"]==1) {
                    $Q = getS('savedQuery_'.$this->fw->QS[0].'_'.$this->fw->QS[1]);
                } else {
                    setS('savedQuery_'.$this->fw->QS[0].'_'.$this->fw->QS[1], $Q);
                }
                
		$Q .= " LIMIT ".max(0,$this->CONFIG['start']).",".max(1,$this->CONFIG['viewPerPage']);
		#$Q .= " LIMIT ".$this->CONFIG['start'].",".max(1,$this->CONFIG['viewPerPage']);
		$this->listQuery = $Q;
#vd($Q);exit;
                $this->listdata = $this->processListData($this->fw->DC->getAllByQuery($Q));
		$this->countAll = $this->fw->DC->getByQuery('SELECT FOUND_ROWS() AS cnt', 'cnt'); 
		return($this->listdata);
		// }}}
	}
        
        public function processListData($data) {
            return $data;
        }
	
	function useFields($view, $fields) {
		// {{{
		$F = array();
		/*
		for($i=0;$i<count($fields);$i++) {
			// {{{
			if(!is_array($fields[$i])) $fields[$i] = array($fields[$i],$fields[$i]);
			// }}}
		}
		*/
		for($i=0;$i<count($fields);$i++) {
			if(!isset($fields[$i]['type'])) $fields[$i]['type'] = 'text';
			if($fields[$i]['type']=='select' || $fields[$i]['type']=='multiselect' || $fields[$i]['type']=='checkbox' || $fields[$i]['type']=='radio') {
				if(isset($fields[$i]['valuestexts']) && is_array($fields[$i]['valuestexts'])) {
					$fields[$i]['values'] = array();
					$fields[$i]['texts'] = array();
					for($j=0;$j<count($fields[$i]['valuestexts']);$j++) {
						$fields[$i]['values'][] = $fields[$i]['valuestexts'][$j]['value'];
						$fields[$i]['texts'][] = $fields[$i]['valuestexts'][$j]['text'];
					}
					
				} else {
					if(isset($fields[$i]['values'])) {
                                            if(!is_array($fields[$i]['values'])) $fields[$i]['values'] = explode("|", $fields[$i]['values']);
                                        }
                                        if(isset($fields[$i]['texts'])) {
                                            if($fields[$i]['texts']=='') $fields[$i]['texts'] = $fields[$i]['values'];
                                            if(!is_array($fields[$i]['texts'])) $fields[$i]['texts'] = explode("|", $fields[$i]['texts']);
                                        }
				}
				$fields[$i]['value2text'] = array();
                                if(isset($fields[$i]['values'])) {
                                    for($j=0;$j<count($fields[$i]['values']);$j++) {
                                            $fields[$i]['value2text'][$fields[$i]['values'][$j]] = $fields[$i]['texts'][$j];
                                    }
                                }
			}
		}
		
		$this->CONFIG['fieldList'][$view] = $fields;
		// }}}
	}
	
	function pages() {
		// {{{
		$p = ceil($this->countAll / $this->CONFIG['viewPerPage']);
                if($p<=1) return;
		$html = "";
		for($i=0;$i<$p;$i++) {
			// {{{
			$html .= "<a href='".getLink($this->defaultLink.'/'.$i*$this->CONFIG['viewPerPage'])."'>[".($i+1)."]</a> ";
			// }}}
		}
		return($html);
		// }}}
	}
	
	
	function createListTable($data=NULL) {
		// {{{
		if($data==NULL) $data = $this->listdata;
	
		$tpl = new \classes\template(); // $this->fw->fw_useSingleClass('template');
#vd($this->CONFIG['fieldList']['list']);
		$tpl->setVariable('fieldlist', $this->CONFIG['fieldList']['list'] );
		$tpl->setVariable('data', $data );
		$tpl->setVariable('primaryKey', $this->CONFIG['primaryKey'] );
                
                if($this->filterList=='filterAlpha') {
                    $tpl->setVariable('filterAlpha', 1);
                    $tpl->setVariable('filterAlphaSelect', $this->getS('filterAlpha'));
                }

		$tpl->setVariable('title', $this->CONFIG['title']);
		$tpl->setVariable('pages', $this->pages() );
		
                $tpl->setVariable('pages_countElements', $this->countAll );
                $tpl->setVariable('pages_elementsPerPage', $this->CONFIG['viewPerPage'] );
                $tpl->setVariable('pages_count', ceil($this->countAll/$this->CONFIG['viewPerPage']) );
                $tpl->setVariable('pages_activePage', floor($this->CONFIG['start']/$this->CONFIG['viewPerPage']) );
                $tpl->setVariable('pages_start', $this->CONFIG['start'] );
                
                $tpl->setVariable('editable', $this->editable);
                
		$tpl->setVariable('defaultLink', $this->defaultLink );
		
		if($this->editLinkCallback!='') {
		    $tpl->setVariable('editLinkCallbackObj', $this->editLinkCallbackObj);
		    $tpl->setVariable('editLinkCallback', $this->editLinkCallback);
		}
                
		if($this->extraLinkCallback!='') {
		    $tpl->setVariable('extraLinkCallbackObj', $this->extraLinkCallbackObj);
		    $tpl->setVariable('extraLinkCallback', $this->extraLinkCallback);
		}

                if(isset($_POST['createEntry']) && $_POST['createEntry']==1) $tpl->setVariable("actionstate", "created");
                if(isset($_POST['updateEntry']) && $_POST['updateEntry']!='') $tpl->setVariable("actionstate", "updated");
                if(isset($this->fw->QS[2]) && $this->fw->QS[2]=='deleted') $tpl->setVariable("actionstate", "deleted");

		
                $tpl->setVariable('actionIcons', $this->addActionIcons);
                
                for($i=0;$i<count($this->tplModifies['list']);$i++) {
                    call_user_func_array($this->tplModifies['list'][$i], array(&$tpl) );
                }
                
                $tpl->setvariable("simpletable", $this);
                
                if(isset($this->tplVars["list"])) {
                	$tpl->setVariable($this->tplVars["list"]);
                }
                
		$html = $tpl->get($this->listTemplateFileName);
		return($html);
		// }}}
	}
	
        public function onUpdate() {
            return (isset($_POST['updateEntry']) && $_POST['updateEntry']!='');
        }
        public function onInsert() {
            return (isset($_POST['createEntry']) && $_POST['createEntry']==1);
        }
        public function getDataPK() {
            if($this->onUpdate()) return $_POST['updateEntry'];
            else if($this->onInsert()) return $this->lastPK;
        }
        
	public function getOneEntry($pk='') {
		// {{{
		$this->data = array();
		if($pk!='') {
			$W = $this->CONFIG['where'];
                        if($this->viewDeleted) $W = array();
			$W[] = $this->CONFIG['primaryKey']."='".$pk."'";
			 
			$Q = "SELECT * FROM ".$this->CONFIG['table'];
			if(isset($this->CONFIG['jointables'])) {
                            for($i=0;$i<count($this->CONFIG['jointables']);$i++) {
                                $Q .= " INNER JOIN ".$this->CONFIG['jointables'][$i]." ON ".$this->CONFIG['joinfk'][$i].(stristr($this->CONFIG['joinfk'][$i],'=') ? '' : "=".$this->CONFIG['primaryKey']);
                            }
                        }
			$Q .= " WHERE ".implode($W,' AND ');
			$this->data = $this->fw->DC->getByQuery($Q);
		}
		return($this->data);
		// }}}
	}
	
	function createEditFormular($data=NULL) {
		// {{{
		$tpl = new \classes\template(); // $tpl = $this->fw->fw_useSingleClass('template');

                if(isset($this->fw->QS[3]) && $this->fw->QS[3]=='error') {
                    $P = getS('lastPostST');
                    setS('lastPostST', '');
                    $data = $P['FORM'];

                    $this->formError = $this->fw->fw_useSingleClass('validierung/formerror');
                    $tpl->setVariable('errors', $this->formError->getErrors() );
                    $this->formError->clearErrors();

                }
		if($data==NULL) $data = $this->data;
		
		$tpl->setVariable('fieldlist', $this->CONFIG['fieldList']['edit'] );
		$tpl->setVariable('data', $data );
		$tpl->setVariable('primaryKey', $this->CONFIG['primaryKey'] );
		$tpl->setVariable('defaultLink', $this->defaultLink );
		$tpl->setVariable('title', $this->CONFIG['title']);
		$tpl->setVariable('hideEditLinks', $this->hideEditLinks);

		$tpl->setVariable('saveButtonCaption', $this->saveButtonCaption);
		$tpl->setVariable('cancelButtonLink', $this->cancelButtonLink);

                $tpl->setVariable('extrahidden', $this->extrahidden);
                $tpl->setVariable('hideSaveButton', $this->hideSaveButton);
                $tpl->setVariable('belowInsideForm', $this->belowInsideForm);
                $tpl->setVariable('ontopInsideForm', $this->ontopInsideForm);
                #vd($this->tplModifies);
                for($i=0;$i<count($this->tplModifies['edit']);$i++) {
                    call_user_func_array($this->tplModifies['edit'][$i], array(&$tpl, $this->entryId) );
                }
                
                if(isset($this->tplVars["edit"])) {
                	$tpl->setVariable($this->tplVars["edit"]);
                }
                
                $tpl->setvariable("simpletable", $this);
                
		$html = $tpl->get($this->editTemplateFileName);
		return($html);
		// }}}
	}
	
	function createViewFormular($data=NULL) {
		// {{{
		if($data==NULL) $data = $this->data;
		$tpl = new \classes\template(); //$tpl = $this->fw->fw_useSingleClass('template');
		$tpl->setVariable('fieldlist', $this->CONFIG['fieldList']['view'] );
		$tpl->setVariable('data', $data );
		$tpl->setVariable('primaryKey', $this->CONFIG['primaryKey'] );
		$tpl->setVariable('defaultLink', $this->defaultLink );
		$tpl->setVariable('title', $this->CONFIG['title']);
		$tpl->setVariable('hideEditLinks', $this->hideEditLinks);
		


		/*
		$html = '';
		$html .= '<table>';
		for($i=0;$i<count($this->CONFIG['fieldList']['view']);$i++) {
			// {{{
			if(isset($this->CONFIG['fieldList']['view'][$i]['caption']) && $this->CONFIG['fieldList']['view'][$i]['caption']!='') {
			    $html .= '<tr>';
			    $html .= '<td>'.$this->CONFIG['fieldList']['view'][$i]['caption'].'</td>';
			    $html .= '<td>'.$data[$this->CONFIG['fieldList']['view'][$i]['field']].'</td>';
			    $html .= '</tr>';
			}
			// }}}
		}
		
		$html .= '</table><br/>';
		
		$html .= '<a href="'.getLink($this->defaultLink).'">&laquo; zur&uuml;ck</a>';
		 */
                
                for($i=0;$i<count($this->tplModifies['view']);$i++) {
                    call_user_func_array($this->tplModifies['view'][$i], array(&$tpl, $this->entryId) );
                }
		
                $tpl->setvariable("simpletable", $this);
                
                $tpl->setVariable($this->tplVars["view"]);
                
		$html = $tpl->get($this->viewTemplateFileName);
		return($html);
		// }}}
	}
	

	function post2fields($post, $view='edit', $prefix='') {
		// {{{
		#vd($post);
		$data = array();
		for($i=0;$i<count($this->CONFIG['fieldList'][$view]);$i++) {
		    if($this->CONFIG['fieldList'][$view][$i]['type']=="password") {
			if(isset($post[$this->CONFIG['fieldList'][$view][$i]['field']]) && $post[$this->CONFIG['fieldList'][$view][$i]['field']]!='') {
			    $post[$this->CONFIG['fieldList'][$view][$i]['field']] = md5(FE_USER_SECRET.$post[$this->CONFIG['fieldList'][$view][$i]['field']]);
			} else {
			    unset($post[$this->CONFIG['fieldList'][$view][$i]['field']]);
			}
		    }
                    #vd($this->CONFIG['fieldList'][$view][$i]['field']);
                    if(isset($this->CONFIG['fieldList'][$view][$i]['field'])) {
                        if(isset($post[$this->CONFIG['fieldList'][$view][$i]['field']]) && $post[$this->CONFIG['fieldList'][$view][$i]['field']]==="NULL") {
                            $post[$this->CONFIG['fieldList'][$view][$i]['field']] = "**NULL**";
                        }
                    }
                    
			if(!isset($this->CONFIG['fieldList'][$view][$i]['selfhandle']) || $this->CONFIG['fieldList'][$view][$i]['selfhandle']!=true) {
			    if($prefix=='' || (isset($this->CONFIG['fieldList'][$view][$i]['field']) && substr($this->CONFIG['fieldList'][$view][$i]['field'],0,strlen($prefix))==$prefix) || $this->disablePrefixCheck) {
				    if(isset($post[$this->CONFIG['fieldList'][$view][$i]['field']]) || $this->CONFIG['fieldList'][$view][$i]['type']=='checkbox' || $this->CONFIG['fieldList'][$view][$i]['type']=='multiselect') {
					    $V = $post[$this->CONFIG['fieldList'][$view][$i]['field']];
					    if($this->CONFIG['fieldList'][$view][$i]['type']=='checkbox' || $this->CONFIG['fieldList'][$view][$i]['type']=='multiselect') {
						    if(is_array($V)) $V = implode($V,'|');
					    } else if($this->CONFIG['fieldList'][$view][$i]['type']=='double') {
						$V = str_replace(',', '.', $V);
					    } else if($this->CONFIG['fieldList'][$view][$i]['type']=='date') {
						if($V=='') {
						    $V = '0000-00-00';
						} else {
						    $V = explode('.', $V);
						    $V = date("Y-m-d", mktime(0,0,0,$V[1],$V[0],$V[2]));
						}
					    } else if($this->CONFIG['fieldList'][$view][$i]['type']=='time') {
						if($V=='') {
						    $V = '00:00:00';
						} else {
						    $V = explode(':', $V);
						    $V = sprintf("%02d", $V[0]).':'.sprintf("%02d", $V[1]);
						}
					    }
					    $data[$this->CONFIG['fieldList'][$view][$i]['field']] = stripslashes($V);
				    }

			    }
			 }
		}
                
		return($data);
		// }}}
	}
	function createEntry($post) {
		// {{{


		$post = $this->handleUploads($post);

                $pref = str_bis($this->CONFIG['primaryKey'], '_').'_';
                $data = $this->post2fields($post['FORM'], 'edit', $pref);
		$pk = $this->fw->DC->insert($data, $this->CONFIG['table']);

		if(isset($this->CONFIG['jointables'])) {
                        for($i=0;$i<count($this->CONFIG['joinprefix']);$i++) {
                            foreach($this->CONFIG['joinprefix'][$i] as $prefix => $table) {
                                    if($table!=$this->CONFIG['table']) {
                                        $data = $this->post2fields($post['FORM'], 'edit', $prefix);
                                        if($data!=array()) {

                                            if(stristr($this->CONFIG['joinfk'][$i],'=')) {
                                                $pkfX = explode('=', $this->CONFIG['joinfk'][$i]);
                                                if(substr($pkfX[0],-3)=='_fk') $pkf = $pkfX[0]; else $pkf = $pkfX[1];
                                            } else {
                                                $pkf = $this->CONFIG['joinfk'][$i];
                                            }

                                            $data[$pkf] = $pk;
                                            $this->fw->DC->insert($data, $table);
                                        }
                                    }
                            }
                        }
		}
                $this->lastPK = $pk;
		return($pk);

                /*
		if(isset($this->CONFIG['jointables'])) {
			$pk = '';

			foreach($this->CONFIG['joinprefix'] as $prefix => $table) {
				$data = $this->post2fields($post['FORM'], 'edit', $prefix);
				if($pk!='') $data[$this->CONFIG['joinfk']] = $pk;
				$pkx = $this->fw->DC->insert($data, $table);
				if($table==$this->CONFIG['table']) {
					$pk = $pkx;
				}
			}
		} else {
			$data = $this->post2fields($post['FORM'], 'edit');
			$pk = $this->fw->DC->insert($data, $this->CONFIG['table']);
		}
		$this->lastPK = $pk;
		return($pk);
                */
		// }}}
	}
	function updateEntry($post) {
		// {{{
            #vd($post);
		$post = $this->handleUploads($post);

                $pref = str_bis($this->CONFIG['primaryKey'], '_').'_';
		#vd($post['FORM']);
                $data = $this->post2fields($post['FORM'], 'edit', $pref);
		
		$this->fw->DC->update($data, $this->CONFIG['table'], $post['updateEntry'], $this->CONFIG['primaryKey']);
                

		if(isset($this->CONFIG['jointables'])) {
                        for($i=0;$i<count($this->CONFIG['joinprefix']);$i++) {
                            foreach($this->CONFIG['joinprefix'][$i] as $prefix => $table) {
                                    if($table!=$this->CONFIG['table']) {
                                        $data = $this->post2fields($post['FORM'], 'edit', $prefix);
                                        if($data!=array()) {
                                            if(stristr($this->CONFIG['joinfk'][$i],'=')) {
                                                $pkfX = explode('=', $this->CONFIG['joinfk'][$i]);
                                                if(substr($pkfX[0],-3)=='_fk') $pkf = $pkfX[0]; else $pkf = $pkfX[1];
                                            } else {
                                                $pkf = $this->CONFIG['joinfk'][$i];
                                            }
                                            $this->fw->DC->update($data, $table, $post['updateEntry'],$pkf);
                                        }
                                    }
                            }
                        }
		}
		$this->lastPK = $post['updateEntry'];
		return($post['updateEntry']);
		// }}}
	}
	function handleUploads($post) {
		
		if(isset($_POST['DELFILE'])) {
			foreach($_POST['DELFILE'] as $key => $val) {
				if($val==1) {
					$post['FORM'][$key] = "";
				}
			}
		}
		
		
		if(isset($_FILES['FORM']) && $_FILES['FORM']!='') {
			foreach($_FILES['FORM']['name'] as $key => $value) {
				if($value!='') {
					$fn = cms_move_uploaded_file($_FILES['FORM']['tmp_name'][$key], projectPath.'/uploads/'.$value);
					$post['FORM'][$key] = $fn;
				}
			} 
		}
		return($post);
	}
	
	
	public function manage($QS) {
		// {{{
		
                if($this->editable) {
                    if(isset($_POST['createEntry']) && $_POST['createEntry']==1) $this->createEntry($_POST);
                    if(isset($_POST['updateEntry']) && $_POST['updateEntry']!='') $this->updateEntry($_POST);
                    #vd($this->fw->QS);
                    
                    if(isset($this->fw->QS[3]) && isset($this->fw->QS[4]) && $this->fw->QS[3]>0 && $this->fw->QS[4]=='del') {
                            if($this->setDeletedDateField=='') {
                                $this->fw->DC->sendQuery("DELETE FROM ".$this->CONFIG['table']." WHERE ".$this->CONFIG['primaryKey']."='".(int)$this->fw->QS[3]."' ");
                            } else {
                                $this->fw->DC->sendQuery("UPDATE ".$this->CONFIG['table']." SET ".$this->setDeletedDateField."=now() WHERE ".$this->CONFIG['primaryKey']."='".(int)$this->fw->QS[3]."' ");
                            }
                            jump2page($this->fw->QS[0].'/'.$this->fw->QS[1].'/deleted');
                    }
                }
			
                $view = $this->action;
                if($this->action=='order') $view = "list";
                if($view=="edit" || $view=="list" || $view=="view") {
                    for($i=0;$i<count($this->CONFIG['fieldList'][$view]);$i++) {
                        if(isset($this->CONFIG['fieldList'][$view][$i]["source"]) && $this->CONFIG['fieldList'][$view][$i]["source"] == "relational") {

                            $this->CONFIG['fieldList'][$view][$i]["values"] = array();
                            $this->CONFIG['fieldList'][$view][$i]["texts"] = array();
                            $this->CONFIG['fieldList'][$view][$i]["value2text"] = array();
                                    
                            if(isset($this->CONFIG['fieldList'][$view][$i]["query"]) && $this->CONFIG['fieldList'][$view][$i]["query"]!="") {
                            	    $Q = $this->CONFIG['fieldList'][$view][$i]["query"];
                            } else {
                            
				    $Q = "SELECT ".$this->CONFIG['fieldList'][$view][$i]["valuefield"]." as value, ".$this->CONFIG['fieldList'][$view][$i]["captionfield"]." as caption 
					    FROM ".$this->CONFIG['fieldList'][$view][$i]["table"]." ";
				    if(isset($this->CONFIG['fieldList'][$view][$i]["where"]) && $this->CONFIG['fieldList'][$view][$i]["where"]!="") {
					$Q .= " WHERE ".$this->CONFIG['fieldList'][$view][$i]["where"]." ";
				    }
				    if(isset($this->CONFIG['fieldList'][$view][$i]["orderby"]) && $this->CONFIG['fieldList'][$view][$i]["orderby"]!="") {
					$Q .= " ORDER BY ".$this->CONFIG['fieldList'][$view][$i]["orderby"]." ";
				    }
			    }
                            $S = $this->fw->DC->getAllByQuery($Q);
                            for($j=0;$j<count($S);$j++) {
                            	$this->CONFIG['fieldList'][$view][$i]["data"][] = $S[$j];
                                $this->CONFIG['fieldList'][$view][$i]["values"][] = $S[$j]["value"];
                                $this->CONFIG['fieldList'][$view][$i]["texts"][] = $S[$j]["caption"];
                                $this->CONFIG['fieldList'][$view][$i]['value2text'][$S[$j]["value"]] = $S[$j]["caption"];
                            }

                        }
                    }
                    #vd($this->CONFIG['fieldList']);
                }
                #vd($this->CONFIG['fieldList'][$view]);
                    
		//$tpl = $this->fw->fw_useSingleClass('template');
		$tpl = new \classes\template();
		if($this->action=='download') {
			$data = $this->getOneEntry($this->entryId);
			$fn = projectPath.'/uploads/'.$data[$this->fw->QS[4]];
			if($fn!='') {
				deliverFile($fn,$data[$this->fw->QS[4]]);
			}
			exit;
		} else if($this->action=='edit' && $this->editable) {
			
			$data = $this->getOneEntry($this->entryId);
                        
			$html = $this->createEditFormular();
			
		} else if($this->action=='view') {
			$data = $this->getOneEntry($this->entryId);
			$html = $this->createViewFormular();
			
		} else {
                       #setS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder', "");
                    /*
                    if(getS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder')=='') {
                        setS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder', $this->orderBy);
                    }
                    
                    
                    $this->orderBy = getS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder');
                    
                     if($this->action=='order') {
                         
                         if($this->orderBy == $this->CONFIG['fieldList']['list'][$this->entryId]['field']) {
                             setS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder', $this->CONFIG['fieldList']['list'][$this->entryId]['field'].' desc');
                         } else {
                             setS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder', $this->CONFIG['fieldList']['list'][$this->entryId]['field']);
                         }
                         $this->orderBy = getS($this->fw->QS[0].'-'.$this->fw->QS[1].'-overrideOrder');
                     }
                    */ 
                     
                     
                    if($this->editable) {
                        if($this->action=='del') {
                            
                            $_POST['checkaction'] = 'delete';
                            $_POST['action'] = array($this->entryId);
                        }
                    
                        if(isset($_POST['checkaction'])) {
                            if($_POST['checkaction']=='delete' && isset($_POST['action'])) {
                                for($i=0;$i<count($_POST['action']);$i++) {
                                    if($this->setDeletedDateField=='') {
                                        $this->fw->DC->sendQuery("DELETE FROM ".$this->CONFIG['table']." WHERE ".$this->CONFIG['primaryKey']."='".(int)$_POST['action'][$i]."' ");
                                    } else {
                                        $this->fw->DC->sendQuery("UPDATE ".$this->CONFIG['table']." SET ".$this->setDeletedDateField."=now() WHERE ".$this->CONFIG['primaryKey']."='".(int)$_POST['action'][$i]."' ");
                                    }
                                }
                            }
                            jump2page('*/*');
                            exit;
                        }
                    }
                        
                    $data = $this->getList();
                    $html = $this->createListTable();
			
		}
		return($html);
		// }}}
	}

	function removeFields($arr, $remove) {
		$arr2 = array();
		for($i=0;$i<count($arr);$i++) {
			if(     !isset($arr[$i]['field']) ||
                                isset($arr[$i]['field'])=="" ||
                                (isset($arr[$i]['field']) && !in_array($arr[$i]['field'], $remove)) 
                                ) $arr2[] = $arr[$i];
		}
		return $arr2;
	}
	function hideFields($arr, $remove) {
		for($i=0;$i<count($arr);$i++) {
			if(isset($arr[$i]['field']) && in_array($arr[$i]['field'], $remove)) {
                            $arr[$i]['type'] = 'hidden';
                        }
		}
		return $arr;
	}
        
	function findField($arr, $find) {
		for($i=0;$i<count($arr);$i++) {
			if($arr[$i]['field']==$find) return $i;
		}
		return -1;
	}
	function leaveFields($arr, $leave, $resort=false) {
		$arr2 = array();
                
                if($resort==false) {
                    for($i=0;$i<count($arr);$i++) {
                            if(isset($arr[$i]['field']) && in_array($arr[$i]['field'], $leave)) $arr2[] = $arr[$i];
                    }
                } else {
                    for($j=0;$j<count($leave);$j++) {
                        for($i=0;$i<count($arr);$i++) {
                            if(isset($arr[$i]['field']) && $arr[$i]['field']== $leave[$j]) $arr2[] = $arr[$i];
                        }
                    }
                }
		return $arr2;
	}

	public function getBundeslaender() {
	    $B = array(
		 array('value' => 'bw', 'text' => "Baden-Württemberg"),
		 array('value' => 'by', 'text' => "Bayern"),
		 array('value' => 'be', 'text' => "Berlin"),
		 array('value' => 'bb', 'text' => "Brandenburg"),
		 array('value' => 'hb', 'text' => "Bremen"),
		 array('value' => 'hh', 'text' => "Hamburg"),
		 array('value' => 'he', 'text' => "Hessen"),
		 array('value' => 'mv', 'text' => "Mecklenburg-Vorpommern"),
		 array('value' => 'ni', 'text' => "Niedersachsen"),
		 array('value' => 'nw', 'text' => "Nordrhein-Westfalen"),
		 array('value' => 'rp', 'text' => "Rheinland-Pfalz"),
		 array('value' => 'sl', 'text' => "Saarland"),
		 array('value' => 'sn', 'text' => "Sachsen"),
		 array('value' => 'st', 'text' => "Sachsen-Anhalt"),
		 array('value' => 'sh', 'text' => "Schleswig-Holstein"),
		 array('value' => 'th', 'text' => "Thüringen")
	    );
	    return $B;
	}

	public function getLaender() {
	    $B = array(
		 array('value' => 'de', 'text' => "Deutschland")
	    );
	    return $B;
	}
	
	function test() {
		// {{{
		return("X");
		// }}}
	}
	protected function setS($name, $value) {
		setS(md5($this->fw->QS[0]).'_'.$name, $value);
	}
	protected function getS($name) {
		return getS(md5($this->fw->QS[0]).'_'.$name);
	}
	
}
?>