$(document).ready(function () {

    // VARIBLES
    let purchases_object = $("#purchases_form_PurchaseObject");
    let method_of_determining = $("#purchases_form_MethodOfDetermining");
    let another_method_of_determining = $('#purchases_form_anotherMethodOfDetermining');
    let another_method_of_determining_row = $('#anotherMethodOfDetermining');
    let postponement_comment = $('#purchases_form_postonementComment');
    let purchases_number = $('#purchases_form_PurchaseNumber');
    let purchases_link = $('#purchases_form_PurchaseLink');

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
    // console.log(method_of_determining);
    function soloSupplier(isThis){
        if(isThis)
        {
            clearValue(publication_date, deadline_date, summing_up_date,
                postponement_date,  postponement_comment);
        }
        // required
        setRequired(isThis, conclusion_date);
        setRequired(!isThis, publication_date);
        // disabled
        setDisabled(isThis, publication_date, deadline_date, summing_up_date, purchases_link,
            purchases_number, postponement_date, postponement_comment);

    }
    function anotherMethodOfDeterminingFunc(isThis)
    {
        if(isThis)
        {
            another_method_of_determining_row.show(1000);
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
        changeInitialFunds(initial_federal_funds, fin_federal_funds);
        changeInitialFunds(initial_funds_of_subject, fin_funds_of_subject);
        changeInitialFunds(initial_employers_funds, fin_employers_funds);
        changeInitialFunds(initial_edication_org_funds, fin_edication_org_funds);

        let method = method_of_determining.find(":selected").text();
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);

        updateInitialSum();
        updateFinSum();

        errors_message.hide();
        // console.log(method_of_determining)


    }

    method_of_determining.change(() => {
        let method = method_of_determining.find(":selected").text();
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);
    });
    // изменение начальной суммы
    initial_federal_funds.change(() => {
        changeInitialFunds(initial_federal_funds, fin_federal_funds);
        updateInitialSum();
    });
    initial_funds_of_subject.change(() => {
        changeInitialFunds(initial_funds_of_subject, fin_funds_of_subject);
        updateInitialSum();
    });
    initial_employers_funds.change(() => {
        changeInitialFunds(initial_employers_funds, fin_employers_funds);
        updateInitialSum();
    });
    initial_edication_org_funds.change(() => {
        changeInitialFunds(initial_edication_org_funds, fin_edication_org_funds);
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
});