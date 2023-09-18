document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementsByClassName("Form");

    form.addEventListener("submit", function (e) {
        e.preventDefalt();

        var formData = {
            first_name: form.querySelector("input[name='first_name']").value,
            last_name: form.querySelector("input[name='last_name']").value,
            age: form.querySelector("input[name='age']").value,
            gender: form.querySelector("input[name='gender']").value,
            telephone: form.querySelector("input[name='telephone']").value,
            email: form.querySelector("input[name='email']").value
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/", true);
        xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");

        xhr.onreadystatechange = function () {
            if(xhr.readyState === 4) {
                if(xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                }
            }
        };

        xhr.send(JSON.stringify(formData));
    });
});