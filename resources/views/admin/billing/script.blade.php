<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
})

function showBillingInfo(obj, id) {
    $.post("getBillingInfo", {clientId: id}, function(result){
        if (result){
            if(result.data){
                $('input#id').val(id);
                $('#billing_type').val(result.data.billing_type);
                $('#amount').val(result.data.amount_per_job);
                $('#send_invoice').val(result.data.send_invoice);
                $('#block_on_fail').val(result.data.block_on_fail);

                $('#cardname').val(result.data.card_name);
                $('#cardnumber').val(result.data.card_number);
                $('#expiration_date').val(result.data.expiration_date);
                $('#security_code').val(result.data.security_code);

                $('#bname').val(result.data.billing_name);
                $('#bmail').val(result.data.billing_mail);
                $('#baddress').val(result.data.billing_address);
                $('#bcity').val(result.data.billing_city);
                $('#bstate').val(result.data.billing_state);
                $('#bzip').val(result.data.billing_zip);
                $('#billing_same_chk').val(result.data.billing_same_chk);

                $('#sname').val(result.data.shipping_name);
                $('#smail').val(result.data.shipping_mail);
                $('#saddress').val(result.data.shipping_address);
                $('#scity').val(result.data.shipping_city);
                $('#sstate').val(result.data.shipping_state);
                $('#szip').val(result.data.shipping_zip);
                $('#shipping_same_chk').val(result.data.shipping_same_chk);
            } else {
                $('input#id').val(id);
                $('#billing_type').val('0');
                $('#amount').val('0');
                $('#send_invoice').val('0');
                $('#block_on_fail').val('0');

                $('#cardname').val('');
                $('#cardnumber').val('');
                $('#expiration_date').val('');
                $('#security_code').val('');

                $('#bname').val('');
                $('#bmail').val('');
                $('#baddress').val('');
                $('#bcity').val('');
                $('#bstate').val('');
                $('#bzip').val('');
                $('#billing_same_chk').val('0');

                $('#sname').val('');
                $('#smail').val('');
                $('#saddress').val('');
                $('#scity').val('');
                $('#sstate').val('');
                $('#szip').val('');
                $('#shipping_same_chk').val('0');
            }
        }
    });
}

function updateBilling(){
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    
    var data = {};
    data.clientId = $("#id").val();

    data.billing_name = $("#bname").val();
    data.billing_mail = $("#bmail").val();
    data.billing_address = $("#baddress").val();
    data.billing_city = $("#bcity").val();
    data.billing_state = $("#bstate").val();
    data.billing_zip = $("#bzip").val();
    data.billing_same_info = $("#billing_same_chk").val();

    data.shipping_name = $("#sname").val();
    data.shipping_mail = $("#smail").val();
    data.shipping_address = $("#saddress").val();
    data.shipping_city = $("#scity").val();
    data.shipping_state = $("#sstate").val();
    data.shipping_zip = $("#szip").val();
    data.shipping_same_info = $("#shipping_same_chk").val();

    data.card_name = $("#cardname").val();
    data.card_number = $("#cardnumber").val();
    data.expiration_date = $("#expiration_date").val();
    data.security_code = $("#security_code").val();

    data.billing_type = $('#billing_type').val();
    data.amount_per_job = $('#amount').val();
    data.send_invoice = $('#send_invoice').val();
    data.block_on_fail = $('#block_on_fail').val();

    $.ajax({
        url:"saveBillingInfo",
        type:'post',
        data: data,
        success:function(res){
            swal.close();
            if(res.success == true) {
                swal.fire({ title: "Success", text: "Billing datas are saved successfully.", icon: "success", confirmButtonText: `OK` });
                $('#modal-block-normal').modal('toggle');
                $('#clients').DataTable().ajax.reload();
            } 
            else{
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                $('#modal-block-normal').modal('toggle');
            }
        },
        error: function(xhr, status, error) {
            swal.close();
            res = JSON.parse(xhr.responseText);
            swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            $('#modal-block-normal').modal('toggle');
        }
    });
}

</script>