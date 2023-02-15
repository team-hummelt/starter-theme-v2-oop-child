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

    })(jQuery); // jQuery End
});