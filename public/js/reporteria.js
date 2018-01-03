$( document ).ready(function(){
	 
	 if($('#search_datetimepicker1_1').length >0 && $('#search_datetimepicker2_1').length>0){
       
        $('#search_datetimepicker1_1').datetimepicker({
     		ignoreReadonly: true,
     		format:'DD-MM-YYYY',
     		locale:'es',
     	});
        $('#search_datetimepicker2_1').datetimepicker({
            ignoreReadonly: true,
            format:'DD-MM-YYYY',
            locale:'es',
        });
        $("#search_datetimepicker1_1").on("dp.change", function (e) {
            $('#search_datetimepicker2_1').data("DateTimePicker").minDate(e.date);
        });
        $("#search_datetimepicker2_1").on("dp.change", function (e) {
            $('#search_datetimepicker1_1').data("DateTimePicker").maxDate(e.date);
        });
     }

     if($('#search_datetimepicker1_2').length >0 && $('#search_datetimepicker2_2').length>0){
       
        $('#search_datetimepicker1_2').datetimepicker({
            ignoreReadonly: true,
            format:'DD-MM-YYYY',
            locale:'es',
        });
        $('#search_datetimepicker2_2').datetimepicker({
            ignoreReadonly: true,
            format:'DD-MM-YYYY',
            locale:'es',
        });
        $("#search_datetimepicker1_2").on("dp.change", function (e) {
            $('#search_datetimepicker2_2').data("DateTimePicker").minDate(e.date);
        });
        $("#search_datetimepicker2_2").on("dp.change", function (e) {
            $('#search_datetimepicker1_2').data("DateTimePicker").maxDate(e.date);
        });
     }

	 $('#btnLimpiar').click(function(){
	 	$('#fecha_desde').val(null);
	 	$('#fecha_hasta').val(null);
	 });
});