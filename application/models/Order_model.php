<?php

class Order_model extends CI_Model {

        public $totaal = 0;
        public $snacks = array();

        public function __construct() {
            parent::__construct();
            $this->load->library('session');
        }

        public function init() {
            $this->snacks = array();
            foreach ($this->session->userdata('snack') As $key => $item) {
                // Voorkomen dat iemand een negatief aantal besteld en zo krediet verwerft.
                if (!empty($item)) {
                    if (is_numeric($item['aantal']) && !empty($item['aantal']) && round($item['aantal']) > 0) {
                        $this->snack->init($item['snack_id']);
                        $snack['id'] = $item['snack_id'];
                        $snack['aantal'] = round($item['aantal']);
                        $snack['prijs'] = $this->snack->prijs;
                        $snack['naam'] = $this->snack->naam;
                        $snack['bij_id'] = $item['bij_id'];

                        // Aan de beschrijving en prijs toevoegen
                        if ($snack['bij_id'] > 0) {
                            $this->bij->init($snack['bij_id']);
                            $snack['naam'] .= ' met '. strtolower($this->bij->naam);
                            $snack['prijs'] += $this->bij->prijs;
                        }

                        $this->snacks[] = $snack;
                        $this->totaal += ($snack['prijs'] * $snack['aantal']);
                    }
                    else {
                        $this->session->set_userdata('info', 'Je kunt alleen getallen invullen die positief zijn. Deze zijn niet meegenomen.');
                    }
                }
            }
            return $this->snacks;
        }

        public function plaats() {
           foreach ($this->session->userdata('snack') As $key => $item) {
                // Voorkomen dat iemand een negatief aantal besteld en zo krediet verwerft.
                if (!empty($item)) {
                    if (is_numeric($item['aantal']) && round($item['aantal']) > 0) {
                        $this->snack->init($item['snack_id']);
                        $snack['aantal'] = round($item['aantal']);

                        $this->db->set('snack_id', $item['snack_id']);
                        $this->db->set('user_id', $this->user->getId());
                        $this->db->set('aantal', round($item['aantal']));
                        $this->db->set('bij_id', $item['bij_id']);
                        $this->db->set('datum', date('Y-m-d'));

                        // Aan de beschrijving en prijs toevoegen
                        if ($item['bij_id'] > 0) {
                            $this->bij->init($item['bij_id']);
                            $snack['prijs'] = ($this->bij->prijs + $this->snack->prijs);
                        }
                        else {
                           $snack['prijs'] = $this->snack->prijs;
                        }

                        // Prijs ook opslaan omdat de prijs in de toekomst kan veranderen.
                        $this->db->set('prijs', $snack['prijs']);
                        $this->db->insert('order');

                        // Sessievariabele legen om te voorkomen dat de persoon dubbel besteld
                        $this->session->set_userdata('snack', array());
                    }
                    else {
                        $this->session->set_userhdata('info', 'Je kunt alleen getallen invullen die positief zijn. Deze zijn niet meegenomen.');
                    }
                }
            }

        }
    }

?>

