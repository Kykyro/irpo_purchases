$(document).ready(function(){
    let input = $('#map_edit_form_organization');
    let mapEditor = $( '#map-editor');
    let obj = JSON.parse(input.val());
    let string = '';


    for(let key in obj){
        if (obj.hasOwnProperty(key)){
            string += `${key}: ${obj[key]["organization"]}\n`
        }
    }
    string = string.replace(/\n$/, "");
    mapEditor.val(string);


    mapEditor.change((event)=>{
       let years = mapEditor.val().split("\n");
       let data = {};


       years.forEach((elem)=>{
           let _data = elem.split(':');
           console.log(_data);
           data[_data[0]] = {"organization": _data[1].trim()};
       });
       input.val(JSON.stringify(data));
    })
});