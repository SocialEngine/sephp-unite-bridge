<?php

class UniteBridge_Migrate_Base {
    protected $db;

    protected $page = 1;

    protected $limit = 100;

    protected $table = null;

    protected $map = array();

    protected $mapCustom = [];

    public function __construct ($options) {
        $this->db = Engine_Db_Table::getDefaultAdapter();
        $this->page = $options['page'];
        $this->limit = $options['limit'];
    }

    /**
     * @param $query Zend_Db_Select
     */
    protected function query ($query) {}

    /**
     * @param $query Zend_Db_Select
     */
    protected function queryCount ($query) {}

    protected function records ($records) {
        return $records;
    }

    protected function getComments ($id, $type) {
        return $this->db->select()
            ->from('engine4_core_comments')
            ->where('resource_type = ?', $type)
            ->where('resource_id = ?', $id)
            ->query()
            ->fetchAll();
    }

    protected function getLikes ($id, $type) {
        return $this->db->select()
            ->from('engine4_core_likes')
            ->where('resource_type = ?', $type)
            ->where('resource_id = ?', $id)
            ->query()
            ->fetchAll();
    }

    public function run () {
        try {
            $records = array();

            $query = $this->db->select()
                ->from($this->table, array('COUNT(*) AS __total'));

            $this->queryCount($query);

            $row = $query->query()->fetch();
            $total = $row['__total'];

            $query = $this->db->select()
                ->from($this->table)
                ->limitPage($this->page, $this->limit);

            $this->query($query);

            $rows = $query->query()->fetchAll();
            foreach ($rows as $row) {
                $map = array();
                foreach ($row as $key => $value) {
                    if (count($this->mapCustom) && in_array($key, $this->mapCustom)) {
                        // $key = $key;
                    } else if (count($this->map)) {
                        if (!isset($this->map[$key])) {
                            continue;
                        }
                        $key = $this->map[$key];
                    }
                    $map[$key] = $value;
                }
                $records[] = $map;
            }

            $records = $this->records($records);

            return array(
                'total' => $total,
                'records' => $records
            );
        } catch (Exception $e) {
            UniteBridge_Controller_Response::error($e);
        }
    }
}
