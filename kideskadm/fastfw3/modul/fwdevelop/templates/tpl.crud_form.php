<?php
/**
* @var $##MODEL2## \##MODUL##\Model\##MODEL##Model
*/
?>
<form data-toggle="validator" class="form-horizontal" role="form" method="post"
	  enctype="multipart/form-data"
	  action="<?= getLink('*/' . $formAction . '/' . $##MODEL2##->getPk()); ?>">


	##FORMFIELDS##


	<?= \classes\CrudViewHelper::getFormButtons(); ?>

</form>