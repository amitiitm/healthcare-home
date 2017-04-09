
<tr>
    <td width="30%">Payment Type</td>
    <td><?php  echo ((isset($lead->paymentType) && isset($lead->paymentType->id)&& isset($lead->paymentType->label))?$lead->paymentType->label:'NA');  ?></td>
</tr>
<tr>
    <td width="30%">Payment Period</td>
    <td><?php  echo ((isset($lead->paymentPeriod) && isset($lead->paymentPeriod->id)&& isset($lead->paymentPeriod->label))?$lead->paymentPeriod->label:'NA');  ?></td>
</tr>
<tr>
    <td width="30%">Payment Mode</td>
    <td><?php  echo ((isset($lead->paymentMode) && isset($lead->paymentMode->id)&& isset($lead->paymentMode->label))?$lead->paymentMode->label:'NA');  ?></td>
</tr>
<tr>
    <td width="30%">Price</td>
    <td><?php  echo (isset($lead->prices) && $lead->prices->count()>0)?($lead->prices->last()->price):'NA';  ?></td>
</tr>
<tr>
    <td width="30%">Price Unit</td>
    <td><?php  echo (isset($lead->prices) && $lead->prices->count()>0 && $lead->prices->last()->priceUnit)?($lead->prices->last()->priceUnit->label):'NA';  ?></td>
</tr>