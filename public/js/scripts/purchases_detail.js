// ToDo: провести рефакторинг!
$(document).ready(function () {

    let initialSum = 0;
    let finSum = 0;
    let initialSumFull = false;
    let method = $('#form_MethodOfDetermining').find(":selected").text();
    if(method === 'Единственный поставщик')
    {
        $('#form_DateOfConclusion').prop('required',true);
        $('#form_publicationDate').prop('required',false);
        $('#form_publicationDate').prop('disabled',"disabled");
        $('#form_deadlineDate').prop('disabled',"disabled");
        $('#form_dateOfSummingUp').prop('disabled',"disabled");
        $('#form_PurchaseLink').prop('disabled',"disabled");
        $('#form_PurchaseNumber').prop('disabled',"disabled");
        $('#form_postponementDate').prop('disabled',"disabled");
        $('#form_postonementComment').prop('disabled',"disabled");
    }
    else
    {
        $('#form_DateOfConclusion').prop('required',false);
        $('#form_publicationDate').prop('required',true);
        $('#form_publicationDate').prop('disabled',false);
        $('#form_deadlineDate').prop('disabled',false);
        $('#form_dateOfSummingUp').prop('disabled',false);
        $('#form_PurchaseLink').prop('disabled',false);
        $('#form_PurchaseNumber').prop('disabled',false);
        $('#form_postponementDate').prop('disabled',false);
        $('#form_postonementComment').prop('disabled',false);
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
    $('#form_MethodOfDetermining').change(() => {
        let method = $(this).find(":selected").text();
        if(method === 'Единственный поставщик')
        {
            $('#form_DateOfConclusion').prop('required',true);
            $('#form_publicationDate').prop('required',false);
            $('#form_publicationDate').prop('disabled',"disabled");
            $('#form_deadlineDate').prop('disabled',"disabled");
            $('#form_dateOfSummingUp').prop('disabled',"disabled");
            $('#form_PurchaseLink').prop('disabled',"disabled");
            $('#form_PurchaseNumber').prop('disabled',"disabled");
            $('#form_postponementDate').prop('disabled',"disabled");
            $('#form_postonementComment').prop('disabled',"disabled");
        }
        else
        {
            $('#form_DateOfConclusion').prop('required',false);
            $('#form_publicationDate').prop('required',true);
            $('#form_publicationDate').prop('disabled',false);
            $('#form_deadlineDate').prop('disabled',false);
            $('#form_dateOfSummingUp').prop('disabled',false);
            $('#form_PurchaseLink').prop('disabled',false);
            $('#form_PurchaseNumber').prop('disabled',false);
            $('#form_postponementDate').prop('disabled',false);
            $('#form_postonementComment').prop('disabled',false);
        }

    });
    // Проверка дат

    // блокировка финальных цен
    $('#form_initialFederalFunds').val() == 0 ?
        $('#form_finFederalFunds').prop('disabled', true) : $('#form_finFederalFunds').prop('disabled', false);

    $('#form_initialFederalFunds').change(() => {
        let val = $('#form_initialFederalFunds').val();

        if(val == 0)
        {
            $('#form_finFederalFunds').prop('disabled', true);
        }
        else{
            $('#form_finFederalFunds').prop('disabled', false);
        }
    });
    $('#form_initialFundsOfSubject').val() == 0 ?
        $('#form_finFundsOfSubject').prop('disabled', true) :  $('#form_finFundsOfSubject').prop('disabled', false);
    $('#form_initialFundsOfSubject').change(() => {
        let val = $('#form_initialFundsOfSubject').val();

        if(val == 0)
        {
            $('#form_finFundsOfSubject').prop('disabled', true);
        }
        else{
            $('#form_finFundsOfSubject').prop('disabled', false);
        }
    });

    $('#form_initialEmployersFunds').val() == 0 ?
        $('#form_finEmployersFunds').prop('disabled', true) : $('#form_finEmployersFunds').prop('disabled', false);
    $('#form_initialEmployersFunds').change(() => {
        let val = $('#form_initialEmployersFunds').val();

        if(val == 0)
        {
            $('#form_finEmployersFunds').prop('disabled', true);
        }
        else{
            $('#form_finEmployersFunds').prop('disabled', false);
        }
    });
    $('#form_initialEducationalOrgFunds').val() == 0 ?
        $('#form_finFundsOfEducationalOrg').prop('disabled', true) : $('#form_finFundsOfEducationalOrg').prop('disabled', false);
    $('#form_initialEducationalOrgFunds').change(() => {
        let val = $('#form_initialEducationalOrgFunds').val();

        if(val == 0)
        {
            $('#form_finFundsOfEducationalOrg').prop('disabled', true);
        }
        else{
            $('#form_finFundsOfEducationalOrg').prop('disabled', false);
        }
    });


});