$(document).ready(function(){
    let remarks = $('.remarks');
    let addressRemarks = $('.address-remarks');
    let arr = {};
    let pathname = window.location.pathname;


    addressRemarks.each(function (index) {
        let i = $(this).data('address');
        console.log(i);
        arr[i] = 0;
    });

    remarks.each(function (index) {
        if(parseInt($( this ).data('remarks')) <= 0)
        {
            $( this ).css('display', 'none');
        }
        else
        {
            let address = $( this ).data('address');
            $( this ).html($( this ).data('remarks'));
            arr[address] +=  parseInt($( this ).data('remarks'));
            if(pathname.includes('region/'))
                $(this).closest('tr').css('background-color', '#ffafa3')
        }
    });

    addressRemarks.each(function (index) {
        let i = $(this).data('address');
        if(arr[i] > 0)
        {
            $(this).html(arr[i]);
        }
        else
        {
            $( this ).css('display', 'none');
        }

    });
    console.log(arr);

});