///////////////////////
// GENERAL FUNCTIONS //
///////////////////////
/**
 * formToJSON take forms data and transform them to JSON format
 * @param  {DOM element} form
 * @return {JSON}
 */
function formToJSON(form) {
	var formdata = $(form).serializeArray();
	var data = {};

	$(formdata).each(function(index, obj) {
		if (data[obj.name] !== undefined){
			if (!Array.isArray(data[obj.name])) {
				data[obj.name] = [data[obj.name]];
			}
			data[obj.name].push(obj.value);
		}else{
			data[obj.name] = obj.value;
		}
	});

	return data;
}