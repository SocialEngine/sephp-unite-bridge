<?php

class UniteBridge_Migrate_Base {
    protected $db;

    protected $page = 1;

    protected $limit = 100;

    protected $table = null;

    protected $map = array();

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

    protected function getComments ($id) {
        return $this->db->select()
            ->from('engine4_activity_comments')
            ->where('resource_id = ?', $id)
            ->query()
            ->fetchAll();
    }

    protected function getLikes ($id) {
        return $this->db->select()
            ->from('engine4_activity_likes')
            ->where('resource_id = ?', $id)
            ->query()
            ->fetchAll();
    }

    public function run () {
        $records = array();

        $query = $this->db->select()
            ->from($this->table);

        $this->queryCount($query);

        $total = $query->query()->rowCount();

        $query = $this->db->select()
            ->from($this->table)
            ->limitPage($this->page, $this->limit);

        $this->query($query);

        $rows = $query->query()->fetchAll();
        foreach ($rows as $row) {
            $map = array();
            foreach ($row as $key => $value) {
                if (!empty($this->map)) {
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
    }
}
