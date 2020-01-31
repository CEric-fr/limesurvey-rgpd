<?php
/**
 * @var $aTabTitles
 * @var $aTabContents
 * @var $has_permissions
 * @var $surveyid
 * @var $surveyls_language
 */
if(isset($data)){
    extract($data);
}
 $count=0;

    $iSurveyID = Yii::app()->request->getParam('surveyid');
    Yii::app()->session['FileManagerContext'] = "edit:survey:{$iSurveyID}";
    initKcfinder();

PrepareEditorScript(false, $this);

// donnees utile a affichage
$rgpd=Yii::app()->pluginManager->loadPlugin('rgpd');
$settings=$rgpd->getPluginSettings();
$vals=$rgpd->rgpd_write_survey($iSurveyID);
$texte=$rgpd->rgpd_texte_survey($iSurveyID,true);

?>
<div class="container-fluid">
    <div class="row">
		<p>L'affichage de la page RGPD est obligatoire, ces paramètres sont optionnels. En cas de champ vide, la valeur défini par l'administrateur sera utilisé.</p>
	</div>
    <div class="row">
<?php foreach($vals as $n=>$v) {
	$vd=$settings[$n]['default'];
	if(isset($settings[$n]['current'])) $vd=$settings[$n]['current'];
?>
		<div class="form-group">
<?php
	switch($settings[$n]['type']) {
		case 'select':
			if($v!='') $vd=$v;
?>
			<label class="control-label" for="<?php echo $n;
?>"><?php echo $settings[$n]['label']; 
?> :</label>
			<div class="">
				<select class="form-control" id="<?php echo $n;
?>" name="<?php echo $n;
?>">
<?php
			foreach($settings[$n]['options'] as $io=>$vo) { ?>
				  <option value="<?php echo $io;
?>"<?php if($io==$vd) echo ' selected="selected"';
?>><?php echo $vo;
?></option>
<?php			}
?>
    			</select>
			</div>
<?php
			break;
		case 'string':
?>
			<label class="control-label" for="<?php echo $n;
?>"><?php echo $settings[$n]['label'];
?> :</label>
			<div class="">
				<input class="form-control" size="80" id="<?php echo $n;
?>" type="text" value="<?php echo htmlspecialchars($v);
?>" name="<?php echo $n;
?>" placeholder="<?php echo htmlspecialchars($vd);
?>" />
			</div>
<?php
			break;
		case 'text':
?>
			<label class="control-label" for="<?php echo $n;
?>"><?php echo $settings[$n]['label'];
?> :</label>
			<div class="">
				<textarea class="form-control" name="<?php echo $n;
?>" id="<?php echo $n;
?>"><?php echo htmlspecialchars($v);
?></textarea>
			</div>
<?php
			break;
	}
?>
		</div>
<?php } ?>
    </div>
	<div class="row">
		<p><b>Contenu du texte RGPD du questionnaire :</b></p>
		<div style="border: 2px solid #5E5E5E; border-radius: 10px;padding: 10px;">
<?php echo $texte;?>
		</div>
	</div>
</div>
