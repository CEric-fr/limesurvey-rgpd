<?php
/*********************************************************************************************************************
Plugin RGPD pour LimeSurvey
Tester sur version 3.22.1+200129 avec base MySql
Version 2.10
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
<li>Les données d\'identification (prénom, nom et adresse mail) issues des bases informatiques académiques 
uniquement pour les personnes concernées par une enquête ciblée et envoyée nominativement.</li>
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
		'rgpd_active_cons'=>array(
			'type'=>'boolean',
			'label'=>'Activer la suppression automatique des données au delà de la durée de conservation',
			'default'=>0,
			'help'=>'',  // cf messages
		),
		'rgpd_adm_duree'=>array(
			'type'=>'int',
			'label'=>'Durée de conservation des données (en mois)',
			'default'=>'12',
			'help'=>'##rgpd_adm_duree##',
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
	protected $rgpd_parlfs='';  // nom du fichier de la liste des fichiers a supprimer
    protected $rgpd_repupload='';  // rep d'upload des fichiers
    protected $rgpd_repupsave='';  // rep de sauvegarde des fichiers upload supprime par le nettoyage
	protected $rgpd_messages=array(
	'rgpd_raz_cons'=>'Cette suppression manuelle supprimera les données selon la durée de conservation choisie.',
	'rgpd_active_cons'=>'Cette action est déclenchée par la visite d\'une page de l\'outil d\'enquête.',
    );
    // Initialisation des variables utiles au fonctionnement
    private function rgpd_init_var() {
		$menu=new Surveymenu;
		$menuliste=new SurveymenuEntries;
		$this->nom_bd['menu']='`'.$menu->tableName().'`';
		$this->nom_bd['menuliste']='`'.$menuliste->tableName().'`';
        $this->nom_bd['raw_prefix']=App()->getDb()->tablePrefix;  // prefix brut simple
		$this->nom_bd['param_survey']='`'.$this->nom_bd['raw_prefix'].'survey_rgpd`';
		$this->nom_bd['survey']='`'.$this->nom_bd['raw_prefix'].'surveys`';  // !! comment obtenir autrement ??
		$this->nom_bd['prefix']=preg_quote($this->nom_bd['raw_prefix'],';');  // prefix echapper PREG pour CRON
        $this->nom_bd['nom_base']='';
        $x=App()->getDb()->connectionString;
        if(preg_match('/dbname=([^;]+);?$/',$x,$reg)) {
            $this->nom_bd['nom_base']=$reg[1];
        }
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
        $this->rgpd_parlfs=$this->rgpd_repcron.'parlfs.txt';
        $this->rgpd_repupload=$_SERVER['DOCUMENT_ROOT'].'/upload/surveys/';  // rep survey upload
        $this->rgpd_repupsave=$_SERVER['DOCUMENT_ROOT'].'/upload/rgpdsave/';  // rep sauvegarde rgpd
		if(!file_exists($this->rgpd_repupsave)) mkdir($this->rgpd_repupsave);  // creation si pas existe
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
    }
	public function init() {
        $this->rgpd_init_var();
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
            $url=$this->api->createUrl(
                'admin/pluginhelper',
                array(
                    'sa'     => 'fullpagewrapper',
                    'plugin' => $this->getName(),
                    'method' => 'rgpdpagestatut'  // Method name in our plugin
                    )
                );
			$event->set('extraMenus', array(
			  new Menu(array(
				'isDropDown' => true,
				'label'=>'RGPD',
				'menuItems' => array(
					new MenuItem(array('href'=>'/index.php?r=admin/pluginmanager/sa/configure&id='.
									   $this->id,'label'=>'Configuration générale')),
                    new MenuItem(array('href'=>$url,'label'=>'Statut données')),
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
			if(isset($_POST['rgpd_raz_cons']) and $_POST['rgpd_raz_cons']==1) {  // lancer manuel de suppression
				if(file_exists($this->rgpd_statutcron)) {
					$x=$this->rgpd_getcroninfo(true);
					if($x['statut']==0) {  // si pas en cour de lancer
						unlink($this->rgpd_statutcron);  // supprimer le fichier
						$this->rgpd_logcron('Fichier status supprimé.');
						$delfich=true;
					}
				}
				$_POST['rgpd_raz_cons']=0;  // ne jamais le mettre en cocher (c'est juste un bouton poussoir)
				$_REQUEST['rgpd_raz_cons']=0;
				$majmes=true;
			}
			if(isset($_POST['rgpd_active_cons'])) {  // activation suppression automatique
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
	// CRON dans deux cas : questionnaire (id>0) et admin (id=0) $debug=true force execution en mode debug
	private function rgpd_exec_cron($id=0,$debug=false) {
        $par=false;
		if($this->rgpd_gs['rgpd_active_cons'] or $debug) {  // on est en mode actif
			if(file_exists($this->rgpd_lockcron) and filemtime($this->rgpd_lockcron)>(time()-30)) {
				return $par;  // si deja un cron et present depuis moins de 30s
			}
			$par=$this->rgpd_getcroninfo();
			if($par['statut']==0) {
				return $par;  // rien a faire, on attend
			}
			if(file_exists($this->rgpd_lockcron) and filemtime($this->rgpd_lockcron)>(time()-30)) {
				return false;  // si deja un cron et present depuis moins de 30s
			}
			file_put_contents($this->rgpd_lockcron,$id.' '.time());  // on lock ici
            $df=time()+1;  // duree en fonction de la page => sur questionnaire 1s sinon admin 4s
            if($id==0 or $debug) {  // admin ou debug
                $df=time()+4;
            }
			$this->rgpd_logcron('ID='.$id.' Etape='.$par['etape'].($debug?' DEBUG':''));
			switch($par['etape']) {
				case 0:  // juste enregistrer
					$this->rgpd_logcron('Fin Etape='.$par['etape']);
					$par['etape']++;  // suite
					break;
				case 1:  // suppression des tables avec leurs fichiers (plus de vider)
					if($par['table_sup']) {  // il reste des tables a supprimer
						foreach($par['table_sup'] as $cle=>$t) {
                            $ids=$this->rgpd_getidfromtable($t);
                            if($ids['id']>0) {  // ok
                                $ch=array();
                                $rep='';
                                if(isset($par['table_fich'][$t],$par['survey'][$ids['id']]['rep_upload'])) {
                                    $ch=$par['table_fich'][$t];  // si existe
                                    $rep=$par['survey'][$ids['id']]['rep_upload'];
                                }
                                $mode=false;
                                if($ids['type']=='old' or !isset($par['survey'][$ids['id']])) {
                                    $mode=true;  // on ne supprime que les table old et sans enquete
                                }
                                $res=$this->rgpd_table_fichier_del($t,$mode,$ch,$rep);
                                $this->rgpd_logcron('Table '.$t.' :'.PHP_EOL.$res,1);
                            }
                            else {  // pas de ID survey trouve : on signale et c'est tout
                                $this->rgpd_logcron('Table : '.$t.' non comprise (pas d\'ID trouvé)',1);
                            }
							unset($par['table_sup'][$cle]);
							if(time()>$df) {  // fini, plus le temps, on en fait au moins 1
                                break;
                            }
						}
					}
					else {
						$this->rgpd_logcron('Fin Etape='.$par['etape']);
						$par['etape']++;  // suite
					}
					break;
				case 2:  // suppression repertoire
					if($par['rep_del']) {
						foreach($par['rep_del'] as $cle=>$r) {
                            if(!$debug) {  // normal
                                if($this->rgpd_recursive_suppr_rep($r)) {
                                    $this->rgpd_logcron('Répertoire supprimé : '.$r,1);
                                }
                                else {
                                    $this->rgpd_logcron('Erreur, suppression du répertoire : '.$r,1);
                                }
                            }
                            else {
                                $this->rgpd_logcron('Répertoire a supprimer : '.$r.' DEBUG',1);
                            }
							unset($par['rep_del'][$cle]);
							if(time()>$df) {  // fini, plus le temps, on en fait au moins 1
                                break;
                            }
						}
					}
					else {
						$this->rgpd_logcron('Fin Etape='.$par['etape']);
						$par['etape']++;  // suite
					}
					break;
                case 3:  // suppression ligne dans lime_saved_control
                    $deltats=($this->rgpd_gs['rgpd_adm_duree']+1)*2628000;  // on envele (mois+1)
                    $dtts=time()-$deltats;  // timestamp de la date de suppression
                    $ok=$this->rgpd_sql_request('delete from `lime_saved_control` where `saved_date`<\''.
                                                 date('Y-m-d H:i:s',$dtts)."'",true);  // lignes a supprimer
                    $this->rgpd_logcron('Fin Etape='.$par['etape']);
                    $par['etape']=100;  // suite
                    break;
				case 100:
					$x=$this->rgpd_gs['rgpd_periode_cons'];
					$next=date('Ymd',time()+(86400*$this->settings['rgpd_periode_cons']['options'][$x]));
					$this->rgpd_logcron('***** Fin nettoyage, prochain : '.$next);
                    $par=$this->rgpd_get_afaire($next);  // init zero
					break;
				default:  // hors porter !!!
					$par['etape']=1;  // on recommence !!!
					break;
			}
			$this->rgpd_getcroninfo($par);  // enregistrer
			unlink($this->rgpd_lockcron);  // liberer lock cron
		}
        return $par;  // retourne les parametres
	}
	// recupere le status du CRON, si $val=array alors ecrit seulement, si $val=true alors retourne contenu
	// si pas de $val alors retourne le contenu si existe sinon le creer avec analyse
	private function rgpd_getcroninfo($val=array()) {
		if($val and is_array($val)) {  // des valeurs alors on ecrit
			return file_put_contents($this->rgpd_statutcron,serialize($val));
		}
		$out=array();
		if(file_exists($this->rgpd_statutcron)) {  // exite
			$out=unserialize(file_get_contents($this->rgpd_statutcron));  // pas de base64
			if(isset($out['date_next'],$out['statut'],$out['etape'],$out['survey'],$out['table_fich'],
					 $out['table_sup'],$out['rep_del'],$out['rep_fich'])) {
				if($out['statut']!=0) {  // en cour statut=1, fin sans analyse
                    return $out;
                }
				if($out['date_next']>date('Ymd')) {  // pas encore la date de la prochaine supp, fin sans analyse
                    return $out;
                }
			}
            else {  // contenu en erreur => on lance
                unlink($this->rgpd_statutcron);
                $out=array();
            }
		}
		if($val===true) {  // force le retour d'un contenu sans analyse (vide ou pas) statut=0
            if(!$out) {
                $out=$this->rgpd_get_afaire(date('Ymd'));
            }
			return $out;
		}
		$this->rgpd_logcron('***** Début nettoyage');
		return $this->rgpd_get_afaire();  // lancer l'analyse statut=1 donc on fait la suppression au prochain CRON
	}
    private function rgpd_get_afaire($next=0) {  // on retourne le contexte des info a faire selon parametre
        //$start=microtime(true);
        $datenext=$next;
        if($next==0) {
            $datenext=date('Ymd');
        }
        $out=array('date_next'=>$datenext,  // date prochain test
                   'err'=>false,  // une erreur presente
                   'txterr'=>'',  // texte de l'erreur
                   'statut'=>1,  // statut : 0=en attente, 1=en cours de test
                   'etape'=>0,  // etape de test de depart qui ne fait que enregistrer le fichier
                   'survey'=>array(),  // liste des enquetes avec [sid]=>array('sid',...) pour info
                   'table_fich'=>array(),  // liste des tables avec fichier nom_table=>array(nom_champ,nom_champ,...)
                   'table_sup'=>array(),  // liste des tables a supprimer ou sans enquete
                   //'table_vide'=>array(),  // liste des tables survey a vider
                   'lime_saved_control'=>0,  // nb de ligne a supprimer dans la table "lime_saved_control"
                   'rep_del'=>array(),  // les repertoires a supprimer
                   'rep_fich'=>array(),  // les repertoires avec fichiers []=>rep files ou images
                   'nb_survey'=>0,  // nombre total d'enquetes (count('survey'))
                   'nb_survey_supr'=>0,  // nombre d'enquetes avec suppression (hors old)
                   'nb_table_old'=>0,  // nombre de table old total (y compris a supprimer)
                   'nb_table_act'=>0,  // nombre de table active total (y compris a supprimer)
                   'nb_tablesup_old'=>0,  // nombre de table old a supprimer
                   'nb_tablesup_orp_old'=>0,  // nombre de table old orpheline supprimer
                   'nb_tablesup_act'=>0,  // nombre de table active a supprimer
                   'nb_tablesup_orp_act'=>0,  // nombre de table active orpheline supprimer
                   'nb_table_act_vide'=>0,  // nombre de table active mais vide
                   'nb_rep_orp'=>0,  // nombre de repertoires orphelins
                   'nb_rep_act'=>0,  // nombre de repertoires actifs (files et images)
                  );
        if($next!=0) {  // si on donne une date, juste rien a faire
            $out['statut']=0;
            return $out;
        }
		$deltats=($this->rgpd_gs['rgpd_adm_duree']+1)*2628000;  // on envele (mois+1)
        $dtts=time()-$deltats;  // timestamp de la date de suppression
		$datebd=date('YmdHis',$dtts);
        $buf=$this->rgpd_sql_request('select `sid` from '.$this->nom_bd['survey']);  // toutes les enquetes
        if($buf) {
            foreach($buf as $e) {  // mettre dans $out['survey'][ID]=array('sid'=>ID)
                $out['survey'][$e['sid']]=$e;
            }
        }
        $out['nb_survey']=count($out['survey']);  // nombre d'enquetes
        $buf=$this->rgpd_sql_request('select TABLE_NAME,COLUMN_NAME from information_schema.COLUMNS'.
                                     " where COLUMN_NAME like '%_filecount' and TABLE_SCHEMA='".
                                     $this->nom_bd['nom_base']."'");
        if($buf) {
            foreach($buf as $t) {  // on range les noms des champs de fichier par nom de table
                $out['table_fich'][$t['TABLE_NAME']][]=substr($t['COLUMN_NAME'],0,-10);
            }
        }
        $buf=$this->rgpd_sql_request('show table status');
		if($buf) {
            if(isset($buf[0]['Name'],$buf[0]['Rows'],$buf[0]['Update_time'])) {  // on lance
                foreach($buf as $t) {
                    $ids=$this->rgpd_getidfromtable($t['Name']);  // detecte les tables d'enquetes
                    if($ids['type']=='old') {  // sauvegarde avec date
                        $out['nb_table_old']++;
                        if(isset($out['survey'][$ids['id']])) {  // enquete existe
                            if($ids['date']<$datebd) {  // enquete existe mais vieille
                                $out['nb_tablesup_old']++;
                                $out['table_sup'][]=$t['Name'];
                                $out['survey'][$ids['id']]['table_supp'][]=$t['Name'];  // pour info
                            }
                            else {
                                $out['survey'][$ids['id']]['table_olds'][]=$t['Name'];  // pour info                        
                            }
                        }
                        else {  // enquete existe plus donc suppr
                            $out['nb_tablesup_orp_old']++;
                            $out['table_sup'][]=$t['Name'];
                        }
                    }
                    elseif($ids['type']=='act') {  // autres
                        $out['nb_table_act']++;
                        if(isset($out['survey'][$ids['id']])) {  // existe
                            $tdup=date('YmdHis',strtotime($t['Update_time']));
                            if($t['Rows']>0) {  // si pas vide
                                if($tdup<$datebd) {  // supprimer car trop vieille
                                    if(!isset($out['survey'][$ids['id']]['table_supp'])) {
                                        $out['nb_survey_supr']++;
                                    }
                                    $out['nb_tablesup_act']++;
                                    $out['survey'][$ids['id']]['table_supp'][]=$t['Name'];  // pour info
                                    $out['table_sup'][]=$t['Name'];  // liste des tables a supprimer
                                }
                                else {  // voir chaque ligne ou non car plus rescente
                                    $out['survey'][$ids['id']]['table_acti'][]=$t['Name'];  // pour info
                                }
                            }
                            else {  // si vide alors ne rien faire, permet de connaitre les tables
                                $out['nb_table_act_vide']++;
                                $out['survey'][$ids['id']]['table_vide'][]=$t['Name'];  // pour info
                            }
                        }
                        else {  // existe pas
                            $out['nb_tablesup_orp_act']++;
                            $out['table_sup'][]=$t['Name'];  // liste des tables a supprimer
                        }
                    }
                }
            }
            else {  // il manque un parametre utile au fonctionnement => fin sur erreur
                $out['err']=true;
                $out['txterr']='Il manque un paramètres utile de la requête "show table status".';
                return $out;
            }
        }
        else {  // pas de table dans la base => fin sur erreur
            $out['err']=true;
            $out['txterr']='Pas de table dans la base !!!.';
            return $out;
        }
        $buf=glob($this->rgpd_repupload.'*',GLOB_ONLYDIR);
        if($buf) {  // on fait une liste des reps a supprimer et des reps upload existants
            $nbrs=strlen($this->rgpd_repupload);
            foreach($buf as $r) {
                $id=(int)substr($r,$nbrs);
                if(isset($out['survey'][$id])) {  // enquete existe : voir si reps existent
                    if(is_dir($r.'/files/')) {  // les rep d'upload client et admin
                        $out['rep_fich'][]=$r.'/files/';
                        $out['survey'][$id]['rep_upload']=$r.'/files/';  // pour info
                    }
                    if(is_dir($r.'/images/')) {  // les rep d'upload images admin
                        $out['rep_fich'][]=$r.'/images/';
                        $out['survey'][$id]['rep_images']=$r.'/images/';  // pour info
                    }
                }
                else {  // enquete n'existe pas => supprimer
                    $out['rep_del'][]=$r.'/';
                }
            }
        }
        $out['nb_rep_act']=count($out['rep_fich']);  // a voir pour le comptage !!!!
        $out['nb_rep_orp']=count($out['rep_del']);
        $buf=$this->rgpd_sql_request('select * from `lime_saved_control` where `saved_date`<\''.
                                     date('Y-m-d H:i:s',$dtts)."'");  // le ligne a supprimer
        if($buf) {
            $out['lime_saved_control']=count($buf);
        }
        //$out['duree']=microtime(true)-$start;
		return $out;
    }
    private function rgpd_getidfromtable($tab) {  // retourne ID enquete a partir nom de table
        $out=array('type'=>'err','id'=>0,'date'=>0);
        if(preg_match(';^'.$this->nom_bd['prefix'].'old_[^_]+_([0-9]+)(_[^_]+)?_([0-9]{14})$;',$tab,$reg)) {
            $out=array('type'=>'old','id'=>(int)$reg[1],'date'=>(int)$reg[3]);
        }
        elseif(preg_match(';^'.$this->nom_bd['prefix'].'[^_]+_([0-9]+)(_.+)?$;',$tab,$reg)) {
            $out=array('type'=>'act','id'=>(int)$reg[1],'date'=>0);
        }
        return $out;
    }
	private function rgpd_logcron($txt='',$sub=0,$fich='x') {  // logger le texte dans un fichier
		$fl=$this->rgpd_repcron.$fich.'log_'.date('Ymd').'.txt';  // fichier de log
		$esp=str_repeat(' ',$sub);
        $txt=str_replace(PHP_EOL,PHP_EOL.$esp,$txt);  // pour les multilignes
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
		$remp=array();  // tableau de remplacement
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
					$aremp=array();
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
	private function rgpd_message_settings($delfich=false) {  // mettre bien les messages de la page de configuration
		if($this->rgpd_gs['rgpd_active_cons']) {  // on a activer la suppression
			$this->settings['rgpd_raz_cons']['help']=$this->rgpd_messages['rgpd_raz_cons'].' Cocher et "Sauvegarder"';
			if(file_exists($this->rgpd_statutcron)) {  // si fichier present
				$x=$this->rgpd_getcroninfo(true);
				if($x['statut']==0) {  // en attente prochaine date de suppression
					$next=substr($x['date_next'],6,2).'/'.substr($x['date_next'],4,2).'/'.substr($x['date_next'],0,4);
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' Prochain lancement au '.$next.'.';
				}
				else {  // en cour de nettoyage
					$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'].
						' <b>Suppression automatique en cours d\'exécution</b>.';
				}
			}
			else {  // si fichier absent
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
		else {  // suppression auto desactive
			$this->settings['rgpd_raz_cons']['help']=$this->rgpd_messages['rgpd_raz_cons'].
				' La suppression automatique doit être activée.';
			$this->settings['rgpd_active_cons']['help']=$this->rgpd_messages['rgpd_active_cons'];
		}
	}
    public function rgpdpagestatut() {  // prepare les donnees pour l'affichage
        $out=array('data'=>array());
        $this->rgpd_init_var();
        $out['data']['nom_bd']=$this->nom_bd;
        $out['data']['rgpd_logo']=$this->rgpd_logo;
        $out['data']['rgpd_gs']=$this->rgpd_gs;
        unset($out['data']['rgpd_gs']['rgpd_texte']);
        $x=$out['data']['rgpd_gs']['rgpd_periode_cons'];
        $out['data']['rgpd_gs']['rgpd_periode_cons']=$this->settings['rgpd_periode_cons']['options'][$x];
        $out['data']['rgpd_repcron']=$this->rgpd_repcron;
        $out['data']['rgpd_parlfs']=$this->rgpd_parlfs;
        $out['data']['rgpd_statutcron']=$this->rgpd_statutcron;
        $out['data']['rgpd_lockcron']=$this->rgpd_lockcron;
        $out['data']['rgpd_repupload']=$this->rgpd_repupload;
        $out['data']['yii_token']=$_COOKIE['YII_CSRF_TOKEN'];
        $out['data']['etat']=$this->rgpd_getcroninfo(true);
        //$out['data']['dev']=(strpos($this->rgpd_parlfs,'dev')!==false);
        if($out['data']['etat']['statut']==0) {  // si pas d'action
            $out['data']['cron_info']=$this->rgpd_get_afaire();
            file_put_contents($this->rgpd_repcron.'page_statut_afaire.txt',print_r($out['data']['cron_info'],true));
            $out['data']['lfs']=$this->rgpd_fichiers_orphelins($out['data']['cron_info']);
            file_put_contents($this->rgpd_repcron.'page_statut_fichsup.txt',print_r($out['data']['lfs'],true));
            if($out['data']['lfs']['fin'] and $out['data']['lfs']['action']) {  // liste finie
                if(isset($_POST['act']) and $_POST['act']==1 and
                   ($out['data']['lfs']['fichiers'] or $out['data']['lfs']['del_upld'])) {  // go suppr
                    $out['data']['lfs']['action']=false;  // action suppression
                    $this->rgpd_logcron('Depart nettoyage fichier',0,'f');
                    $this->rgpd_recursive_suppr_rep($this->rgpd_repupsave,false);  // pas de test de verif operation
                    $out['data']['lfs']=$this->rgpd_fichiers_suppr($out['data']['lfs']);
                }
            }
            elseif(!$out['data']['lfs']['fin'] and !$out['data']['lfs']['action']) {  // suppr en cours
                $out['data']['lfs']=$this->rgpd_fichiers_suppr($out['data']['lfs']);
            }
        }
        return $this->renderPartial('page_statut',$out,true);
    }
    // suprime ou vide ($mode=true pour supprimer, false pour vider, debug pour tester)
    private function rgpd_table_fichier_del($nomtab,$mode=true,$champfich=array(),$repfich='') {  // traite table
        $out='';
        if($nomtab) {
            if($repfich and $champfich and is_array($champfich)) {  // test pour suprimer fichiers
                $ch='`'.implode('`,`',$champfich).'`';
                $buf=$this->rgpd_sql_request('select '.$ch.' from `'.$nomtab.'`');  // lire tous les enregistrements
                if($buf) {
                    foreach($buf as $l) {  // pour chaque ligne
                        foreach($l as $v) {  // pour chaque cellule
                            $f=isset($v)?$v:'';  // pour enlever null
                            if($f and preg_match_all(';"filename":"([^"]+)";U',$f,$reg,PREG_SET_ORDER)) {
                                foreach($reg as $r) {
                                    if(file_exists($repfich.$r[1])) {
                                        if($mode===true or $mode===false) unlink($repfich.$r[1]);  // pas de verif
                                        $out.='  - '.$nomtab.' : '.$repfich.$r[1].' SUPPR'.PHP_EOL;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($mode===true) {  // suppression table
                Yii::app()->db->createCommand()->dropTable($nomtab);
                $out.=' * '.$nomtab.' SUPPR';
            }
            elseif($mode===false) {  // vider table
                Yii::app()->db->createCommand()->truncateTable($nomtab);
                $out.=' * '.$nomtab.' VIDER';
            }
            else {  // mode debug
                $out.=' * '.$nomtab.' SUPPR/VIDER:'.$mode;
            }
        }
        else {
            $out='ERREUR : pas de nom de table';
        }
        return $out;
    }
    private function rgpd_fichiers_orphelins($par) {  // les parametres issu de rgpd_get_afaire
        $df=time()+10;  // fin de travail dans 10s maxi
        $out=array('fin'=>false,  // operation finie si true
                   'action'=>true,  // type d'action en cours true->liste, false->suppression
                   'rgpdsave'=>false,  // true si presence de fichier
                   'gsurvey'=>array(),  // liste des rep ID survey deja entree dans fichiers
                   'fichiers'=>array(),  // liste des fichiers fu_ a supprimer (arbo systeme pas web)
                   'uploads'=>array(),  // liste des fichiers presents dans les textes HTML sans fichier uploader
                   'del_upld'=>array(),  // liste des fichiers files/images a supprimer/move (arbo systeme pas web)
                   'table_fich'=>array(),  // copie de $par pour travail (vide quand travail fini)
                   'rep_fich'=>array(),  // copie de $par pour travail mais uniquement les files : [ID]=>rep files
                   'rep_upld'=>array(),  // copie de $par pour travail des files et images : [ID]=>array(rep)
                   'tbl_check'=>array(array('table'=>'questions',  // liste des tables pour fichiers upload admin
                                           'champ'=>array('question','help'),
                                           'chsid'=>'sid',
                                          ),
                                     array('table'=>'groups',
                                           'champ'=>array('description'),
                                           'chsid'=>'sid',
                                          ),
                                     array('table'=>'surveys_languagesettings',
                                           'champ'=>array('surveyls_description','surveyls_welcometext',
                                                          'surveyls_endtext','surveyls_email_invite',
                                                          'surveyls_email_remind','surveyls_email_register', 'surveyls_email_confirm','email_admin_notification',
                                                          'email_admin_responses','attachments'),
                                           'chsid'=>'surveyls_survey_id',
                                          ),
                                    ),
                   //'debug'=>array(),  // pour debug (supprimer apres mise en prod finale)
                  );
        $initvar=false;  // pour dire qu'il faut remplir les deux variables copie
        if(file_exists($this->rgpd_parlfs) and filemtime($this->rgpd_parlfs)<(time()-21600)) {  // fichier trop vieux
            unlink($this->rgpd_parlfs);  // si plus de 6h -> supprime
            $initvar=true;
        }
        if(!$initvar and file_exists($this->rgpd_parlfs)) {  // si pas init et fichiers buf existe
            $cont=unserialize(file_get_contents($this->rgpd_parlfs));
            $var=array();
            $nbc=0;
            foreach($out as $n=>$v) {
                if(isset($cont[$n]) and gettype($cont[$n])==gettype($v)) {
                    $nbc++;
                    $var[$n]=$cont[$n];
                }
            }
            if($nbc!=count($out)) {  // si pas tout ce qu'il faut -> supprime
                unlink($this->rgpd_parlfs);
                $initvar=true;
            }
            else {
                $out=$var;
            }
        }  // $out contient buf ou vide a remplir
        else {  // c'est bizarre mais j'ai peur que la suppression du premier test ne fausse le 2eme file_exists
            $initvar=true;
        }
        if($initvar and isset($par['table_fich'],$par['rep_fich'])) {  // init $out avec $par
            $out['table_fich']=$par['table_fich'];
            foreach($par['rep_fich'] as $r) {  // mettre dans ['rep_fich'] un tableau [ID]=>rep files uniquement
                if(preg_match(';/([0-9]+)/files/$;',$r,$reg)) {  // files
                    $out['rep_fich'][$reg[1]]=$r;
                    $out['rep_upld'][$reg[1]][]=$r;
                }
                if(preg_match(';/([0-9]+)/images/$;',$r,$reg)) {  // images
                    $out['rep_upld'][$reg[1]][]=$r;
                }
            }
        }
        if($out['fin'] or !$out['action']) {  // si operation pas finie ou suppression
            return $out;
        }  // faire le check
        if($out['table_fich']) {  // si non vide on fait
            foreach($out['table_fich'] as $t=>$chs) {  // pour chaque table avec des champs fichiers
                $ids=$this->rgpd_getidfromtable($t);
                if($ids['id']>0 and isset($par['survey'][$ids['id']]['rep_upload'])) {  // si ID et rep existe
                    if(!isset($out['gsurvey'][$ids['id']])) {  // 1 fois pour chaque enquete, lire tous les fichiers
                        $gfs=glob($par['survey'][$ids['id']]['rep_upload'].'fu_*');  // les fichiers commence par fu_
                        $out['gsurvey'][$ids['id']]=count($gfs);  // mettre le nombre total de fichiers
                        if($gfs) {  // si non vide
                            foreach($gfs as $f) {  // on ajoute les fichiers du repertoire
                                $out['fichiers'][$f]=$f;
                            }
                        }
                    }
                    $ch='`'.implode('`,`',$chs).'`';  // tous les champs avec fichiers
                    $buf=$this->rgpd_sql_request('select '.$ch.' from `'.$t.'`');  // lire tous les enregistrements
                    if($buf) {  // si non vide
                        foreach($buf as $l) {  // pour chaque ligne
                            foreach($l as $v) {  // pour chaque cellule
                                $f=isset($v)?$v:'';  // pour enlever null (filename ne contient que le nom du fichier)
                                if($f and preg_match_all(';"filename":"([^"]+)";U',$f,$reg,PREG_SET_ORDER)) {
                                    foreach($reg as $r) {  // pour chaque fichier, le retirer si present
                                        if(isset($out['fichiers'][$par['survey'][$ids['id']]['rep_upload'].$r[1]])) {
                                            unset($out['fichiers'][$par['survey'][$ids['id']]['rep_upload'].$r[1]]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                unset($out['table_fich'][$t]);
                if(time()>$df) {  // on arrete
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
        }  // si je sors ici c'est qu'il n'y a plus de table_fich a traiter
        if($out['rep_fich']) {  // si il reste des repertoire files
            foreach($out['rep_fich'] as $i=>$r) {  // parcours des repertoires files
                if(!isset($out['gsurvey'][$i])) {  // si pas deja traiter alors on liste les fichiers
                    $gfs=glob($r.'fu_*');  // le masque des fichiers commence par fu_
                    $out['gsurvey'][$i]=count($gfs);
                    if($gfs) {
                        foreach($gfs as $f) {  // on ajoute tous les fichiers
                            $out['fichiers'][$f]=$f;
                        }
                    }
                }
                unset($out['rep_fich'][$i]);  // on enleve ce rep
                if(time()>$df) {  // on arrete
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
        }  // si je sors ici c'est qu'il n'y a plus de rep_fich a traiter
        if($out['tbl_check']) {  // si il reste des table a explorer
            foreach($out['tbl_check'] as $i=>$t) {  // pour chaque table
                $buf=$this->rgpd_bdrech_fich($t['table'],$t['champ'],$t['chsid']);
                if($buf) {  // si ya des fichier dans la base (champ texte HTML)
                    $out['uploads']=array_merge($out['uploads'],$buf);
                }
                unset($out['tbl_check'][$i]);  // on enleve cette table faite
                if(time()>$df) {  // on arrete
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
            //$out['debug']['upload']=$out['uploads'];  // debug
        }  // si je sors ici c'est qu'il n'y a plus de tbl_check a traiter
        if($out['rep_upld']) {  // si encore des repertoire a scanner
            $nbroot=strlen($_SERVER['DOCUMENT_ROOT']);  // nb carac rep root
            foreach($out['rep_upld'] as $i=>$rs) {  // pour chaque repertoire
                foreach($rs as $r) {  // on fait les deux obligaroirement
                    $buf=glob($r.'*.*');  // tous les fichiers (pas les fu_* car sans .ext)
                    if($buf) {  // si non vide
                        foreach($buf as $f) {  // pour chaque fichier
                            $frel=substr($f,$nbroot);
                            //$out['debug']['all_fich_rep'][]=$f;  // debug
                            if(isset($out['uploads'][$frel])) {  // on enleve les fichiers present
                                unset($out['uploads'][$frel]);
                            }
                            else {  // sinon on ajoute a supprimer
                                $out['del_upld'][$f]=$f;
                            }
                        }
                    }
                }
                unset($out['rep_upld'][$i]);  // on enleve cette table faite
                if(time()>$df) {  // on arrete
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
        }  // si je sors ici c'est qu'il n'y a plus de rep_upld a traiter
        $buf=glob($this->rgpd_repupsave.'*',GLOB_ONLYDIR);
        if($buf) {  // ya des rep
            $out['rgpdsave']=true;
        }
        $out['fin']=true;
        file_put_contents($this->rgpd_parlfs,serialize($out));
        return $out;
    }
    private function rgpd_fichiers_suppr($par) {  // on supprime tous les fichiers
        $df=time()+15;  // temps de travail maxi
        $out=$par;
        $out['rgpdsave']=false;
        if(!isset($out['fin'],$out['action'],$out['fichiers'],$out['del_upld'])) {  // on fait rien si pas de data ok
            $this->rgpd_logcron('Erreur : manque variable.',0,'f');
            return $out;
        }
        if($out['action']) {  // on fait rien si pas d'ordre
            return $out;
        }
        $out['fin']=false;  // en cours de suppression
        if($out['fichiers']) {  // suppression de tous les fichiers orphelins
            foreach($out['fichiers'] as $i=>$f) {
                if(file_exists($f)) {
                    if(unlink($f)) {  // ok
                        $this->rgpd_logcron('Fichier supprimer : '.$f,1,'f');
                    }
                    else {
                        $this->rgpd_logcron('Erreur, fichier non supprimer : '.$f,1,'f');
                    }
                }
                unset($out['fichiers'][$i]);
                if(time()>$df) {  // on arrete si trop de temps passe
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
        }  // si je sors ici c'est qu'il n'y a plus de fichiers a traiter
        if($out['del_upld']) {  // deplacement de tous les fichiers orphelins
            foreach($out['del_upld'] as $i=>$f) {  // $f est le nom du fichier
                if(file_exists($f)) {  // fichier present
                    $fc=str_replace('/surveys/','/rgpdsave/',$f);
                    $x=strrpos($fc,'/');
                    if($x!==false) {  // on est bon
                        $rs=substr($fc,0,$x);
                        if(!is_dir($rs)) {
                            if(mkdir($rs,0777,true)) {  // recursive car au moins deux niveau nouveau au debut
                                $this->rgpd_logcron('Répertoire créer : '.$rs,1,'f');
                            }
                            else {
                                $this->rgpd_logcron('Erreur, création répertoire : '.$rs,1,'f');
                            }
                        }
                        if(rename($f,$fc)) {
                            $out['rgpdsave']=true;
                            $this->rgpd_logcron('Fichier déplacé : '.$fc,1,'f');
                        }
                        else {
                            $this->rgpd_logcron('Erreur, fichier non déplacé : '.$f,1,'f');
                        }
                    }
                    else {
                        $this->rgpd_logcron('Erreur, fichier non déplacé : '.$f.', répertoire non compris : '.$fc,
                                            1,'f');
                    }
                    $ft=preg_replace(';/(files|images)/;','/.thumbs$0',$f);  // on fait de meme pour le thumbs
                    if(file_exists($ft)) {  // ce fichier n'existe pas obligatoirement
                        $fc=str_replace('/surveys/','/rgpdsave/',$ft);
                        $x=strrpos($fc,'/');
                        if($x!==false) {  // on est bon
                            $rs=substr($fc,0,$x);
                            if(!is_dir($rs)) {
                                if(mkdir($rs,0777,true)) {  // recursive car au moins deux niveau nouveau au debut
                                    $this->rgpd_logcron('Répertoire créer : '.$rs,1,'f');
                                }
                                else {
                                    $this->rgpd_logcron('Erreur, création répertoire : '.$rs,1,'f');
                                }
                            }
                            if(rename($ft,$fc)) {
                                $this->rgpd_logcron('Fichier déplacé : '.$fc,1,'f');
                            }
                            else {
                                $this->rgpd_logcron('Erreur, fichier non déplacé : '.$ft,1,'f');
                            }
                        }
                        else {
                            $this->rgpd_logcron('Erreur, fichier non déplacé : '.$ft.', répertoire non compris : '.$fc,
                                                1,'f');
                        }
                    }
                }
                else {
                    $this->rgpd_logcron('Erreur, fichier absent : '.$f,1,'f');
                }
                unset($out['del_upld'][$i]);
                if(time()>$df) {  // on arrete si trop de temps passe
                    file_put_contents($this->rgpd_parlfs,serialize($out));
                    return $out;
                }
            }
        }  // si je sors ici c'est qu'il n'y a plus de del_upld a traiter
        $this->rgpd_logcron('FINI',0,'f');
        $out['fin']=true;
        $out['action']=true;
        unlink($this->rgpd_parlfs);
        return $out;
    }
    private function rgpd_bdrech_fich($table,$champ,$chsid='sid') {
        $out=array();  // cherche dans $table les fichiers present dans chaque $champ
        if(!$table) {  // si table vide
            return $out;
        }
        if(!$chsid) {  // si nom du champ survey ID vide
            return $out;
        }
        if(!is_array($champ) or !$champ) {  // si champ vide ou pas un array
            return $out;
        }
        foreach($champ as $c) {  // pour chaque champ (meme si un seul)
            $buf=$this->rgpd_sql_request('select `'.$chsid.'`,`'.$c.'` from `'.$this->nom_bd['raw_prefix'].
                                         $table.'` where `'.$c."` like '%/upload/surveys/%'");
            if($buf) {  // ya
                foreach($buf as $l) {  // pour chaque ligne
                    if(preg_match_all(';(/upload/surveys/([0-9]+)/(files|images)/[^"]+)";U',
                                      $l[$c],$reg,PREG_SET_ORDER)) {
                        foreach($reg as $r) {  // pour chaque fichier
                            $f=urldecode($r[1]);  // remplace les %xx et +
                            $out[$f]=$r[2].'|'.$table.'|'.$c.'|'.$f;  // localisation dans la base
                        }
                    }
                }
            }
        }
        return $out;
    }
    private function rgpd_recursive_suppr_rep($rep,$delrep=true) {  // supprime le contenu et le repertoire donne
        if(substr($rep,-1)=='/') {  // avec ou sans / terminal
            $rep=substr($rep,0,-1);
        }
        if(!is_dir($rep)) {
            return false;
        }
        $fs=array_diff(scandir($rep),array('.','..'));  // tous sauf . et ..
        foreach($fs as $f) {
            if(is_dir($rep.'/'.$f)) {
                if(!$this->rgpd_recursive_suppr_rep($rep.'/'.$f)) {  // Erreur
                    return false;
                }
            }
            else {
                if(!unlink($rep.'/'.$f)) {  // Erreur
                    return false;
                }
            }
        }
        if($delrep) {
            return rmdir($rep);
        }
        return true;
    }
}
?>