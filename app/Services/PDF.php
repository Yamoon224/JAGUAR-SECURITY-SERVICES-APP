<?php
namespace App\Services;

use Codedge\Fpdf\Fpdf\Fpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Imagick;

class PDF extends Fpdf
{
    private static ?string $watermarkTilePath = null;

    private function buildWatermarkTile(): ?string
    {
        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        $logoPath = file_exists('images/logo-icon.png')
            ? realpath('images/logo-icon.png')
            : (file_exists('images/logo-icon.jpg') ? realpath('images/logo-icon.jpg') : null);

        if (!$logoPath) {
            return null;
        }

        $tilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'jss_wm_tile_v2.png';

        if (file_exists($tilePath)) {
            return $tilePath;
        }

        try {
            $size = 54;
            $ext  = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $src  = $ext === 'png' ? imagecreatefrompng($logoPath) : imagecreatefromjpeg($logoPath);

            if (!$src) {
                return null;
            }

            $sw = imagesx($src);
            $sh = imagesy($src);

            $resized = imagecreatetruecolor($size, $size);
            imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $size, $size, $sw, $sh);
            imagedestroy($src);

            $tile = imagecreatetruecolor($size, $size);

            for ($px = 0; $px < $size; $px++) {
                for ($py = 0; $py < $size; $py++) {
                    $rgb     = imagecolorat($resized, $px, $py);
                    $r       = ($rgb >> 16) & 0xFF;
                    $g       = ($rgb >> 8) & 0xFF;
                    $b       = $rgb & 0xFF;
                    $gray    = (int)(0.299 * $r + 0.587 * $g + 0.114 * $b);
                    $blended = (int)(255 * 0.88 + $gray * 0.12);
                    imagesetpixel($tile, $px, $py, imagecolorallocate($tile, $blended, $blended, $blended));
                }
            }

            imagedestroy($resized);
            imagepng($tile, $tilePath, 6);
            imagedestroy($tile);

            return file_exists($tilePath) ? $tilePath : null;

        } catch (\Throwable $e) {
            return null;
        }
    }

    private function drawWatermark(): void
    {
        if (self::$watermarkTilePath === null) {
            self::$watermarkTilePath = $this->buildWatermarkTile();
        }

        if (self::$watermarkTilePath === null) {
            return;
        }

        $tileSize = 18;
        for ($y = 0; $y < 297; $y += $tileSize) {
            for ($x = 0; $x < 210; $x += $tileSize) {
                $this->Image(self::$watermarkTilePath, $x, $y, $tileSize, $tileSize);
            }
        }
    }

    // En-tête
    function Header()
    {
        $this->drawWatermark();

        // Logo
        $this->Image('images/logo-icon.jpg', 10, 4, 12);
        
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(180, 6, utf8_decode("JAGUAR SECURITY SERVICES SARL"), 'C', 0, 'C');
        
        $path = 'images/qrcode.svg';
        
        // Générer le QR code au format SVG
        QrCode::margin(4)
            ->backgroundColor(211,211,211, 80)
            ->size(100)
            ->generate(url()->current(), $path);
        // Conversing from svg to png format
    	$png = new Imagick();
        $png->readImageBlob(file_get_contents($path));
        $png->writeImages('qrcode.png', false);
        $png->resizeImage(80, 80, imagick::FILTER_LANCZOS, 1); 
        
        $this->Image($png->getImageFilename(), 186, 3, 14, 0);
        
        // Police Arial gras 15
        $this->SetFont('Arial', 'B', 15);
        $this->Ln(7);
        // Décalage à droite
        $this->Cell(190, 0, '', 1);
        // Titre
        // Saut de ligne
        $this->Ln(4);
    }

    // Pied de page
    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetXY(40, -19.5);
        
        // Police Arial italique 8
        // Numéro de page
        $this->SetFont('Arial', 'IB', 8);
        $this->Cell(130, 2,'Page '.$this->PageNo(), 'LR', 1,'C');
        $this->SetX(40);
        $this->SetFont('Arial', 'I', 6.5);
        $this->Cell(130, 3, utf8_decode("SIS A L'AEROPORT INTERNATIONAL D'AHMED SEKOU TOURE"), 'LR', 1, 'C');
        $this->SetX(40);
        $this->Cell(130, 3, utf8_decode("Immeuble TOURE APPARTEMENT 4A-AUTO-ROUTE FIDEL CASTRO"), 'LR', 1, 'C');
        $this->SetX(40);
        $this->Cell(130, 3, utf8_decode("COMMUNE DE MATOTO - BP: 2024 Conakry - République de Guinée"), 'LR', 1, 'C');
        $this->SetX(40);
        $this->SetFont('Arial', 'IB', 6.5);
        $this->Cell(130, 3, utf8_decode("Tél: 00224 625 12 32 32 - Site Web: www.jss-gn.com"), 'LR', 1, 'C');
        // Flag Guinea
        $this->Image('images/flag.png', 10, 277, 25);
        $this->Image('images/branding.png', 175, 277, 25);
    }

    // Tableau simple
    function BasicTable($header, $data)
    {
        // En-tête
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();
        // Données
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40, 6, $col, 1);
            $this->Ln();
        }
    }

    // Tableau amélioré
    function ImprovedTable($header, $data)
    {
        // Largeurs des colonnes
        $w = array(40, 35, 45, 40);
        // En-tête
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
        $this->Ln();
        // Données
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR');
            $this->Cell($w[1],6,$row[1],'LR');
            $this->Cell($w[2],6,number_format($row[2],0,',',' '),'LR',0,'R');
            $this->Cell($w[3],6,number_format($row[3],0,',',' '),'LR',0,'R');
            $this->Ln();
        }
        // Trait de terminaison
        $this->Cell(array_sum($w),0,'','T');
    }

    // Tableau coloré
    function FancyTable($header, $data, $isReceipt, $others = [], $isDiama = false, $currency = 'GNF')
    {
        // ✅ Normalisation devise
        $currency = strtoupper($currency);
        $isUSD = $currency === 'USD';
    
        // ✅ Taux de conversion (GNF -> USD)
        $rate = 1;
        if ($isUSD) {
            try {
                $response = @file_get_contents("https://open.er-api.com/v6/latest/GNF");
                if ($response !== false) {
                    $json = json_decode($response, true);
                    $rate = $json['rates']['USD'] ?? 1;
                }
            } catch (\Exception $e) {
                $rate = 1;
            }
        }
    
        // Styles
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'IB');
    
        $w = [10, 80, 25, 25, 20, 30];
    
        // Header
        foreach ($header as $i => $col) {
            $this->Cell($w[$i], 7, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln();
    
        // Reset style
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('Arial', 'I', 8);
    
        $fill = false;
        $sum = 0;
        $taxs = 0;
        $oraspc = 0;
    
        foreach ($data as $key => $row) {
    
            // Calculs
            $price = $row->price;
            $tvaAmount = $row->tva * 0.01 * $price;
            $orasp = $row->oraspc;
            $ttc = $price + $tvaAmount;
    
            // Conversion si USD
            if ($isUSD && $rate > 0) {
                $price *= $rate;
                $tvaAmount *= $rate;
                $orasp *= $rate;
                $ttc *= $rate;
            }
    
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
    
            $this->Cell(
                $w[1],
                6,
                utf8_decode(
                    $row->employee->matricule . " - " .
                    ($row->employee->currentAffectation()->location ?? 'Non défini')
                ),
                'LR', 0, 'C', $fill
            );
    
            $this->Cell($w[2], 6, moneyFormat($price, ".", $currency), 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, moneyFormat($tvaAmount, ".", $currency), 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, moneyFormat($orasp, ".", $currency), 'LR', 0, 'C', $fill);
            $this->Cell($w[5], 6, moneyFormat($ttc, ".", $currency), 'LR', 0, 'C', $fill);
    
            $this->Ln();
            $fill = !$fill;
    
            $sum += $price;
            $taxs += $tvaAmount;
            $oraspc += $orasp;
        }
    
        // Ligne de fin
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln();
    
        $this->SetFont('Arial', 'I', 9);
    
        $ttc = $sum + $taxs + $oraspc;
    
        $supp = [
            'TOTAL HT' => moneyFormat($sum, ".", $currency),
            'TOTAL TVA' => moneyFormat($taxs, ".", $currency),
            'TOTAL ORASPC' => moneyFormat($oraspc, ".", $currency),
        ];
    
        if (!empty($others['discount'])) {
            $discount = $others['discount'];
            if ($isUSD) $discount *= $rate;
    
            $ttc -= $discount;
            $supp['TOTAL REMISE / HT'] = moneyFormat($discount, ".", $currency);
        }
    
        if (!empty($others['arrears'])) {
            $arrears = $others['arrears'];
            if ($isUSD) $arrears *= $rate;
    
            $ttc += $arrears;
            $supp['TOTAL ARRIERES'] = moneyFormat($arrears, ".", $currency);
        }
    
        $supp['MONTANT TTC'] = moneyFormat($ttc, ".", $currency);
    
        foreach ($supp as $key => $item) {
            $this->setX(115);
            $this->Cell(40, 9, $key, 'LRT', 0, 'L', $fill);
            $this->Cell(45, 9, utf8_decode($item), 'LRT', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    
        $this->setX(115);
        $this->Cell(85, 0, '', 'T');
    
        // Signature
        $this->SetFont('Arial', 'I', 8);
        $this->setXY(10, $this->getY() - 24);
        $this->Cell(80, 0, 'Conakry le ' . date('d/m/Y'), '');
        $this->Image('images/signature.png', 10, $this->getY() + 2, 30, 0);
    
        $this->Ln(25);
        $this->SetFont('Arial', 'I', 9);
    
        // $txt = "Sauf erreur ou omission, le montant de "
        //     . ($isReceipt ? 'ce reçu' : 'cette facture')
        //     . " s'élève à " . $supp['MONTANT TTC'] . ".";
            
        $txt = "Sauf Erreur ou Omission, le montant de cette facture s'élève à "
            . $supp['MONTANT TTC']
            . " comme TTC Payable en liquidité, par chèque ou virement bancaire à l'ordre de: "
            . "JAGUAR SECURITY SERVICES SARL - RIB: 036.001.0100002646.43 - ACCESS BANK GUINEE.";
    
        $this->MultiCell(190, 5, utf8_decode($txt));
    }
    
    function bankTransfer($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.6);
        $this->SetFont('','B');
        // En-tête
        $w = array(10, 75, 35, 35, 35);
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 9);
        $sum = 0; $tva = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->firstname." ".$row->name), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row->matricule), 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, utf8_decode($row->rib), 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, moneyFormat(($row->salary+$row->prime)-($row->acompte+$row->sanction)-($row->salary+$row->prime)*($row->cnss+$row->rts)*0.01), 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill; 
            $sum += ($row->salary+$row->prime)-($row->acompte+$row->sanction)-($row->salary+$row->prime)*($row->cnss+$row->rts)*0.01; 
            $tva += ($row->salary+$row->prime-$row->acompte-$row->sanction)*($row->cnss+$row->rts)*0.01; 
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(2);
        $this->SetFont('Arial', 'I', 9);
        foreach(array('TOTAL HT'=>moneyFormat($sum+$tva), 'TAXES (CNSS & RTS)'=>moneyFormat($tva), 'MONTANT TTC'=>moneyFormat($sum)) as $key => $item)
        {
            $this->setX(105);
            $this->Cell(50, 9, $key, 'LRT', 0, 'L', $fill);
            $this->Cell(45, 9, utf8_decode($item), 'LRT', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->setX(105);
        $this->Cell(95, 0, '', 'T');
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'Conakry, le '.date('d/m/Y'), '');
        $this->Image('images/signature.png', 70, $this->getY()-30, 30, 0);
        
        $this->setX(30);
        $this->Image('images/cachet.png', 10, $this->getY()-30, 20, 0);
        $this->Image('images/signature_only.png', 35, $this->getY()-15, 50, 0);
        
        $this->setX(100);
        
    }
    
    function getEmployeesAffected($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('','IB');
        // En-tête
        $w = array(10, 35, 40, 105);
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 11);
        $sum = 0; $tva = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->matricule), 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, moneyFormat(($row->salary+$row->prime)-($row->acompte+$row->sanction)-($row->salary+$row->prime)*($row->cnss+$row->rts)*0.01), 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, utf8_decode($row->affectations->first()->customer->name), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; 
            $sum += ($row->salary+$row->prime)-($row->acompte+$row->sanction)-($row->salary+$row->prime)*($row->cnss+$row->rts)*0.01; 
            $tva += ($row->salary+$row->prime)*($row->cnss+$row->rts)*0.01; 
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(2);
        $this->SetFont('Arial', 'I', 10);
        foreach(array('TOTAL HT'=>moneyFormat($sum+$tva), 'TAXES (CNSS & RTS)'=>moneyFormat($tva), 'MONTANT TTC'=>moneyFormat($sum)) as $key => $item)
        {
            $this->setX(115);
            $this->Cell(40, 9, $key, 'LRT', 0, 'L', $fill);
            $this->Cell(45, 9, utf8_decode($item), 'LRT', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->setX(115);
        $this->Cell(85, 0, '', 'T');
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'PDG', '');
        $this->Image('images/signature_pdg.png', 140, $this->getY()+3, 70, 0);
    }
    
    function getHiredAffected($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('','IB');
        // En-tête
        $w = [8, 88, 75, 18];
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 8);
        $sum = 0; $tva = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->firstname." ".$row->name." (".$row->matricule.")"), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row->affectations->first()->customer->name), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, utf8_decode(date('d/m/Y', strtotime($row->affectations->first()->created_at))), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; 
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'PDG', '');
        $this->Image('images/signature_pdg.png', 140, $this->getY()+3, 70, 0);
    }
    
    function getApplicant($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('','IB');
        // En-tête
        $w = [8, 88, 75, 18];
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 8);
        $sum = 0; $tva = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->firstname." ".$row->name." (".$row->applicationid.")"), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row->phone." / ".$row->address), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, utf8_decode(date('d/m/Y', strtotime($row->created_at))), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; 
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'PDG', '');
        $this->Image('images/signature_pdg.png', 140, $this->getY()+3, 70, 0);
    }
    
    function getPartners($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('','IB');
        // En-tête
        $w = [8, 82, 63, 12, 25];
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 8);
        $sum = 0; $affectation = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->name), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode(substr($row->phone." / ".$row->address, 0, 38)), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, utf8_decode($row->affectations->count()), 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, utf8_decode(moneyFormat($row->amount)), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; 
            $sum += $row->amount;
            $affectation += $row->affectations->count();
        }
        $this->Cell(153, 9, utf8_decode("TOTAL"), 'LRT', 0, 'L', $fill);
        $this->Cell(12, 9, utf8_decode($affectation), 'LRT', 0, 'C', $fill);
        $this->Cell(25, 9, utf8_decode(moneyFormat($sum)), 'LRT', 0, 'C', $fill);
        $this->Ln();
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'PDG', '');
        $this->Image('images/signature_pdg.png', 140, $this->getY()+3, 70, 0);
    }
    
    function getAppointments($header, $data)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(150, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('','IB');
        // En-tête
        $w = [8, 56, 45, 18, 31, 35];
        for($i=0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 215, 215);
        $this->SetTextColor(0);
        $this->SetLineWidth(.1);
        $this->SetFont('');
        // Données
        $fill = false;
        $this->SetFont('Arial', 'I', 8);
        $sum = 0; $affectation = 0;
        foreach($data as $key => $row)
        {
            $this->Cell($w[0], 6, ++$key, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row->visitor), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, utf8_decode($row->company), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, utf8_decode(date('d/m/Y', strtotime($row->expected_at))), 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, utf8_decode($row->start_time." A ".$row->end_time), 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, utf8_decode(""), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; 
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T', 1);
        $this->Ln(5);
        $this->setX(160);
        $this->Cell(80, 0, 'PDG', '');
        $this->Image('images/signature_pdg.png', 140, $this->getY()+3, 70, 0);
    }
}