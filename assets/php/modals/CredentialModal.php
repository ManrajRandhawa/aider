<?php

require $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/billplz/vendor/autoload.php";
use Billplz\Client;

class CredentialModal {

    private $billplz;

    function __construct() {
        $this->billplz = Client::make('25fdf06a-4d5a-4c79-a3b1-45ca0bf09f9c', 's-riZ1XA_ey7S4BEolrFzuCw');
    }

    /**
     * @return Client
     */
    public function getBillplz(): Client
    {
        return $this->billplz;
    }

    function getBanks() {
        $bank = $this->billplz->bank();

        $list = $bank->supportedForFpx();
        $bankListArray = $list->toArray();

        $totalNumberOfBanks = count($bankListArray["banks"]);

        $sortedArrayCount = 0;
        $sortedBankListArray = array();

        $optionsList = "";

        for($i = 0; $i < $totalNumberOfBanks; $i++) {
            if($bankListArray["banks"][$i]["active"] === true) {
                $sortedBankListArray[$sortedArrayCount] = $bankListArray["banks"][$i]["name"];

                switch($sortedBankListArray[$sortedArrayCount]) {
                    case "ABMB0212":
                        $optionsList .= "<option value='ABMB0212'>Alliance Bank</option>";
                        break;
                    case "ABB0233":
                        $optionsList .= "<option value='ABB0233'>Affin Bank</option>";
                        break;
                    case "AMBB0209":
                        $optionsList .= "<option value='AMBB0209'>AmBank</option>";
                        break;
                    case "BCBB0235":
                        $optionsList .= "<option value='BCBB0235'>CIMB Clicks</option>";
                        break;
                    case "BIMB0340":
                        $optionsList .= "<option value='BIMB0340'>Bank Islam</option>";
                        break;
                    case "BKRM0602":
                        $optionsList .= "<option value='BKRM0602'>Bank Rakyat</option>";
                        break;
                    case "BMMB0341":
                        $optionsList .= "<option value='BMMB0341'>Bank Muamalat</option>";
                        break;
                    case "BSN0601":
                        $optionsList .= "<option value='BSN0601'>BSN</option>";
                        break;
                    case "CIT0219":
                        $optionsList .= "<option value='CIT0219'>Citibank</option>";
                        break;
                    case "HLB0224":
                        $optionsList .= "<option value='HLB0224'>Hong Leong Bank</option>";
                        break;
                    case "HSBC0223":
                        $optionsList .= "<option value='HSBC0223'>HSBC Bank</option>";
                        break;
                    case "MB2U0227":
                        $optionsList .= "<option value='MB2U0227'>Maybank2u</option>";
                        break;
                    case "PBB0233":
                        $optionsList .= "<option value='PBB0233'>Public Bank</option>";
                        break;
                    case "RHB0218":
                        $optionsList .= "<option value='RHB0218'>RHB Now</option>";
                        break;
                    case "SCB0216":
                        $optionsList .= "<option value='SCB0216'>Standard Chartered</option>";
                        break;
                    default:
                        $optionsList .= "";
                        break;
                }

                $sortedArrayCount++;
            }
        }

        $response['data'] = $optionsList;

        return $response;
    }

    function createBill($userEmail, $userName, $bankCode, $amount) {
        $bill = $this->billplz->bill();

        $responseBill = $bill->create(
            'ty9rl5fy',
            $userEmail,
            null,
            $userName,
            \Duit\MYR::given(doubleval($amount) * 100),
            'https://aider.my/aider/members/webhook.php',
            'Thank you for supporting us!',
            [
                'reference_1_label' => "Bank Code",
                'reference_1' => $bankCode,
                'redirect_url' => 'https://aider.my/aider/members/payment.php'
            ]
        );

        $url = $responseBill->toArray()['url'];
        $response['data'] = $url . "?auto_submit=true";

        return $response;
    }

}