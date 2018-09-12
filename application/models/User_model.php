<?php

class User_model extends CI_Model {

        public $id = 0;
        public $email = '';
		private $admin = '0';
        private $password = '';
        public $krediet = 0.0;
        private $resetkey = '';

        public function __construct() {
            parent::__construct();
            $this->load->database();
            $this->load->library('session');
            $this->load->model('Snack_model', 'snack');
            $this->load->model('Snack_model', 'bij');
        }

        public function init($id = 0) {
            if ($id == 0) {
                $id = $this->session->userdata('uid');
            }
            $q = $this->db->get_where('user', array('id' => $id));
            if ($q->num_rows() > 0) {
                $r = $q->result();
                $this->setVars($r);
                return true;
            }
            else {
                return false;
            }
        }
        public function emailExists($email) {
            $q = $this->db->get_where('user', array('email' => $email));
            if ($q->num_rows() > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        public function initByEmail($email) {
            $q = $this->db->get_where('user', array('email' => $email));
            $r = $q->result();
            $this->init($r[0]->id);
        }
        public function getResetKey() {
            return $this->resetkey;
        }
        public function createResetKey($email) {
            #$this->resetkey = md5(rand(11111111111111,99999999999999999999));
            $this->resetkey = md5(rand(1000000000,9999999999));
            $this->db->set('resetkey', $this->resetkey);
            $this->db->where('email', $email);
            $this->db->update('user');

            return $this->resetkey;
        }

        public function newPassword() {
            $newRaw = rand(11,99) . chr(rand(65,90)) . chr(rand(97,122)) . rand(0,9) . chr(rand(97,122)) . chr(rand(65,90)) . rand(0,9) . chr(rand(97,122)) . chr(rand(65,90));
            $this->password = md5($newRaw);
            $this->db->set('password', $this->password);
            $this->db->set('resetkey', '');
            $this->db->where('id', $this->id);
            $this->db->update('user');
            return $newRaw;
        }

        public function isIngelogd() {
            if ($this->session->userdata('uid') != '') {
                return true;
            }
            else {
                return false;
            }
        }

        public function getId() {
            return $this->id;
        }

        public function login($user, $pass) {
            $pass = md5($pass);
            $q = $this->db->get_where('user', array('email' => $user, 'password' => $pass));
            if ($q->num_rows() > 0) {
                $r = $q->result();
                $this->session->set_userdata('uid', $r[0]->id);
                $this->setVars($r);
                return true;
            }
            else {
                return false;
            }
        }

        public function loguit() {
                $this->session->unset_userdata('uid');
        }

        public function isAdmin() {
            return ($this->admin == '1');
        }

        public function getOrderHistorie($limit = 0) {
            $userId = $this->user->getId();

            $q = $this->db->query(" SELECT *
                                    FROM `order`
                                    WHERE user_id = $userId
                                    ORDER BY datum DESC;");

            $r = $q->result();
            $toRe = array();
            foreach ($r As $key => $item) {
                $this->snack->init($item->snack_id);
                $this->bij->init($item->bij_id);
                $aItem = (array) $item;
                $aItem['naam'] = $this->snack->naam;
                $aItem['bij'] = $this->bij->naam;

                $toRe[$item->datum][] = $aItem;

                if (isset($toRe[$item->datum]['totaal'])) {
                    $toRe[$item->datum]['totaal'] += $aItem['aantal'] * $aItem['prijs'];
                }
                else {
                    $toRe[$item->datum]['totaal'] = $aItem['aantal'] * $aItem['prijs'];
                }
            }
            if ($limit == 0) {
                return $toRe;
            }
            else {
                $cnt = 0;
                $toReLimit = array();
                foreach ($toRe As $key => $item) {
                    if ($cnt < $limit) {
                        $toReLimit[$key] = $item;
                        $cnt++;
                    }
                    else {
                        break;
                    }
                }
                return $toReLimit;
            }
        }

        public function getLastOrder() {
            return $this->getOrderHistorie(1);
        }

        public function getOrderByDate($datum) {
           $userId = $this->user->getId();

            $q = $this->db->query(" SELECT *
                                    FROM `order`
                                    WHERE user_id = $userId
                                    AND datum = '$datum';");

            $r = $q->result();
            $toRe = array();
            foreach ($r As $key => $item) {
                $this->snack->init($item->snack_id);
                $this->bij->init($item->bij_id);
                $aItem = (array) $item;
                $aItem['naam'] = $this->snack->naam;
                $aItem['bij'] = $this->bij->naam;

                $toRe[$item->datum][] = $aItem;

                if (isset($toRe[$item->datum]['totaal'])) {
                    $toRe[$item->datum]['totaal'] += $aItem['aantal'] * $aItem['prijs'];
                }
                else {
                    $toRe[$item->datum]['totaal'] = $aItem['aantal'] * $aItem['prijs'];
                }
            }
                $cnt = 0;
                $toReLimit = array();
                foreach ($toRe As $key => $item) {
                        $toReLimit[$key] = $item;
                }
                return $toReLimit;

        }

        public function getBetalingen() {
        $this->db->order_by('datum', 'desc'); 
            $q = $this->db->get_where('inleg', array('user_id' => $this->user->getId()));
            return $q->result();
        }

        // Implementatie scheelt een extra query load/login en SPOD
        private function setVars($r) {
            $this->id = $r[0]->id;
            $this->email = $r[0]->email;
			$this->admin= $r[0]->admin;
            $this->password = $r[0]->password;
            $this->resetkey = $r[0]->resetkey;
            $userId = $this->getId();

            // Totale inleg bepalen
            $q = $this->db->query(" SELECT SUM(bedrag) As bedrag
                                    FROM inleg
                                    WHERE user_id = $userId;");
            $r = $q->result();
            $inleg = $r[0]->bedrag;

            // Totale uitgaven bepalen
            $q = $this->db->query(" SELECT SUM(prijs * aantal) As bedrag
                                    FROM `order`
                                    WHERE user_id = $userId;");
            $r = $q->result();
            $orderkosten = $r[0]->bedrag;
            $this->krediet = ($inleg - $orderkosten);
        }

    }
?>

