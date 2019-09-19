<?php

class UniteBridge_Migrate_Base {
    protected $db;

    protected $page = 1;

    protected $limit = 100;

    protected $table = null;

    protected $tableIdKey = null;

    protected $commentKey = null;

    protected $likeKey = null;

    protected $hasPhoto = false;

    protected $replacements = [
        'title' => 'subject',
        'description' => 'body',
        'owner_id' => 'user_id'
    ];

    /**
     * @var array
     * @deprecated
     */
    protected $map = array();

    /**
     * @var array
     * @deprecated
     */
    protected $mapCustom = [];

    public function __construct ($options) {
        $this->db = Engine_Db_Table::getDefaultAdapter();
        $this->page = $options['page'];
        $this->limit = $options['limit'];

        $this->init();
    }

    protected function init () {}

    /**
     * @param $query Zend_Db_Select
     */
    protected function query ($query) {}

    /**
     * @param $query Zend_Db_Select
     */
    protected function queryCount ($query) {}

    protected function record ($record) {
        return $record;
    }

    /**
     *
     * @deprecated
     * @param $records
     * @return mixed
     */
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
                    if (isset($this->replacements[$key])) {
                        $key = $this->replacements[$key];
                    }
                    if ($this->tableIdKey && $this->tableIdKey == $key) {
                        $key = 'id';
                    }
                    $map[$key] = $value;
                }

                if (isset($map['id'])) {
                    if ($this->commentKey) {
                        $map['comments'] = $this->getComments($map['id'], $this->commentKey);
                    }

                    if ($this->likeKey) {
                        $map['reactions'] = $this->getLikes($map['id'], $this->likeKey);
                    }

                    if ($this->hasPhoto) {
                        $map['photo'] = '';
                        if ($map['id']) {
                            $image = $this->db->select()
                                ->from('engine4_storage_files')
                                ->where('file_id = ?', $map['id'])
                                ->query()
                                ->fetch();
                            $map['photo'] = 'sephp:/' . $image['storage_path'];
                        }
                    }
                }

                $records[] = $this->record($map);
            }

            $records = $this->records($records);

            return array(
                'table' => $this->table,
                'total' => $total,
                'records' => $records
            );
        } catch (Exception $e) {
            UniteBridge_Controller_Response::error($e);
        }
    }
}
