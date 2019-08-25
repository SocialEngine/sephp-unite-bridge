<?php

class UniteBridge_Migrate_Base {
    private $db;

    private $page = 1;

    private $limit = 100;

    protected $table = null;

    protected $map = array();

    public function __construct ($options) {
        $this->db = Engine_Db_Table::getDefaultAdapter();
        $this->page = $options['page'];
        $this->limit = $options['limit'];
    }

    protected function joinLeft () {}

    public function run () {
        $records = array();

        $total = $this->db->select()
            ->from($this->table)
            ->query()
            ->rowCount();

        $query = $this->db->select()
            ->from($this->table)
            ->limitPage($this->page, $this->limit);

        $this->joinLeft($query);

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
        return array(
            'total' => $total,
            'records' => $records
        );
    }
}
