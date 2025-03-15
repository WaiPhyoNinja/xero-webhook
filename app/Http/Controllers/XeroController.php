<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\LineItem;
use XeroAPI\XeroPHP\ApiException;

class XeroController extends Controller
{
    protected $xero;

    public function __construct(OauthCredentialManager $xero)
    {
        $this->xero = $xero;
    }

    public function createInvoice()
    {
        if (!$this->xero->hasValidToken()) {
            return redirect()->route('xero.auth');
        }

        $accountingApi = new AccountingApi($this->xero->getXeroAPIClient());
        $tenantId = $this->xero->getTenantId();

        $contact = new Contact(['name' => 'John Doe']);
        $lineItem = new LineItem([
            'description' => 'Test Product',
            'quantity' => 1,
            'unit_amount' => 100.00
        ]);

        $invoice = new Invoice([
            'type' => Invoice::TYPE_ACCREC,
            'contact' => $contact,
            'line_items' => [$lineItem],
            'date' => new \DateTime(),
            'due_date' => (new \DateTime())->modify('+7 days'),
            'invoice_number' => 'INV-1001'
        ]);

        try {
            $response = $accountingApi->createInvoices($tenantId, new \XeroAPI\XeroPHP\Models\Accounting\Invoices(['invoices' => [$invoice]]));
            return response()->json($response);
        } catch (ApiException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
