<?php

namespace App\Controller\Admin;
use App\Entity\Charge;
use App\Entity\Client;
use App\Entity\Expense;
use App\Entity\Paiment;
use App\Entity\Procedure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BillingController extends AbstractController
{
    private  $months = [1=>"Jan", 2=>"Feb", 3=>"Mar", 4=>"Apr", 5=>"May", 6=>"Jun", 7=>"Jul", 8=>"Aug", 9=>"Sep", 10=>"Oct", 11=>"Nov", 12=>"Dec", 13=>'Year'];
    /**
     * @Route("/adm/report-general", name="admin_report_general")
     */
    public function index()
    {
        $paimentRepository = $this->getDoctrine()->getRepository(Paiment::class);
        $generalReportData = $paimentRepository->getGeneralReport($this->getUser());
        $reportData = [];
        $total = 0;
        foreach ($generalReportData as $oneRow){
            if(!isset($reportData[$oneRow["pid"]])) {
                $reportData[$oneRow["pid"]]=[];
            }
            if(!isset($reportData[$oneRow["pid"]][$oneRow["m"]])) {
                $reportData[$oneRow["pid"]][$oneRow["m"]]=[];
            }
            $reportData[$oneRow["pid"]][$oneRow["m"]][]=$oneRow;
            if (!isset($reportData[$oneRow["pid"]][13])) {
               $reportData[$oneRow["pid"]][13] = [$oneRow];
            } else {
                $reportData[$oneRow["pid"]][13][0]["amnt"] += $oneRow["amnt"];
            }
            $total += $oneRow["amnt"];
        }

        $generalReportDataTotals = $paimentRepository->getGeneralReportTotals($this->getUser());
        $generalReportDataTotalsFormatted = [];
        foreach ($generalReportDataTotals as $td) {
            $generalReportDataTotalsFormatted[$td["m"]]=$td;
        }
        $generalReportDataTotalsFormatted[13] = ['amnt' => $total];

        $procedureRepository = $this->getDoctrine()->getRepository(Procedure::class);
        $procedures = $procedureRepository->findBy(["user"=>$this->getUser()]);

        $proceduresData=[];
        foreach ($procedures as $oneProcedure){
            $proceduresData[$oneProcedure->getId()] = $oneProcedure;
        }

        $expenseRepository = $this->getDoctrine()->getRepository(Expense::class);
        $expenses = $expenseRepository->getExpensesReport($this->getUser());

        $chargesRrepository = $this->getDoctrine()->getRepository(Charge::class);
        $charges = $chargesRrepository->findBy(["user"=>$this->getUser()]);
        $chargesData=[];
        foreach ($charges as $charge) {
            $chargesData[$charge->getId()] = $charge;
        }

        $expensesFormatted = [];
        $expensesTotal = 0;
        $expensesTotalMonth = [];
        foreach ($expenses as $item) {
            if (!isset($expensesFormatted[$item['ch']])) {
                $expensesFormatted[$item['ch']] = [];
            }
            if (!isset($expensesFormatted[$item['ch']][$item['m']])) {
                $expensesFormatted[$item['ch']][$item['m']] = [];
            }
            $expensesFormatted[$item['ch']][$item['m']][]=$item;

            if (!isset($expensesFormatted[$item["ch"]][13])) {
                $expensesFormatted[$item["ch"]][13] = [$item];
            } else {
                $expensesFormatted[$item["ch"]][13][0]["amnt"] += $item["amnt"];
            }
            if(!isset($expensesTotalMonth[$item["m"]])) {
                $expensesTotalMonth[$item["m"]] = ["amnt"=>0];
            }
            $expensesTotalMonth[$item["m"]]["amnt"] += $item['amnt'];
            $expensesTotal += $item["amnt"];
        }
        $expensesTotalMonth[13]["amnt"]=$expensesTotal;

        $data = ['test'=>'test',
                 'd'=>$reportData,
                 'months' => $this->months,
                 'procedures' => $proceduresData,
                 'totals' => $generalReportDataTotalsFormatted,
                 'expenses' => $expensesFormatted,
                 'expensesMonth' => $expensesTotalMonth,
                 'expenesesTotal' => $expensesTotal,
                 'charges' =>$chargesData
        ];
        return $this->render('report/general.html.twig', $data);
    }

    /**
     * @Route ("/adm/report-by-proc/{id}", name="admin_report_by_procedure")
     */
    public function byProcedure(Procedure $procedure){

        $paymentRepository = $this->getDoctrine()->getRepository(Paiment::class);
        $payments = $paymentRepository->getReportByProcedure($this->getUser(), $procedure);

        $paymentsData = [];
        foreach ($payments as $onePayment) {
            if(!isset($paymentsData[$onePayment['cid']])){
                $paymentsData[$onePayment['cid']]=[];
            }
            if(!isset($paymentsData[$onePayment['cid']][$onePayment["m"]])){
                $paymentsData[$onePayment['cid']][$onePayment["m"]]=[];
            }
            $paymentsData[$onePayment['cid']][$onePayment["m"]]=$onePayment;
        }
        $clientRepository = $this->getDoctrine()->getRepository(Client::class);
        $clients = $clientRepository->findBy(["user"=>$this->getUser()]);
        $clientsData = [];
        foreach ($clients as $client) {
            $clientsData[$client->getId()] = $client;
        }
        $data = [
            'payments'=>$paymentsData,
            'months' => $this->months,
            'procedure' => $procedure,
            'clients' => $clientsData
        ];

        return $this->render('report/by-procedure.html.twig', $data);

    }
}