$(document).ready(function(){
	$('form').validate({
		rules: {
			email: "required",
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			}
		},
		messages: {
			email: "Введите Email",
			password: {
				required: "Введите пароль",
				minlength: "Пароль должен быть не меньше 6 символов"
			},
			confirm_password: {
				required: "Введите пароль",
				minlength: "Пароль должен быть не меньше 6 символов",
				equalTo: "Повторный пароль введен не верно"
			}
		}
	});
});