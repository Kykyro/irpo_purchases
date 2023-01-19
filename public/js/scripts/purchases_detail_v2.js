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



            if(formState === "Другое"){
                let editablePage = [0, 1, 2, 3, 4, 5, 6, 7];
                if(!editablePage.includes(currentIndex)){
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
        onFinished: function (event, currentIndex)
        {
            var form = $(this);

            // Submit form input
            form.submit();
        }
    }).validate({

        rules: {

            'purchases_form[PurchaseObject]': "required",
            'purchases_form[anotherMethodOfDetermining]': "required",


        },
        messages: {
            'purchases_form[PurchaseObject]': "Введите предмет закупки",
            'purchases_form[anotherMethodOfDetermining]': "Укажите Способ определения поставщика",
            'purchases_form[file]': "Прикрепите файл договора/проекта договора",

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
    let showFieldset = [0, 1, 2, 3, 4, 5, 6, 7];






});