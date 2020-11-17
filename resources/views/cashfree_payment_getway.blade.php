 <form id="redirectForm" method="post" action="https://test.cashfree.com/billpay/checkout/post/submit">
    <input type="text" name="appId" value="21010681dcd7ab378a42b948001012"/><br>
    <input type="text" name="customerEmail" value="Johndoe@test.com"/><br>
    <input type="text" name="customerName" value="John Doe"/><br>
    <input type="text" name="customerPhone" value="9999999999"/><br>
    <input type="text" name="notifyUrl" value="{{url('payment-notify')}}"/><br>
    <input type="text" name="orderAmount" value="100"/><br>
    <input type="text" name="orderCurrency" value="INR"/><br>
    <input type="text" name="orderId" value="order00001"/><br>
    <input type="text" name="orderNote" value="test"/><br>
    <input type="text" name="returnUrl" value="{{url('payment-success')}}"/><br>
    <input type="text" name="signature" value="{{$signature}}"/><br>
    <input type="submit" value="Pay">
</form>