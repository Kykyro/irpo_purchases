$(document).ready(function () {


    $("#wizard").steps();

    $("#form").steps({
        bodyTag: "fieldset",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Финиш",
            next: "Далее",
            previous: "Назад",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex, newIndex)
        {
            // Always allow going backward even if the current step contains invalid fields!
            if (currentIndex > newIndex)
            {
                return true;
            }

            // Forbid suppressing "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age").val()) < 18)
            {
                return false;
            }

            var form = $(this);

            // Clean up if user went backward before
            if (currentIndex < newIndex)
            {
                // To remove error styles
                $(".body:eq(" + newIndex + ") label.error", form).remove();
                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
            }

            // Disable validation on fields that are disabled or hidden.
            form.validate().settings.ignore = ":disabled,:hidden";

            // Start validation; Prevent going forward if false
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex)
        {
            if(formState === "Единственный поставщик"){
                if(!showFieldset.includes(currentIndex)){
                    if(currentIndex > priorIndex)
                        $(this).steps("next");
                    else
                        $(this).steps("previous");
                }
            }

            if(
                formState === "Аукцион в электронной форме" ||
                formState === "Открытый конкурс" ||
                formState === "Запрос котировок" ||
                formState === "Электронный магазин"
            ){
                if(!showFieldset.includes(currentIndex)){
                    if(currentIndex > priorIndex)
                        $(this).steps("next");
                    else
                        $(this).steps("previous");
                }
            }


            $("a[href='#finish']").hide();

        },
        onFinishing: function (event, currentIndex)
        {

            var form = $(this);

            // Disable validation on fields that are disabled.
            // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
            form.validate().settings.ignore = ":disabled";

            // Start validation; Prevent form submission if false
            return form.valid();
        },
    }).validate({

        rules: {

            'purchases_form[PurchaseObject]': "required",
            'purchases_form[anotherMethodOfDetermining]': "required",


        },
        messages: {
            'purchases_form[PurchaseObject]': "Введите предмет закупки",
            'purchases_form[anotherMethodOfDetermining]': "Укажите Способ определения поставщика",
            'purchases_form[file]': "Прикрепите файл договора/проекта договора",
            'purchases_form[initialFederalFunds]': {
                required: "Заполните хотя бы один пункт",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите не более 2-х знаков после запятой"),
            },

            'purchases_form[initialFundsOfSubject]': {
                required: "Заполните хотя бы один пункт",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите не более 2-х знаков после запятой"),
            },
            'purchases_form[initialEmployersFunds]': {
                required: "Заполните хотя бы один пункт",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите не более 2-х знаков после запятой"),
            },
            'purchases_form[initialEducationalOrgFunds]': {
                required: "Заполните хотя бы один пункт",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите не более 2-х знаков после запятой"),
            },

            'purchases_form[publicationDate]': "Это поле обязательно",
            'purchases_form[DateOfConclusion]': "Это поле обязательно",
            'purchases_form[finFederalFunds]' : 'fffffff',
            'purchases_form[prepayment]' : {
                required: "Это поле обязательно",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите число без дробной части"),
            }

        }
    });

    // VARIBLES
    let purchases_object = $("#purchases_form_PurchaseObject");
    let method_of_determining = $("#purchases_form_MethodOfDetermining");
    let another_method_of_determining = $('#purchases_form_anotherMethodOfDetermining');
    let another_method_of_determining_row = $('#anotherMethodOfDetermining');
    let postponement_comment = $('#purchases_form_postonementComment');
    let purchases_number = $('#purchases_form_PurchaseNumber');
    let purchases_link = $('#purchases_form_PurchaseLink');
    let file = $('#purchases_form_file');
    let isPlanned = $('#purchases_form_isPlanned');
    let isPlannedRow = $('#isPlanned');
    let finSumRow = $('#fin-sum-row');
    let hasPrepayment = $('#purchases_form_isHasPrepayment');
    let prepaymentBlock = $('#prepayment-block');
    let contractStatusSelect = $('#purchases_form_conractStatus');
    let comment = $('#purchases_form_Comments');
    let contract_info_block = $('#contact-info-block');

    // Начальная цена контракта
    let initial_federal_funds = $("#purchases_form_initialFederalFunds");
    let initial_funds_of_subject = $("#purchases_form_initialFundsOfSubject");
    let initial_employers_funds = $("#purchases_form_initialEmployersFunds");
    let initial_edication_org_funds = $("#purchases_form_initialEducationalOrgFunds");

    // Итоговая цена контракта
    let fin_federal_funds = $("#purchases_form_finFederalFunds");
    let fin_funds_of_subject = $("#purchases_form_finFundsOfSubject");
    let fin_employers_funds = $("#purchases_form_finEmployersFunds");
    let fin_edication_org_funds = $("#purchases_form_finFundsOfEducationalOrg");

    // Даты
    let publication_date = $("#purchases_form_publicationDate");
    let deadline_date = $("#purchases_form_deadlineDate");
    let summing_up_date = $("#purchases_form_dateOfSummingUp");
    let postponement_date = $("#purchases_form_postponementDate");
    let conclusion_date = $("#purchases_form_DateOfConclusion");
    let delivery_date = $("#purchases_form_DeliveryTime");

    // Суммы
    let initial_sum = $(".sum_initial");
    let fin_sum = $(".sum_fin");

    // Сообщения об ошибке
    let errors_message = $('.error-message');
    let publication_date_error = $("#publication-date-error");
    let deadline_date_error = $("#deadline-date-error");
    let summing_up_date_error = $("#summing-up-date-error");
    let postponement_date_error = $("#postponement-date-error");
    let conclusion_date_error = $("#conclusion-date-error");
    let delivery_date_error = $("#delivery-date-error");

    // ------
    let formState = "Единственный поставщик";
    let showFieldset = [0, 1, 4, 5, 6, 7];


    // function skipStep() {
    //
    // }

    function soloSupplier(isThis){

        if(isThis)
        {
            clearValue(publication_date, deadline_date, summing_up_date,
                postponement_date,  postponement_comment);

            isPlannedRow.hide();
            isPlanned.prop('checked', false);
            isPlannedChange();
            showFieldset = [0, 1, 4, 5, 6, 7];


            $("#form-t-2").hide();
            $("#form-t-3").hide();
            // $("#form-t-4").hide();

            finSumRow.hide();
        }
        else {
            isPlannedRow.show();
            finSumRow.show();
            showFieldset = [0, 1, 2, 3, 4, 5, 6, 7];

        }

        setRequired(!isThis, conclusion_date);
        setRequired(!isThis, publication_date);
        setDisabled(isThis, publication_date, deadline_date, summing_up_date, purchases_link,
            purchases_number, postponement_date, postponement_comment);

    }
    function anotherMethodOfDeterminingFunc(isThis)
    {
        if(isThis)
        {
            another_method_of_determining_row.show();
            setRequired(isThis, another_method_of_determining);
        }
        else
        {
            another_method_of_determining.val('');
            another_method_of_determining_row.hide('fast', ()=>{});
            setRequired(isThis, another_method_of_determining);
        }
    }

    function changeMethod(method, value, func) {
        if(method === value){
            func(true);
        }
        else {
            func(false);
        }
    }
    function clearValue(...args) {
        for (i = 0; i < args.length; i++){
            args[i].val('');
        }
    }
    function setRequired(value, ...args) {
        for (i = 0; i < args.length; i++){
            args[i].prop('required',value);
        }
    }
    function setDisabled(value, ...args) {
        for (i = 0; i < args.length; i++){
            args[i].prop('disabled',value);
            if(value){
                args[i].val("");
            }
        }
    }
    function getSum(...args) {
        let sum = 0;
        for (i = 0; i < args.length; i++){
            sum += +args[i].val();
        }
        return sum;
    }
    function changeInitialFunds(initial, fin) {
        initial.val() === "" ?
            setDisabled(true, fin) : setDisabled(false, fin);
    }
    function updateInitialSum() {
        if(
            initial_federal_funds.val() > 0 ||
            initial_funds_of_subject.val() > 0 ||
            initial_employers_funds.val() > 0 ||
            initial_edication_org_funds.val() > 0
        )
        {
            // console.log('yes');
            setRequired(false, initial_federal_funds, initial_funds_of_subject,
                initial_employers_funds, initial_edication_org_funds);
            $(".body:eq(" + 1 + ") label.error", form).remove();
            $(".body:eq(" + 1 + ") .error", form).removeClass("error");
        }
        else{
            // console.log('no');
            setRequired(true, initial_federal_funds, initial_funds_of_subject,
                initial_employers_funds, initial_edication_org_funds)
        }
        initial_sum.text(getSum(initial_federal_funds, initial_funds_of_subject,
            initial_employers_funds, initial_edication_org_funds) + " ₽");
    }
    function updateFinSum() {

        fin_sum.text(getSum(fin_federal_funds, fin_funds_of_subject,
            fin_employers_funds, fin_edication_org_funds) + " ₽");
    }
    // Проверка корректности дат
    function checkDate(date1, date2, error){
        if(date1.val() > date2.val() && date2.val() !== ""){
            date2.addClass( "red-bg" ).focus().next().show();
            error.show();
            return false;
        }
        else {
            date2.removeClass( "red-bg" ).next().hide();
            error.hide();
            return true;
        }
    }

    function onLoad() {
        let method = method_of_determining.find(":selected").text();
        formState = method;
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);

        updateInitialSum();
        updateFinSum();
        isHasPrepayment();
        $('.number').hide();
        contractStatusChange();
        errors_message.hide();


    }

    function hideDeliver(isHide) {
        let kpp = $('#purchases_form_supplierKPP');
        let inn = $('#purchases_form_supplierINN');
        let name = $('#purchases_form_supplierName');

        if(isHide){
            setDisabled(true, kpp, inn, name);
            // $("#form-t-5").hide();
        }
        else{
            setDisabled(false, kpp, inn, name);
            // $("#form-t-5").show().parent().removeClass("done").addClass("disabled");
        }
    }

    function isPlannedChange(){
        if(isPlanned.is(':checked')){
            // console.log('aaaaa');
            $("#form-t-1").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-2").hide();
            $("#form-t-3").hide();
            $("#form-t-4").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-5").hide();
            $("#form-t-6").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-7").show().parent().removeClass("done").addClass("disabled");
            finSumRow.hide();
            showFieldset = [0, 1, 4, 6, 7];
            clearValue(publication_date, deadline_date, summing_up_date, purchases_link,
                purchases_number, postponement_date, postponement_comment,
                conclusion_date, delivery_date);
            setDisabled(true, publication_date, deadline_date, summing_up_date, purchases_link,
                purchases_number, postponement_date, postponement_comment, conclusion_date, delivery_date);
        }
        else{
            $("#form-t-1").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-2").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-3").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-4").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-5").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-6").show().parent().removeClass("done").addClass("disabled");
            $("#form-t-7").show().parent().removeClass("done").addClass("disabled");
            finSumRow.show();
            showFieldset = [0, 1, 2, 3, 4, 5, 6, 7];
            setDisabled(false, publication_date, deadline_date, summing_up_date, purchases_link,
                purchases_number, postponement_date, postponement_comment, conclusion_date, delivery_date);
        }
    }

    isPlanned.change(isPlannedChange);

    function isHasPrepayment(){
        let _prepaymentInput = $('#purchases_form_prepayment');
        if(hasPrepayment.is(':checked')){
            prepaymentBlock.show('fast');
            setRequired(true, _prepaymentInput);
            setDisabled(false, _prepaymentInput);
        }
        else{
            prepaymentBlock.hide('fast');
            setDisabled(true, _prepaymentInput);
            clearValue(_prepaymentInput);
        }
    }

    hasPrepayment.change(isHasPrepayment);

    function contractStatusChange(){
        // Догово на стадии подписания
        if(contractStatusSelect.val() == 1){
            hideDeliver(true);
            contract_info_block.hide('fast');
            setDisabled(true,  conclusion_date, delivery_date, file, fin_edication_org_funds, fin_employers_funds,
                fin_federal_funds, fin_funds_of_subject, comment);
        }
        // Догово подписан
        if(contractStatusSelect.val() == 2){
            hideDeliver(false);
            contract_info_block.show('fast');
            setDisabled(false,  conclusion_date, delivery_date, file, fin_edication_org_funds, fin_employers_funds,
                fin_federal_funds, fin_funds_of_subject, comment);
        }
        updateFinSum();

    }

    contractStatusSelect.change(contractStatusChange);

    method_of_determining.change(() => {
        $("#form-t-1").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-2").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-3").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-4").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-5").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-6").show().parent().removeClass("done").addClass("disabled");
        $("#form-t-7").show().parent().removeClass("done").addClass("disabled");

        let method = method_of_determining.find(":selected").text();
        formState = method;
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);
    });

    // изменение начальной суммы
    initial_federal_funds.change(() => {
        // changeInitialFunds(initial_federal_funds, fin_federal_funds);
        updateInitialSum();
    });
    initial_funds_of_subject.change(() => {
        // changeInitialFunds(initial_funds_of_subject, fin_funds_of_subject);
        updateInitialSum();
    });
    initial_employers_funds.change(() => {
        // changeInitialFunds(initial_employers_funds, fin_employers_funds);
        updateInitialSum();
    });
    initial_edication_org_funds.change(() => {
        // changeInitialFunds(initial_edication_org_funds, fin_edication_org_funds);
        updateInitialSum();
    });

    // изменение финальной суммы суммы
    fin_federal_funds.change(() => {
        updateFinSum();
    });
    fin_funds_of_subject.change(() => {
        updateFinSum();
    });
    fin_employers_funds.change(() => {
        updateFinSum();
    });
    fin_edication_org_funds.change(() => {
        updateFinSum();
    });

    $('form[name="purchases_form"]').submit((event) => {
        let isValid = true;
        let validArr = [];

        validArr.push(checkDate(publication_date, deadline_date, deadline_date_error));
        validArr.push(checkDate(deadline_date, summing_up_date, summing_up_date_error));
        validArr.push(checkDate(summing_up_date, postponement_date, postponement_date_error));
        validArr.push(checkDate(postponement_date, conclusion_date, conclusion_date_error));
        validArr.push(checkDate(conclusion_date, delivery_date, delivery_date_error));

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
            return;

        event.preventDefault();
    });

    onLoad();

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });


});