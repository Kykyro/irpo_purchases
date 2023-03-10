$(document).ready(function () {

    $("#wizard").steps();

    let form = $("#form").steps({
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

            let form = $(this);

            // Clean up if user went backward before
            if (currentIndex < newIndex)
            {
                // Убрать сообщения об ошибках
                $(".body:eq(" + newIndex + ") label.error", form).remove();
                $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
            }

            // Отключить валидацию на скрытые или заблокированные поля
            form.validate().settings.ignore = ":disabled,:hidden";

            // Проверка формы при переходе страницы
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex)
        {
            // Переключаемся только на указанные разделы
            if(!showFieldset.includes(currentIndex)){
                if(currentIndex > priorIndex)
                    $(this).steps("next");
                else
                    $(this).steps("previous");
            }

            $("a[href='#finish']").hide();
        },
        onFinishing: function (event, currentIndex)
        {

            var form = $(this);

            // не проверять заблокированные поля
            form.validate().settings.ignore = ":disabled";

            // Проверить форму на соотвестивие валидациии
            return form.valid();
        },
    });

    // VARIBLES
    //предмет закупки
    let purchases_object = $("#purchases_form_PurchaseObject");
    let method_of_determining = $("#purchases_form_MethodOfDetermining");
    let another_method_of_determining = $('#purchases_form_anotherMethodOfDetermining');
    let another_method_of_determining_row = $('#anotherMethodOfDetermining');
    let isPlanned = $('#purchases_form_isPlanned');
    let isPlannedRow = $('#isPlanned');
    let plannedPublicationDateRow = $('#planned-date-row');
    let plannedPublicationDate = $('#purchases_form_plannedPublicationDate');

    // Цена контракта договора
    let initial_federal_funds = $("#purchases_form_initialFederalFunds");
    let initial_funds_of_subject = $("#purchases_form_initialFundsOfSubject");
    let initial_employers_funds = $("#purchases_form_initialEmployersFunds");
    let initial_edication_org_funds = $("#purchases_form_initialEducationalOrgFunds");
    let hasPrepayment = $('#purchases_form_isHasPrepayment');
    let prepaymentBlock = $('#prepayment-block');

    // Срок размещения закупки
    let publication_date = $("#purchases_form_publicationDate");
    let deadline_date = $("#purchases_form_deadlineDate");
    let summing_up_date = $("#purchases_form_dateOfSummingUp");
    let postponement_date = $("#purchases_form_postponementDate");
    let postponement_comment = $('#purchases_form_postonementComment');

    // ссылка на закупку
    let purchases_number = $('#purchases_form_PurchaseNumber');
    let purchases_link = $('#purchases_form_PurchaseLink');

    // Дата заключения контракта/договора
    let finSumRow = $('#fin-sum-row');
    let contractStatusSelect = $('#purchases_form_conractStatus');
    let contract_info_block = $('#contact-info-block');
    let file = $('#purchases_form_file');
    let conclusion_date = $("#purchases_form_DateOfConclusion");
    let delivery_date = $("#purchases_form_DeliveryTime");
    let comment = $('#purchases_form_Comments');
    let fin_federal_funds = $("#purchases_form_finFederalFunds");
    let fin_funds_of_subject = $("#purchases_form_finFundsOfSubject");
    let fin_employers_funds = $("#purchases_form_finEmployersFunds");
    let fin_edication_org_funds = $("#purchases_form_finFundsOfEducationalOrg");
    let hasAdditionalAgreement = $("#purchases_form_hasAdditionalAgreement");
    let additional_agreement_row = $("#additional-agreement-row");
    let additional_agreement_file = $("#purchases_form_AdditionalAgreement_file");


    //Фактическое расходование средств
    let fact_federal_funds = $("#purchases_form_factFederalFunds");
    let fact_funds_of_subject = $("#purchases_form_factFundsOfSubject");
    let fact_employers_funds = $("#purchases_form_factEmployersFunds");
    let fact_edication_org_funds = $("#purchases_form_factFundsOfEducationalOrg");

    // ------
    let formState = "Единственный поставщик";
    let showFieldset = [0, 1, 3, 4, 5, 6];
    // Суммы
    let initial_sum = $(".sum_initial");
    let fin_sum = $(".sum_fin");

    let todayInput = $('#today');

    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear() + "-" + (month) + "-" + (day);
    todayInput.val(today);

    let  purchasePlacement = $('#purchase-placement');

    let _formState = {
        _determ: 0,
        _isPlanning: 0,
        _contract: 0,

        get determ() { return this._determ; },
        set determ(value) {
            this._determ = value;
            if(this._determ === 0){
                console.log('Ед. поставщик');

                clearValue(publication_date, deadline_date, summing_up_date,
                    postponement_date,  postponement_comment);

                isPlannedRow.hide();
                isPlanned.prop('checked', false);
                isPlannedChange();
                showFieldset = [0, 1, 3, 4, 5, 6];

                hideStep(2);

                finSumRow.hide();
                setRequired(false, publication_date);
                setRequired(true, conclusion_date);
                setDisabled(true, publication_date, deadline_date, summing_up_date, purchases_link,
                    purchases_number, postponement_date, postponement_comment);
                purchasePlacement.hide();
            }
            else {
                console.log('Конкуретный способ определения поставщика');

                setRequired(true, publication_date, conclusion_date);
                setDisabled(false, publication_date, deadline_date, summing_up_date, purchases_link,
                    purchases_number, postponement_date, postponement_comment);
                isPlannedRow.show();
                finSumRow.show();
                showFieldset = [0, 1, 2, 3, 4, 5, 6];
                isPlannedChange();
                purchasePlacement.show();
            }
        },

        get isPlanning() { return this._isPlanning; },
        set isPlanning(value) {
            this._isPlanning = value;
            if(this._isPlanning === 0){
                console.log('объявлена / закантрактована');

                showFieldset = [0, 1, 2, 3, 4, 5, 6];
                showFieldset.forEach((elem)=>{
                    showStep(elem);
                });

                finSumRow.show();
                if(this._determ === 0)
                {
                    purchasePlacement.hide();
                }else
                {
                    purchasePlacement.show();
                }



                plannedPublicationDateRow.hide();
                setDisabled(false, publication_date, deadline_date, summing_up_date, purchases_link,
                    purchases_number, postponement_date, postponement_comment, conclusion_date, delivery_date,
                    fact_edication_org_funds, fact_employers_funds,
                    fact_federal_funds, fact_funds_of_subject, contractStatusSelect, plannedPublicationDateRow);

            }
            else {
                console.log('планируется');

                showFieldset = [0, 1, 6];
                let hideSteps = [2, 3, 4, 5];
                showFieldset.forEach((elem)=>{
                    showStep(elem);
                });
                hideSteps.forEach((elem)=>{
                    hideStep(elem);
                });
                finSumRow.hide();
                plannedPublicationDateRow.show();
                clearValue(publication_date, deadline_date, summing_up_date, purchases_link,
                    purchases_number, postponement_date, postponement_comment,
                    conclusion_date, delivery_date);
                setDisabled(true, publication_date, deadline_date, summing_up_date, purchases_link,
                    purchases_number, postponement_date, postponement_comment, conclusion_date, delivery_date,
                    fact_edication_org_funds, fact_employers_funds,
                    fact_federal_funds, fact_funds_of_subject, contractStatusSelect, plannedPublicationDateRow);
                contractStatusSelect.val(1);
                contractStatusChange();
                purchasePlacement.hide();
            }
        },

        get contract() { return this._contract; },
        set contract(value) {
            this._contract = value;
            if(this._contract === 0){
                console.log('не подписан');

                hideDeliver(true);
                contract_info_block.hide('fast');
                setDisabled(true,  conclusion_date, delivery_date,  fin_edication_org_funds, fin_employers_funds,
                    fin_federal_funds, fin_funds_of_subject, comment,
                    fact_edication_org_funds, fact_employers_funds, fact_federal_funds, fact_funds_of_subject);
                let hide = [4, 5];
                hide.forEach((elem)=>{
                    if(showFieldset.includes(elem)) {
                        showFieldset = showFieldset.filter(function (value, index, arr) {
                            return value !== elem;
                        });
                        hideStep(elem);
                    }
                });
                let _step = $("#form-t-"+6);
                _step.show().parent().removeClass("done").addClass("disabled");
            }
            else {
                console.log('подписан');

                hideDeliver(false);
                contract_info_block.show('fast');
                setDisabled(false,  conclusion_date, delivery_date,  fin_edication_org_funds, fin_employers_funds,
                    fin_federal_funds, fin_funds_of_subject, comment,
                    fact_edication_org_funds, fact_employers_funds, fact_federal_funds, fact_funds_of_subject);

                let show = [5, 4, 6];
                show.forEach((elem)=>{
                    if(!showFieldset.includes(elem)) {
                        showFieldset.push(elem);
                        showStep(elem);
                    }
                });
                let _step = $("#form-t-"+6);
                _step.show().parent().removeClass("done").addClass("disabled");
            }
        },
    };

    function NMCKgreatedThenFinSum() {
        let f_sum = getSum(fin_federal_funds, fin_funds_of_subject,
            fin_employers_funds, fin_edication_org_funds);
        let i_sum = getSum(initial_federal_funds, initial_funds_of_subject,
            initial_employers_funds, initial_edication_org_funds);
        return i_sum >= f_sum || hasAdditionalAgreement.is(':checked');
    }
    function FinSumGreatedThenFactSum() {
        let f_sum;
        if(formState === 'Единственный поставщик')
            f_sum = getSum(initial_federal_funds, initial_funds_of_subject,
                initial_employers_funds, initial_edication_org_funds);
        else
            f_sum = getSum(fin_federal_funds, fin_funds_of_subject,
                fin_employers_funds, fin_edication_org_funds);

        let fact_sum = getSum(fact_federal_funds, fact_funds_of_subject,
            fact_employers_funds, fact_edication_org_funds);

        if(f_sum >= fact_sum)
            return true;
        else
            return false;
    }
    $.validator.addMethod("NMCKmoreThenFinSum", function(value, element) {
        return NMCKgreatedThenFinSum();
    }, "* Amount must be greater than zero");

    $.validator.addMethod("FinSumGreatedThenFactSum", function(value, element) {
        return FinSumGreatedThenFactSum();
    }, "* Amount must be greater than zero");

    $.validator.addMethod("greaterThanDate",
        function(value, element, params) {

            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                || (Number(value) > Number($(params).val()));
        },'Must be greater than {0}.');

    $.validator.addMethod("lessThanDate",
        function(value, element, params) {

            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) <= new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                || (Number(value) <= Number($(params).val()));
        },'Must be less than {0}.');


    function soloSupplier(isThis){
        if(isThis)
        {
            _formState.isPlanning = 0;
            _formState.determ = 0;
        }
        else {
            _formState.determ = 1;
        }
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
            setRequired(false, initial_federal_funds, initial_funds_of_subject,
                initial_employers_funds, initial_edication_org_funds);
            $(".body:eq(" + 1 + ") label.error", form).remove();
            $(".body:eq(" + 1 + ") .error", form).removeClass("error");
        }
        else{
            setRequired(true, initial_federal_funds, initial_funds_of_subject,
                initial_employers_funds, initial_edication_org_funds)
        }
        let _sum = getSum(initial_federal_funds, initial_funds_of_subject,
            initial_employers_funds, initial_edication_org_funds);
        _sum = _sum.toLocaleString('ru-RU');
        initial_sum.text(_sum + " ₽");
    }

    function updateFinSum() {
        let _sum = getSum(fin_federal_funds, fin_funds_of_subject,
            fin_employers_funds, fin_edication_org_funds);
        _sum = _sum.toLocaleString('ru-RU');
        fin_sum.text( _sum+ " ₽");
    }

    // при загрузке
    function onLoad() {

        let method = method_of_determining.find(":selected").text();
        formState = method;
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);
        isPlannedChange();

        updateInitialSum();
        updateFinSum();
        isHasPrepayment();
        $('.number').hide();
        contractStatusChange();
        hasAdditionalAgreementChange();
    }

    function hideDeliver(isHide) {
        let kpp = $('#purchases_form_supplierKPP');
        let inn = $('#purchases_form_supplierINN');
        let name = $('#purchases_form_supplierName');

        if(isHide){
            setDisabled(true, kpp, inn, name);
        }
        else{
            setDisabled(false, kpp, inn, name);
        }
    }
    //--------------------------------
    // Скрыть шаг
    //--------------------------------
    function hideStep(index){
        $("#form-t-"+index).hide();
    }
    //--------------------------------
    //показать шаг
    //--------------------------------
    function showStep(index){
        let _step = $("#form-t-"+index);
        if(index === 0)
        {
            _step.show().parent().removeClass("done");
            return;
        }
        _step.show().parent().removeClass("done").addClass("disabled");
    }
    //--------------------------------
    // ЗАКУПКА ПЛАНИРУЕТСЯ
    //--------------------------------
    function isPlannedChange(){
        if(isPlanned.is(':checked')){
            _formState.isPlanning = 1;
        }
        else{
            _formState.isPlanning = 0;
        }
    }
    isPlanned.change(isPlannedChange);

    // АВАНСОВЫЙ ПЛАТЕЖ
    function isHasPrepayment(){
        let _prepaymentInput = $('#purchases_form_prepayment');
        let _prepaymentDate = $('#purchases_form_prepaymentDate');
        if(hasPrepayment.is(':checked')){
            prepaymentBlock.show('fast');
            setRequired(true, _prepaymentInput, _prepaymentDate);
            setDisabled(false, _prepaymentInput, _prepaymentDate);
        }
        else{
            prepaymentBlock.hide('fast');
            setDisabled(true, _prepaymentInput, _prepaymentDate);
            clearValue(_prepaymentInput, _prepaymentDate);
        }
    }
    hasPrepayment.change(isHasPrepayment);

    // СТАТУС КОНТРАКТА
    function contractStatusChange(){
        // Догово на стадии подписания
        if(contractStatusSelect.val() == 1){
            _formState.contract = 0;
        }

        // Догово подписан
        if(contractStatusSelect.val() == 2){
            _formState.contract = 1;
        }
        updateFinSum();
    }
    contractStatusSelect.change(contractStatusChange);

    // ДОПОЛНИТЕЛЬНЫЕ СОГЛАШЕНИЯ
    function hasAdditionalAgreementChange()
    {
        if(hasAdditionalAgreement.is(':checked')){
            additional_agreement_row.show('fast');
            setDisabled(false, additional_agreement_file);
        }
        else{
            additional_agreement_row.hide('fast');
            setDisabled(true, additional_agreement_file);
        }
    }
    hasAdditionalAgreement.change(hasAdditionalAgreementChange);

    // МЕТОД ОПРЕДЕЛНИЯ ПОСТАВЩИКА
    function methodOfDeterminingChange(){
        showStep(1);
        showStep(2);
        showStep(3);
        showStep(4);
        showStep(5);
        showStep(6);
        showStep(7);


        let method = method_of_determining.find(":selected").text();
        formState = method;
        changeMethod(method, "Другое", anotherMethodOfDeterminingFunc);
        changeMethod(method, "Единственный поставщик", soloSupplier);
    }
    method_of_determining.change(methodOfDeterminingChange);

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



    onLoad();


    // Заблокировать кнопку Enter
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });


    // Валидация полей
    form.validate({

        rules: {

            'purchases_form[PurchaseObject]': "required",
            'purchases_form[anotherMethodOfDetermining]': "required",
            'purchases_form[finFederalFunds]': {
                NMCKmoreThenFinSum : true
            },
            'purchases_form[finFundsOfSubject]': {
                NMCKmoreThenFinSum : true
            },
            'purchases_form[finEmployersFunds]': {
                NMCKmoreThenFinSum : true
            },
            'purchases_form[finEducationalOrgFunds]': {
                NMCKmoreThenFinSum : true
            },
            'purchases_form[factFederalFunds]': {
                FinSumGreatedThenFactSum : true
            },
            'purchases_form[factFundsOfSubject]': {
                FinSumGreatedThenFactSum : true
            },
            'purchases_form[factEmployersFunds]': {
                FinSumGreatedThenFactSum : true
            },
            'purchases_form[factFundsOfEducationalOrg]': {
                FinSumGreatedThenFactSum : true
            },
            'purchases_form[publicationDate]': {
                lessThanDate: todayInput
            }
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

            'purchases_form[publicationDate]':{
                required: "Это поле обязательно",
                lessThanDate: 'Дата публикации извещения должна быть не позднее текущей даты.',
            } ,
            'purchases_form[DateOfConclusion]': "Это поле обязательно",
            'purchases_form[finFederalFunds]' : {
                NMCKmoreThenFinSum: 'Цена контракта / договора не может быть больше НМЦК'
            },
            'purchases_form[finFundsOfSubject]' : {
                NMCKmoreThenFinSum: 'Цена контракта / договора не может быть больше НМЦК'
            },
            'purchases_form[finEmployersFunds]' : {
                NMCKmoreThenFinSum: 'Цена контракта / договора не может быть больше НМЦК'
            },
            'purchases_form[finEducationalOrgFunds]' : {
                NMCKmoreThenFinSum: 'Цена контракта / договора не может быть больше НМЦК'
            },
            'purchases_form[prepayment]' : {
                required: "Это поле обязательно",
                max: $.validator.format("Не может быть больше {0}."),
                min: $.validator.format("Не может быть меньше {0}"),
                number: "Неверное значение.",
                step: $.validator.format("Укажите число без дробной части"),
            },
            // FinSumGreatedThenFactSum
            'purchases_form[factFederalFunds]' : {
                FinSumGreatedThenFactSum: 'Фактическое расходование не может быть больше Цены контракта / договора'
            },
            'purchases_form[factFundsOfSubject]' : {
                FinSumGreatedThenFactSum: 'Фактическое расходование не может быть больше Цены контракта / договора'
            },
            'purchases_form[factEmployersFunds]' : {
                FinSumGreatedThenFactSum: 'Фактическое расходование не может быть больше Цены контракта / договора'
            },
            'purchases_form[factEducationalOrgFunds]' : {
                FinSumGreatedThenFactSum: 'Фактическое расходование не может быть больше Цены контракта / договора'
            },
            'purchases_form[PurchaseLink]':
                {
                    required: "Это поле обязательно для заполнения"
                },
            'purchases_form[PurchaseNumber]':
                {
                    required: "Это поле обязательно для заполнения"
                },
            'purchases_form[prepaymentDate]':
                {
                    required: "Это поле обязательно для заполнения"
                },
            'purchases_form[plannedPublicationDate]':
                {
                    required: "Это поле обязательно для заполнения"
                }
        }
    });
});