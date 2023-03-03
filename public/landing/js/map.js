window.onload = function() {
    fetch('/api/map').then(function(response) {
        response.json().then(function(data) {


            $('#year-select').change(() => {

            })
            var hint = document.getElementById('hint-region');
            data = JSON.parse(data);

            let map = new RussianMap({
                viewPort: "-65 0 1134 620",
                mapId: 'russian-map',
                width: 1100,
                height: 600,
                // дефолтовые атрибуты для контуров регионов
                defaultAttr: {
                    fill: '#FFFFFF', // цвет которым закрашивать
                    stroke: '#000', // цвет границы
                    'stroke-width': 0.2, // ширина границы
                    'stroke-linejoin': 'round' // скруглять углы
                },
                mouseMoveAttr: {
                    fill: '#7baec3',
                },
                onMouseMove: function(e) {
                    $('#region-title').html(this.region.name)
                    if(this.region.organization) {
                        let organization = JSON.parse(this.region.organization);
                        let year = $('#year-select option:selected').text();
                        for (var org in organization[year]) {
                            let orgSplit = organization[year][org].split(',');
                            for(let _org in orgSplit){
                                let orgElem = "<li class='organization'>" + orgSplit[_org] + "</li>";
                                $("#org-list").append(orgElem)
                            }

                        }

                    }
                    hint.style.display = 'inline-block';
                    if( e.pageX + hint.offsetWidth < document.body.offsetWidth ){

                        hint.style.top = e.pageY - 100 + 'px';
                        hint.style.left = e.pageX - 100 + 'px';
                    } else {
                        hint.style.top = e.pageY - 100 + 'px';
                        hint.style.left = e.pageX - hint.offsetWidth - 100 + 'px';
                    }

                },
                onMouseOut: function(event) {
                    hint.style.display = 'none';
                    $("#org-list").html("");
                },
                onMouseClick: function(event) {

                },

            }, data);

        }).then(() => {
            window.addEventListener('resize', WinResize);
            let winWidth = document.documentElement.clientWidth;
            let winHeight = document.documentElement.clientHeight;
            let mapSVG = $('#russian-map').children('svg');

            ResizeMap();

            function ResizeMap(){
                mapSVG.attr('width', winWidth/1.5 + 'px');
                mapSVG.attr('height', winHeight/1.5 + 'px');
            }


            function WinResize(){
                winWidth = document.documentElement.clientWidth;
                winHeight = document.documentElement.clientHeight;
                console.log(winHeight, winWidth);
                ResizeMap();
            }
        })
    });
};