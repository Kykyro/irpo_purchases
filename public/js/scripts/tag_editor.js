$(document).ready(function () {

    let tagButton = $('.tag-button');
    let tagList = $('.tag-list ');

    fetch('/api/get-all-user-tag').then(function (response) {
        response.json().then(function (data) {


            tagList.tagsinput({
                itemValue: 'id',
                itemText: 'tag',
                typeahead: {
                    source: JSON.parse(data),
                    afterSelect: function(val) { this.$element.val(""); },
                },
                freeInput: true

            });

        })
    });

    tagButton.hover(
        (e) => {
            $(e.target).closest('button').addClass('badge-primary');
        },
        (e) => {
            $(e.target).closest('button').removeClass('badge-primary');
        });

    tagButton.on('click', (e) => {
        let form = $('#tag-save-form');
        let target = $(e.target).closest('button');


        form.attr('action', '/inspector/users-tags/save/'+target.data('tagId'));

        fetch('/api/get-user-tag/' + target.data('tagId')).then(function (response) {
            response.json().then(function (data) {
                tagList.tagsinput('removeAll');
                let tags = JSON.parse(data);
                for (let i = 0; i < tags.length; i++) {
                    console.log(tags[i]);
                    tagList.tagsinput('add', tags[i]);
                }
            })
        });
        $('#tagModalLabel').html(target.data('org'));
    });


});