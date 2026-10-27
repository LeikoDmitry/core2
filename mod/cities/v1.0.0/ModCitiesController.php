<?php
require_once DOC_ROOT . 'core2/inc/classes/Common.php';
require_once DOC_ROOT . 'core2/inc/classes/Panel.php';
require_once DOC_ROOT . 'core2/inc/classes/Alert.php';
require_once __DIR__ . '/classes/View.php';

use Core2\Mod\Cities\View;
use Core2\Panel;
use Core2\Alert;


/**
 * @property \Cities $dataCities
 */
class ModCitiesController extends Common {

    /**
     * Справочник городов Республики Беларусь
     * @return string
     * @throws Exception
     */
    public function action_index(): string {

        $app   = "index.php?module=cities";
        $view  = new View();
        $panel = new Panel();

        $content = '';

        try {
            if (isset($_GET['edit'])) {
                if (empty($_GET['edit'])) {
                    $panel->setTitle($this->_("Добавление города"), '', $app);
                    $content = $view->getEdit($app);

                } else {
                    $city = $this->dataCities->getRowById((int)$_GET['edit']);

                    if ( ! $city) {
                        throw new Exception($this->_("Город не найден"));
                    }

                    $panel->setTitle($city->name, $this->_('Редактирование города'), $app);
                    $content = $view->getEdit($app, $city);
                }

            } else {
                $panel->setTitle($this->_("Города Республики Беларусь"));
                $content = $view->getList($app);
            }

        } catch (\Exception $e) {
            $content = Alert::danger($e->getMessage(), 'Ошибка');
        }

        $panel->setContent($content);
        return $panel->render();
    }
}
