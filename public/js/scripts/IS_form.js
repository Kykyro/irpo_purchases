$(document).ready(function(){
   let labels = $('.label-hidden');
   let industryInput = $('#form_industry');
   let UGPSInput = $('#form_UGPS');
   let typeInput = $('#form_type');

    function changeType(){
        switch (typeInput.val()){
            case 'workshops_IS':
                labels.hide('slow');
                industryInput.hide('slow');
                industryInput.val(null);
                UGPSInput.hide('slow');
                UGPSInput.val(null);
                break;
            case 'cluster_IS':
                labels.show('slow');
                industryInput.show('slow');
                UGPSInput.show('slow');
                break;
            default:
                break;
        }
    }

    changeType();

   typeInput.change(()=>{
       changeType();
   });

});