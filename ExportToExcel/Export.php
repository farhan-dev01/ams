<?php

require('PHPExcel.php');

//$host="localhost";
//$user="bytecrea_zoom";
//$pass="Zoomdb123;";
//$db="bytecrea_zoomdb";
//$conn1=new mysqli($host,$user,$pass,$db) or die("Unable to connect");
$conn1=new mysqli("localhost","root","","zoom") or die("Unable to connect");
// Load an existing spreadsheet
try {
    $phpExcel1 = PHPExcel_IOFactory::load('January.xlsx');
    $Ecol1 =
        ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z'
            , 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ',
            'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA',"BB",'BC','BD','BE'];

    $short1 = ['present' => 'P', 'absent' => 'A', 'workingOnSite' => 'WS', 'unPaidLeave' => 'UL', 'weeklyOff' => 'WO',
        'maternityLeave' => 'ML', 'annualLeave' => 'AL', 'compassionate' => 'CO', 'sickLeave' => 'SL',
        'paternityLeave' => 'PL', 'businessTrip' => 'BT','publicHoliday'=>'PH'];

    $phpExcel1->setActiveSheetIndex(0);

    $querytable1 = mysqli_query($conn1, "select * from holidays");
    $Holidays = " , ";
    $holidayStart ="";
    $holidayend ="";

    while ($r = mysqli_fetch_array($querytable1)){
        $holidayStart = date_create($r[2]);
        $holidayend = date_create($r[3]);

            do{
                $Holidays .=date_format($holidayStart, 'Y-m-d').' , ';
                date_add($holidayStart, date_interval_create_from_date_string('1 days'));
            }while($holidayStart <= $holidayend);
    }
    function genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays)
    {
        $isPrepared=false;
        if(isset($_GET['dname']))
        {
            $dname=($_GET['dname']);
            $querytable1 = mysqli_query($conn1, "SELECT a.*,e.*,d.dname,DAY(a.date) as `day` from attendance as a join employee as e on a.eid = e.eid JOIN department 
as d on e.deptno = d.deptno where d.dname like '%" . $dname . "%' and a.date BETWEEN '".date_format($MstDate1,"Y-m-d")."' and '".date_format($MendDate1,"Y-m-")
                ."25' order by a.eid,a.DATE");
        }
        else {
            $querytable1 = mysqli_query($conn1, "SELECT a.*,e.*,d.dname,DAY(a.date) as `day` from attendance as a join employee as e on a.eid = e.eid JOIN department 
as d on e.deptno = d.deptno where a.date BETWEEN '" . date_format($MstDate1, "Y-m-d") . "' and '" . date_format($MendDate1, "Y-m-")
                . "25' order by a.eid,a.DATE");
        }
        $Erow1 = 4;
        $i1 = 0;
        $cid1 = 0;
        $sheet1 = $phpExcel1->getActiveSheet();

        $sheet1->getCell('B1')->setValue($year1 . ' Employee Attendance');
        $sheet1->getCell('B2')->setValue(date_format($MendDate1, 'F'));
        $sheet1->getCell('C2')->setValue(date_format($MstDate1, 'M-Y'));
        $sheet1->getCell('I2')->setValue(date_format($MendDate1, 'M-Y'));
        $sheet1->getCell($ycol1)->setValue($year1 . '');

        $dateHold = date_format($MstDate1, 'Y-m-d');
        $isNext = false;
        $newRow=7;
        while (true) {
            $newRow++;
            if(!$isNext){
                if($result1 = mysqli_fetch_array($querytable1)){

                }else{break;}
            }
            if (is_null($result1)){
                //echo '<h1>extra line</h1>';
                break;
            }

            $MstDate1 = date_create($dateHold);
            $datediff1 = date_diff($MendDate1, $MstDate1)->days;
            $count1 = ['present' => 0, 'absent' => 0, 'workingOnSite' => 0, 'unPaidLeave' => 0, 'weeklyOff' => 0,
                'maternityLeave' => 0, 'annualLeave' => 0, 'compassionate' => 0, 'sickLeave' => 0,
                'paternityLeave' => 0, 'businessTrip' => 0,'publicHoliday'=>0];
            $i1 = 0;
            $Erow1 += 1;
            if ($Erow1 > 27) {
                $sheet1->insertNewRowBefore($Erow1 + 1, 1);
            }
            $cid1 = $result1['eid'];
            $day1 = date_format($MstDate1, 'd');
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($result1['eid']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($result1['ename']);
            $i1++;
            //$d= $MstDate1['date'];
            $isNext = false;
            do {
                $s = "";
                if ($day1 == $result1["day"]) {
                    if ($result1['eid'] == $cid1) {
                        $s1 = $short1[$result1['status']];
                        $count1[$result1['status']]++;
                        $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($s1);
                        $result1 = mysqli_fetch_array($querytable1);
                        if ($result1['eid'] != $cid1)
                        {
                            $isNext = true;
                        }
                    }
                    else
                    {
                        $isNext = true;
                    }
                }


                if(strpos($Holidays,date_format($MstDate1, 'Y-m-d'))  && $sheet1->getCell($Ecol1[$i1] . $Erow1)->getValue()==null){

                    $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue("PH");
                    $count1['publicHoliday']++;
                }
                if(date_format($MstDate1,'D') == 'Fri' && $sheet1->getCell($Ecol1[$i1] . $Erow1)->getValue()==null){
                    $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue("WO");
                    $count1['weeklyOff']++;
                }
                if(!$isPrepared){
                    $sheet1->getCell($Ecol1[$i1] . 3)->setValue(date_format($MstDate1,'D'));
                }

                $i1++;
                date_add($MstDate1, date_interval_create_from_date_string('1 days'));
                $day1 = date_format($MstDate1, 'd');
                $newdiiff=date_diff($MendDate1, $MstDate1)->days;
            } while ($MstDate1 <= $MendDate1 );
            next:

            $i1 = $datediff1 +2;

            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($datediff1);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['present']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['absent']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['workingOnSite']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['unPaidLeave']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['weeklyOff']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['maternityLeave']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['annualLeave']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['compassionate']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['sickLeave']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['paternityLeave']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['businessTrip']);
            $i1++;
            $sheet1->getCell($Ecol1[$i1] . $Erow1)->setValue($count1['publicHoliday']);
            $i1++;

            $isPrepared=true;
        }

    }

    //jan
    $ycol1 = 'AH2';
    $year1 = isset($_GET['year']) ? $_GET['year'] :'2019';
    $stdate1 = ($year1 - 1) . '-12-26';
    $date1 = ($year1) . '-1-25';

    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);

    date_add($MendDate1, date_interval_create_from_date_string('1 months'));

    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //feb
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-1-26';
    $date1 = ($year1) . '-2-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(1);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //march
    $ycol1 = 'AE2';

    $stdate1 = ($year1) . '-2-26';
    $date1 = ($year1) . '-3-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(2);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //april
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-3-26';
    $date1 = ($year1) . '-4-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(3);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //may
    $ycol1 = 'AG2';

    $stdate1 = ($year1) . '-4-26';
    $date1 = ($year1) . '-5-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(4);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //june
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-5-26';
    $date1 = ($year1) . '-6-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(5);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //july
    $ycol1 = 'AG2';

    $stdate1 = ($year1) . '-6-26';
    $date1 = ($year1) . '-7-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(6);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //aug
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-7-26';
    $date1 = ($year1) . '-8-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(7);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //sep
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-8-26';
    $date1 = ($year1) . '-9-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(8);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //oct
    $ycol1 = 'AG2';

    $stdate1 = ($year1) . '-9-26';
    $date1 = ($year1) . '-10-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(9);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //nov
    $ycol1 = 'AH2';

    $stdate1 = ($year1) . '-10-26';
    $date1 = ($year1) . '-11-25';
    $MstDate1 = date_create($stdate1);
    $MendDate1 = date_create($stdate1);
    date_add($MendDate1, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(10);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate1,$MendDate1,$Ecol1,$short1,$ycol1,$Holidays);

    //dec

    $ycol1 = 'AG2';
    $stdate1 = ($year1) . '-11-26';
    $date1 = ($year1) . '-12-25';
    $MstDate2 = date_create($stdate1);
    $MendDate2 = date_create($stdate1);
    date_add($MendDate2, date_interval_create_from_date_string('1 months'));
    $phpExcel1->setActiveSheetIndex(11);
    genrate_sheet($conn1,$phpExcel1,$year1,$MstDate2,$MendDate2,$Ecol1,$short1,$ycol1,$Holidays);
    $phpExcel1->setActiveSheetIndex(0);


    $writer1 = PHPExcel_IOFactory::createWriter($phpExcel1, "Excel2007");
// Save the spreadsheet

    header('Content-Type: application/vnd.ms-excel');

    header('Content-Disposition: attachment;filename="file.xlsx"');

    header('Cache-Control: max-age=0');

    $writer1->save('php://output');

}catch(\Exception $ex){ echo $ex;}