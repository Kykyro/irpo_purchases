// ToDo: провести рефакторинг!
$(document).ready(function () {
    function soloSupplier(isThis){
        if(isThis)
        {
            $('#purchases_form_publicationDate').val('');
            $('#purchases_form_deadlineDate').val('');
            $('#purchases_form_dateOfSummingUp').val('');
            $('#purchases_form_PurchaseLink').val('');
            $('#purchases_form_PurchaseNumber').val('');
            $('#purchases_form_postponementDate').val('');
            $('#purchases_form_postonementComment').val('');
        }
        // required
        $('#purchases_form_DateOfConclusion').prop('required',isThis);
        $('#purchases_form_publicationDate').prop('required', !isThis);
        // disabled
        $('#purchases_form_publicationDate').prop('disabled',isThis);
        $('#purchases_form_deadlineDate').prop('disabled',isThis);
        $('#purchases_form_dateOfSummingUp').prop('disabled',isThis);
        $('#purchases_form_PurchaseLink').prop('disabled',isThis);
        $('#purchases_form_PurchaseNumber').prop('disabled',isThis);
        $('#purchases_form_postponementDate').prop('disabled',isThis);
        $('#purchases_form_postonementComment').prop('disabled',isThis);

    }
    function anotherMethodOfDeterminingFunc(isThis)
    {
        if(isThis)
        {
            anotherMethodOfDetermining.show(1000);
        }
        else
        {
            $('#purchases_form_anotherMethodOfDetermining').val('');
            anotherMethodOfDetermining.hide('fast', ()=>{})
        }
    }
    var methodOfDetermining = $('.MethodOfDetermining option[value="Другое"]');
    var methodOfDeterminingInput =  $("#purchases_form_anotherMethodOfDetermining");
    let initialSum = 0;
    let finSum = 0;
    let initialSumFull = false;
    let method = $('#purchases_form_MethodOfDetermining').find(":selected").text();
    var anotherMethodOfDetermining = $('#anotherMethodOfDetermining')
    if(method === 'Другое')
    {
        anotherMethodOfDeterminingFunc(true);
    }
    else
    {
        anotherMethodOfDeterminingFunc(false);
    }
    if(method === 'Единственный поставщик')
    {
        soloSupplier(true);
    }
    else
    {
        soloSupplier(false);
    }

    // начальная сумма
    $(".initial").each(function (i) {
        let value = $(this).val();
        initialSum += Number(value);
    }).promise().done(function () {
        if(initialSum > 0){
            $(".initial").prop('required',false);
        }
        else
        {
            $(".initial").prop('required',true);
        }
        let sum = initialSum.toString();
        sum = sum + "₽";
        $(".sum_initial").html(sum);
        initialSum = 0;
    });

    $(".initial").change(function() {
        $(".initial").each(function (i) {
            let value = $(this).val();
            if(value > 0){
                initialSumFull = true;
            }
            initialSum += Number(value);
        }).promise().done(function () {
            if(initialSum > 0){
                $(".initial").prop('required',false);
            }
            else
            {
                $(".initial").prop('required',true);
            }
            let sum = initialSum.toString();
            sum = sum + "₽";
            $(".sum_initial").html(sum);
            initialSum = 0;
        });
    });
// финальная сумма
    $(".fin").each(function (i) {
        let value = $(this).val();
        finSum += Number(value);
    }).promise().done(function () {

        let sum = finSum.toString();
        sum = sum + "₽";
        $(".sum_fin").html(sum);
        finSum = 0;
    });

    $(".fin").change(function() {
        $(".fin").each(function (i) {
            let value = $(this).val();
            finSum += Number(value);
        }).promise().done(function () {

            let sum = finSum.toString();
            sum = sum + "₽";
            $(".sum_fin").html(sum);
            finSum = 0;
        });
    });

    // изменения при выборе способова закупки
    $('#purchases_form_MethodOfDetermining').change(() => {
        console.log('aaaaa');
        let method = $(this).find(":selected").text();
        if(method === 'Единственный поставщик')
        {
            soloSupplier(true);
        }
        else
        {
            soloSupplier(false);
        }
        // Если выбрано "другое"
        if(method === 'Другое')
        {
            anotherMethodOfDeterminingFunc(true);
        }
        else
        {
            anotherMethodOfDeterminingFunc(false);
        }
    });
    // Проверка дат

    // блокировка финальных цен
    $('#purchases_form_initialFederalFunds').val() == 0 ?
        $('#purchases_form_finFederalFunds').prop('disabled', true) : $('#purchases_form_finFederalFunds').prop('disabled', false);

    $('#purchases_form_initialFederalFunds').change(() => {
        let val = $('#purchases_form_initialFederalFunds').val();

        if(val == 0)
        {
            $('#purchases_form_finFederalFunds').prop('disabled', true).val('');
        }
        else{
            $('#purchases_form_finFederalFunds').prop('disabled', false);
        }
    });
    $('#purchases_form_initialFundsOfSubject').val() == 0 ?
        $('#purchases_form_finFundsOfSubject').prop('disabled', true) :  $('#purchases_form_finFundsOfSubject').prop('disabled', false);
    $('#purchases_form_initialFundsOfSubject').change(() => {
        let val = $('#purchases_form_initialFundsOfSubject').val();

        if(val == 0)
        {
            $('#purchases_form_finFundsOfSubject').prop('disabled', true).val('');
        }
        else{
            $('#purchases_form_finFundsOfSubject').prop('disabled', false);
        }
    });

    $('#purchases_form_initialEmployersFunds').val() == 0 ?
        $('#purchases_form_finEmployersFunds').prop('disabled', true) : $('#form_finEmployersFunds').prop('disabled', false);
    $('#purchases_form_initialEmployersFunds').change(() => {
        let val = $('#form_initialEmployersFunds').val();

        if(val == 0)
        {
            $('#form_finEmployersFunds').prop('disabled', true).val();
        }
        else{
            $('#form_finEmployersFunds').prop('disabled', false);
        }
    });
    $('#purchases_form_initialEducationalOrgFunds').val() == 0 ?
        $('#purchases_form_finFundsOfEducationalOrg').prop('disabled', true) : $('#form_finFundsOfEducationalOrg').prop('disabled', false);
    $('#purchases_form_initialEducationalOrgFunds').change(() => {
        let val = $('#form_initialEducationalOrgFunds').val();

        if(val == 0)
        {
            $('#purchases_form_finFundsOfEducationalOrg').prop('disabled', true).val('');
        }
        else{
            $('#purchases_form_finFundsOfEducationalOrg').prop('disabled', false);
        }
    });
    // Проверка корректности дат
    function checkDate(date1, date2){
        if(date1.val() > date2.val() && date2.val() !== ""){
            date2.addClass( "red-bg" ).focus();
            return false;
        }
        else {
            date2.removeClass( "red-bg" );
            return true;
        }
    }
    $('form[name="purchases_form"]').submit((event) => {
        let publicationDate = $('#purchases_form_publicationDate');
        let deadlineDate = $('#purchases_form_deadlineDate');
        let dateOfSummingUp = $('#purchases_form_dateOfSummingUp');
        let postponementDate = $('#purchases_form_postponementDate');
        let DateOfConclusion = $('#purchases_form_DateOfConclusion');
        let DeliveryTime = $('#purchases_form_DeliveryTime');
        let isValid = true;
        let validArr = [];

        validArr.push(checkDate(publicationDate, deadlineDate));
        validArr.push(checkDate(deadlineDate, dateOfSummingUp));
        validArr.push(checkDate(dateOfSummingUp, postponementDate));
        validArr.push(checkDate(dateOfSummingUp, DateOfConclusion));
        validArr.push(checkDate(DateOfConclusion, DeliveryTime));

        for (let i = 0; i < validArr.length; i++) {
            if(validArr[i] === false){
                isValid = false;
                break;
            }
            else {
                isValid = true;
            }
        }

        if(isValid)
            return

        event.preventDefault();
    })

});