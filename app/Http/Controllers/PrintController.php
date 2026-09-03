<?php

namespace App\Http\Controllers;

use Imagick;
use App\Services\PDF;
use App\Models\Bill;
use App\Models\Leaf;
use App\Models\Mail;
use App\Models\Meet;
use App\Models\Dotation;
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

        // ✅ Exonération : gérée par affectation comme ORASPC/TVA, on calcule ici
        // le taux effectif (pondéré par la TVA de chaque affectation) pour la
        // mention d'en-tête.
        $totalTva = $affectations->sum(fn ($a) => ($a->tva ?? 0) * 0.01 * $a->price);
        $totalExoneration = $affectations->sum(fn ($a) => ($a->tva ?? 0) * 0.01 * $a->price * (($a->exoneration ?? 0) * 0.01));
        $exonerationPct = $totalTva > 0 ? ($totalExoneration / $totalTva * 100) : 0;

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

        if ($exonerationPct > 0) {
            $pctLabel = (floor($exonerationPct) == $exonerationPct)
                ? number_format($exonerationPct, 0)
                : number_format($exonerationPct, 2);
            self::$obj->SetTextColor(0, 128, 0);
            self::$obj->SetFont('Arial', 'B', 9);
            self::$obj->setX(10);
            self::$obj->Cell(190, 5, utf8_decode('EXONERATION DE ' . $pctLabel . '% DE LA TVA'), 0, 1, 'C');
            self::$obj->SetTextColor(0, 0, 0);
            self::$obj->Ln(1);
        }

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

    public static function billAnnual(int $id, int $year)
    {
        $customer = Customer::find($id);

        self::$obj->SetTitle(utf8_decode('Facture Annuelle - ' . $customer->name . ' - ' . $year));
        self::$obj->SetFont('Arial', 'IB', 8);
        self::$obj->AddPage();

        // Company statutory info
        self::$obj->setXY(10, self::$obj->getY() - 3);
        self::$obj->SetFont('Arial', 'I', 8);
        self::$obj->Cell(100, 3, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('N°ENTREPRISE/RCCM/GN.TCC.2020.B.07295'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('NIF:655987501; CLE TVA:9K | BP: 2024 Conakry'), 0, 1);
        self::$obj->Cell(100, 3, utf8_decode('TELEPHONE: +224 625 12 32 32 | EMAIL: jaguar28jss@gmail.com'), 0, 1);

        self::$obj->setXY(100, self::$obj->getY() - 12);
        self::$obj->SetFont('Arial', 'IB', 8);
        self::$obj->Cell(100, 4, utf8_decode($customer->name), 0, 1);
        self::$obj->SetFont('Arial', 'I', 8);
        self::$obj->setX(100);
        self::$obj->MultiCell(100, 4, utf8_decode($customer->address ?? ''), 0, 1);
        self::$obj->setX(100);
        self::$obj->Cell(100, 4, utf8_decode('Tel: ' . $customer->phone), 0, 1);
        self::$obj->setX(100);
        self::$obj->Cell(100, 4, utf8_decode('Responsable: ' . $customer->responsible), 0, 1);

        self::$obj->Ln(3);
        self::$obj->setX(10);
        self::$obj->SetFont('Arial', 'IB', 8);
        self::$obj->Cell(50, 4, utf8_decode('N° FACTURE:'), 0);
        self::$obj->setX(35);
        self::$obj->Cell(80, 4, utf8_decode('JSS' . $customer->id . $year . 'ANN'), 0, 0);
        self::$obj->setX(130);
        self::$obj->Cell(30, 4, utf8_decode('ANNEE:'), 0);
        self::$obj->Cell(40, 4, utf8_decode($year), 0, 1);
        self::$obj->Ln(2);

        // Monthly summary table
        $headers = ['MOIS', 'NB AGENTS', 'MONTANT HT', 'TVA', 'ORASPC', 'TOTAL TTC'];
        $widths  = [28, 24, 36, 30, 24, 48];

        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 8);
        foreach ($headers as $i => $h) {
            self::$obj->Cell($widths[$i], 7, utf8_decode($h), 1, 0, 'C', true);
        }
        self::$obj->Ln();
        self::$obj->SetFillColor(224, 215, 215);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 8);

        $grandHT = 0; $grandTVA = 0; $grandOraspc = 0; $fill = false;
        $allAffectations = collect();

        for ($m = 1; $m <= 12; $m++) {
            $firstDay = Carbon::createFromDate($year, $m, 1)->startOfMonth()->format('Y-m-d');
            $lastDay  = Carbon::createFromDate($year, $m, 1)->endOfMonth()->format('Y-m-d');

            $affectations = Affectation::with(['employee' => fn($q) => $q->where('deleted', 0)])
                ->whereDate('begin', '<=', $lastDay)
                ->whereDate('end', '>=', $firstDay)
                ->where('customer_id', $id)
                ->orderBy('location')
                ->get();

            if ($affectations->isEmpty()) continue;

            $ht     = $affectations->sum('price');
            $tva    = $affectations->sum(fn($a) => $a->tva * 0.01 * $a->price);
            $oraspc = $affectations->sum('oraspc');
            $ttc    = $ht + $tva + $oraspc;

            self::$obj->Cell($widths[0], 6, utf8_decode(strtoupper(__('lang.' . getMonthName($m)))), 'LR', 0, 'C', $fill);
            self::$obj->Cell($widths[1], 6, $affectations->count(), 'LR', 0, 'C', $fill);
            self::$obj->Cell($widths[2], 6, moneyFormat($ht), 'LR', 0, 'C', $fill);
            self::$obj->Cell($widths[3], 6, moneyFormat($tva), 'LR', 0, 'C', $fill);
            self::$obj->Cell($widths[4], 6, moneyFormat($oraspc), 'LR', 0, 'C', $fill);
            self::$obj->Cell($widths[5], 6, moneyFormat($ttc), 'LR', 0, 'C', $fill);
            self::$obj->Ln();
            $fill = !$fill;

            $grandHT += $ht; $grandTVA += $tva; $grandOraspc += $oraspc;
            $allAffectations = $allAffectations->merge($affectations);
        }

        // Totals row
        $grandTTC = $grandHT + $grandTVA + $grandOraspc;
        self::$obj->SetFont('Arial', 'B', 8);
        self::$obj->SetFillColor(200, 200, 200);
        self::$obj->Cell($widths[0] + $widths[1], 7, utf8_decode('TOTAL ANNUEL'), 'LRB', 0, 'C', true);
        self::$obj->Cell($widths[2], 7, moneyFormat($grandHT), 'LRB', 0, 'C', true);
        self::$obj->Cell($widths[3], 7, moneyFormat($grandTVA), 'LRB', 0, 'C', true);
        self::$obj->Cell($widths[4], 7, moneyFormat($grandOraspc), 'LRB', 0, 'C', true);
        self::$obj->Cell($widths[5], 7, moneyFormat($grandTTC), 'LRB', 0, 'C', true);
        self::$obj->Ln(10);

        // Effectif affecté complet
        self::$obj->SetFont('Arial', 'B', 9);
        self::$obj->SetFillColor(50, 50, 50);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(190, 8, utf8_decode('EFFECTIF AFFECTE - ANNEE ' . $year), 1, 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 8);
        foreach (['#', 'MATRICULE', 'NOM & PRENOM', 'SITE', 'TARIF MENSUEL'] as $i => $h) {
            self::$obj->Cell([8, 28, 60, 60, 34][$i], 7, utf8_decode($h), 1, 0, 'C', true);
        }
        self::$obj->Ln();
        self::$obj->SetFillColor(224, 215, 215);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFont('Arial', 'I', 8);

        $unique = $allAffectations->unique(fn($a) => $a->employee_id);
        $fill = false;
        foreach ($unique->values() as $key => $aff) {
            $emp = $aff->employee;
            if (!$emp) continue;
            self::$obj->Cell(8,  6, $key + 1, 'LR', 0, 'C', $fill);
            self::$obj->Cell(28, 6, utf8_decode($emp->matricule ?? ''), 'LR', 0, 'C', $fill);
            self::$obj->Cell(60, 6, utf8_decode(($emp->firstname ?? '') . ' ' . ($emp->name ?? '')), 'LR', 0, 'L', $fill);
            self::$obj->Cell(60, 6, utf8_decode($aff->location ?? ''), 'LR', 0, 'L', $fill);
            self::$obj->Cell(34, 6, moneyFormat($aff->price), 'LR', 0, 'C', $fill);
            self::$obj->Ln();
            $fill = !$fill;
        }
        self::$obj->Cell(190, 0, '', 'T', 1);

        self::$obj->Ln(8);
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->MultiCell(190, 5, utf8_decode(
            'Sauf Erreur ou Omission, le montant total de cette facture annuelle s\'eleve a ' .
            moneyFormat($grandTTC) . ' GNF pour l\'annee ' . $year .
            ' payable en liquidite, par cheque ou virement bancaire a l\'ordre de JAGUAR SECURITY SERVICES SARL.'
        ));

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
    
    public static function salaryReceipt(int $id, int $month = NULL)
    {
        $month = is_null($month) ? (int) date('m') : (int) $month;
        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }
        $employee = Employee::findOrFail($id);

        $baseSalary = (float) $employee->salary;
        $transport = (float) ($employee->transport_indemnity ?? 0);
        $meal = (float) ($employee->meal_allowance ?? 0);
        $housing = (float) ($employee->housing_indemnity ?? 0);
        $punctuality = (float) ($employee->punctuality_allowance ?? 0);
        $responsibility = (float) ($employee->responsibility_allowance ?? 0);
        $gross = $baseSalary + $transport + $meal + $housing + $punctuality + $responsibility;
        $tax = $gross * ((float) ($employee->cnss ?? 0) + (float) ($employee->rts ?? 0)) * 0.01;
        $net = $gross - $tax - ((float) ($employee->acompte ?? 0) + (float) ($employee->sanction ?? 0));

        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode('BULLETIN DE SALAIRE - ' . $employee->matricule . ' - ' . __('lang.' . getMonthName($month))));
        self::$obj->AddPage();

        // Double border frame
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(1.0);
        self::$obj->Rect(5, 19, 200, 254);
        self::$obj->SetDrawColor(50, 50, 50);
        self::$obj->SetLineWidth(0.3);
        self::$obj->Rect(7.5, 21, 195, 250);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);

        // En-tête société (identique à la première capture)
        self::$obj->SetX(10);
        self::$obj->SetFont('Arial', 'BI', 11);
        self::$obj->Cell(150, 5, utf8_decode('JAGUAR SECURITY SERVICES'), 0, 1, 'L');
        self::$obj->SetFont('Arial', 'I', 8);
        self::$obj->SetX(10);
        self::$obj->Cell(150, 4, utf8_decode('CAPITAL SOCIAL: 10.000.000 GNF'), 0, 1, 'L');
        self::$obj->SetX(10);
        self::$obj->Cell(150, 4, utf8_decode('Aéroport International AST, Conakry'), 0, 1, 'L');
        self::$obj->SetX(10);
        self::$obj->Cell(150, 4, utf8_decode('Téléphone: +224 625 12 32 32'), 0, 1, 'L');
        self::$obj->SetX(10);
        self::$obj->Cell(150, 4, utf8_decode('Email: jaguar28jss@gmail.com'), 0, 1, 'L');
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(0.5);
        self::$obj->Line(10, self::$obj->GetY() + 1, 200, self::$obj->GetY() + 1);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);
        self::$obj->Ln(5);

        // Title box
        self::$obj->SetX(25);
        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 14);
        self::$obj->Cell(160, 11, utf8_decode('BULLETIN DE SALAIRE'), 0, 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFillColor(255, 255, 255);
        self::$obj->Ln(4);

        // Employee info row
        self::$obj->SetFont('Arial', 'B', 9);
        self::$obj->SetFillColor(230, 230, 230);
        self::$obj->Cell(95, 6, utf8_decode('Employe : ' . $employee->firstname . ' ' . $employee->name), 1, 0, 'L', true);
        self::$obj->Cell(95, 6, utf8_decode('Matricule : ' . $employee->matricule), 1, 1, 'L', true);
        self::$obj->SetFillColor(240, 240, 240);
        self::$obj->Cell(95, 6, utf8_decode('Poste : ' . $employee->position), 1, 0, 'L', true);
        self::$obj->Cell(95, 6, utf8_decode('Mois : ' . __('lang.' . getMonthName($month))), 1, 1, 'L', true);
        self::$obj->SetFillColor(255, 255, 255);
        self::$obj->Ln(4);

        $items = [
            ['label' => 'Salaire de base', 'amount' => $baseSalary],
            ['label' => 'Indemnité de transport', 'amount' => $transport],
            ['label' => 'Prime de repas', 'amount' => $meal],
            ['label' => 'Indemnité de logement', 'amount' => $housing],
            ['label' => "Prime de ponctualité et d'assiduité", 'amount' => $punctuality],
            ['label' => 'Prime de responsabilité', 'amount' => $responsibility],
        ];

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(110, 7, utf8_decode('Éléments'), 1, 0, 'L');
        self::$obj->Cell(80, 7, utf8_decode('Montant'), 1, 1, 'C');
        self::$obj->SetFont('Arial', '', 10);
        foreach ($items as $item) {
            self::$obj->Cell(110, 7, utf8_decode($item['label']), 1, 0, 'L');
            self::$obj->Cell(80, 7, utf8_decode(moneyFormat($item['amount'])), 1, 1, 'C');
        }
        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(110, 7, utf8_decode('Salaire brut total'), 1, 0, 'L');
        self::$obj->Cell(80, 7, utf8_decode(moneyFormat($gross)), 1, 1, 'C');
        self::$obj->Cell(110, 7, utf8_decode('Retenues (CNSS/RTS)'), 1, 0, 'L');
        self::$obj->Cell(80, 7, utf8_decode(moneyFormat($tax)), 1, 1, 'C');
        self::$obj->Cell(110, 7, utf8_decode('Avances / sanctions'), 1, 0, 'L');
        self::$obj->Cell(80, 7, utf8_decode(moneyFormat((float) ($employee->acompte ?? 0) + (float) ($employee->sanction ?? 0))), 1, 1, 'C');
        self::$obj->Cell(110, 7, utf8_decode('Net à payer'), 1, 0, 'L');
        self::$obj->Cell(80, 7, utf8_decode(moneyFormat($net)), 1, 1, 'C');

        self::$obj->Ln(6);
        self::$obj->MultiCell(190, 6, utf8_decode('Sauf erreur ou omission, le montant de ce bulletin de salaire s\'élève à ' . moneyFormat($net) . ' pour le mois de ' . __('lang.' . getMonthName($month)) . '.'));

        // Signature & cachet de la comptabilité
        self::$obj->Ln(8);
        self::$obj->SetFont('Arial', 'BI', 10);
        self::$obj->SetX(120);
        self::$obj->Cell(80, 5, utf8_decode('La Comptabilité'), 0, 1, 'C');
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->SetX(120);
        self::$obj->Cell(80, 5, utf8_decode('Conakry, le ' . date('d/m/Y')), 0, 1, 'C');
        $signY = self::$obj->GetY() + 2;
        /* if (file_exists('images/signature.png')) {
            self::$obj->Image('images/signature.png', 132, $signY, 30, 0);
        } */
        if (file_exists('images/signature.png')) {
            self::$obj->Image('images/signature.png', 138, $signY + 4, 46, 0);
        }

        self::$obj->Output();
        exit;
    }

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

    /**
     * Export des dotations, filtrable par période
     * (jour / semaine / mois / trimestre / semestre / année), au format PDF ou CSV.
     */
    public static function getDotationsReport(Request $request)
    {
        [$from, $to, $periodLabel] = self::resolvePeriod($request);

        $query = Dotation::with(['employee.affectations.customer', 'equipment'])
            ->orderByDesc('created_at');

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $dotations = $query->get();

        if (strtolower((string) $request->query('format')) === 'csv') {
            return self::streamDotationsCsv($dotations, $periodLabel);
        }

        self::initSimpleReport('Rapport Dotations');
        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(190, 6, self::textCell('Periode : ' . $periodLabel, 120), 0, 1, 'C');
        self::$obj->Cell(190, 6, self::textCell('Total : ' . $dotations->count() . ' dotation(s)', 120), 0, 1, 'C');
        self::$obj->Ln(2);

        self::renderTableSection(
            'Dotations',
            ['#', 'Date', 'Employe', 'Matricule', 'Fonction', 'Equipement', 'Qte'],
            [8, 22, 46, 26, 34, 40, 14],
            $dotations->map(fn ($item) => [
                $item->id,
                optional($item->created_at)->format('d/m/Y'),
                trim((optional($item->employee)->firstname ?? '') . ' ' . (optional($item->employee)->name ?? '')),
                optional($item->employee)->matricule,
                optional($item->employee)->position,
                optional($item->equipment)->name,
                $item->qty,
            ])->toArray(),
            'Aucune dotation sur la periode.'
        );

        self::$obj->Output();
        exit;
    }

    /**
     * Détermine l'intervalle [from, to] et son libellé à partir de ?period et ?date.
     *
     * @return array{0:?\Carbon\Carbon,1:?\Carbon\Carbon,2:string}
     */
    private static function resolvePeriod(Request $request): array
    {
        $period = strtolower((string) $request->query('period', 'all'));
        $anchor = $request->filled('date')
            ? Carbon::parse($request->query('date'))
            : Carbon::now();

        switch ($period) {
            case 'jour':
            case 'day':
                return [$anchor->copy()->startOfDay(), $anchor->copy()->endOfDay(),
                    'Jour du ' . $anchor->format('d/m/Y')];

            case 'semaine':
            case 'week':
                $start = $anchor->copy()->startOfWeek();
                $end = $anchor->copy()->endOfWeek();
                return [$start, $end, 'Semaine du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y')];

            case 'mois':
            case 'month':
                return [$anchor->copy()->startOfMonth(), $anchor->copy()->endOfMonth(),
                    'Mois de ' . $anchor->format('m/Y')];

            case 'trimestre':
            case 'quarter':
                return [$anchor->copy()->startOfQuarter(), $anchor->copy()->endOfQuarter(),
                    'Trimestre T' . $anchor->quarter . ' ' . $anchor->year];

            case 'semestre':
            case 'semester':
                $firstHalf = $anchor->month <= 6;
                $start = $anchor->copy()->month($firstHalf ? 1 : 7)->startOfMonth();
                $end = $anchor->copy()->month($firstHalf ? 6 : 12)->endOfMonth();
                return [$start, $end, 'Semestre S' . ($firstHalf ? 1 : 2) . ' ' . $anchor->year];

            case 'annee':
            case 'year':
                return [$anchor->copy()->startOfYear(), $anchor->copy()->endOfYear(),
                    'Annee ' . $anchor->year];

            default:
                return [null, null, 'Toutes les dotations'];
        }
    }

    /**
     * Export CSV (séparateur « ; », BOM UTF-8 pour Excel) de la collection de dotations.
     */
    private static function streamDotationsCsv($dotations, string $periodLabel)
    {
        $filename = 'dotations_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($dotations, $periodLabel) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['Periode', $periodLabel], ';');
            fputcsv($out, ['Total', $dotations->count() . ' dotation(s)'], ';');
            fputcsv($out, [], ';');
            fputcsv($out, [
                '#', 'Date', 'Heure', 'Prenom', 'Nom', 'Matricule', 'Fonction', 'Telephone',
                'Site d\'affectation', 'Equipement', 'Quantite',
            ], ';');

            foreach ($dotations as $item) {
                $employee = $item->employee;
                $affectation = $employee ? $employee->currentAffectation() : null;
                $site = $affectation
                    ? ($affectation->location ?: (optional($affectation->customer)->name ?? ''))
                    : '';

                fputcsv($out, [
                    $item->id,
                    optional($item->created_at)->format('d/m/Y'),
                    optional($item->created_at)->format('H:i'),
                    optional($employee)->firstname,
                    optional($employee)->name,
                    optional($employee)->matricule,
                    optional($employee)->position,
                    optional($employee)->phone,
                    $site,
                    optional($item->equipment)->name,
                    $item->qty,
                ], ';');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Inventaire détaillé des équipements + valeur totale du stock disponible (PDF).
     */
    public static function getInventoryReport()
    {
        $equipments = Equipment::with('dotations')->orderBy('name')->get();

        self::initSimpleReport('Inventaire detaille');

        $headers = ['#', 'Equipement', 'Prix', 'Qte totale', 'Dotee', 'Deterioree', 'Disponible', 'Valeur (GNF)'];
        $widths  = [8, 48, 22, 22, 18, 20, 20, 32]; // total 190

        if ($equipments->isEmpty()) {
            self::$obj->SetFont('Arial', 'I', 10);
            self::$obj->Cell(190, 8, utf8_decode('Aucun equipement enregistre.'), 1, 1, 'C');
            self::$obj->Output();
            exit;
        }

        $printHeaders = function () use ($headers, $widths) {
            self::$obj->SetFont('Arial', 'B', 8);
            self::$obj->SetFillColor(224, 215, 215);
            self::$obj->SetTextColor(0, 0, 0);
            foreach ($headers as $i => $header) {
                self::$obj->Cell($widths[$i], 6, self::textCell($header, 18), 1, 0, 'C', true);
            }
            self::$obj->Ln();
        };

        $printHeaders();

        $totQty = $totAlloc = $totDeter = $totAvail = $totValue = 0.0;

        self::$obj->SetFont('Arial', '', 8);
        foreach ($equipments as $i => $equipment) {
            if (self::$obj->GetY() > 272) {
                self::$obj->AddPage();
                $printHeaders();
                self::$obj->SetFont('Arial', '', 8);
            }

            $alloc = (float) $equipment->dotations->sum('qty');
            $deter = (float) ($equipment->deteriorated_qty ?? 0);
            $avail = (float) $equipment->available_qty;
            $value = $avail * (float) $equipment->price;

            $totQty += (float) $equipment->qty;
            $totAlloc += $alloc;
            $totDeter += $deter;
            $totAvail += $avail;
            $totValue += $value;

            self::$obj->Cell($widths[0], 6, self::textCell((string) ($i + 1), 6), 1, 0, 'R');
            self::$obj->Cell($widths[1], 6, self::textCell($equipment->name, 30), 1, 0, 'L');
            self::$obj->Cell($widths[2], 6, self::textCell(number_format((float) $equipment->price, 0, ',', ' '), 14), 1, 0, 'R');
            self::$obj->Cell($widths[3], 6, self::textCell(self::numTrim((float) $equipment->qty), 12), 1, 0, 'R');
            self::$obj->Cell($widths[4], 6, self::textCell(self::numTrim($alloc), 10), 1, 0, 'R');
            self::$obj->Cell($widths[5], 6, self::textCell(self::numTrim($deter), 10), 1, 0, 'R');
            self::$obj->Cell($widths[6], 6, self::textCell(self::numTrim($avail), 10), 1, 0, 'R');
            self::$obj->Cell($widths[7], 6, self::textCell(number_format($value, 0, ',', ' '), 18), 1, 1, 'R');
        }

        self::$obj->SetFont('Arial', 'B', 8);
        self::$obj->Cell($widths[0] + $widths[1] + $widths[2], 6, self::textCell('TOTAL', 20), 1, 0, 'R');
        self::$obj->Cell($widths[3], 6, self::textCell(self::numTrim($totQty), 12), 1, 0, 'R');
        self::$obj->Cell($widths[4], 6, self::textCell(self::numTrim($totAlloc), 10), 1, 0, 'R');
        self::$obj->Cell($widths[5], 6, self::textCell(self::numTrim($totDeter), 10), 1, 0, 'R');
        self::$obj->Cell($widths[6], 6, self::textCell(self::numTrim($totAvail), 10), 1, 0, 'R');
        self::$obj->Cell($widths[7], 6, self::textCell(number_format($totValue, 0, ',', ' '), 18), 1, 1, 'R');

        self::$obj->Ln(3);
        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->SetFillColor(40, 40, 40);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->Cell(156, 8, utf8_decode('VALEUR TOTALE DU STOCK DISPONIBLE (GNF)'), 1, 0, 'R', true);
        self::$obj->Cell(34, 8, self::textCell(number_format($totValue, 0, ',', ' '), 20), 1, 1, 'R', true);
        self::$obj->SetTextColor(0, 0, 0);

        self::$obj->Output();
        exit;
    }

    /**
     * Affiche un nombre sans décimales inutiles (2.0 -> "2", 2.5 -> "2,5").
     */
    private static function numTrim(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ',', ' '), '0'), ',');
    }

    public static function getEquipmentsReport()
    {
        $equipments = Equipment::with('dotations')->orderBy('name')->get();

        self::initSimpleReport('Rapport Equipements');
        self::renderTableSection(
            'Equipements',
            ['#', 'Nom', 'Qte totale', 'Qte dispo'],
            [10, 116, 32, 32],
            $equipments->map(fn ($item) => [
                $item->id,
                $item->name,
                self::numTrim((float) $item->qty),
                self::numTrim((float) $item->available_qty),
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

    /**
     * Liste générale des courriers, avec Arrivées et Départs présentés
     * séparément. ?type=arrivee | depart pour n'exporter qu'un seul volet.
     */
    public static function getMailsReport(Request $request)
    {
        $type = strtoupper((string) $request->query('type'));
        $mails = Mail::orderByDesc('mail_datetime')->orderByDesc('id')->get();

        $headers = ['#', 'N. Courrier', 'Date', 'Source', 'Destinataire', 'Objet'];
        $widths  = [10, 34, 26, 38, 38, 44]; // total 190

        $row = fn ($item, $i) => [
            $i,
            $item->mail_id,
            $item->mail_datetime ? Carbon::parse($item->mail_datetime)->format('d/m/Y H:i') : '-',
            $item->srce,
            $item->destinator,
            $item->subject,
        ];

        $sections = [
            'ARRIVEE' => 'Courriers Arrivees',
            'DEPART' => 'Courriers Departs',
        ];
        if (in_array($type, ['ARRIVEE', 'DEPART'], true)) {
            $sections = array_intersect_key($sections, [$type => true]);
        }

        self::initSimpleReport('Rapport Courriers' . ($type ? ' - ' . ucfirst(strtolower($type)) . 's' : ''));

        foreach ($sections as $name => $title) {
            $subset = $mails->where('name', $name)->values();

            self::renderTableSection(
                $title . ' (' . $subset->count() . ')',
                $headers,
                $widths,
                $subset->map(fn ($item) => $row($item, $item->id))->values()->toArray(),
                'Aucun courrier.'
            );
            self::$obj->Ln(3);
        }

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
                strip_tags($item->points),
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

        $dotations = Dotation::with('employee', 'equipment')->orderByDesc('id')->get();
        $equipments = Equipment::orderBy('name')->get();
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
            ['#', 'Nom', 'Quantite', 'Prix'],
            [10, 112, 28, 40],
            $equipments->map(fn ($item) => [
                $item->id,
                $item->name,
                self::numTrim((float) $item->qty),
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
                strip_tags($item->points),
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

    public static function workAttestation(int $id)
    {
        $employee = Employee::where('deleted', 0)->findOrFail($id);
        $today    = Carbon::now();

        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode('Attestation de travail - ' . $employee->matricule));
        self::$obj->AddPage();

        // === DOUBLE BORDER FRAME ===
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(1.2);
        self::$obj->Rect(5, 19, 200, 254);
        self::$obj->SetDrawColor(50, 50, 50);
        self::$obj->SetLineWidth(0.4);
        self::$obj->Rect(7.5, 21, 195, 250);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);

        // === COMPANY STATUTORY INFO ===
        $y = self::$obj->GetY();
        self::$obj->SetXY(10, $y);
        self::$obj->SetFont('Arial', 'I', 7);
        self::$obj->Cell(47, 4, utf8_decode('Capital social: 10.000.000 GNF'), 0, 0, 'L');
        self::$obj->Cell(58, 4, utf8_decode('RCCM: GN.TCC.2020.B.07295'), 0, 0, 'C');
        self::$obj->Cell(45, 4, utf8_decode('NIF: 655987501 | CLE TVA: 9K'), 0, 0, 'C');
        self::$obj->Cell(40, 4, utf8_decode('Tel: +224 625 12 32 32'), 0, 1, 'R');

        // Red separator line
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(0.6);
        $lineY = self::$obj->GetY() + 1;
        self::$obj->Line(10, $lineY, 200, $lineY);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);
        self::$obj->Ln(7);

        // === TITLE BOX ===
        self::$obj->SetX(25);
        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 15);
        self::$obj->Cell(160, 12, utf8_decode('ATTESTATION DE TRAVAIL'), 0, 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFillColor(255, 255, 255);
        self::$obj->Ln(12);

        // === MAIN TEXT ===
        self::$obj->SetFont('Arial', '', 11);
        self::$obj->SetX(15);
        self::$obj->MultiCell(180, 7, utf8_decode(
            'Je soussigne, Monsieur TOURE Moussa, PDG de JAGUAR SECURITY SERVICES SARL, ' .
            'certifie que M./Mme ' . $employee->firstname . ' ' . $employee->name .
            ', matricule ' . $employee->matricule .
            ', a occupe le poste de ' . $employee->position .
            ' au sein de notre entreprise depuis le ' .
            (!empty($employee->contract_start_at)
                ? Carbon::parse($employee->contract_start_at)->format('d/m/Y')
                : 'date non renseignee') . '.'
        ), 0, 'J');

        self::$obj->Ln(8);
        self::$obj->SetX(15);
        self::$obj->MultiCell(180, 7, utf8_decode(
            'En foi de quoi, la presente attestation lui est delivree a sa demande pour servir et valoir ce que de droit.'
        ), 0, 'J');

        self::$obj->Ln(25);

        // === SIGNATURE SECTION ===
        $sigLineY = self::$obj->GetY();
        self::$obj->SetXY(10, $sigLineY);
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->Cell(100, 6, utf8_decode('Fait a Conakry, le ' . $today->format('d/m/Y')), 0, 0, 'L');
        self::$obj->SetX(120);
        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(80, 6, utf8_decode('Le PDG'), 0, 1, 'C');

        self::$obj->SetX(120);
        self::$obj->Cell(80, 6, utf8_decode('Moussa TOURE'), 0, 1, 'C');

        $sigImgY = self::$obj->GetY() + 2;
        self::$obj->Image('images/signature_pdg.png', 130, $sigImgY, 50, 0);
        self::$obj->Ln(22);

        self::$obj->SetX(120);
        self::$obj->SetFont('Arial', '', 9);
        self::$obj->Cell(80, 6, utf8_decode('President Directeur General (PDG)'), 0, 1, 'C');

        self::$obj->Output();
        exit;
    }

    /**
     * Lettre d'acceptation de congé : document formalisé qui accompagne
     * obligatoirement toute demande de congé. Elle précise le nom, le
     * prénom, le matricule de l'employé, le motif du congé et la période
     * accordée. Pour les congés sanitaires ou touristiques, la destination
     * (pays ou ville) y figure également.
     */
    public static function leaveAcceptance(int $id)
    {
        $leaf     = Leaf::with('employee')->findOrFail($id);
        $employee = $leaf->employee;
        $today    = Carbon::now();

        $begin = Carbon::parse($leaf->begin);
        $end   = Carbon::parse($leaf->end);
        $days  = $begin->diffInDays($end) + 1;

        self::$obj = new PDF('P', 'mm', 'A4');
        self::$obj->SetTitle(utf8_decode('Lettre d\'acceptation de conge - ' . ($employee->matricule ?? '')));
        self::$obj->AddPage();

        // === DOUBLE BORDER FRAME ===
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(1.2);
        self::$obj->Rect(5, 19, 200, 254);
        self::$obj->SetDrawColor(50, 50, 50);
        self::$obj->SetLineWidth(0.4);
        self::$obj->Rect(7.5, 21, 195, 250);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);

        // === COMPANY STATUTORY INFO ===
        self::$obj->SetXY(10, self::$obj->GetY());
        self::$obj->SetFont('Arial', 'I', 7);
        self::$obj->Cell(47, 4, utf8_decode('Capital social: 10.000.000 GNF'), 0, 0, 'L');
        self::$obj->Cell(58, 4, utf8_decode('RCCM: GN.TCC.2020.B.07295'), 0, 0, 'C');
        self::$obj->Cell(45, 4, utf8_decode('NIF: 655987501 | CLE TVA: 9K'), 0, 0, 'C');
        self::$obj->Cell(40, 4, utf8_decode('Tel: +224 625 12 32 32'), 0, 1, 'R');

        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(0.6);
        $lineY = self::$obj->GetY() + 1;
        self::$obj->Line(10, $lineY, 200, $lineY);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);
        self::$obj->Ln(7);

        // === TITLE BOX ===
        self::$obj->SetX(20);
        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 14);
        self::$obj->Cell(170, 12, utf8_decode('LETTRE D\'ACCEPTATION DE CONGE'), 0, 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFillColor(255, 255, 255);
        self::$obj->Ln(4);

        self::$obj->SetFont('Arial', 'I', 9);
        self::$obj->Cell(190, 6, utf8_decode('Date d\'edition: ' . $today->format('d/m/Y')), 0, 1, 'C');
        self::$obj->Ln(4);

        // === INTRO ===
        self::$obj->SetFont('Arial', '', 11);
        self::$obj->SetX(15);
        self::$obj->MultiCell(180, 7, utf8_decode(
            'La Direction de JAGUAR SECURITY SERVICES SARL accuse reception de la demande de conge ' .
            'et informe l\'interesse(e) que celle-ci est acceptee dans les conditions ci-apres :'
        ), 0, 'J');
        self::$obj->Ln(4);

        // === DETAILS TABLE ===
        $rows = [
            ['Nom', strtoupper($employee->name ?? '-')],
            ['Prenom', $employee->firstname ?? '-'],
            ['Matricule', $employee->matricule ?? '-'],
            ['Fonction', $employee->position ?? '-'],
            ['Nature du conge', $leaf->type_label],
            ['Motif du conge', $leaf->reason],
            ['Periode accordee', 'Du ' . $begin->format('d/m/Y') . ' au ' . $end->format('d/m/Y') .
                ' (' . $days . ' jour' . ($days > 1 ? 's' : '') . ')'],
        ];
        if ($leaf->requiresDestination() || ! empty($leaf->destination)) {
            $rows[] = ['Destination (pays ou ville)', $leaf->destination ?: '-'];
        }

        foreach ($rows as $row) {
            $yStart = self::$obj->GetY();
            self::$obj->SetXY(15, $yStart);
            self::$obj->SetFont('Arial', 'B', 10);
            self::$obj->SetFillColor(235, 235, 235);
            self::$obj->MultiCell(55, 8, utf8_decode($row[0]), 1, 'L', true);
            $yAfterLabel = self::$obj->GetY();

            self::$obj->SetXY(70, $yStart);
            self::$obj->SetFont('Arial', '', 10);
            self::$obj->MultiCell(125, 8, utf8_decode($row[1]), 1, 'L');
            $yAfterValue = self::$obj->GetY();

            self::$obj->SetY(max($yAfterLabel, $yAfterValue));
        }

        self::$obj->Ln(8);
        self::$obj->SetX(15);
        self::$obj->SetFont('Arial', '', 11);
        self::$obj->MultiCell(180, 7, utf8_decode(
            'A l\'issue de cette periode, l\'interesse(e) est tenu(e) de reprendre effectivement son ' .
            'service. La presente lettre vaut autorisation formelle d\'absence pour la duree indiquee.'
        ), 0, 'J');

        self::$obj->Ln(22);
        $sigY = self::$obj->GetY();
        self::$obj->SetXY(10, $sigY);
        self::$obj->SetFont('Arial', 'I', 10);
        self::$obj->Cell(100, 6, utf8_decode('Fait a Conakry, le ' . $today->format('d/m/Y')), 0, 0, 'L');
        self::$obj->SetX(120);
        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(80, 6, utf8_decode('La Direction des Ressources Humaines'), 0, 1, 'C');
        self::$obj->Ln(18);
        self::$obj->SetX(120);
        self::$obj->SetFont('Arial', '', 9);
        self::$obj->Cell(80, 6, utf8_decode('Signature et cachet'), 0, 1, 'C');

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
        self::$obj->borderOnEachPage = true;
        self::$obj->AddPage();

        // Company statutory info
        self::$obj->SetFont('Arial', 'I', 7);
        self::$obj->Cell(47, 4, utf8_decode('Capital social: 10.000.000 GNF'), 0, 0, 'L');
        self::$obj->Cell(58, 4, utf8_decode('RCCM: GN.TCC.2020.B.07295'), 0, 0, 'C');
        self::$obj->Cell(45, 4, utf8_decode('NIF: 655987501 | CLE TVA: 9K'), 0, 0, 'C');
        self::$obj->Cell(40, 4, utf8_decode('Tel: +224 625 12 32 32'), 0, 1, 'R');
        self::$obj->SetDrawColor(150, 0, 0);
        self::$obj->SetLineWidth(0.5);
        self::$obj->Line(10, self::$obj->GetY() + 1, 200, self::$obj->GetY() + 1);
        self::$obj->SetDrawColor(0, 0, 0);
        self::$obj->SetLineWidth(0.2);
        self::$obj->Ln(7);

        // Title box
        self::$obj->SetX(25);
        self::$obj->SetFillColor(150, 0, 0);
        self::$obj->SetTextColor(255, 255, 255);
        self::$obj->SetFont('Arial', 'B', 14);
        self::$obj->Cell(160, 11, utf8_decode('CONTRAT DE TRAVAIL'), 0, 1, 'C', true);
        self::$obj->SetTextColor(0, 0, 0);
        self::$obj->SetFillColor(255, 255, 255);
        self::$obj->Ln(4);

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
        self::$obj->Cell(190, 6, utf8_decode('Type de contrat: ' . $contractTypeLabel), 0, 1, 'L');
        self::$obj->Cell(190, 6, utf8_decode('Date de debut: ' . $startDate), 0, 1, 'L');
        self::$obj->Cell(190, 6, utf8_decode('Date de fin: ' . $endDate), 0, 1, 'L');
        self::$obj->Ln(1);

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Entre les soussignes :'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Jaguar Security Services SARL, representee par Monsieur TOURE Moussa, agissant en qualite de Gerant, ci-apres denommee l\'Employeur.'));
        self::$obj->Ln(1);
        self::$obj->MultiCell(190, 5, utf8_decode('Et M./Mme ' . $employee->firstname . ' ' . $employee->name . ', matricule ' . ($employee->matricule ?? '-') . ', telephone ' . ($employee->phone ?? '-') . ', domicilie(e) a ' . ($employee->address ?? '-') . ', ci-apres denomme(e) l\'Employe(e).'));
        self::$obj->Ln(1);
        self::$obj->MultiCell(190, 5, utf8_decode('A ete etabli le present contrat regi par le Code du travail de la Republique de Guinee (L/2014/072/CNT du 10 janvier 2014) et les textes d\'application.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 1 : Nature et duree du contrat'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Le present contrat est de type ' . $contractType . ', a compter du ' . $startDate . '. Date de fin : ' . $endDate . '. Statut actuel : ' . $status . '. Lieu de recrutement : Conakry. Lieu de travail : ' . ($affectation->location ?? 'Jaguar Security Services') . '.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 2 : Fonction'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('L\'employe(e) est engage(e) pour assumer la fonction : ' . ($employee->position ?? '-') . '. L\'employeur se reserve le droit, pour necessite de service, de confier d\'autres taches conformement a la loi.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 3 : Obligation de l\'employe'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Durant la validite du present contrat, l\'employe(e) s\'engage a ne pas exercer une autre activite professionnelle susceptible de nuire a la bonne marche de l\'entreprise.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 4 : Remuneration'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Pour une duree de 40 heures par semaine, la remuneration mensuelle comprend : Salaire de base : ' . moneyFormat((float) $employee->salary) . ' ; Prime : ' . moneyFormat((float) ($employee->prime ?? 0)) . '. Les autres indemnites applicables sont fixees selon les regles internes en vigueur.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 5 : Conges'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('L\'employe(e) beneficie annuellement d\'un conge dont la duree est determinee conformement a la legislation en vigueur, avec maintien des droits prevus par la loi.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 6 : Avantages sociaux'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('L\'employe(e) beneficie du regime des assurances sociales guineennes (frais medicaux, accident de travail, maladie professionnelle, prestations familiales, retraite), conformement a la legislation en vigueur.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 7 : Periode d\'essai - Preavis'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Pendant la periode d\'essai, le contrat peut etre resilie par l\'une des parties. Apres engagement definitif, un preavis de 30 jours est requis en cas de rupture, sous reserve des dispositions legales applicables.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Article 8 : Differends'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('L\'Inspection du Travail du lieu de travail est competente pour examiner tout differend lie a l\'execution du present contrat. En cas de non-conciliation, le Tribunal du Travail de Conakry est competent.'));

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(190, 6, utf8_decode('Reference RH'), 0, 1, 'L');
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->MultiCell(190, 5, utf8_decode('Contact urgence : ' . ($employee->emergency_name ?? '-') . ' | Telephone : ' . ($employee->emergency_phone ?? '-')));

        self::$obj->Ln(4);
        self::$obj->SetFont('Arial', '', 10);
        self::$obj->Cell(95, 6, utf8_decode('Fait a Conakry, le ' . $today->format('d/m/Y')), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode('Pour approbation'), 0, 1, 'R');
        self::$obj->Ln(10);

        self::$obj->SetFont('Arial', 'B', 10);
        self::$obj->Cell(95, 6, utf8_decode('Moussa TOURE'), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode("L'Employe(e)"), 0, 1, 'R');

        // Signature de l'employeur
        self::$obj->Image('images/signature_pdg.png', 14, self::$obj->GetY() + 1, 45, 0);

        self::$obj->Ln(12);
        self::$obj->SetFont('Arial', '', 9);
        self::$obj->Cell(95, 6, utf8_decode('Président Directeur Général (PDG)'), 0, 0, 'L');
        self::$obj->Cell(95, 6, utf8_decode($employee->firstname . ' ' . $employee->name), 0, 1, 'R');

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
