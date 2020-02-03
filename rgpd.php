<?php
/*********************************************************************************************************************
Plugin RGPD pour LimeSurvey
Tester sur version 3.22.1+200129
Version 1.01
Academie de Poitiers
Eric Camus
*********************************************************************************************************************/
use LimeSurvey\Menu\MenuItem;
use LimeSurvey\Menu\Menu;
class rgpd extends PluginBase {
	protected $storage = 'DbStorage';
	static protected $description = 'Gestion de la page RGPD';
	static protected $name = 'RGPD';
	
	protected $settings=array(  // on met rien par defaut, "rgpd_def_*" sont parametrable par questionnaire
		'rgpd_info_1' => array(
			'type' => 'info',
			'content' => '<h4>Valeurs par défaut pour chaque questionnaire :</h4>
<style>
	div[data-name="rgpd_def_createur"],div[data-name="rgpd_def_mcreateur"] {
		display:none;
	}
</style>',
		),
		'rgpd_def_responsable'=>array(
			'type'=>'string',
			'label'=>'Responsable',
			'default'=>'',
			'help'=>'##rgpd_def_responsable##',
		),
		'rgpd_def_structure'=>array(
			'type'=>'string',
			'label'=>'Structure',
			'default'=>'',
			'help'=>'##rgpd_def_structure##',
		),
		'rgpd_def_descriptif'=>array(
			'type'=>'text',
			'label'=>'Détail de la finalité du traitement',
			'default'=>'',
			'help'=>'##rgpd_def_descriptif##',
		),
		'rgpd_def_services'=>array(
			'type'=>'string',
			'label'=>'Service',
			'default'=>'',
			'help'=>'##rgpd_def_services##',
		),
		'rgpd_def_createur'=>array(
			'type'=>'string',
			'label'=>'Nom du responsable du questionnaire',
			'default'=>'',
			'help'=>'##rgpd_def_createur##',
		),
		'rgpd_def_mcreateur'=>array(
			'type'=>'string',
			'label'=>'Courriel du responsable du questionnaire',
			'default'=>'',
			'help'=>'##rgpd_def_mcreateur##',
		),
		'rgpd_info_2' => array(
			'type' => 'info',
			'content' => '<h4>Texte des conditions RGPD :</h4>',
		),
		'rgpd_texte'=>array(
			'type'=>'html',
			'label'=>'Texte du RGPD',
			'default'=>'<h3>Le responsable du traitement</h3>
<div>##rgpd_def_responsable## est responsable du traitement des données collectées.</div>
<h3>Finalité du traitement</h3>
<div>Le traitement a pour objet de recueillir les données nécessaires à la structure (##rgpd_def_structure##) 
pour la gestion et le suivi, d\'un service à la personne concernée par les questions posées, ou d\'une 
mission dont est investie le créateur du questionnaire.
<br />
##rgpd_def_descriptif##
<br />
Pour toutes demandes d\'informations complémentaires sur la finalité de ce traitement vous pouvez 
contacter le responsable du questionnaire : ##rgpd_def_mcreateur##.</div>
<h3>Base légale</h3>
<div>Article 6 (1) a du règlement général sur la protection des données -&nbsp;RGPD 
La personne concernée a consenti au traitement de ses données à caractère personnel pour une ou plusieurs 
finalités spécifiques.</div>
<h3>Données traitées :</h3>
<div><b>Source de données</b> :</div>
<div>
<ul><li>Les données saisies par la personne&nbsp;dans&nbsp;l\'enquête</li>
<li>Les données de connexion (adresse IP, logs)</li>
<li>Les cookies (permet de reprendre le questionnaire en cours de saisie)</li>
</ul>
<b>Caractère obligatoire du recueil des données</b> : La non-fourniture des données obligatoires, 
précisées dans le document par un astérisque, entraînera l’impossibilité de répondre à la finalité 
de ce traitement.</div>
<h3>Destinataires des données traitées</h3>
<div>Ces données sont destinées :</div>
<div>
<ul>
<li> à : ##rgpd_def_createur## (##rgpd_def_structure## ##rgpd_def_services##),</li>
<li>aux administrateurs de l\'outil d\'enquête mis à disposition par la DSI du Rectorat de Poitiers.</li>
</ul>
</div>
<h3>Durée de conservation des données</h3>
<div>Les données sont conservées pendant une durée de ##rgpd_adm_duree## mois à compter de la date 
d\'expiration de l\'enquête.</div>
<h3>Exercer ses droits</h3>
<div>Vous pouvez à tout moment demander l\'accès, la rectification, l\'effacement, la portabilité 
ou la limitation des données vous concernant, ou vous opposer à leur traitement. Pour toute demande 
d\'exercice de vos droits sur ce traitement, le responsable du questionnaire et le délégué à la 
protection des données (DPD ou DPO) de l\'académie de Poitiers sont vos interlocuteurs.</div>
<ul>
	<li>Contacter le responsable du questionnaire : ##rgpd_def_mcreateur##</li>
	<li>Contacter le DPD par voie électronique : dpd@ac-poitiers.fr.</li>
	<li>Contacter le DPD par courrier postal : A l\'attention du Délégué à la Protection des Données, 
	22 Rue Guillaume 7 le Troubadour, 86000 Poitiers.</li>
</ul>
<div>Pour en savoir plus sur 
<a target="_blank" rel="nofollow" href="https://www.cnil.fr/fr/les-droits-pour-maitriser-vos-donnees-personnelles"> 
l\'exercice de vos droits</a>, vous pouvez également consulter le site de la Commission nationale de l\'informatique 
et des libertés (<a target="_blank" rel="nofollow" href="https://www.cnil.fr/">CNIL</a>).</div>
<div>Toute personne estimant que le droit à la protection de ses données n\'est pas assuré, peut introduire une 
réclamation auprès de la Commission Nationale de l\'Informatique et des Libertés (CNIL), 3 Place de Fontenoy - 
TSA 80715 - 75334 PARIS CEDEX 07.</div>',
		),
		'rgpd_info_3' => array(
			'type' => 'info',
			'content' => '<h4>Paramètres généraux :</h4>',
		),
		'rgpd_domaine_mail'=>array(
			'type'=>'string',
			'label'=>'Nom de domaine des courriels',
			'default'=>'',
			'help'=>'Domaine des courriels transformés en lien cliquable',
		),
		'rgpd_altlogo'=>array(
			'type'=>'string',
			'label'=>'Attribut ALT du logo',
			'default'=>'',
			'help'=>'Aucun logo présent',
		),
		'rgpd_info_4' => array(
			'type' => 'info',
			'content' => '<h4>Gestion des données :</h4>',
		),
		'rgpd_adm_duree'=>array(
			'type'=>'int',
			'label'=>'Durée de conservation des données (en mois)',
			'default'=>'12',
			'help'=>'##rgpd_adm_duree##',
		),
		'rgpd_active_cons'=>array(
			'type'=>'boolean',
			'label'=>'Activer la suppression automatique des données au delà de la durée de conservation',
			'default'=>0,
			'help'=>'',  // cf messages
		),
		'rgpd_periode_cons'=>array(
			'type'=>'select',
			'label'=>'Périodicité de la suppression automatique (en jours)',
			'default'=>1,
			'options'=>array(
				1=>7,
				2=>15,
				3=>30,
				4=>60,
            ),
			'help'=>'Ce paramètre sera pris en compte après la prochaine suppression.',
		),
		'rgpd_raz_cons'=>array(
			'type'=>'boolean',
			'label'=>'Lancer maintenant le processus de suppression',
			'default'=>0,
			'help'=>'',  // cf messages
		),
	);
	protected $nom_bd=array();  // nom des tables utiles encadrer par ``
	protected $rgpd_logo='';  // nom de l'image du logo dans /plugins/rgpd/assets/
	protected $rgpd_gs=array();  // les donnees generales settings (current ou default)
	protected $rgpd_repcron='';  // repertoire des log du cron (cf init)
	protected $rgpd_statutcron='';  // nom du fichier de statut
	protected $rgpd_lockcron='';  // nom du fichier lock du cron (present si en execution)
	protected $rgpd_messages=[
	'rgpd_raz_cons'=>'Cette suppression manuelle supprimera les données selon la durée de conservation choisie.',
	'rgpd_active_cons'=>'Cette action est déclenchée par la visite d\'une page de l\'outil d\'enquête.'];

	public function init() {
		$menu=new Surveymenu;
		$menuliste=new SurveymenuEntries;
		$this->nom_bd['menu']='`'.$menu->tableName().'`';
		$this->nom_bd['menuliste']='`'.$menuliste->tableName().'`';
		$this->nom_bd['param_survey']='`'.App()->getDb()->tablePrefix.'survey_rgpd`';
		$this->nom_bd['survey']='`'.App()->getDb()->tablePrefix.'surveys`';  // !! comment obtenir autrement ??
		$in=$this->getPluginSettings();  // donnees settings plugin dans base
		foreach($in as $n=>$v) {  // init $rgpd_gs
			if(isset($v['default'])) {
				$this->rgpd_gs[$n]=$v['default'];
				if(isset($v['current'])) $this->rgpd_gs[$n]=$v['current'];
			}
		}
		$this->rgpd_repcron=dirname(__FILE__).'/logcron/';  // repertoire des log du cron
		if(!file_exists($this->rgpd_repcron)) mkdir($this->rgpd_repcron);  // creation si pas existe
		$this->rgpd_statutcron=$this->rgpd_repcron.'statut.txt';  // fichier de status du cron
		$this->rgpd_lockcron=$this->rgpd_repcron.'lock.txt';  // un seul cron en meme temps
		$this->rgpd_message_settings();
		$logo=glob(dirname(__FILE__).'/assets/logo_*.*');
		$nbroot=strlen($_SERVER['DOCUMENT_ROOT']);
		if($logo) {
			foreach($logo as $l) {
				if(preg_match(',\.(png|gif|jpg|jpeg)$,',$l)) {
					$this->rgpd_logo=substr($l,$nbroot);  // le premier trouve
					$this->settings['rgpd_altlogo']['help']='Logo : '.$this->rgpd_logo;
					break;
				}
			}
		}
		$f=dirname(__FILE__).'/assets/aide_rgpd_limesurvey.pdf';
		if(file_exists($f)) {
			$this->settings['rgpd_info_1']['content'].='<div style="float:right;"><a href="'.
				substr($f,$nbroot).'" target="_blank"><span class="fa fa-info-circle"></span> Aide</a></div>';
		}
		$this->subscribe('beforeSurveyPage');  // le questionnaire
		$this->subscribe('beforeAdminMenuRender');  // menu RGPD config generale
		$this->subscribe('beforeActivate');  // activation
		$this->subscribe('beforeDeactivate');  // desactivation
		$this->subscribe('beforeSurveySettingsSave');  // pour intercepter les maj RGPD !!!
		$this->subscribe('beforeControllerAction');  // pour l'affichage page de config
   }
	public function beforeSurveyPage() {
		$event=$this->getEvent();
		$idsurvey=$event->get('surveyId');
		$this->rgpd_exec_cron($idsurvey);  // CRON questionnaire
		$url=$_SERVER['REQUEST_URI'];
		$rgpd_ok=0;
		$par=array('nom_ses'=>'acp_rgpd_'.$idsurvey,
				   'ses_id'=>'survey_'.$idsurvey,
				   'ses_postkey'=>false,
				   'ses_count'=>0,
				   );  // les infos utile ??
		if(isset($_SESSION[$par['ses_id']])) {  // presence de rentre dans qestionnaire
			$par['ses_count']=count($_SESSION[$par['ses_id']]);
			if(isset($_SESSION[$par['ses_id']]['LEMpostKey'])) {
				$par['ses_postkey']=true;
			}
		}
		if(!$par['ses_postkey'] and $par['ses_count']<2) {  // pas de rentre alors on remet a zero
			if(isset($_SESSION[$par['nom_ses']])) $_SESSION[$par['nom_ses']]=0;
		}
		if(preg_match(',&rgpd_ok=([01]),',$url,$val)) {
			$url=str_replace($val[0],'',$url);
			$rgpd_ok=0+$val[1];
		}
		if($rgpd_ok==1) {  // fini on valide
			$_SESSION[$par['nom_ses']]=1;
		}
		if(isset($_SESSION[$par['nom_ses']]) and $_SESSION[$par['nom_ses']]==1) {  // fini c'est bon
			return;
		}
		$info=getSurveyInfo($idsurvey,App()->getLanguage());  // info questionnaire
		// info complementaire
		$cont=file_get_contents(dirname(__FILE__).'/views/layout_rgpd.html');
		$remp=array('##acp_url_ici##'=>$url,
					'##rgpd_texte##'=>$this->rgpd_texte_survey($idsurvey),
					'##rgpd_logo##'=>$this->rgpd_logo,
					'##rgpd_altlogo##'=>htmlspecialchars($this->rgpd_gs['rgpd_altlogo']),
				   );
		foreach($info as $nom=>$v) {  // mettre toutes les chaine
			if(!(is_array($v) or is_object($v))) {
				$remp['##'.$nom.'##']=$v;
			}
		}
		$cont=strtr($cont,$remp);
		echo $cont;
		Yii::app()->end(); // So we can still see debug messages
	}
	public function beforeAdminMenuRender() {
		if(Permission::model()->hasGlobalPermission('superadmin', 'read')) {
			$event=$this->getEvent();
			$event->set('extraMenus', array(
			  new Menu(array(
				'isDropDown' => true,
				'label'=>'RGPD',
				'menuItems' => array(
					new MenuItem(array('href'=>'/index.php?r=admin/pluginmanager/sa/configure&id='.
									   $this->id,'label'=>'Configuration générale')),
				)
			  ))
			));
		}
		$this->rgpd_exec_cron();  // CRON admin
	}
	public function beforeSurveySettingsSave() {  // ici on enregistre les donnees RGPD d'un questionnaire
		$sid=0;
		if(isset($_POST['sid'])) $sid=0+$_POST['sid'];
		$inp=array();
		foreach($_POST as $n=>$v) {
			if(preg_match(',^rgpd_def_.+$,',$n)) {
				$inp[$n]=$v;
			}
		}
		$ok=$this->rgpd_write_survey($sid,$inp);
	}
	public function beforeControllerAction() {  // enregistre les parametres dans les variables de classe
		$event=$this->getEvent();
		$chact=$event->get('controller').':'.$event->get('action').':'.$event->get('subaction');
		if($chact=='admin:pluginmanager:configure') {
			$majmes=false;
			$delfich=false;
			if(isset($_POST['rgpd_raz_cons']) and $_POST['rgpd_raz_cons']==1) {
				if(file_exists($this->rgpd_statutcron)) {  // supprimer le fichier
					$x=$this->rgpd_getcroninfo(true);
					if($x['statut']==0) {  // si pas en cour de lancer
						unlink($this->rgpd_statutcron);
						$this->rgpd_logcron('Fichier status supprimé.');
						$delfich=true;
					}
				}
				$_POST['rgpd_raz_cons']=0;
				$_REQUEST['rgpd_raz_cons']=0;
				$majmes=true;
			}
			if(isset($_POST['rgpd_active_cons'])) {
				$active=0;
				if($_POST['rgpd_active_cons']==1) $active=1;
				if($this->rgpd_gs['rgpd_active_cons'] xor $active) {  // en cas de changement
					$this->rgpd_gs['rgpd_active_cons']=$active;
					$majmes=true;
				}
			}
			if($majmes) {
				$this->rgpd_message_settings($delfich);
			}
		}
	}
	public function beforeActivate() {
		// creer table "lime_survey_rgpd" si besoin
		$ok=$this->rgpd_sql_request('CREATE TABLE IF NOT EXISTS '.$this->nom_bd['param_survey'].
									" (`sid` bigint(20) NOT NULL DEFAULT '0',`param` mediumtext NOT NULL,".
									' UNIQUE KEY `sid` (`sid`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;',true);
		if($ok===false) {
			$event=$this->getEvent();
            $event->set('success',false);
            $event->set('message','<p>Erreur : ne peut pas créer la table "'.$this->nom_bd['param_survey'].'".</p>');
            return;  // erreur !
		}
		$trouve=$this->rgpd_trouve_menu();
		if($trouve['menu_item_id']>0) {
			return;  // fini, pas de truc a faire
		}
		if($trouve['menu_id']==0) {
			$event=$this->getEvent();
            $event->set('success',false);
            $event->set('message','<p>Erreur : ne trouve pas le menu "settings".</p>');
            return;  // erreur !
		}
		// Attention a l'evolution des champs de cette table mais la fonction 'staticAddMenuEntry'
		// ne permet pas de mettre en oeuvre l'init de ce menu, donc ajout direct
		$menuEntryArray = array(
			'menu_id'=>$trouve['menu_id'],
			'ordering'=>3,  // au dessus de policy (4)
			'name'=>'rgpd',
			'title'=>'Configuration RGPD',
			'menu_title'=>'Configuration RGPD', 
			'menu_description'=>'Configuration RGPD de ce questionnaire',
			'menu_icon'=>'shield',
			'menu_icon_type'=>'fontawesome',
			'menu_class'=>'',
			'menu_link'=>'',
			'action'=>'updatesurveylocalesettings',
			'template'=>'editLocalSettings_main_view',
			'partial'=>'/../../plugins/rgpd/admin/views/tab_edit_view_rgpd',
			'classes'=>'',
			'permission'=>'surveysettings',
			'permission_grade'=>'read',
			'getdatamethod'=>'_getDataSecurityEditData',
			'active'=>1,
			'showincollapse'=>1,
			'changed_at'=>date('Y-m-d H:i:s'),
			'changed_by'=>0,
			'created_at'=>date('Y-m-d H:i:s'),
			'created_by'=>0,
		);
		$id=$this->rgpd_sql_insert($this->nom_bd['menuliste'],$menuEntryArray);
		if($id>0) {
			$itemmenu=new SurveymenuEntries;
			$itemmenu->reorderMenu($trouve['menu_id']);
		}
		else {
			$event=$this->getEvent();
            $event->set('success',false);
            $event->set('message','<p>Erreur : ajout du menu échoué.</p>');
            return;  // erreur !
		}
	}
	// la table "lime_survey_rgpd" n'est pas detruite ni les valeurs dans "lime_plugin_settings"
	public function beforeDeactivate() {
		$trouve=$this->rgpd_trouve_menu();
		if($trouve['menu_item_id']==0) return;  // fini, pas de truc a faire
		$ok=$this->rgpd_sql_request('delete from '.$this->nom_bd['menuliste'].' where `id`='.
									$trouve['menu_item_id'],true);
		if($ok!=1) {
			$event=$this->getEvent();
            $event->set('success',false);
            $event->set('message','<p>Erreur : ne peut pas supprimer la ligne de menu !.</p>');
            return;  // erreur !
		}
		$itemmenu=new SurveymenuEntries;
		$itemmenu->reorderMenu($trouve['menu_id']);
	}
	// CRON dans deux cas : questionnaire (id>0) et admin (id=0) 
	private function rgpd_exec_cron($id=0) {
		if($this->rgpd_gs['rgpd_active_cons']) {  // on est en mode actif
			if(file_exists($this->rgpd_lockcron) and filemtime($this->rgpd_lockcron)>(time()-30)) {
				return;  // si deja un cron et present depuis moins de 30s
			}
			$par=$this->rgpd_getcroninfo();
			if($par['statut']==0) {
				return;  // rien a faire, on attend
			}
			if(file_exists($this->rgpd_lockcron) and filemtime($this->rgpd_lockcron)>(time()-30)) {
				return;  // si deja un cron et present depuis moins de 30s
			}
			file_put_contents($this->rgpd_lockcron,$id.' '.time());  // on lock ici
			$deldt=time()-($this->rgpd_gs['rgpd_adm_duree']+1)*2628000;  // on envele (mois+1)
			$df=time()+4;  // maximum pendant 4s
			$this->rgpd_logcron('ID='.$id.' Etape='.$par['etape']);
			switch($par['etape']) {
				case 0:  // juste enregistrer
					$this->rgpd_logcron('Fin Etape='.$par['etape']);
					$par['etape']++;  // suite
					break;
				case 1:  // suppression des tables old
					if($par['table_sup']) {
						foreach($par['table_sup'] as $cle=>$t) {
							if(time()>$df) break;  // fini, plus le temps
							$texist=$this->rgpd_sql_request("show table status like '".$t."'");
							if($texist) {
								Yii::app()->db->createCommand()->dropTable($t);
								$this->rgpd_logcron('Table : '.$t.' supprimée',1);
							}
							else {
								$this->rgpd_logcron('Table : '.$t.' absente',1);
							}
							unset($par['table_sup'][$cle]);
						}
					}
					else {
						$this->rgpd_logcron('Fin Etape='.$par['etape']);
						$par['etape']++;  // suite
					}
					break;
				case 2:  // nettoyage des tables
					if($par['table_survey']) {
						foreach($par['table_survey'] as $cle=>$t) {
							if(time()>$df) break;  // fini, plus le temps
							$buf=$this->rgpd_sql_request('select `expires`,`startdate`,`datecreated` from '.
														 $this->nom_bd['survey'].' where `sid`='.$cle,false,true);
							$del=true;  // mettre a false si presence questionnaire dans la base
							$vide=true;  // mettre a false si on ne doit pas vider les tables
							if($buf) {  // on 
								$date=0;
								if($buf['datecreated']) {
									$date=strtotime($buf['datecreated']);
								}
								if($buf['startdate']) {
									$x=strtotime($buf['startdate']);
									if($x>$date) $date=$x;
								}
								if($buf['expires']) {
									$x=strtotime($buf['expires']);
									if($x>$date) $date=$x;
								}
								$vide=false;
								$del=false;
								if($date>0) {
									if($deldt>$date) {
										$vide=true;
									}
								}
							}
							if($del) {  // on supprime les tables si existent car plus de questionnaire !!!
								foreach($t as $d) {
									$texist=$this->rgpd_sql_request("show table status like '".$d."'");
									if($texist) {
										Yii::app()->db->createCommand()->dropTable($d);
										$this->rgpd_logcron('Table : '.$d.' supprimée',1);
									}
									else {
										$this->rgpd_logcron('Table : '.$d.' absente',1);
									}
								}
								$vide=false;
							}
							if($vide) {  // on vide les tables si existent
								foreach($t as $d) {
									$texist=$this->rgpd_sql_request("show table status like '".$d."'",false,true);
									if($texist) {
										if(isset($texist['Rows']) and $texist['Rows']>0) {
											Yii::app()->db->createCommand()->truncateTable($d);
											$this->rgpd_logcron('Table : '.$d.' vidée',1);
										}
										else {
											$this->rgpd_logcron('Table : '.$d.' déjà vide',1);
										}
									}
									else {
										$this->rgpd_logcron('Table : '.$d.' absente',1);
									}
								}
							}
							unset($par['table_survey'][$cle]);
						}
					}
					else {
						$this->rgpd_logcron('Fin Etape='.$par['etape']);
						$par['etape']=100;  // suite
					}
					break;
				case 100:
					$x=$this->rgpd_gs['rgpd_periode_cons'];
					$next=date('Ymd',time()+(86400*$this->settings['rgpd_periode_cons']['options'][$x]));
					$this->rgpd_logcron('***** Fin nettoyage, prochain : '.$next);
					unset($par);  // destruction
					$par=['date_next'=>$next,  // prochain dans X jours
						  'statut'=>0,
						  'etape'=>0,
						  'table_sup'=>[],
						  'table_survey'=>[],
						  ];
					break;
				default:  // hors porter !!!
					$par['etape']=1;  // on recommence !!!
					break;
			}
			$this->rgpd_getcroninfo($par);  // enregistrer
			unlink($this->rgpd_lockcron);  // liberer lock cron
		}
	}
	// recupere le status du CRON, si $val=array alors ecrit seulement, si $val=true alors retourne contenu
	// si pas de $val alors retourne le contenu si existe sinon le creer
	private function rgpd_getcroninfo($val=[]) {
		if($val and is_array($val)) {  // des valeurs alors on ecrit
			return file_put_contents($this->rgpd_statutcron,serialize($val));
		}
		$out=[];
		if(file_exists($this->rgpd_statutcron)) {
			$out=unserialize(file_get_contents($this->rgpd_statutcron));  // pas de base64
			if(isset($out['date_next'],$out['statut'],$out['etape'],
					 $out['table_sup'],$out['table_survey'])) {
				if($out['statut']!=0) return $out;  // fini car en cour
				if($out['date_next']>date('Ymd')) return $out;  // pas encore la date
			}
		}
		if($val===true) {
			return $out;
		}
		// on retourne le nouveau test sans creer le fichier, ou nouveau cycle qqs sa presence
		$out=['date_next'=>date('Ymd'),  // date prochain test
			  'statut'=>1,  // statut : 0=en attente, 1=en cours de test
			  'etape'=>0,  // etape de test de depart qui ne fait que enregistrer le fichier
			  'table_sup'=>[],  // liste restante des tables a supprimer
			  'table_survey'=>[],  // liste des tables survey a tester
			 ];
		$this->rgpd_logcron('***** Début nettoyage');
		$deltats=($this->rgpd_gs['rgpd_adm_duree']+1)*2628000;  // on envele (mois+1)
		$datebd=date('YmdHis',time()-$deltats);
		$buf=Yii::app()->db->schema->getTableNames();  // les tables
		if($buf) {
			foreach($buf as $t) {
				if(preg_match(';^lime_old_.*_([0-9]{14})$;',$t,$reg)) {  // tous les old
					if($reg[1]<$datebd) {
						$out['table_sup'][]=$t;
					}
				}
				elseif(preg_match(';^lime_survey_([0-9]+)(.*)$;',$t,$reg)) {
					if($reg[2]) {
						$out['table_survey'][$reg[1]][$reg[2]]=$t;
					}
					else {
						$out['table_survey'][$reg[1]]['_survey']=$t;
					}
				}
				elseif(preg_match(';^lime_tokens_([0-9]+)$;',$t,$reg)) {
					$out['table_survey'][$reg[1]]['_tokens']=$t;
				}
			}
		}
		return $out;
	}
	private function rgpd_logcron($txt='',$sub=0) {  // logger le texte
		$fl=$this->rgpd_repcron.'xlog_'.date('Ymd').'.txt';  // fichier de log
		$esp=str_repeat(' ',$sub);
		file_put_contents($fl,date('H:i:s ').$esp.$txt.PHP_EOL,FILE_APPEND);
	}
	// pour trouver la ligne du menu
	private function rgpd_trouve_menu() {
		$out=array('menu_id'=>0,'menu_item_id'=>0);
		$buf=$this->rgpd_sql_request('select `id` from '.$this->nom_bd['menu']." where `name`='settings'",
									 false,true);
		if($buf) $out['menu_id']=0+$buf['id'];
		if($out['menu_id']!=0) {
			$buf=$this->rgpd_sql_request('select `id` from '.$this->nom_bd['menuliste'].
										 " where `name` like '%rgpd%'",false,true);
			if($buf) $out['menu_item_id']=0+$buf['id'];
		}
		return $out;
	}
	// requete dans la base directement ; $exec=true pour exec simple, false pour select (defaut)
	// accepte les nom de base {{nom}} !!
	private function rgpd_sql_request($txt='',$exec=false,$first=false) {
		if($exec) {
			return Yii::app()->db->createCommand($txt)->execute();
		}
		if($first) {
			return Yii::app()->db->createCommand($txt)->queryRow();
		}
		return Yii::app()->db->createCommand($txt)->queryAll();
	}
	private function rgpd_sql_insert($table,$var) {
		if(!is_array($var)) return false;
		if(strlen($table)==0) return false;
		$reqnom='';
		$reqval='';
		foreach($var as $cle=>$val) {
			$reqnom.='`'.$cle.'`,';
			$reqval.="'".addslashes($val)."',";
		}
		$reqnom=substr($reqnom,0,-1);
		$reqval=substr($reqval,0,-1);
		return $this->rgpd_sql_request('insert into '.$table.' ('.$reqnom.') values ('.$reqval.')',true);
	}
	public function rgpd_texte_survey($sid,$param=false) {  // retourne le texte du RGPD (rgpd_texte)
		$dommail=str_replace('.','\\.',$this->rgpd_gs['rgpd_domaine_mail']);
		$vals=$this->rgpd_write_survey($sid);
		$remp=[];  // tableau de remplacement
		foreach($vals as $n=>$v) {
			$style='';
			switch($this->settings[$n]['type']) {
				case 'select':
					$io=($v=='')?$this->rgpd_gs[$n]:$v;
					if(!isset($this->settings[$n]['options'][$io])) $io=$this->settings[$n]['default'];
					$remp['##'.$n.'##']=$this->settings[$n]['options'][$io];
					break;
				case 'text':  // champ de texte : les retour chariot sont changer en <br />
					$t=($v=='')?$this->rgpd_gs[$n]:$v;
					$aremp=[];
					if(preg_match_all(';https?://([^ \r\n]+);',$t,$reg,PREG_SET_ORDER)) {  // lien sur texte maxi 50 cars
						foreach($reg as $l) {
							$atxt=(strlen($l[1])>50)?substr($l[1],0,50).'...':$l[1];
							$aremp[htmlspecialchars($l[0])]='<a href="'.$l[0].'" target="_blank">'.
								htmlspecialchars($atxt).'</a>';
						}
					}
					$t=htmlspecialchars($t);
					if($aremp) $t=strtr($t,$aremp);
					$remp['##'.$n.'##']=preg_replace(';\r?\n;','<br />',$t);
					$style=' style="text-align:left;"';
					break;
				default:
					$remp['##'.$n.'##']=htmlspecialchars(($v=='')?$this->rgpd_gs[$n]:$v);
					if($n=='rgpd_def_mcreateur') {  // cas special du mail : faire un lien mailto
						$remp['##'.$n.'##']='<a href="mailto:'.$remp['##'.$n.'##'].'">'.$vals['rgpd_def_createur'].'</a>';
					}
					break;
			}
			if($param) {
				$remp['##'.$n.'##']='<span data-toggle="tooltip" data-placement="top" class="badge badge-secondary"'.
					' title="'.htmlspecialchars($this->settings[$n]['label']).'"'.$style.'>'.$remp['##'.$n.'##'].
					'</span>';
			}
		}
		foreach($this->rgpd_gs as $n=>$v) {  // remplacement admin : variable rgpd_adm_xxxx
			if(preg_match(',^rgpd_adm_.+$,',$n)) {
				$remp['##'.$n.'##']=$v;
			}
		}
		$out=$this->rgpd_gs['rgpd_texte'];
		if($out) {
			// ajouter les mailto interdit dans l'editeur HTML avant variables
			if(preg_match_all(',[0-9a-zA-Z_.-]+@'.$dommail.',U',$out,$mail,PREG_SET_ORDER)) {
				foreach($mail as $m) {
					$out=str_replace($m[0],'<a href="mailto:'.$m[0].'" rel="nofollow">'.$m[0].'</a>',$out);
				}
			}
			$out=strtr($out,$remp);
		}
		return $out;
	}
	public function rgpd_write_survey($sid,$val=array()) {  // pour lire, ne pas mettre de $val
		$change=false;
		$buf=$this->rgpd_sql_request('select * from '.$this->nom_bd['param_survey'].
									 ' where `sid`='.(0+$sid),false,true);  // lire
		$info=getSurveyInfo($sid,App()->getLanguage());  // info questionnaire
		$valori=false;
		if($buf) $valori=unserialize(base64_decode($buf['param']));
		$maj=array();  // pour la MAJ
		foreach($this->rgpd_gs as $n=>$v) {  // on rempli $maj
			if(preg_match(',^rgpd_def_.+$,',$n)) {  // uniquement les variables questionnaire
				$maj[$n]=isset($valori[$n])?$valori[$n]:'';
				if(isset($val[$n])) {  // si valeur donnee alors remplir
					$maj[$n]=$val[$n];
				}
				switch($n) {
					case 'rgpd_def_createur':
						if($maj[$n]=='') {  // si valeur egal defaut alors maj vide
							$maj[$n]=$info['admin'];
						}
						break;
					case 'rgpd_def_mcreateur':
						if($maj[$n]=='') {  // si valeur egal defaut alors maj vide
							$maj[$n]=$info['adminemail'];
						}
						break;
					default:
						if($maj[$n]==$v) {  // si valeur egal defaut alors maj vide
							$maj[$n]='';
						}
						break;
				}
			}
		}
		foreach($maj as $n=>$v) {  // detection changement
			if(!isset($valori[$n])) {
				$change=true;
			}
			else {
				if($valori[$n]!=$v) {
					$change=true;
				}
			}
		}
		if($change) {  // il faut enregistrer quelque chose
			$inval=base64_encode(serialize($maj));  // encodage
			if($valori===false) {  // insert
				$req='insert into '.$this->nom_bd['param_survey']." (`sid`,`param`) values ('".$sid."','".$inval."')";
			}
			else {  // update
				$req='update '.$this->nom_bd['param_survey']." set `param`='".$inval."' where `sid`=".$sid;
			}
			$ok=$this->rgpd_sql_request($req,true);
			if($ok===false) return false;
		}
		return $maj;
	}
	private function rgpd_message_settings($delfich=false) {  // me bien les messages de la page de configuration
		if($this->rgpd_gs['rgpd_active_cons']) {
			$this->settings['rgpd_raz_cons']['help']=$this->rgpd_messages['rgpd_raz_cons'].' Cocher et "Sauvegarder"';
			if(file_exists($this->rgpd_statutcron)) {
				$x=$this->rgpd_getcroninfo(true);
				if($x['statut']==0) {
					$next=substr($x['date_next'],6,2).'/'.substr($x['date_next'],4,2).'/'.substr($x['date_next'],0,4);
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' Prochain lancement au '.$next.'.';
				}
				else {
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' <b>Suppression automatique en cours d\'exécution</b>.';
				}
			}
			else {
				if($delfich) {  // cas particulier ou le fichier viens d'etre supprimer
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' <b>Suppression automatique en cours d\'exécution</b>.';
				}
				else {
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' Pas de fichier .';
				}
			}
		}
		else {
			$this->settings['rgpd_raz_cons']['help']=$this->rgpd_messages['rgpd_raz_cons'].
				' La suppression automatique doit être activée.';
			$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'];
		}
	}
}
?>