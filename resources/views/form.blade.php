<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type = "text/css" rel = "stylesheet" href = "{{ asset('css/form.css') }}">
    <script src="{{ asset('js/jquery.3.7.1.min.js') }}"></script>
    
    <title>Form</title>
</head>
<body>
    <div class="login-form">
        <div class="form-placeholder">
            <p class="form-name">Отправка данных</p>
        </div>
        <form method="post" id="From">
            <p class="name-data">Имя</p>
            <input type="text" name="first_name" placeholder="Имя..." class="form-data" required>
            <p class="name-data">Фамилия</p>
            <input type="text" name="last_name" placeholder="Фамилия..." class="form-data" required>
            <p class="name-data">Возраст</p>
            <input type="number" name="age" min="1" value="1" class="form-data">
            <p class="name-data" >Пол</p>
            <input type="radio" name="gender" value="Женщина">Женщина    
            <input type="radio" name="gender" value="Мужчина">Мужчина
            <p class="name-data">Телефон</p>
            <input type="tel" name="telephone" class="form-data" required>
            <p class="name-data">Email</p>
            <input type="email" name="email" class="form-data" required>
            <button type="button" class="submit" id="submit">Войти</button>
        </form>
    </div>
    
</body>
<script>
    $(document).ready(function(){
        $("#submit").on('click',function(e) {
            e.preventDefault();

            var formData = {
                _token: $("input[name='_token']").val(), 
                first_name: $("input[name='first_name']").val(),
                last_name: $("input[name='last_name']").val(),
                age: $("input[name='age']").val(),
                gender: $("input[name='gender']").val(),
                telephone: $("input[name='telephone']").val(),
                email: $("input[name='email']").val()
            }

            $.ajax({
                type: "POST",
                url: "/validate",
                data: JSON.stringify(formData),
                contentType: "application/json",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response){
                    console.log("Успешно отправлено: ", response);
                    window.location.href = "/send";
                },
                error: function(error){
                    console.log("Ошибка: ", error);
                }
            })
        });
    });
</script> 
</html>