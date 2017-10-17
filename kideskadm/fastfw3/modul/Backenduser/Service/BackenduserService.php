<?php

namespace Backenduser\Service;

class BackenduserService extends \classes\AbstractService {

	static public function get($bu_fk) {
		#if (!($bu_fk instanceof \Backenduser\Model\BackenduserModel)) {
		if(is_numeric($bu_fk)) {
			if ((int)$bu_fk > 0) {
				$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();

				return $backenduserRepository->findByPk($bu_fk);
			} else {
				return new \classes\NullObj();
			}
		}

		return $bu_fk;
	}

}

?>