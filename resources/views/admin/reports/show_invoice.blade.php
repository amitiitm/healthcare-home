<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    .invoice-title h2, .invoice-title h3 {
    display: inline-block; 
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
</style>
<?php 
  $order = App\Models\ORM\Order::find($invoice->order_id);                             
  $service = App\Models\ORM\Service::find($order->service_id);
  $locality = App\Models\ORM\Locality::find($invoice->locality_id);
  $service_name = ($service->name == 'Assistive Care') ? 'Nursing Care' : $service->name;
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="container" style="height:100px">
                <a href="http://www.pramaticare.com" class="navbar-brand">
                    <img height="100px" alt="" src="http://crm.pramaticare.com/static/images/invoice_logo.jpg">
                </a>
               
            </div>
            <hr>
            <div class="invoice-title">
                <h3 class="pull-right">Invoice No:&nbsp; {!!$invoice->invoice_number!!}</h3>
            </div>
            
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>{!!ucfirst($invoice->customer_name)!!}</strong><br>
                        {!!$invoice->address!!}<br>
                        {!!$locality->formatted_address!!}<br>
                        {!!$invoice->landmark!!}<br>
                        {!!$invoice->email!!}<br>
                        {!!$invoice->mobile!!}
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <span><strong>Invoice Date: </strong>&nbsp;{!!date_format($invoice->created_at,"d F, Y")!!}</span><br>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Invoice summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Description</strong></td>
                                    <td><strong>Days</strong></td>
                                    <td><strong>Rate</strong></td>
                                    <td class="text-center"><strong>Invoice Amount</strong></td>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td>{!!$service_name!!} service for the duration of 
                                        <br>{!!date_format(new DateTime($invoice->invoice_from_date), "d M, Y")!!} - 
                                        {!!date_format(new DateTime($invoice->invoice_to_date), "d M, Y")!!}
                                    </td>
                                    <td>{!!$invoice->billing_days!!}</td>
                                    <td>{!!$invoice->price_per_day!!}</td>
                                    <td class="text-center">Rs. {!!$invoice->total_amount!!}</td>
                                    
                                </tr>
                                
                                <?php if($invoice->pending_days > 0 || $invoice->outstanding_amount > 0) { ?>
                                <tr>
                                    <td>Previous Outstanding</td>
                                    <td>{!!$invoice->pending_days!!}</td>
                                    <td>{!!$invoice->price_per_day!!}</td>
                                    <td class="text-center">Rs. {!!$invoice->outstanding_amount!!}</td>
                                    
                                </tr>
                                <?php } ?>
                                
                                 <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>Total</td>
                                    <td class="text-center">Rs. {!!($invoice->outstanding_amount + $invoice->total_amount)!!}</td>
                                    
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(empty($invoice->payumoney_url)) { ?>
 
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Account Details</strong></h3>
                    </div>
                    <div class="panel-body">
                        <p><strong>Make all chq payable to "Pramati Healthcare pvt. Ltd."</strong></p>
                        <p>E-7, 2nd Floor, Sector 3, Noida, UP 201301</p>
                        <p>+91 8010667766</p>
                        <p>Bank Name: Yes Bank</p>
                        <p>Acct no: 006183900002531</p>
                        <p>IFSC Code: YESB0000061</p>
                        <p>Pan No. : AAICP1196K</p>
                        <p>CIN :U74999DL215PTC280420</p>
                        <h3 class="panel-title">Account Manager Name : Anup Kumar</h3>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Payu payment Link</strong></h3>
                    </div>
                    <div class="panel-body">
                        <a href="{!! $invoice->payumoney_url!!}" target="_blank">{!! $invoice->payumoney_url!!}</a>
                    </div>
                </div>
            </div>
        <?php } ?>
   
    </div>
</div>