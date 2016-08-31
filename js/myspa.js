var viewModel = {
    documents: ko.observableArray([]),
    visible: ko.observable(false),
    title: ko.observable("Список ваших документов"),
    search: ko.observable(""),
    page: ko.observable(1),
    updateStatus: ko.observable(false),
    updateDoc: ko.observable(null)
};

viewModel.filteredDocuments = ko.computed(function () {
	var page = viewModel.page();
	var search = viewModel.search().toLowerCase();
	if (search){
		return ko.utils.arrayFilter(viewModel.documents(), function (item) {
	        return item.text.toLowerCase().indexOf(search) !== -1;
	    }).slice(page*10-10, page*10);
	}	    
	else{
		return viewModel.documents.slice(page*10-10, page*10);
	}	    
});

viewModel.pages = ko.computed(function () {
	var arrLength = viewModel.documents().length;
	var search = viewModel.search();
	if (search) {
		arrLength = ko.utils.arrayFilter(viewModel.documents(), function (item) {
        	return item.text.toLowerCase().indexOf(search) !== -1;
    	}).length;
	};
	var countPage = Math.ceil(arrLength / 10);
	var page = viewModel.page();
	if (page > countPage) {
		viewModel.page(1);
	};
	var arrPage = new Array();
	for (var i = 1; countPage >= i; i++) {
		arrPage.push(i);
	}
	return arrPage;

});

$(document).ready(function(){
	$.ajax({
		url: "spaGetDocs.php",
		type: "GET",
		success: function(data){
			viewModel.documents.removeAll();
			var data = JSON.parse(data);
		    for (var item in data) {
		        viewModel.documents.push(data[item]);
		    }
		    mySort(viewModel.documents);
		}
	});
	ko.applyBindings(viewModel);
});

function remove(doc) {
	if (confirm("Вы подтверждаете удаление?")) {
		$.ajax({
		url: "spaRemove.php",
		data: doc._id.$id,
		type: "DELETE",
		success: function(){
			for(var i in viewModel.documents()){
				if (viewModel.documents()[i]._id.$id == doc._id.$id) {
                    viewModel.documents.remove(viewModel.documents()[i]);
                    break;
                }
			}
		}
		});	
	}
}

function addVisible(){
	viewModel.visible(true);
	viewModel.title("Новый документ");
	$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param) 
	});
	$('#addForm').validate({
		rules: {
			doc_file: {
				required: true,
				filesize: 1048576
			},
			doc_text: "required",
			doc_date: "required"
		},
		messages: {
			doc_file: {
				required: "Выберите файл",
				filesize: "Файл не должен превышать 10МБ"
			},
			doc_text: "Введите описание",
			doc_date: "Выберите дату"
		},
		submitHandler: function(form) {
			var formData = new FormData($(form)[0]);
			$.ajax({
			 	url: "spaAdd.php",
			    type: "POST",
			    processData: false,
			    contentType: false,
			    data:  formData,
			    success: function(data){
					var data = JSON.parse(data);
				    viewModel.documents.push(data);
				    viewModel.visible(false);
					viewModel.title("Список ваших документов");
					mySort(viewModel.documents);
				}
			});
		}
	});
}

function cancel(){
	viewModel.visible(false);
	viewModel.title("Список ваших документов");
}

function mySort(model){
	model.sort(function (left, right) { return left.date == right.date ? 0 : (left.date < right.date ? 1 : -1) });
}

function index(i){
	viewModel.page(i);
}

function update(doc){
	viewModel.updateDoc(doc);
	viewModel.updateStatus(true);
}

ko.components.register('update-component', {
    viewModel: function (doc) {
	    this.updateTitle = ko.observable("Изменение документа");
	    this.updateDocument = ko.observable(doc);
	    this.updateCancel = function(){
	    	viewModel.updateStatus(false);
	    };
	    this.updateSubmit = function(form){
	    	if ($(form).valid()) { 
		    	$.ajax({
			 	url: "spaUpdate.php",
			    type: "PUT",
			    data:  JSON.stringify(this.updateDocument()),
			    success: function(data){
					var data = JSON.parse(data);
					for(var i in viewModel.documents()){
						if (viewModel.documents()[i]._id.$id == data._id.$id) {
		                    viewModel.documents()[i] = data;
		                    break;
		                }
					}
					viewModel.updateStatus(false);
					mySort(viewModel.documents);
				}
				});
	    	}    	
	    };
	    $("#updateDocument").validate({
   			rules: {
				doc_text: "required",
				doc_date: "required"
			},
			messages: {
				doc_text: "Введите описание",
				doc_date: "Выберите дату"
			}
       	});
	},
    template: 
    '<h1 data-bind="text: updateTitle"></h1>\
    <form data-bind="submit: updateSubmit" id="updateDocument" class="form-horizontal" role="form">\
		<div class="form-group">\
			<label for="doc_text" class="col-sm-2 control-label">Описание</label>\
			<div class="col-sm-10">\
				<input type="text" class="form-control" id="doc_text" name="doc_text" data-bind="value: updateDocument().text">\
			</div>\
		</div>\
		<div class="form-group">\
		    <label for="doc_date" class="col-sm-2 control-label">Дата</label>\
		    <div class="col-sm-3">\
		    	<input type="date" class="form-control" id="doc_date" name="doc_date" data-bind="value: updateDocument().date">\
		    </div>\
	    </div>\
		<div class="form-group">\
			<div class="col-sm-offset-2 col-sm-2">\
				<button type="submit" class="btn btn-default">\
					Сохранить\
				</button>\
			</div>\
			<div class="col-sm-2">\
				<button class="btn btn-default" data-bind="click: updateCancel">\
					Отмена\
				</button>\
			</div>\
		</div>\
	</form>'
});