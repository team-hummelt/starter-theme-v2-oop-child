document.addEventListener("DOMContentLoaded", function () {
    (function ($) {
        function xhr_admin_ajax_handle(data, is_formular = true, callback) {
            let xhr = new XMLHttpRequest();
            let formData = new FormData();
            xhr.open('POST', child_admin_obj.ajax_url, true);
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
            formData.append('_ajax_nonce', child_admin_obj.nonce);
            formData.append('action', 'ChildAdmin');
            xhr.send(formData);
        }

        $(document).on('click', '.child-action', function () {
            let type = $(this).attr('data-type');
            let formData;
            let id;
            let target;
            let parent;
            switch (type) {
                case 'delete_document':
                    formData = {
                        'method' :type,
                        'id' :id,
                        'handle': $(this).attr('data-handle')
                    }
                    swal_fire_app_delete(formData)
                    return false;
            }
            if (formData) {
                xhr_admin_ajax_handle(formData, false, btn_action_callback)
            }
        });

        function btn_action_callback() {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                switch (data.type) {
                    case 'delete_document':
                        if(data.handle === 'upload_table') {
                            $('#current'+data.id).parent('tr').remove();
                        }
                        swal_alert_response(data);
                        break;
                }
            }
        }

        $(document).on('submit', '.child-documents-form', function (event) {
            let form = $(this).closest("form").get(0);
            xhr_admin_ajax_handle(form, true, child_settings_callback)
            event.preventDefault()
        })

        function child_settings_callback() {
            let data = JSON.parse(this.responseText);
            let formClass = $(".child-documents-form");
            switch (data.type) {
                case 'update_document':


                    break;
            }
            swal_alert_response(data)
        }

        let testForm ={
            'method':'admin_test',
            'message' : 'hello admin'
        }
        xhr_admin_ajax_handle(testForm, false);

        let uploadExample = $('#uploadExample');
        if(uploadExample) {
            init_upload_dropzone();
        }


        function swal_alert_response(data) {
            if (data.status) {
                Swal.fire({
                    position: 'top-end',
                    title: data.title,
                    text: data.msg,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    customClass: {
                        popup: 'bg-light'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then();
            } else {
                Swal.fire({
                    position: 'top-end',
                    title: data.title,
                    text: data.msg,
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    customClass: {
                        popup: 'swal-error-container'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then();
            }
        }

        function swal_fire_app_delete(data) {
            Swal.fire({
                title: 'Dokument wirklich löschen?',
                reverseButtons: true,
                html: '<span class="swal-delete-body">Das Dokument wird <b>unwiderruflich gelöscht!</b><br> Das Löschen kann <b>nicht</b> rückgängig gemacht werden.</span>',
                confirmButtonText: 'Dokument löschen',
                cancelButtonText: 'Abbrechen',
                showClass: {
                    //popup: 'animate__animated animate__fadeInDown'
                },
                customClass: {
                    popup: 'swal-delete-container'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    xhr_admin_ajax_handle(data, false, btn_action_callback)
                }
            });
        }

        //Message Handle
        function success_message(msg) {
            let x = document.getElementById("snackbar-success");
            x.innerHTML = msg;
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 3000);
        }

        function warning_message(msg) {
            let x = document.getElementById("snackbar-warning");
            x.innerHTML = msg;
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 3000);
        }

    })(jQuery); // jQuery End
});