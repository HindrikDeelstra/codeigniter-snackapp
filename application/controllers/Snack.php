<?php

class Snack extends CI_Controller {

    function __construct () {
        parent::__construct();
        $this->load->model('User_model', 'user');
        $this->load->model('Snack_model', 'snack');
        $this->load->model('Snack_model', 'bij');
    }

    public function index() {
        $this->load->view('header');
        if ($this->user->isIngelogd()) {
            $this->user->init();
            $data = array('snacks' => $this->snack->getAllHoofdProducten());
            $strBijproducten = '';
            foreach ($this->snack->getBijproducten() As $bp) {
                $strBijproducten .= '<option value="'. $bp->id . '">'. $bp->naam . ' (&euro; '. number_format($bp->prijs,2) . ')</option>';
            }
            $data['strBijproducten'] = $strBijproducten;
            $this->load->view('main', $data);
        }
        else {
            $this->load->view('login');
        }
        $this->load->view('footer');
    }

    function order() {
        if ($this->user->isIngelogd()) {
            $this->user->init();
            $snacksruw = $this->input->post('snack');

            // Bijproducten verwerken
            $bij = $this->input->post('bij');
            foreach ($snacksruw As $id => $aantal) {
                $a = array();
                if (round($aantal) > 0) {
                    $a['snack_id'] = $id;
                    $a['aantal'] = $aantal;
                    $a['bij_id'] = $bij[$id];
                    $snacks[] = $a;
                }
            }

            $this->session->set_userdata('snack', $snacks);
            $this->load->model('Order_model', 'order');
            $this->order->init();

            $data['user'] = $this->user;
            $data['order'] = $this->order;

            $this->load->view('header');
            $this->load->view('overzicht', $data);
            $this->load->view('footer');
        }
        else {
            Header("Location: /");
        }
    }

    function lostpass($id = 0, $key = '') {
            $this->load->view('header');
            $this->load->library('email');
            $data['bericht'] = 'De instructies zijn, indien het e-mailadres bestaat, verstuurd.';

            if (strlen($this->input->post('email')) > 5 && $this->user->emailExists($this->input->post('email'))) {
                $key = $this->user->createResetkey($this->input->post('email'));
                $this->user->initByEmail($this->input->post('email'));
                $data = array('id' => $this->user->id,'email' => $this->input->post('email'), 'resetkey' => $key);
                $emailText = $this->load->view('email', $data, true);
                $this->load->view('lostpass_2');
                #mail($this->input->post('email'), 'Wachtwoord resetten?', $emailText, 'FROM: snack@oxilion.nl');

                $this->email->from('h.deelstra@oxilion.nl');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Wachtwoord resetten?');
                $this->email->message($emailText);
                $this->email->send();
            }
            elseif (is_numeric($id) && strlen($key) >= 10) {
                $this->user->init($id);
                if ($this->user->getResetkey() == $key) {
                    // Goed. E-mail versturen.
                    $this->user->init($id);
                    $newpass = $this->user->newPassword();
                    $data = array('email' => $this->user->email, 'password' => $newpass);
                    $emailText = $this->load->view('email_newpass', $data, true);
                    #mail($this->user->email, 'Nieuw wachtwoord', $emailText, 'FROM: snack@oxilion.nl');

                    $this->email->from('h.deelstra@oxilion.nl');
                    $this->email->to($this->user->email);
                    $this->email->subject('Nieuw wachtwoord');
                    $this->email->message($emailText);
                    $this->email->send();


                    // Output naar scherm
                    $this->load->view('lostpass_sent', array('email' => $this->user->email));
                }
                else {
                    // Niet goed.
                    $this->load->view('lostpass_fout');
                }

            }
            elseif (!$this->user->emailExists($this->input->post('email'))) {
                $this->load->view('lostpass', array('melding' => '<div class="infoMsg rounded">Sorry, je e-mailadres wordt niet herkend!</div>'));
            }
            else {
                $this->load->view('lostpass');
            }

            $this->load->view('footer');
    }

    function definitief() {
        if ($this->user->isIngelogd()) {
            $this->load->model('Order_model', 'order');
            $this->order->init();
            $this->user->init();
            $this->order->plaats();

            $this->load->view('header');
            $this->load->view('done');
            $this->load->view('footer');
        }
        else {
            Header("Location: /");
        }
    }

    function historie() {
        if ($this->user->isIngelogd()) {
            $this->user->init();
            $data['orders'] = $this->user->getOrderHistorie();
            $data['betalingen'] = $this->user->getBetalingen();
            $this->load->view('header');
            $this->load->view('historie', $data);
            $this->load->view('footer');
        }
        else {
            Header("Location: /");
        }
    }

    function reorder($datum = NULL) {
        if ($this->user->isIngelogd()) {
            $this->user->init();
            if ($datum != NULL) {
                $tmp = $this->user->getOrderByDate($datum);
            }
            else {
                $tmp = $this->user->getLastOrder();
            }

            foreach ($tmp As $key => $item) {
                foreach ($item As $nr => $inhoud) {
                    if (isset($inhoud['aantal'])) {
                        $a = array();
                       // print_r($inhoud);
                        $a['snack_id'] = $inhoud['snack_id'];
                        $a['aantal'] = (int)$inhoud['aantal'];
                        $a['bij_id'] = $inhoud['bij_id'];
                        $snacks[] = $a;
                    }
                }
            }

            if(!empty($snacks)) {
                    $this->session->set_userdata('snack', $snacks);
                    $this->load->model('Order_model', 'order');
                    $this->order->init();

                    $data['user'] = $this->user;
                    $data['order'] = $this->order;

                    $this->load->view('header');
                    $this->load->view('overzicht', $data);
                    $this->load->view('footer');
            } else {
                    $this->load->view('header');
                    $this->load->view('geenhistorie');
                    $this->load->view('footer');
            }

        }
        else {
            Header("Location: /");
        }
    }

    function reorderLast() {
        if ($this->user->isIngelogd()) {
            $this->user->init();
            $data['orders'] = $this->user->getOrderHistorie();
        }
        else {
            Header("Location: /");
        }
    }

    function login() {
        if ($this->user->login($this->input->post('email'), $this->input->post('pass'))) {
            Header("Location: /");
        }
        else {
            $this->session->set_flashdata('info', 'Inloggegevens onjuist.');
            Header("Location: /");
        }
    }
    function bestellingen() {
        if (strlen($this->input->post('vanaf')) > 1) {
            $vanaf = $this->input->post('vanaf');
        }
        else {
            $vanaf = date('Y-m-d', strtotime('Monday this week -1 day'));
        }
        $data['bestellingen'] = $this->snack->getBestellingenVanaf($vanaf);
        $data['vanaf'] = $vanaf;

        $this->load->view('header');
        $this->load->view('bestellingen', $data);
        $this->load->view('footer');
    }

    function loguit() {
        $this->user->loguit();
        $this->index();
    }
}
?>

