$(document).ready(function () {
    let formSuccess = $('#form-success');
    formSuccess.hide();
    $('form[name="form"]').submit((event) => {
        let isValid = true;
        let fio = $('#form_FIO').val();
        let email = $('#form_email').val();
        let topic = $('#form_topic').val();
        let message = $('#form_message').val();

        if(fio === '' || email === '' || message === '' || topic === ''){
            isValid = false
        }


        if (isValid){
            $('form[name="form"]').hide();
            formSuccess.show("slow");

            return;
        }


        event.preventDefault();
    })
})