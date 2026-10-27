<?php
namespace Core2\Mod\Cities;

require_once DOC_ROOT . 'core2/inc/classes/Common.php';
require_once DOC_ROOT . 'core2/inc/classes/class.list.php';
require_once DOC_ROOT . 'core2/inc/classes/class.edit.php';


/**
 * @property \Cities $dataCities
 */
class View extends \Common {

    /**
     * Список городов
     * @param string $app
     * @return string
     * @throws \Exception
     */
    public function getList(string $app): string {

        $list = new \listTable('cities');

        $list->addSearch($this->_("Название"), "name",   "TEXT");
        $list->addSearch($this->_("Область"),  "region", "LIST"); $list->sqlSearch[] = $this->dataCities->getRegions();

        $list->SQL = "
            SELECT id,
                   name,
                   region,
                   CASE WHEN is_regional_center_sw = 'Y' THEN 'да' END AS is_regional_center,
                   is_active_sw
            FROM mod_cities
            WHERE 1=1 /*ADD_SEARCH*/
            ORDER BY name
        ";

        $list->addColumn($this->_("Название"),        "",    "TEXT");
        $list->addColumn($this->_("Область"),         "250", "TEXT");
        $list->addColumn($this->_("Областной центр"), "150", "TEXT");
        $list->addColumn("",                          "1%",  "STATUS");

        $list->paintCondition = "'TCOL_04' == 'N'";
        $list->paintColor     = "fafafa";

        $list->addURL  = "{$app}&edit=0";
        $list->editURL = "{$app}&edit=TCOL_00";

        return $list->render();
    }


    /**
     * Форма добавления / редактирования города
     * @param string                           $app
     * @param \Zend_Db_Table_Row_Abstract|null $city
     * @return string
     * @throws \Exception
     */
    public function getEdit(string $app, \Zend_Db_Table_Row_Abstract $city = null): string {

        $edit = new \editTable('cities');

        $edit->SQL = $this->db->quoteInto("
            SELECT id,
                   name,
                   region,
                   is_regional_center_sw
            FROM mod_cities
            WHERE id = ?
        ", $city ? $city->id : 0);

        $edit->addControl($this->_("Название"),        "TEXT",  "maxlength=\"128\" style=\"width:385px\"", "", "", true);
        $edit->addControl($this->_("Область"),         "LIST",  "style=\"width:385px\"", "", "", true); $edit->selectSQL[] = ['' => '--'] + $this->dataCities->getRegions();
        $edit->addControl($this->_("Областной центр"), "RADIO", "", "", "N", true); $edit->selectSQL[] = ['Y' => 'да', 'N' => 'нет'];

        $edit->addButtonSwitch('is_active_sw', $city ? $city->is_active_sw == 'Y' : true);

        $edit->back = $app;
        $edit->addButton($this->_("Вернуться к списку городов"), "load('$app')");
        $edit->save("xajax_saveCity(xajax.getFormValues(this.id))");

        return $edit->render();
    }
}
