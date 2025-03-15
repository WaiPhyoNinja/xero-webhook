<?php

namespace App\Http\Controllers;

use Webfox\Xero\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;

class XeroWebhookController extends Controller
{
    public function __invoke(Request $request, Webhook $webhook)
    {
        Log::info("webhook create");
        if (!$webhook->validate($request->header('x-xero-signature'))) {
            return response('', Response::HTTP_UNAUTHORIZED);
        }

        foreach ($webhook->getEvents() as $event) {
            if ($event->getEventType() === 'CREATE' && $event->getEventCategory() === 'INVOICE') {
                $this->invoiceCreated($request, $event->getResource());
            } elseif ($event->getEventType() === 'CREATE' && $event->getEventCategory() === 'CONTACT') {
                $this->contactCreated($request, $event->getResource());
            } elseif ($event->getEventType() === 'UPDATE' && $event->getEventCategory() === 'INVOICE') {
                $this->invoiceUpdated($request, $event->getResource());
            } elseif ($event->getEventType() === 'UPDATE' && $event->getEventCategory() === 'CONTACT') {
                $this->contactUpdated($request, $event->getResource());
            }
        }

        return response('200', Response::HTTP_OK);
    }

    protected function invoiceCreated(Request $request, Invoice $invoice)
    {
        // Handle invoice creation logic here
    }

    protected function contactCreated(Request $request, Contact $contact)
    {
        // Handle contact creation logic here
    }

    protected function invoiceUpdated(Request $request, Invoice $invoice)
    {
        // Handle invoice update logic here
    }

    protected function contactUpdated(Request $request, Contact $contact)
    {
        // Handle contact update logic here
    }
}
