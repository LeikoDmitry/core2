<?php
require_once "core2/inc/ajax.func.php";


/**
 * @property \Cities $dataCities
 */
class ModAjax extends ajaxFunc {

    /**
     * Сохранение города
     * @param array $data
     * @return xajaxResponse
     */
    public function axSaveCity(array $data): xajaxResponse {

        $this->error = [];
        $fields      = [
            'name'                  => 'req',
            'region'                => 'req',
            'is_regional_center_sw' => 'req',
            'is_active_sw'          => 'req',
        ];

        $data['control'] = $this->clearData($data['control']);

        if ($this->ajaxValidate($data, $fields)) {
            return $this->response;
        }

        try {
            if ( ! array_key_exists($data['control']['region'], $this->dataCities->getRegions())) {
                throw new Exception($this->_('Укажите область из списка.'));
            }

            $refid = (int)$this->getSessFormField($data['class_id'], 'refid');

            if ($this->dataCities->isExists($data['control']['name'], $data['control']['region'], $refid)) {
                throw new Exception($this->_('Такой город в этой области уже есть.'));
            }

        } catch (Exception $e) {
            $this->error[] = $e->getMessage();
            $this->displayError($data);
            return $this->response;
        }

        if ( ! $this->saveData($data)) {
            return $this->response;
        }

        $this->done($data);
        return $this->response;
    }
}
