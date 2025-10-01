<?php
if($data['etat']['statut']==1) { ?>
    <h4><b>Opération de suppression des données en cours.</b></h4>
    <p>Recharger cette page accélerera la procédure.</p>
<?php }
else {
    $lenrep=strlen($data['rgpd_repupload']); ?>
    <p><a href="#suppr">Nettoyage des fichiers</a> non fait par la suppression automatiques des données.</p>
    <h4>Configuration de la gestion des données</h4>
    <?php
    if($data['rgpd_gs']['rgpd_active_cons']==0) { ?>
    <p>&nbsp;Suppression automatique des données <b>désactivée</b>.</p>
    <?php }
    else {
        $dt=isset($data['etat']['date_next'])?$data['etat']['date_next']:$data['cron_info']['date_next'];
        $next=substr($dt,6,2).'/'.substr($dt,4,2).'/'.substr($dt,0,4);
    ?><p>&nbsp;Suppression automatique des données <b>activée</b>, prochain lancement au <?php
        echo $next; ?></p>
    <?php } ?>
    <p>&nbsp;Durée de conservation des données : <?php
    echo $data['rgpd_gs']['rgpd_adm_duree']; ?> mois.</p>
    <p>&nbsp;Périodicité de la suppression automatique : <?php
    echo $data['rgpd_gs']['rgpd_periode_cons']; ?> jours</p>
    <h4>Opération a faire si la suppression des données était lancée maintenant</h4>
    <p>Pour les <?php
    $nbop=0;
    echo $data['cron_info']['nb_survey']; ?> enquêtes dans la base<?php
    if($data['cron_info']['nb_survey_supr']>0) {
        $nbop++; ?>, <?php
        echo $data['cron_info']['nb_survey_supr']; ?> vont avoir au moins une table supprimée/vidée<?php } ?>.</p>
    <p>Il y a actuellement <?php 
    echo $data['cron_info']['nb_table_old']+$data['cron_info']['nb_table_act']; ?> tables pour ces enquêtes (<?php
    echo $data['cron_info']['nb_table_act']; ?> tables actives et <?php
    echo $data['cron_info']['nb_table_old']?> tables de sauvegardes)</p>
    <?php
    if($data['cron_info']['table_sup']) {
        $nbop++; ?>
    <p>&nbsp;<?php echo count($data['cron_info']['table_sup']);
    ?> tables a supprimer/vider (comprend aussi les fichiers téléchargés) :</p>
    <ul>
<?php if($data['cron_info']['nb_tablesup_act']>0) { ?>
        <li>actives : <?php echo $data['cron_info']['nb_tablesup_act']; ?></li><?php } ?>
<?php if($data['cron_info']['nb_tablesup_old']>0) { ?>
        <li>sauvegardes : <?php echo $data['cron_info']['nb_tablesup_old']; ?></li><?php } ?>
<?php if($data['cron_info']['nb_tablesup_orp_act']>0) { ?>
        <li>actives orphelines : <?php echo $data['cron_info']['nb_tablesup_orp_act']; ?></li><?php } ?>
<?php if($data['cron_info']['nb_tablesup_orp_old']>0) { ?>
        <li>sauvegardes orphelines : <?php echo $data['cron_info']['nb_tablesup_orp_old']; ?></li><?php } ?>
    </ul>
    <p>&nbsp;Liste des tables à supprimer/vider :</p>
    <ul>
<?php
    foreach($data['cron_info']['table_sup'] as $t) { ?>
        <li><?php
        echo $t; ?></li>
<?php } ?>
    </ul>
<?php } 
    if($data['cron_info']['rep_del']) {
        $nbop++; ?>
    <p>&nbsp;<?php echo count($data['cron_info']['rep_del']);
?> répertoires orphelins a supprimer :</p>
    <ul>
<?php
    foreach($data['cron_info']['rep_del'] as $t) { ?>
        <li><?php echo substr($t,$lenrep); ?></li>
<?php } ?>
    </ul>
<?php }
    if($data['cron_info']['lime_saved_control']>0) {
        $nbop++; ?>
    <p>&nbsp;<?php
    echo $data['cron_info']['lime_saved_control'];
?> lignes dans la table "lime_saved_control" a supprimer :</p>
<?php }
    if($nbop==0) { ?>
    <p>Rien a faire.</p>
<?php }
?>
    <h4><a name="suppr"></a>Nettoyage des fichiers</h4>
<?php
    if($data['lfs']['fin'] and $data['lfs']['action']) {  // mode liste finie
        if($data['lfs']['fichiers'] or $data['lfs']['del_upld']) {  // demande suppression
?>
<form method="post">
    <input type="hidden" value="<?php
echo $data['yii_token'];
?>" name="YII_CSRF_TOKEN">
    <input type="hidden" name="act" value="1" />
    <input type="submit" name="valide" value="Lancer le nettyage des fichiers" />
</form>
<?php }
    }
    elseif(!$data['lfs']['fin'] and $data['lfs']['action']) {  // mode liste pas finie
?>
<form method="post">
    <input type="hidden" value="<?php
echo $data['yii_token']; ?>" name="YII_CSRF_TOKEN">
    <input type="submit" name="valide" value="Continuer le calcul du nettoyage des fichiers..." />
</form>
<?php }
    elseif($data['lfs']['fin'] and !$data['lfs']['action']) {  // mode suppression finie
?>
    <p>Nettoyage des fichiers finie.</p>
<?php }
    else {  // mode suppression pas finie
?>
<form method="post">
    <input type="hidden" value="<?php
echo $data['yii_token']; ?>" name="YII_CSRF_TOKEN">
    <input type="hidden" name="act" value="1" />
    <input type="submit" name="valide" value="Continuer le nettoyage des fichiers..." />
</form>
<?php }
if($data['lfs']['rgpdsave']) { ?>
    <p style="background-color:#FFCECF; margin-top: 10px">&nbsp;Attention : il y a des fichiers présents dans le répertoire de sauvegarde du dernier nettoyage, ces fichiers seront supprimer au prochain nettoyage.</p><?php }
?>
    <p><b>Liste des fichiers orphelins uploader dans les enquêtes a supprimer (<?php
echo count($data['lfs']['fichiers']); ?>) :</b></p>
<?php
    if($data['lfs']['fichiers']) {  // liste des fichiers
    ?>
    <ul>
    <?php
    foreach($data['lfs']['fichiers'] as $f) { ?>
        <li><?php echo substr($f,$lenrep); ?></li>
    <?php } ?>
    </ul>
    <?php
    }
else {
    ?>
    <p>Aucun fichier.</p>
    <?php
}
if($data['lfs']['uploads']) { ?>
    <p><b>Liste des fichiers de présentation des enquêtes absent (aucune action)(<?php
echo count($data['lfs']['uploads']); ?>) :</b></p>
    <ul>
<?php foreach($data['lfs']['uploads'] as $f) {
        $buf=explode('|',$f,4); ?>
        <li><?php
echo 'Enquête ID=<a href="index.php?r=admin/survey/sa/view&surveyid='.$buf[0].'" target="_blank">'.
    $buf[0].'</a> ; table:'.$buf[1].' ; champ:'.$buf[2].' ; fichier:'.$buf[3]; ?></li>
<?php } ?>
    </ul>
<?php } ?>
    <p><b>Liste des fichiers orphelins de présentation des enquêtes a déplacer dans /upload/rgpdsave/ (répertoire vider avant nettoyage)(<?php
echo count($data['lfs']['del_upld']); ?>) :</b></p>
<?php
if($data['lfs']['del_upld']) { ?>
    <ul>
    <?php
    foreach($data['lfs']['del_upld'] as $f) { ?>
        <li><?php echo substr($f,$lenrep); ?></li>
    <?php } ?>
    </ul>
<?php    
}
else {
?>
    <p>Aucun fichier.</p>
<?php }
}
/*if($data['dev']) {
    echo '<pre>'.print_r($data,true).'</pre>';
}*/
?>
