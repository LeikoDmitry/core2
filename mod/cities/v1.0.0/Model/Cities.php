<?php


/**
 * Справочник городов Республики Беларусь
 */
class Cities extends \Zend_Db_Table_Abstract {

    const REGIONS = [
        'г. Минск',
        'Брестская область',
        'Витебская область',
        'Гомельская область',
        'Гродненская область',
        'Минская область',
        'Могилёвская область',
    ];

    protected $_name = 'mod_cities';


    /**
     * Получение записи по Id
     * @param int $id
     * @return \Zend_Db_Table_Row_Abstract|null
     */
    public function getRowById(int $id):? \Zend_Db_Table_Row_Abstract {

        $select = $this->select()->where("id = ?", $id);

        return $this->fetchRow($select);
    }


    /**
     * Список областей для выпадающих списков
     * @return array
     */
    public function getRegions(): array {

        return array_combine(self::REGIONS, self::REGIONS);
    }


    /**
     * Проверка, есть ли уже такой город в области
     * @param string $name
     * @param string $region
     * @param int    $exclude_id
     * @return bool
     */
    public function isExists(string $name, string $region, int $exclude_id = 0): bool {

        $select = $this->select()
            ->where("name = ?", $name)
            ->where("region = ?", $region)
            ->where("id != ?", $exclude_id);

        return (bool)$this->fetchRow($select);
    }
}
