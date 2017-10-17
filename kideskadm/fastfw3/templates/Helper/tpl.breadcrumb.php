<?php
$active = 'home';
if (\Product\Service\ActiveProductgroupService::isProductgroupSet()) {
	$active = 'group';
}
if (\Product\Service\ActiveProductService::isProductSet()) {
	$active = 'product';
}
if (\Question\Service\ActiveQuestionpoolService::isQuestionpoolSet()) {
	$active = 'pool';
}
?>
<ol class="breadcrumb">


	<li<?= ($active == 'home' ? ' class="active"' : ''); ?>>
		<img src="resources/tuev_sued_logo.png" style="height:20px;">
		<a href="<?= getLink("Dashboard/clear");?>">Home</a>
	</li>

	<?php if(\Product\Service\ActiveProductgroupService::isProductgroupSet()) { ?>
		<li<?= ($active == 'group' ? ' class="active"' : ''); ?>>
			<i class="fa fa-group fa-fw"></i>&nbsp;<a  title="Familie/Gruppe" href="<?= getLink("Product-Productgroup/view/".\Product\Service\ActiveProductgroupService::get()->getPk());?>"><?= \Product\Service\ActiveProductgroupService::get()->getFullname(); ?></a>
		</li>
	<?php } ?>

	<?php if(\Product\Service\ActiveProductService::isProductSet()) { ?>
		<li<?= ($active == 'product' ? ' class="active"' : ''); ?>>
			<i class="fa fa-dot-circle-o fa-fw"></i>&nbsp;<a title="Produkt" href="<?= getLink("Product/view/".\Product\Service\ActiveProductService::get()->getPk());?>"><?= \Product\Service\ActiveProductService::get()->getName(); ?> (<?= \Product\Service\ActiveProductService::get()->getNumber(); ?>)</a>
		</li>

		<?php if(\Exam\Service\ActiveExamService::isExamSet()) { ?>
			<li>
				<i class="fa  fa-file-text-o fa-fw"></i>
				<a title="Prüfung" href='<?= getLink('Exam/view/'.\Exam\Service\ActiveExamService::get()->getPk());?>'><?= \Exam\Service\ActiveExamService::get()->getName(); ?></a>
			</li>
		<?php } ?>

		<?php if(\Event\Service\ActiveEventService::isEventSet()) { ?>
			<li>
				<i class="fa  fa-university fa-fw"></i>
				<a title="Veranstaltung" href='<?= getLink('Event/view/'.\Event\Service\ActiveEventService::get()->getPk());?>'><?= \Event\Service\ActiveEventService::get()->getName(); ?> (<?= \Event\Service\ActiveEventService::get()->getNumber(); ?>)</a>
			</li>
		<?php } ?>

	<?php } ?>

	<?php if(\Question\Service\ActiveQuestionpoolService::isQuestionpoolSet()) { ?>
		<li<?= ($active == 'pool' ? ' class="active"' : ''); ?>>
			<i class="fa  fa-list-ol fa-fw"></i>&nbsp;<a  title="Fragenpool" href="<?= getLink("Question-Questionpool/view/".\Question\Service\ActiveQuestionpoolService::get()->getPk());?>"><?= \Question\Service\ActiveQuestionpoolService::get()->getName(); ?></a>
		</li>
	<?php } ?>

</ol>