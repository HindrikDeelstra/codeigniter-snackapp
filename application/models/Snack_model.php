<?php

class Snack_model extends CI_Model {

    public $id = 0;
    public $naam = '';
    public $prijs = 0.00;
    public $order = 0;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function init($id) {
        if ($id > 0) {
            $q = $this->db->get_where('snack', array('id' => $id));
            $r = $q->result();
            $this->id = $r[0]->id;
            $this->naam = $r[0]->naam;
            $this->prijs = $r[0]->prijs;
            $this->order = $r[0]->order;
        }
        else {
            $this->id = 0;
            $this->naam = '';
            $this->prijs = 0;
            $this->order = 0;
        }
    }

    public function getAll() {
        $q = $this->db->get('snack');
        return $q->result();
    }

    public function getAllHoofdProducten() {
    $this->db->order_by('order' , 'asc');
        $this->db->order_by('naam' , 'asc');
    $q = $this->db->get_where('snack', array('bijproduct' => 0, 'zichtbaar' => 1));
    //$q = $this->db->query("SELECT * FROM snack WHERE bijproduct='0' AND zichtbaar='1' ORDER BY `order` ASC,naam ASC;");
        return $q->result();
    }

    public function getBijproducten() {
        $this->db->order_by('order' , 'asc');
        $this->db->order_by('naam' , 'asc');
        $q = $this->db->get_where('snack', array('bijproduct' => 1, 'zichtbaar' => 1));
    //$q = $this->db->query("SELECT * FROM snack WHERE bijproduct='1' AND zichtbaar='1' ORDER BY `order` ASC,naam ASC;");
        return $q->result();
    }

    public function getBestellingenVanaf($datum) {
        $q = $this->db->query(" SELECT `s`.`naam` AS `naam`,
                `s2`.`naam` AS `bij`,
                COUNT(`o`.`id`) AS `aantal2`,
                SUM(`aantal`) AS `aantal`
                                FROM `order` AS `o`
                                LEFT JOIN `snack` AS `s` ON `o`.`snack_id` = `s`.`id`
                                LEFT JOIN `snack` AS `s2` ON `o`.`bij_id` = `s2`.`id`
                                WHERE `o`.`datum` > '$datum'
                                GROUP BY `s`.`id`, `s2`.`id`
                ORDER BY `s`.`order`, `s`.`naam`;");
        $tore = array();
        foreach ($q->result_array() as $r) {
            $tore[] = $r;
        }
        return $tore;
    }

}

?>

