<?php

class Beheer extends CI_Controller {

        function __construct() {
                parent::__construct();
                $this->load->database();
                $this->load->model('User_model', 'user');
        }

        public function index() {
        }

        function snackstats() {
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        $data = array('users' => $this->getAllUserStats()['users'], 'checksum' => $this->getAllUserStats()['checksum'], 'foodstats' => $this->getAllSnackStats(), 'firstorder' => $this->getFirstOrderDate());
                        $this->load->view('snackstats', $data);
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }

        function orders() {
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        $data = array('orders' => $this->getLastOrders());
                        $this->load->view('orders', $data);
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }

        function getAdmins() {
            $q = $this->db->get_where('user', array('admin' => '1'));
            $r = $q->result();
            $a = array();
            foreach ($r as $user) {
                $a[] = $user->email;
            }
            return $a;
        }

        function declareren() {
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        if($this->user->isAdmin()) {
                            $data = array('userstats' => $this->getAllUserStats()['users'], 'checksum' => $this->getAllUserStats()['checksum'], 'datum' => $this->getLastOrderDate(), 'admin' => $this->user->isAdmin());
                            $this->load->view('declareren', $data);
                        } else {
                            $data = array('admins' => $this->getAdmins());
                            $this->load->view('geentoegang', $data);
                        }
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }

        function gedeclareerd() {
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        if($this->input->post('bedrag') != '0.00') {
                                //$decl = array('uid' => $this->input->post('uid'), 'bedrag' => $this->input->post('bedrag'), 'datum' => $this->input->post('datum'));
                                $this->db->set('user_id', $this->input->post('uid'));
                                $this->db->set('bedrag', $this->input->post('bedrag'));
                                $this->db->set('datum', $this->input->post('datum'));
                                $this->db->insert('inleg');

                                $this->load->view('decladone');
                        } else {
                                header("Location: /index.php/beheer/declareren");
                        }
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }

        function getAllUserStats() {
                $q = $this->db->query(" SELECT *,
                                        SUBSTRING_INDEX(`email`,'@',1) as `naam`
                                        FROM `user_stats`
                                        WHERE `email` LIKE '%@oxilion.nl'
										OR `email` LIKE '%@fundaments.nl'
                                        ORDER BY `saldo`;");
                $r = $q->result();
                #return $r;

                $s = array();
                $c = 0;
                foreach($r As $u) {
                                $s[$u->user_id]['naam'] = $u->naam;
                                $s[$u->user_id]['ingelegd'] = $u->ingelegd;
                                $s[$u->user_id]['laatsteinleg'] = $u->laatsteinleg;
                                $s[$u->user_id]['gekocht'] = $u->gekocht;
                                $s[$u->user_id]['laatstgekocht'] = $u->laatstgekocht;
                                $s[$u->user_id]['saldo'] = $u->saldo;
                                $c += $s[$u->user_id]['saldo'];
                }

                return array('users' => $s, 'checksum' => $c);
        }

        function getAllSnackStats() {
                $q = $this->db->get('snack_stats');
                $r = $q->result();

                return $r;
        }

        function getLastOrders() {
                $q = $this->db->query(" SELECT SUBSTRING_INDEX(`email`,'@',1) AS `usernaam`,
                                        `user_id`,
                                        CONCAT(`sa`.`naam`,IF(`sb`.`naam` IS NOT NULL, CONCAT(' ',`sb`.`naam`),'')) AS `snack`,
                                        `order`.`prijs`,
                                        `order`.`aantal`,
                                        `datum`,
                                        `datumtijd`
                                        FROM `order`
                                        JOIN `user` ON `order`.`user_id`=`user`.`id`
                                        JOIN `snack` AS `sa` ON `order`.`snack_id` = `sa`.`id`
                                        LEFT JOIN `snack` AS `sb` ON `order`.`bij_id` = `sb`.`id`
                                        WHERE WEEKOFYEAR(`datum`) = WEEKOFYEAR(CURDATE())
                                        AND YEAR(`datum`) = YEAR(CURDATE())
                                        ORDER BY `datumtijd` ASC,`user_id`;");
                $r = $q->result();

                return $r;
        }

        function getFirstOrderDate() {
                $this->db->select_min('datum');
                $q = $this->db->get('order');
                $r = $q->result();

                return $r[0]->datum;
        }

        function getLastOrderDate() {
                $this->db->select_max('datum');
                $q = $this->db->get('order');
                $r = $q->result();

                return $r[0]->datum;
        }

        function nieuwpass() {
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        $this->load->view('nieuwpass');
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }

        function setnieuwpass() {
                $this->load->library('email');
                $this->load->view('header');
                if ($this->user->isIngelogd()) {
                        $this->user->init();
                        if($this->input->post('nieuwpass') == $this->input->post('nieuwpass2')) {
                                if(strlen($this->input->post('nieuwpass')) >= 8) {
                                        $this->db->set('password', md5($this->input->post('nieuwpass')));
                                        $this->db->where('id', $this->user->id);
                                        $this->db->update('user');

                                        $this->email->set_mailtype("html");
                                        $this->email->from('h.deelstra@oxilion.nl');
                                        $this->email->to($this->user->email);
                                        $this->email->subject('Nieuw wachtwoord ingesteld');
                                        $this->email->message('Er is via de snack-app een nieuw wachtwoord ingesteld.</p></br>
                                                               Indien dit onverwacht komt, is je account gehacked, vraag een nieuw wachtwoord aan via:</p></br>
                                                               https://snack.oxilion.nl/index.php/snack/lostpass');
                                        $this->email->send();

                                        $this->load->view('nieuwpass_done');
                                } else {
                                        $tekst = 'Wachtwoord te kort (moet minimaal 8 tekens zijn)';
                                        $this->load->view('nieuwpass_fout', array('reden' => $tekst));
                                }
                        } else {
                                $tekst = 'Ingevoerde wachtwoorden niet gelijk';
                                $this->load->view('nieuwpass_fout', array('reden' => $tekst));
                        }
                } else {
                        $this->load->view('login');
                }
                $this->load->view('footer');
        }


}
?>

