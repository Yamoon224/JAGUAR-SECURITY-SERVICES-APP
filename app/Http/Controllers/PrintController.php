<?php

namespace App\Http\Controllers;

use Imagick;
use App\Services\PDF;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Applicant;
use App\Models\Appointment;
use App\Models\Affectation;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintController extends Controller
{
    private static $obj;

    public function __construct()
    {
        self::$obj = new PDF('P','mm','A4');
    }
    
    private static function getUsdRate()
    {
        try {
            $response = file_get_contents("https://open.er-api.com/v6/latest/GNF");
            $data = json_decode($response, true);
    
            return $data['rates']['USD'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    public static function bill(int $id, int $year, int $month = NULL, $isReceipt = 0)
    {
        $month = is_null($month) ? date('m') : $month;
        $customer = Customer::find($id);
    
        $affectations = Affectation::with(['employee' => fn ($item) => $item->where('deleted', 0)])
            ->whereDate('end', '>=', date($year . '-' . $month . '-d'))
            ->whereDate('begin', '<=', date($year . '-' . $month . '-d'))
            ->where('customer_id', $id)
            ->orderBy('location')
            ->get();
    
        // ✅ Gestion devise
        $isUSD = ($id === 209);
        $rate = $isUSD ? self::getUsdRate() : 1;
        $currency = $isUSD ? 'USD' : 'GNF';
    
        self::$obj->SetTitle(utf8_decode("Facture - " . $customer->name . " - " . __('lang.' . getMonthName($month))));
        self::$obj->SetFont('Arial', 'IB', 8);
        self::$obj->AddPage();
    
        self::$obj->setXY(10, self::$obj->getY() - 3);
        self::$obj->SetFont('Arial', 'I', 8);
        self::$obj->Cell(100, 3, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('N°ENTREPRISE/RCCM/GN.TCC.2020.B.07295'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('NIF:655987501; CLE TVA:9K'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
    
        self::$obj->setXY(100, self::$obj->getY() - 15);
        self::$obj->SetFont('Arial', 'IB', 8);
        self::$obj->Cell(100, 4, utf8_decode($customer->name), 0, 1);
        self::$obj->SetFont('Arial', 'I', 8);
        self::$obj->setX(100);
        self::$obj->MultiCell(100, 4, utf8_decode($customer->address), 0, 1);
        self::$obj->setX(100);
        self::$obj->Cell(100, 4, utf8_decode('Tél: ' . $customer->phone), 0, 1);
        self::$obj->setX(100);
        self::$obj->Cell(100, 4, utf8_decode('Responsable: ' . $customer->responsible), 0, 1);
    
        // ✅ Affichage taux si USD
        if ($isUSD && $rate > 0) {
            self::$obj->Ln(2);
            self::$obj->SetFont('Arial', 'I', 7);
            self::$obj->Cell(100, 4, utf8_decode('Taux du jour: 1 GNF = ' . number_format($rate, 6) . ' USD'), 0, 1);
        }
    
        self::$obj->Ln(3);
        self::$obj->setX(10);
        self::$obj->SetFont('Arial', 'IB', 8);
    
        self::$obj->Cell(40, 4, utf8_decode($isReceipt == 0 ? 'N° FACTURE:' : strtoupper('N° RECU:')), 0);
        self::$obj->setX(30);
        self::$obj->Cell(100, 4, utf8_decode('JSS' . $customer->id . $month . $year), 0, 0);
    
        self::$obj->setX(80);
        self::$obj->Cell(40, 4, utf8_decode("MOIS:"), 0);
        self::$obj->setX(90);
        self::$obj->Cell(100, 4, utf8_decode(strtoupper(__('lang.' . getMonthName($month)))), 0, 0);
    
        self::$obj->setX(140);
        self::$obj->Cell(40, 4, utf8_decode("ANNEE:"), 0);
        self::$obj->setX(153);
        self::$obj->Cell(100, 4, utf8_decode($year), 0, 1);
    
        self::$obj->Ln(1);
    
        $bill = Bill::firstwhere([
            'month_id' => $month,
            'year_id' => $year,
            'customer_id' => $id
        ]);
    
        // ✅ Headers dynamiques avec devise
        $headers = [
            '#',
            'N° Matricule & Site',
            'Montant HT',
            'TVA',
            'ORASPC',
            'TTC'
        ];
    
        self::$obj->FancyTable(
            $headers,
            $affectations,
            $isReceipt,
            $bill ? ['discount' => $bill->discount, 'arrears' => $bill->arrears] : [],
            false,
            $currency
        );
    
        self::$obj->Output();
        exit;
    }

    // public static function bill(int $id, int $year, int $month = NULL, $isReceipt = 0)
    // {
    //     $month = is_null($month) ? date('m') : $month;
    //     $customer = Customer::find($id);
    //     $affectations = Affectation::with(['employee' => fn ($item) => $item->where('deleted', 0)])
    //         ->whereDate('end', '>=', date($year .'-'. $month. '-d'))
    //         ->whereDate('begin', '<=', date($year .'-'. $month. '-d'))
    //         ->where('customer_id', $id)
    //         ->orderBy('location')
    //         ->get();
            
        
    //     self::$obj->SetTitle(utf8_decode("Facture - ".$customer->name." - ".__('lang.'.getMonthName($month))));
    //     self::$obj->SetFont('Arial', 'IB', 8);
    //     self::$obj->AddPage();
    //     self::$obj->setXY(10, self::$obj->getY()-3);
    //     self::$obj->SetFont('Arial', 'I', 8);
    //     self::$obj->Cell(100, 3, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1);
    //     self::$obj->Cell(100, 3, utf8_decode('N°ENTREPRISE/RCCM/GN.TCC.2020.B.07295'), 0, 1);
    //     self::$obj->Cell(100, 3, utf8_decode('NIF:655987501; CLE TVA:9K'), 0, 1);
    //     self::$obj->Cell(100, 3, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
    //     self::$obj->Cell(100, 3, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        
    //     self::$obj->setXY(100, self::$obj->getY()-15);
    //     self::$obj->SetFont('Arial', 'IB', 8);
    //     self::$obj->Cell(100, 4, utf8_decode($customer->name), 0, 1);
    //     self::$obj->SetFont('Arial', 'I', 8);
    //     self::$obj->setX(100);
    //     self::$obj->MultiCell(100, 4, utf8_decode($customer->address), 0, 1);
    //     self::$obj->setX(100);
    //     self::$obj->Cell(100, 4, utf8_decode('Tél: '.$customer->phone), 0, 1);
    //     self::$obj->setX(100);
    //     self::$obj->Cell(100, 4, utf8_decode('Responsable: '.$customer->responsible), 0, 1);
    //     self::$obj->Ln(3);
    //     self::$obj->setX(10);
    //     self::$obj->SetFont('Arial', 'IB', 8);
    //     self::$obj->Cell(40, 4, utf8_decode($isReceipt == 0 ? 'N° FACTURE:' : strtoupper('N° RECU:')), 0);
    //     self::$obj->setX(30);
    //     self::$obj->Cell(100, 4, utf8_decode('JSS'.$customer->id.$month.$year), 0, 0);
    //     self::$obj->setX(80);
    //     self::$obj->Cell(40, 4, utf8_decode("MOIS:"), 0);
    //     self::$obj->setX(90);
    //     self::$obj->Cell(100, 4, utf8_decode(strtoupper(__('lang.'.getMonthName($month)))), 0, 0);
    //     self::$obj->setX(140);
    //     self::$obj->Cell(40, 4, utf8_decode("ANNEE:"), 0);
    //     self::$obj->setX(153);
    //     self::$obj->Cell(100, 4, utf8_decode($year), 0, 1);
    //     self::$obj->Ln(1);
    //     // $bill = $customer->bills->where(['month_id'=>$month])->first();
    //     $bill = Bill::firstwhere(['month_id'=>$month, 'year_id'=>$year, 'customer_id'=>$id]);
        
    //     self::$obj->FancyTable(
    //         ['#', 'N° Matricule & Site', 'Montant HT', 'TVA', 'ORASPC', 'TTC'], 
    //         $affectations, 
    //         $isReceipt, 
    //         $bill ? ['discount'=>$bill->discount, 'arrears'=>$bill->arrears] : [], 
    //         false
    //     ); 
    //     // self::$obj->FancyTable(['#', 'N° Matricule & Site', 'Montant HT', 'TVA', 'TTC'], $affectations, $isReceipt, in_array($id, [1, 121, 122, 123, 146, 148, 147, 152])); 
    //     self::$obj->Output();
    //     exit;
    // }
    
    public static function paymentReceipt(int $id, int $month = NULL)
    {
        $month = is_null($month) ? date('m') : $month;
        $employee = Employee::find($id);
        self::$obj = new PDF('L','mm',array(210, 210));        
        
        self::$obj->SetTitle(utf8_decode("BULLETIN DE SALAIRE - ".$employee->matricule." - ".__('lang.'.getMonthName($month))));
        self::$obj->AddPage();
        self::$obj->SetFont('Arial', 'IB', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->setXY(10, 16);
        self::$obj->Cell(190, 8, utf8_decode(strtoupper('BULLETIN DE SALAIRE')), 'LTRB', 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->setXY(10, 26);
        self::$obj->Cell(100, 5, utf8_decode('JAGUAR SECURITY SERVICES'), 0, 1);
        
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->Cell(100, 5, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Aéroport International AST, Conakry'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Téléphone: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Email: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->Ln(2);
        self::$obj->Cell(190, 0, '', 'LTRB', 1);
        self::$obj->setXY(10, 56);
        self::$obj->SetFont('Arial', 'IB', 10);
        self::$obj->Cell(100, 5, utf8_decode($employee->firstname." ".$employee->name), 0, 1);
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->Cell(100, 5, utf8_decode(!empty($employee->address) ? $employee->address : "Pas d'adresse"), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Téléphone: '.$employee->phone), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Matricule: '.$employee->matricule), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Fonction: '.$employee->position), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('Affectation: '.$employee->currentAffectation()->customer->name), 0, 1);
        // self::$obj->Cell(100, 5, utf8_decode('Nom du Garant: '.$employee->emergency_name), 0, 1);
        // self::$obj->Cell(100, 5, utf8_decode('Téléphone Garant: '.$employee->emergency_phone), 0, 1);
        self::$obj->Ln(2);
        self::$obj->Cell(190, 0, '', 'LTRB', 1);
        self::$obj->Ln(2);
        self::$obj->SetFont('Arial', 'IB', 10);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(30, 8, utf8_decode('MOIS'), 'LTRB', 0, 'C', true);
        self::$obj->setX(40);
        self::$obj->Cell(45, 8, utf8_decode('SALAIRE'), 'LTRB', 0, 'C', true);
        self::$obj->setX(85);
        self::$obj->Cell(35, 8, utf8_decode('PRIMES'), 'LTRB', 0, 'C', true);
        self::$obj->setX(120);
        self::$obj->Cell(35, 8, utf8_decode('TAXES'), 'LTRB', 0, 'C', true);
        self::$obj->setX(155);
        self::$obj->Cell(45, 8, utf8_decode(strtoupper('Net à Payer')), 'LTRB', 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->setX(10);
        self::$obj->Cell(30, 8, utf8_decode(__('lang.'.getMonthName($month))), 'LTRB', 0, 'C');
        self::$obj->setX(40);
        self::$obj->Cell(45, 8, moneyFormat($employee->salary), 'LTRB', 0, 'C');
        self::$obj->setX(85);
        self::$obj->Cell(35, 8, moneyFormat($employee->prime), 'LTRB', 0, 'C');
        self::$obj->setX(120);
        self::$obj->Cell(35, 8, moneyFormat(($employee->salary+$employee->prime)*($employee->cnss+$employee->rts)*0.01), 'LTRB', 0, 'C');
        self::$obj->setX(155);
        $net = ($employee->salary+$employee->prime)-(($employee->salary+$employee->prime)*($employee->cnss+$employee->rts)*0.01)-($employee->acompte + $employee->sanction);
        
        self::$obj->Cell(45, 8, moneyFormat($net), 'LTRB', 1, 'C');
        self::$obj->SetFont('Arial', 'I', 12);
        self::$obj->Ln(1);
        
        self::$obj->Cell(190, 8, utf8_decode("Sauf Erreur ou Omission, le montant de ce reçu de reglement salaire s'élève à ".moneyFormat($net)), '', 1, 'L');
        self::$obj->Ln(10);
        self::$obj->setX(30);
        self::$obj->Cell(80, 0, utf8_decode('Signature Salarié'), '');
        // self::$obj->Image('images/signature_pdg.png', 15, self::$obj->getY()+3, 70, 0);
        self::$obj->setX(160);
        self::$obj->Cell(80, 0, 'Comptable', '');
        self::$obj->Image('images/signature.png', 150, self::$obj->getY()+3, 50, 0);
        self::$obj->Image('images/flag.png', 10, 190, 25);
        self::$obj->Image('images/branding.png', 175, 190, 25);
        self::$obj->Output();
        exit;
    }
    
    public static function payByBankTransfer(int $month = NULL, $bank = '')
    {
        $month = is_null($month) ? date('m') : $month;
        $employees = Employee::where(['hastopay'=>1, 'issuspended'=>0, 'deleted'=>0]);
        
        $employees = ($bank == '*' ? $employees : $employees->where(['bank' => empty($bank) ? NULL : $bank]))->get();
        $suffix = empty($bank) ? 'COMPTABILITE' : strtoupper($bank);
        self::$obj->SetTitle(utf8_decode(( $bank != '*' ? 'REGLEMENT DE SALAIRE A LA '.$suffix : 'ETAT DE SALAIRES' ) .' POUR LE MOIS DE '.strtoupper(__('lang.'.getMonthName($month)))));
        
        self::$obj->AddPage();
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(100, 5, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 45);
        self::$obj->SetFont('Arial', 'B', 11);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode( ( $bank != '*' ? 'LES REGLEMENTS DE SALAIRE A LA '.$suffix.' ' : 'ETAT DE SALAIRES ' ) .'POUR LE MOIS DE '.strtoupper(__('lang.'.getMonthName($month)))), 'LTRB', 1, 'C', true);
        
        self::$obj->setXY(10, 55);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 10);
        if(!empty($bank)) {
            if( $bank != '*' )
                self::$obj->MultiCell(190, 5, utf8_decode('Chers responsables de la '.$suffix.' : Nous vous autorisons de procéder au paiement de salaire par virement bancaire les employés dont les prénoms et noms, matricules, RIB et salaires suivent.'));
        } else {
           self::$obj->MultiCell(190, 5, utf8_decode('Sur Autorisation de la Direction Générale de JAGUAR SECURITY SERVICES SARL, les employés dont les prénoms et noms, matricules et salaires suivent seront payés par la comptabilité.'));
        }
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 9);
        
        if(!empty($bank) && $bank != '*') {
            $bankno = ['DIAMA BANK' => '0010330009', 'BANQUE ISLAMIQUE DE GUINEE' =>'44 104', 'ACCESS BANK GUINEE' =>'036.001.0100002646.43'];
            self::$obj->Ln(5);
            self::$obj->setX(10);
            self::$obj->Cell(60, 5, utf8_decode('INTITULE COMPTE:'), 0);
            self::$obj->setX(45);
            self::$obj->Cell(120, 5, utf8_decode('JAGUAR SECURITY SERVICES SARL'), 0, 1);
            self::$obj->setXY(10, 80);
            self::$obj->Cell(60, 5, utf8_decode('N° COMPTE:'), 0);
            self::$obj->setX(32);
            self::$obj->Cell(120, 5, utf8_decode($bankno[$suffix]), 0, 1);
            self::$obj->setXY(10, 85);
            self::$obj->Cell(60, 5, utf8_decode('BANQUE:'), 0);
            self::$obj->setX(28);
            self::$obj->Cell(120, 5, utf8_decode($suffix), 0, 1);
            self::$obj->setXY(10, 70);
            
            self::$obj->Ln(5);
            self::$obj->Cell(60, 5, utf8_decode('MOIS : '), 0);
            self::$obj->setX(23);
            self::$obj->Cell(120, 5, utf8_decode(strtoupper( __('lang.'.getMonthName($month) ))), 0, 1);
            self::$obj->Ln(10);
        }
        
        
        self::$obj->bankTransfer(['#', 'EMPLOYE', 'N° MATRICULE', 'RIB', 'NET A PAYER'], $employees);
        
        self::$obj->Output();
        exit;
    }
    
    public static function getEmployeesAffected()
    {
        $employees = Employee::with('affectations')
            ->has('affectations')
            ->where('deleted', 0)
            ->get();
        
        self::$obj->SetTitle(utf8_decode('Employés Affectés'));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        self::$obj->Cell(100, 5, mb_convert_encoding('JAGUAR SECURITY SERVICES', 'ISO-8859-1', 'UTF-8'), 0, 1);
        self::$obj->SetFont('Arial', 'I', 11);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 40);
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode('LA LISTE DES EMPLOYES AFFECTES'), 'LTRB', 1, 'C', true);
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 12);
        self::$obj->getEmployeesAffected(array('#', 'N° MATRICULE', 'SALAIRE', 'PARTENAIRE'), $employees);  
        self::$obj->Output();
        exit;
    }
    
    public static function getHiredAffected()
    {
        $employees = Employee::with('affectations')
            ->has('affectations')
            ->where('deleted', 0)
            ->get();
        
        self::$obj->SetTitle(utf8_decode('Employés Affectés'));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        self::$obj->Cell(100, 5, mb_convert_encoding('JAGUAR SECURITY SERVICES', 'ISO-8859-1', 'UTF-8'), 0, 1);
        self::$obj->SetFont('Arial', 'I', 11);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 42);
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode("LA LISTE DES EMPLOYES ET LE SITE D'AFFECTATION"), 'LTRB', 1, 'C', true);
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 12);
        self::$obj->getHiredAffected(['#', 'EMPLOYE', 'SITE', "DATE"], $employees);  
        self::$obj->Output();
        exit;
    }
    
    public static function getApplicantReport()
    {
        $applicants = Applicant::where(['deleted'=>0, 'status'=>'inprogress'])->get();
        
        self::$obj->SetTitle(utf8_decode('Employés Affectés'));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        self::$obj->Cell(100, 5, mb_convert_encoding('JAGUAR SECURITY SERVICES', 'ISO-8859-1', 'UTF-8'), 0, 1);
        self::$obj->SetFont('Arial', 'I', 11);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 42);
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode("LA LISTE DES POSTULANTS"), 'LTRB', 1, 'C', true);
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 12);
        self::$obj->getApplicant(['#', 'POSTULANT', 'TELEPHONE && ADRESSE', "DATE"], $applicants);  
        self::$obj->Output();
        exit;
    }
    
    
    public static function getPartnerReport()
    {
        $partners = Customer::where('deleted', 0)->get();
        
        self::$obj->SetTitle(utf8_decode('LISTE PARTENAIRES'));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        self::$obj->Cell(100, 5, mb_convert_encoding('JAGUAR SECURITY SERVICES', 'ISO-8859-1', 'UTF-8'), 0, 1);
        self::$obj->SetFont('Arial', 'I', 11);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 42);
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode("LA LISTE DES PARTENAIRES"), 'LTRB', 1, 'C', true);
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->getPartners(['#', 'PARTENAIRE', 'TELEPHONE && ADRESSE', "N/A", "MONTANT"], $partners);  
        self::$obj->Output();
        exit;
    }
    
    public static function getAppointmentReport()
    {
        $appointments = Appointment::all();
        
        self::$obj->SetTitle(utf8_decode('LES RENDEZ-VOUS'));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        self::$obj->Cell(100, 5, mb_convert_encoding('JAGUAR SECURITY SERVICES', 'ISO-8859-1', 'UTF-8'), 0, 1);
        self::$obj->SetFont('Arial', 'I', 11);
        self::$obj->Cell(100, 5, utf8_decode('AEROPORT INTERNATIONAL AST, CONAKRY'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('TELEPHONE: +224 625 12 32 32'), 0, 1);
        self::$obj->Cell(100, 5, utf8_decode('EMAIL: jaguar28jss@gmail.com'), 0, 1);
        self::$obj->setXY(10, 42);
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(193, 8, utf8_decode("LES RENDEZ-VOUS"), 'LTRB', 1, 'C', true);
        
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->getAppointments(['#', 'VISITEUR', 'ENTREPRISE', "DATE", "PERIODE", "OBSERVATIONS"], $appointments);  
        self::$obj->Output();
        exit;
    }
    
    
    public static function getQrcodeWifi()
    {
        self::$obj = new Fpdf('P','mm','A4');
        
        self::$obj->SetTitle(utf8_decode("JSS WIFI"));
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->AddPage();
        
        self::$obj->SetFont('Arial', 'B', 20);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 12, utf8_decode('JAGUAR SECURITY SERVICES'), 'LTRB', 1, 'C', true);
        
        // Générer le QR code au format SVG
        // $qrcode = QrCode::wiFi([
        //     'encryption' => 'WPA', // Cryptage "WPA" ou "WEP"
        //     'ssid' => 'Flybox-3F1439', // Nom du réseau WiFi
        //     'password' => '4G7sKUXN', // Clé de sécurité
        //     'hidden' => 'false' // Si le réseau WiFi est masqué "true" ou non "false"
        // ]);
        $qrcode = QrCode::wiFi([
            'encryption' => 'WPA', // Cryptage "WPA" ou "WEP"
            'ssid' => 'JAGUAR SECURITY-MouNa', // Nom du réseau WiFi
            'password' => 'Jaguar28jss@', // Clé de sécurité
            'hidden' => 'false' // Si le réseau WiFi est masqué "true" ou non "false"
        ]);
        
        // Conversing from svg to png format
    	$png = new Imagick();
        $png->readImageBlob($qrcode);
        $png->writeImages('qrcode.png', true);
        $png->resizeImage(190, 190, imagick::FILTER_MITCHELL, 1); 
        
        self::$obj->Image($png->getImageFilename(), 60, 30, 90, 90);
        self::$obj->Ln(105);
        self::$obj->Cell(190, 12, utf8_decode('ACCES WIFI'), 'LTRB', 1, 'C', true);
        
        self::$obj->Output();
        exit;
    }
}
