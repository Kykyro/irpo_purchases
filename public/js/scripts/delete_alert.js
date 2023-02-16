$(document).ready(function(){
    $('.delete-alert').click((event)=>{
        var answer = window.confirm("Выдействительно хотите удалить этот объект?");
        if (answer) {
            console.log('yes');

        }
        else {
            console.log('no');
            event.preventDefault();
        }
    })
});