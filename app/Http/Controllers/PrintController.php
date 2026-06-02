<?php

namespace App\Http\Controllers;

use Imagick;
use App\Services\PDF;
use App\Models\Bill;
use App\Models\Leaf;
use App\Models\Meet;
use App\Models\Dotation;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Applicant;
use App\Models\Appointment;
use App\Models\Affectation;
use App\Models\Suspension;
use App\Models\Licenciement;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    private static function textCell($value, int $max = 40): string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return '-';
        }

        if (mb_strlen($text) > $max) {
            $text = mb_substr($text, 0, $max - 3) . '...';
        }

        return utf8_decode($text);
    }

    private static function renderTableSection(string $title, array $headers, array $widths, array $rows, string $emptyMessage = 'Aucune donnee'): void
    {
        if (self::$obj->GetY() > 240) {
            self::$obj->AddPage();
        }

        self::$obj->Ln(2);
        self::$obj->SetFont('Arial', 'B', 11);
        self::$obj->SetFillColor(40, 40, 40);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, self::textCell($title, 120), 1, 1, 'L', true);

        self::$obj->SetFont('Arial', 'B', 9);
        self::$obj->SetFillColor(224, 215, 215);
        self::$obj->SetTextColor(0, 0, 0);

        foreach ($headers as $index => $header) {
            self::$obj->Cell($widths[$index], 7, self::textCell($header, 32), 1, 0, 'C', true);
        }
        self::$obj->Ln();

        if (empty($rows)) {
            self::$obj->SetFont('Arial', 'I', 9);
            self::$obj->Cell(190, 7, self::textCell($emptyMessage, 120), 1, 1, 'L');
            return;
        }

        self::$obj->SetFont('Arial', 'I', 8);
        foreach ($rows as $row) {
            if (self::$obj->GetY() > 265) {
                self::$obj->AddPage();
                self::$obj->SetFont('Arial', 'B', 9);
                self::$obj->SetFillColor(224, 215, 215);
                foreach ($headers as $index => $header) {
                    self::$obj->Cell($widths[$index], 7, self::textCell($header, 32), 1, 0, 'C', true);
                }
                self::$obj->Ln();
                self::$obj->SetFont('Arial', 'I', 8);
            }

            foreach ($row as $index => $item) {
                self::$obj->Cell($widths[$index], 6, self::textCell($item, 36), 1, 0, 'L');
            }
            self::$obj->Ln();
        }
    }

    private static function initSimpleReport(string $title): void
    {
        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode($title));
        self::$obj->AddPage();
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->Cell(190, 8, utf8_decode(strtoupper($title)), 0, 1, 'C');
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(190, 6, utf8_decode('Date: ' . date('d/m/Y H:i')), 0, 1, 'C');
        self::$obj->Ln(2);
    }

    private static function normalizeContractType(?string $contract): string
    {
        $value = strtoupper(trim((string) $contract));

        if ($value === 'CDD') {
            return 'CDD';
        }

        if ($value === 'CDI') {
            return 'CDI';
        }

        if (stripos((string) $contract, 'stag') !== false) {
            return 'Stage';
        }

        return 'Autres';
    }

    public static function getCategoriesReport()
    {
        $categories = Category::orderBy('name')->get();

        self::initSimpleReport('Rapport Categories');
        self::renderTableSection(
            'Categories',
            ['#', 'Nom', 'Date creation'],
            [20, 110, 60],
            $categories->map(fn ($item) => [
                $item->id,
                $item->name,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune categorie disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getDotationsReport()
    {
        $dotations = Dotation::with('employee', 'equipment')->orderByDesc('id')->get();

        self::initSimpleReport('Rapport Dotations');
        self::renderTableSection(
            'Dotations',
            ['#', 'Employe', 'Equipement', 'Quantite', 'Date'],
            [10, 68, 50, 22, 40],
            $dotations->map(fn ($item) => [
                $item->id,
                trim((optional($item->employee)->firstname ?? '') . ' ' . (optional($item->employee)->name ?? '')),
                optional($item->equipment)->name,
                $item->qty,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune dotation disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getEquipmentsReport()
    {
        $equipments = Equipment::with('category', 'dotations')->orderBy('name')->get();

        self::initSimpleReport('Rapport Equipements');
        self::renderTableSection(
            'Equipements',
            ['#', 'Nom', 'Categorie', 'Qte totale', 'Qte dispo'],
            [10, 64, 52, 32, 32],
            $equipments->map(fn ($item) => [
                $item->id,
                $item->name,
                optional($item->category)->name,
                (int) $item->qty,
                (int) ($item->qty - $item->dotations->sum('qty')),
            ])->toArray(),
            'Aucun equipement disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getLeavesReport()
    {
        $leaves = Leaf::with('employee')->orderByDesc('id')->get();

        self::initSimpleReport('Rapport Conges');
        self::renderTableSection(
            'Conges',
            ['#', 'Employe', 'Debut', 'Fin', 'Motif'],
            [10, 64, 28, 28, 60],
            $leaves->map(fn ($item) => [
                $item->id,
                trim((optional($item->employee)->firstname ?? '') . ' ' . (optional($item->employee)->name ?? '')),
                !empty($item->begin) ? Carbon::parse($item->begin)->format('d/m/Y') : '-',
                !empty($item->end) ? Carbon::parse($item->end)->format('d/m/Y') : '-',
                $item->reason,
            ])->toArray(),
            'Aucun conge disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getSuspensionsReport()
    {
        $suspensions = Suspension::with('employee')->orderByDesc('id')->get();

        self::initSimpleReport('Rapport Suspensions');
        self::renderTableSection(
            'Suspensions',
            ['#', 'Employe', 'Duree', 'Unite', 'Motif'],
            [10, 64, 24, 22, 70],
            $suspensions->map(fn ($item) => [
                $item->id,
                trim((optional($item->employee)->firstname ?? '') . ' ' . (optional($item->employee)->name ?? '')),
                $item->duration,
                $item->unit,
                $item->reason,
            ])->toArray(),
            'Aucune suspension disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getLicenciementsReport()
    {
        $licenciements = Licenciement::with('employee')->orderByDesc('id')->get();

        self::initSimpleReport('Rapport Licenciements');
        self::renderTableSection(
            'Licenciements',
            ['#', 'Employe', 'Motif', 'Date'],
            [10, 70, 75, 35],
            $licenciements->map(fn ($item) => [
                $item->id,
                trim((optional($item->employee)->firstname ?? '') . ' ' . (optional($item->employee)->name ?? '')),
                $item->reason,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucun licenciement disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getMeetsReport()
    {
        $meets = Meet::orderByDesc('id')->get();

        self::initSimpleReport('Rapport Reunions');
        self::renderTableSection(
            'Reunions',
            ['#', 'Objet', 'Points', 'Date'],
            [10, 70, 75, 35],
            $meets->map(fn ($item) => [
                $item->id,
                $item->objet ?? $item->object,
                $item->points,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune reunion disponible.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getOperationsReport(Request $request)
    {
        $contractType = strtoupper((string) $request->query('contract_type', 'ALL'));
        if (!in_array($contractType, ['ALL', 'CDD', 'CDI', 'STAGIAIRE'], true)) {
            $contractType = 'ALL';
        }

        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode('Rapport RH et Logistique'));
        self::$obj->AddPage();
        self::$obj->SetFont('Arial', 'B', 12);
        self::$obj->Cell(190, 8, utf8_decode('RAPPORT RH ET LOGISTIQUE'), 0, 1, 'C');
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(190, 6, utf8_decode('Date: ' . date('d/m/Y H:i')), 0, 1, 'C');
        self::$obj->Cell(190, 6, utf8_decode('Filtre contrat: ' . $contractType), 0, 1, 'C');
        self::$obj->Ln(2);

        $categories = Category::orderBy('name')->get();
        $dotations = Dotation::with('employee', 'equipment')->orderByDesc('id')->get();
        $equipments = Equipment::with('category')->orderBy('name')->get();
        $leaves = Leaf::with('employee')->orderByDesc('id')->get();
        $suspensions = Suspension::with('employee')->orderByDesc('id')->get();
        $licenciements = Licenciement::with('employee')->orderByDesc('id')->get();
        $meets = Meet::orderByDesc('id')->get();

        $contracts = Employee::where('deleted', 0)->orderBy('name')->get();
        $selectedContracts = match ($contractType) {
            'CDD' => $contracts->where('contract', 'CDD'),
            'CDI' => $contracts->where('contract', 'CDI'),
            'STAGIAIRE' => $contracts->filter(fn ($employee) => stripos((string) $employee->contract, 'stagiaire') !== false),
            default => $contracts,
        };

        $expiredContracts = $contracts->filter(function ($employee) {
            if (empty($employee->contract_end_at)) {
                return false;
            }

            return Carbon::parse($employee->contract_end_at)->lt(Carbon::today());
        });

        $cddContracts = $contracts->where('contract', 'CDD');
        $cdiContracts = $contracts->where('contract', 'CDI');

        self::renderTableSection(
            'Categories',
            ['#', 'Nom', 'Date creation'],
            [20, 110, 60],
            $categories->map(fn ($item) => [
                $item->id,
                $item->name,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune categorie disponible.'
        );

        self::renderTableSection(
            'Dotations',
            ['#', 'Employe', 'Equipement', 'Quantite', 'Date'],
            [10, 68, 50, 22, 40],
            $dotations->map(fn ($item) => [
                $item->id,
                optional($item->employee)->firstname . ' ' . optional($item->employee)->name,
                optional($item->equipment)->name,
                $item->qty,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune dotation disponible.'
        );

        self::renderTableSection(
            'Equipements',
            ['#', 'Nom', 'Categorie', 'Quantite', 'Prix'],
            [10, 70, 48, 22, 40],
            $equipments->map(fn ($item) => [
                $item->id,
                $item->name,
                optional($item->category)->name,
                (int) $item->qty,
                moneyFormat((float) $item->price),
            ])->toArray(),
            'Aucun equipement disponible.'
        );

        self::renderTableSection(
            'Conges',
            ['#', 'Employe', 'Debut', 'Fin', 'Motif'],
            [10, 64, 28, 28, 60],
            $leaves->map(fn ($item) => [
                $item->id,
                optional($item->employee)->firstname . ' ' . optional($item->employee)->name,
                !empty($item->begin) ? Carbon::parse($item->begin)->format('d/m/Y') : '-',
                !empty($item->end) ? Carbon::parse($item->end)->format('d/m/Y') : '-',
                $item->reason,
            ])->toArray(),
            'Aucun conge disponible.'
        );

        self::renderTableSection(
            'Suspensions',
            ['#', 'Employe', 'Duree', 'Unite', 'Motif'],
            [10, 64, 24, 22, 70],
            $suspensions->map(fn ($item) => [
                $item->id,
                optional($item->employee)->firstname . ' ' . optional($item->employee)->name,
                $item->duration,
                $item->unit,
                $item->reason,
            ])->toArray(),
            'Aucune suspension disponible.'
        );

        self::renderTableSection(
            'Licenciements',
            ['#', 'Employe', 'Motif', 'Date'],
            [10, 70, 75, 35],
            $licenciements->map(fn ($item) => [
                $item->id,
                optional($item->employee)->firstname . ' ' . optional($item->employee)->name,
                $item->reason,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucun licenciement disponible.'
        );

        self::renderTableSection(
            'Reunions',
            ['#', 'Objet', 'Points', 'Date'],
            [10, 70, 75, 35],
            $meets->map(fn ($item) => [
                $item->id,
                $item->objet,
                $item->points,
                optional($item->created_at)->format('d/m/Y'),
            ])->toArray(),
            'Aucune reunion disponible.'
        );

        self::renderTableSection(
            'Contrats employes - Filtre ' . $contractType,
            ['#', 'Employe', 'Type', 'Debut', 'Fin', 'Statut'],
            [10, 56, 36, 28, 28, 32],
            $selectedContracts->values()->map(function ($item, $index) {
                $isExpired = !empty($item->contract_end_at) && Carbon::parse($item->contract_end_at)->lt(Carbon::today());

                return [
                    $index + 1,
                    $item->firstname . ' ' . $item->name,
                    $item->contract,
                    !empty($item->contract_start_at) ? Carbon::parse($item->contract_start_at)->format('d/m/Y') : '-',
                    !empty($item->contract_end_at) ? Carbon::parse($item->contract_end_at)->format('d/m/Y') : '-',
                    $isExpired ? 'Expire' : 'En cours',
                ];
            })->toArray(),
            'Aucun contrat pour ce filtre.'
        );

        self::renderTableSection(
            'Contrats expires',
            ['#', 'Employe', 'Type', 'Date expiration'],
            [10, 90, 40, 50],
            $expiredContracts->values()->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item->firstname . ' ' . $item->name,
                    $item->contract,
                    Carbon::parse($item->contract_end_at)->format('d/m/Y'),
                ];
            })->toArray(),
            'Aucun contrat expire.'
        );

        self::renderTableSection(
            'Repartition CDD',
            ['#', 'Employe', 'Debut', 'Fin'],
            [10, 90, 45, 45],
            $cddContracts->values()->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item->firstname . ' ' . $item->name,
                    !empty($item->contract_start_at) ? Carbon::parse($item->contract_start_at)->format('d/m/Y') : '-',
                    !empty($item->contract_end_at) ? Carbon::parse($item->contract_end_at)->format('d/m/Y') : '-',
                ];
            })->toArray(),
            'Aucun contrat CDD.'
        );

        self::renderTableSection(
            'Repartition CDI',
            ['#', 'Employe', 'Debut', 'Fin'],
            [10, 90, 45, 45],
            $cdiContracts->values()->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item->firstname . ' ' . $item->name,
                    !empty($item->contract_start_at) ? Carbon::parse($item->contract_start_at)->format('d/m/Y') : '-',
                    !empty($item->contract_end_at) ? Carbon::parse($item->contract_end_at)->format('d/m/Y') : '-',
                ];
            })->toArray(),
            'Aucun contrat CDI.'
        );

        self::$obj->Output();
        exit;
    }

    public static function getEmployeeContract(int $id)
    {
        $employee = Employee::where('deleted', 0)->findOrFail($id);
        $affectation = $employee->currentAffectation();
        $today = Carbon::now();

        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode('Contrat de Travail - ' . $employee->matricule));
        self::$obj->AddPage();

        self::$obj->SetFont('Arial', 'B', 14);
        self::$obj->Cell(190, 9, utf8_decode('CONTRAT DE TRAVAIL'), 0, 1, 'C');
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(190, 6, utf8_decode('Date d\'edition: ' . $today->format('d/m/Y H:i')), 0, 1, 'C');
        self::$obj->Ln(2);
        $contractTypeLabel = self::normalizeContractType($employee->contract);
        $contractTypeRaw = trim((string) ($employee->contract ?? ''));
        $contractType = $contractTypeRaw !== '' ? $contractTypeLabel . ' (' . $contractTypeRaw . ')' : $contractTypeLabel;
        $startDate = !empty($employee->contract_start_at) ? Carbon::parse($employee->contract_start_at)->format('d/m/Y') : '-';
        $endDate = !empty($employee->contract_end_at) ? Carbon::parse($employee->contract_end_at)->format('d/m/Y') : '-';
        $isExpired = !empty($employee->contract_end_at) && Carbon::parse($employee->contract_end_at)->lt(Carbon::today());
        $status = $isExpired ? 'Expire' : 'En cours';

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Type de contrat: ' . $contractTypeLabel), 0, 1, 'L');
        self::$obj->Cell(190, 7, utf8_decode('Date de debut: ' . $startDate), 0, 1, 'L');
        self::$obj->Cell(190, 7, utf8_decode('Date de fin: ' . $endDate), 0, 1, 'L');
        self::$obj->Ln(1);

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Entre les soussignes :'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Jaguar Security Services SARL, representee par Monsieur TOURE Moussa, agissant en qualite de Gerant, ci-apres denommee l\'Employeur.'));
        self::$obj->Ln(1);
        self::$obj->MultiCell(190, 6, utf8_decode('Et M./Mme ' . $employee->firstname . ' ' . $employee->name . ', matricule ' . ($employee->matricule ?? '-') . ', telephone ' . ($employee->phone ?? '-') . ', domicilie(e) a ' . ($employee->address ?? '-') . ', ci-apres denomme(e) l\'Employe(e).'));
        self::$obj->Ln(1);
        self::$obj->MultiCell(190, 6, utf8_decode('A ete etabli le present contrat regi par le Code du travail de la Republique de Guinee (L/2014/072/CNT du 10 janvier 2014) et les textes d\'application.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 1 : Nature et duree du contrat'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Le present contrat est de type ' . $contractType . ', a compter du ' . $startDate . '. Date de fin : ' . $endDate . '. Statut actuel : ' . $status . '. Lieu de recrutement : Conakry. Lieu de travail : ' . ($affectation->location ?? 'Jaguar Security Services') . '.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 2 : Fonction'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('L\'employe(e) est engage(e) pour assumer la fonction : ' . ($employee->position ?? '-') . '. L\'employeur se reserve le droit, pour necessite de service, de confier d\'autres taches conformement a la loi.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 3 : Obligation de l\'employe'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Durant la validite du present contrat, l\'employe(e) s\'engage a ne pas exercer une autre activite professionnelle susceptible de nuire a la bonne marche de l\'entreprise.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 4 : Remuneration'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Pour une duree de 40 heures par semaine, la remuneration mensuelle comprend : Salaire de base : ' . moneyFormat((float) $employee->salary) . ' ; Prime : ' . moneyFormat((float) ($employee->prime ?? 0)) . '. Les autres indemnites applicables sont fixees selon les regles internes en vigueur.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 5 : Conges'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('L\'employe(e) beneficie annuellement d\'un conge dont la duree est determinee conformement a la legislation en vigueur, avec maintien des droits prevus par la loi.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 6 : Avantages sociaux'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('L\'employe(e) beneficie du regime des assurances sociales guineennes (frais medicaux, accident de travail, maladie professionnelle, prestations familiales, retraite), conformement a la legislation en vigueur.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 7 : Periode d\'essai - Preavis'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Pendant la periode d\'essai, le contrat peut etre resilie par l\'une des parties. Apres engagement definitif, un preavis de 30 jours est requis en cas de rupture, sous reserve des dispositions legales applicables.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Article 8 : Differends'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('L\'Inspection du Travail du lieu de travail est competente pour examiner tout differend lie a l\'execution du present contrat. En cas de non-conciliation, le Tribunal du Travail de Conakry est competent.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 7, utf8_decode('Reference RH'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 6, utf8_decode('Contact urgence : ' . ($employee->emergency_name ?? '-') . ' | Telephone : ' . ($employee->emergency_phone ?? '-')));

        self::$obj->Ln(8);
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->Cell(95, 6, utf8_decode('Fait a Conakry, le ' . $today->format('d/m/Y')), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode('Pour approbation'), 0, 1, 'R');
        self::$obj->Ln(14);

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(95, 6, utf8_decode('L\'Employeur'), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode('L\'Employe(e)'), 0, 1, 'R');

        // Signature de l'employeur
        self::$obj->Image('images/signature_pdg.png', 14, self::$obj->GetY() + 1, 45, 0);

        self::$obj->Ln(16);
        self::$obj->SetFont('Arial', '', 9);
        self::$obj->Cell(95, 6, utf8_decode('Nom & Signature'), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode('Nom & Signature'), 0, 1, 'R');

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
