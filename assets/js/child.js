document.addEventListener("DOMContentLoaded", function () {
    (function ($) {

        function xhr_child_ajax_handle(data, is_formular = true, callback) {
            let xhr = new XMLHttpRequest();
            let formData = new FormData();
            xhr.open('POST', child_localize_obj.ajax_url, true);
            if (is_formular) {
                let input = new FormData(data);
                for (let [name, value] of input) {
                    formData.append(name, value);
                }
            } else {
                for (let [name, value] of Object.entries(data)) {
                    formData.append(name, value);
                }
            }
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    if (typeof callback === 'function') {
                        xhr.addEventListener("load", callback);
                        return false;
                    }
                }
            }
            formData.append('_ajax_nonce', child_localize_obj.nonce);
            formData.append('action', 'ChildNoAdmin');
            xhr.send(formData);
        }

        let currentPage = $('#theme-current-page');
        if(currentPage.length){
            let formData = {
                'method': 'ajax_test'
            }

            xhr_child_ajax_handle(formData, false, current_page_callback)
        }

        function current_page_callback() {
            let data = JSON.parse(this.responseText);
            if(data.status) {
                console.log(data.msg);
            }
        }

        $(window).on("scroll", function () {
            mediaResizeFunction();
        });

        $(window).on("resize", function () {
            mediaResizeFunction();
        });
        let width = document.getElementById('masthead').getBoundingClientRect().width;
        mediaResizeFunction(width);

        function mediaResizeFunction(resize) {
            let menuOffcanvas = $('#offcanvasSmallForm');
            if($(window).scrollTop()> 200){
                menuOffcanvas.css('top', (80)+'px');
            } else {
                menuOffcanvas.css('top', (130)+'px');
            }
            let width = document.getElementById('masthead').getBoundingClientRect().width;
            if(resize){
                width = resize
            }
            let leftBox;
            let rightBox
            leftBox = $('.left-box');
            rightBox = $('.right-box');
            let w = 0;
            if (leftBox.length !== 0) {
                if (width > 1400) {
                    w = (width - 1332) / 2
                    leftBox.css('marginLeft', (w) + 'px')
                }
                if (width < 1400 && width > 1200) {
                    w = (width - 1152) / 2
                    leftBox.css('marginLeft', (w) + 'px')
                }
                if (rightBox.length !== 0) {
                    if (width > 1400) {
                        w = (width - 1332) / 2
                        rightBox.css('marginRight', w + 'px')
                    }
                    if (width < 1400 && width > 1200) {
                        w = (width - 1152) / 2
                        rightBox.css('marginRight', w + 'px')
                    }
                }
            }
            let content = $('.site-content');
            if (width < 1400) {
                content.addClass('page-mobil')
            } else {
                content.removeClass('page-mobil')
            }

            let containerWidth = $('.container-width');
            if (containerWidth.length !== 0) {
                if (width <= 1199) {
                    containerWidth.addClass('container');
                } else {
                    containerWidth.removeClass('container')
                }
            }
        }


        if (currentPage.attr('data-page')) {
            let menu = $('#bootscore-navbar li');
            $.each(menu, function () {
                if($(this).hasClass('child-menu')){
                    let link = $('a ', $(this));
                    let href = link.attr('href');
                    let setUrl =  child_localize_obj.site_url+'/'+href;
                   // link.attr('href', setUrl);
                }
            });
        }

        $('#offcanvas-navbar').on('show.bs.offcanvas', function (event) {
            let target = $('a.nav-link ',$(event.target));
            let parent = $(target).parent('li');
            let page = $('#theme-current-page').attr('data-page');
            let pages = ['datenschutzerklaerung', 'impressum'];
            if(!pages.includes(page)) {
                parent.each(function (index, value) {
                    if (!$(this).hasClass('d-link')) {
                     //   $('a.nav-link ', $(this)).attr('data-bs-dismiss', 'offcanvas').attr('data-bs-target', '#offcanvas-navbar')
                    }
                });
            }
        });

    })(jQuery); // jQuery End
});